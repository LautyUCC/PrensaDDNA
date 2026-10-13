#!/usr/bin/env python3
"""Local HTTP regression checks; disposable news fixtures cleaned in finally."""
import hashlib
import json
from html.parser import HTMLParser
from pathlib import Path
import subprocess
from urllib.parse import urljoin, urlencode
from urllib.request import urlopen

ROOT = Path(__file__).resolve().parent.parent
BASE = 'http://localhost:8080'
CLI = ['docker', 'compose', '--profile', 'tools', 'run', '--rm', 'cli']

def cli(code):
    result = subprocess.run(CLI + ['eval', code], cwd=ROOT, text=True, capture_output=True, check=True)
    return result.stdout.strip()

class Page(HTMLParser):
    def __init__(self, html):
        super().__init__(); self.cards = []; self.links = []; self.nav = []; self.image = None; self.return_links = []
        self.feed(html)
    def handle_starttag(self, tag, attrs):
        a = dict(attrs)
        if tag == 'article' and 'news-archive-card' in a.get('class', '').split(): self.cards.append(a.get('id'))
        if tag == 'a': self.links.append(a.get('href', ''))
        if tag == 'a' and 'return-home-menu' in a.get('class', '').split(): self.return_links.append(a.get('href'))
        if tag == 'a' and 'primary-menu__panel-link' in a.get('class', '').split(): self.nav.append(a.get('href'))
        if tag == 'img' and 'territory-map__image' in a.get('class', '').split(): self.image = a.get('src')

def read(path):
    response = urlopen(urljoin(BASE, path), timeout=15)
    assert response.status == 200, path
    return response.read().decode()

ids = []
checks = []
try:
    assert cli('echo wp_get_environment_type();') == 'local', 'Only local environment is allowed'
    assert cli('echo wp_parse_url(home_url(), PHP_URL_HOST);') in ['localhost', '127.0.0.1'], 'Only localhost is allowed'
    agenda = read('/agenda/'); contact = read('/contacto/')
    assert Page(agenda).nav == Page(contact).nav and len(Page(agenda).nav) == 6
    assert 'primary-navigation--home' in agenda and 'site-header--home' in agenda
    checks.append('Agenda reutiliza exactamente los seis destinos del menú de Contacto')
    news = read('/category/novedades/')
    assert Page(news).nav == Page(agenda).nav == Page(contact).nav
    assert news.count('id="primary-menu"') == 1 and 'primary-navigation--home' in news
    assert Page(news).return_links == [BASE + '/'] and 'Volver al inicio' in news
    assert Page(agenda).return_links == [BASE + '/#menu-principal-home']
    checks.append('Novedades comparte menú único y botón de regreso con Agenda/Contacto; enlace de regreso a Home')
    home = read('/'); image = Page(home).image
    assert image.endswith('/mapa-cordoba-2026.png')
    data = urlopen(image, timeout=15).read()
    source = ROOT / 'RECURSOS GRÁFICOS - WEB DDNA 2026/mapa cordoba.png'
    assert hashlib.sha256(data).digest() == hashlib.sha256(source.read_bytes()).digest()
    checks.append('Territorio sirve HTTP 200 y bytes idénticos al nuevo PNG')
    # Create only marked disposable local posts. Never import production content.
    ids = json.loads(cli('''$c=get_category_by_slug('novedades'); if(!$c){WP_CLI::error('Falta categoría');} $ids=[]; for($n=1;$n<=13;$n++){ $id=wp_insert_post(['post_type'=>'post','post_status'=>'publish','post_title'=>'DDNALOCALVERIFY noticia '.$n,'post_content'=>'Fixture descartable local','post_date'=>wp_date('Y-m-d H:i:s',time()-$n),'post_category'=>[$c->term_id],'meta_input'=>['_ddna_local_ui_test'=>1,'_ddna_news_enabled'=>true]],true); if(is_wp_error($id)){WP_CLI::error($id->get_error_message());} $ids[]=$id;} echo wp_json_encode($ids);'''))
    first = read('/category/novedades/'); second = read('/category/novedades/?paged=2')
    assert len(Page(first).cards) == 12 and len(Page(second).cards) >= 1
    assert not set(Page(first).cards) & set(Page(second).cards)
    assert 'Category:' not in first and 'Categoría:' not in first and 'id="news-archive-title">Novedades' in first
    assert 'news-archive__pagination' in first and '/assets/css/components/news-archive.css' in first
    assert first.index('</main>') < first.index('<footer')
    checks.append('Novedades: 12 por página, página 2 distinta, título y footer en flujo correcto')
    search = read('/category/novedades/?'+urlencode({'buscar':'DDNALOCALVERIFY'}))
    assert Page(search).nav == Page(contact).nav and Page(search).return_links == [BASE + '/']
    assert Page(second).nav == Page(contact).nav and Page(second).return_links == [BASE + '/']
    assert len(Page(search).cards) == 12
    assert any('buscar=DDNALOCALVERIFY' in link and 'paged=2' in link for link in Page(search).links)
    missing = read('/category/novedades/?'+urlencode({'buscar':'DDNALOCALVERIFYNORESULTS'}))
    assert not Page(missing).cards and 'No se encontraron novedades' in missing
    checks.append('Buscador filtra, conserva término al paginar y maneja cero resultados')
    print(json.dumps(checks, ensure_ascii=False, indent=2))
finally:
    if ids:
        # Delete only fixtures with both an explicit ID and our marker.
        cli('$ids=json_decode(\''+json.dumps(ids)+'\',true); foreach($ids as $id){if(get_post_meta($id,"_ddna_local_ui_test",true)){wp_delete_post($id,true);}}')
