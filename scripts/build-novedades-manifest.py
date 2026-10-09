#!/usr/bin/env python3
"""Build the reviewed portable manifest from supplied PDF/DOCX sources.
Requires PyMuPDF for PDF extraction. Does not contact or modify WordPress.
"""
import hashlib
import html
import json
import re
import unicodedata
import xml.etree.ElementTree as ET
import zipfile
from pathlib import Path
import fitz

ROOT = Path(__file__).resolve().parent.parent
SOURCE = ROOT / 'RECURSOS GRÁFICOS - WEB DDNA 2026/novedades'
NS = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
def normalize(s):
    return ' '.join(s.replace('\u200b', '').replace('\u00ad', '').split())
def slug(s):
    s = unicodedata.normalize('NFKD', s).encode('ascii', 'ignore').decode().lower()
    return re.sub(r'[^a-z0-9]+', '-', s).strip('-')
def digest(p):
    return hashlib.sha256(p.read_bytes()).hexdigest()
def source_document(p):
    links = []
    if p.suffix.lower() == '.docx':
        with zipfile.ZipFile(p) as z:
            x = ET.fromstring(z.read('word/document.xml'))
            paragraphs = [''.join((t.text or '') if t.tag.endswith('}t') else '\n' for t in n.iter() if t.tag.endswith(('}t', '}br', '}cr'))) for n in x.findall('.//w:p', NS)]
            rels = {r.attrib['Id']: r.attrib['Target'] for r in ET.fromstring(z.read('word/_rels/document.xml.rels')) if r.attrib.get('TargetMode') == 'External'} if 'word/_rels/document.xml.rels' in z.namelist() else {}
            for h in x.findall('.//w:hyperlink', NS):
                key = h.attrib.get('{http://schemas.openxmlformats.org/officeDocument/2006/relationships}id')
                if key in rels: links.append((rels[key], normalize(''.join(t.text or '' for t in h.findall('.//w:t', NS)))))
    else:
        with fitz.open(p) as doc:
            text = '\n'.join(pg.get_text(sort=True) for pg in doc)
            paragraphs = re.split(r'\n\s*\n', text)
            for pg in doc:
                for link in pg.get_links():
                    if 'uri' in link: links.append((link['uri'], normalize(pg.get_textbox(link['from']))))
    return [normalize(t) for t in paragraphs if normalize(t)], links

def build():
    entries = []; assets = {}
    def asset(p, title):
        key = digest(p)
        if key not in assets:
            assets[key] = {'path': str(p.relative_to(SOURCE)), 'sha256': key, 'size': p.stat().st_size, 'alt': title, 'filename': p.name}
        return key
    for group in sorted(p for p in SOURCE.iterdir() if p.is_dir()):
        for folder in sorted(p for p in group.iterdir() if p.is_dir()):
            docs = sorted(p for p in folder.rglob('*') if p.suffix.lower() in ['.pdf', '.docx'])
            parsed = [(p, *source_document(p)) for p in docs]
            usable = [d for d in parsed if d[1]]
            chosen = next((d for d in usable if d[0].suffix.lower() == '.docx'), usable[0] if usable else None)
            paragraphs, links = (chosen[1][:], chosen[2][:]) if chosen else ([], [])
            # PDF exports retain embedded links that some companion DOCXs omit.
            links = sorted(set(links + [l for _, _, ls in parsed for l in ls]))
            title = paragraphs.pop(0) if paragraphs else folder.name
            pending = ['Fecha de publicación original: PENDIENTE DE DEFINICIÓN (no confundir fechas de eventos con publicación).']
            if not chosen: pending += ['Texto y confirmación de título editorial: PENDIENTE DE DEFINICIÓN; título conservado del nombre de carpeta.']
            images = sorted(p for p in folder.rglob('*') if p.suffix.lower() in ['.jpg', '.jpeg', '.png', '.webp'])
            def is_aux(p):return any(t in str(p.relative_to(folder)).lower() for t in ['miniatura', 'botón', 'boton'])
            candidates = [p for p in images if not is_aux(p) and ('portada' in p.name.lower() or any('portada' in q.lower() for q in p.relative_to(folder).parts[:-1]))]
            if not candidates and folder.name == 'NOVEDAD PRECONGRESO':
                candidates = [p for p in images if p.name == 'Novedades Web.png']
            main = candidates[0] if len(candidates) == 1 else None
            if not main:pending += ['Imagen principal: PENDIENTE DE DEFINICIÓN (sin asset inequívoco de portada).']
            body = []
            inline = []
            all_source_text = ' '.join(t for _, ps, _ in parsed for t in ps)
            for paragraph in paragraphs:
                if re.fullmatch(r'(?:CARRUSEL DE FOTOS|FOTOS?|BOTÓN Y COMUNICADO ENLAZADO)', paragraph, re.I):continue
                match = re.fullmatch(r'\(Imagen\s+(\d+)\)', paragraph, re.I)
                if match:
                    number = match[1]
                    found = next((p for p in images if re.fullmatch(r'IMAGEN '+number, p.stem, re.I)), None)
                    if found:
                        key = asset(found, title);inline.append(found);body.append('{{image:'+key+'}}')
                    else: pending += ['Imagen inline '+number+': PENDIENTE DE DEFINICIÓN.']
                    continue
                # Remove layout instructions while retaining the source button label.
                paragraph = paragraph.replace('(BOTON INSCRIPCIONES) link:', 'INSCRIPCIONES:').replace('(BOTON PROGRAMA)', 'PROGRAMA')
                # Restore an URL line-wrapped by the PDF export, using its annotation.
                for url, label in links:
                    if url.startswith('http') and label.startswith('http'):
                        collapsed = paragraph.replace(' ', '')
                        if url in collapsed:paragraph = paragraph.replace(collapsed, url) if collapsed == paragraph.replace(' ', '') and paragraph.startswith('http') else paragraph
                escaped = html.escape(paragraph)
                # Match longest link labels first and avoid introducing nested anchors.
                tokens = {}
                for n, (url, label) in enumerate(sorted(links, key=lambda x:len(x[1]), reverse=True)):
                    if not label or not re.match(r'^(https?://|mailto:)', url):continue
                    if label.startswith('http') and url in paragraph:label = url
                    token = f'__DDNALINK{n}__'
                    target = html.escape(label)
                    if target in escaped:
                        escaped = escaped.replace(target, token)
                        tokens[token] = '<a href="'+html.escape(url, quote=True)+'">'+target+'</a>'
                for token, anchor in tokens.items():escaped = escaped.replace(token, anchor)
                body.append('<p>'+escaped+'</p>')
            content = '\n'.join(body)
            # Preserve any embedded hyperlink not recoverable from its split label.
            for url, label in links:
                if re.match(r'^(https?://|mailto:)', url) and html.escape(url, quote=True) not in content:
                    content += '\n<p><a href="'+html.escape(url, quote=True)+'">'+html.escape(label or url)+'</a></p>'
            attachments = [p for p, _, _ in parsed if any(q.strip().upper() == 'COMUNICADO' for q in p.relative_to(folder).parts[:-1])]
            for p in attachments:content += '\n<p><a href="{{media:'+asset(p, title)+'}}">'+html.escape(p.stem)+'</a></p>'
            photos = [p for p in images if not is_aux(p) and p != main and p not in inline]
            def natural(p):return [int(t) if t.isdigit() else t.lower() for t in re.split(r'(\d+)', p.name)]
            photos.sort(key=natural)
            # User approved carousel for all multiple-photo news. Inline photos stay inline.
            carousel = 'CARRUSEL' in all_source_text.upper() or (len(photos)+(1 if main else 0) > 1)
            if carousel and not photos:pending += ['Carrusel indicado pero sin fotos adicionales disponibles.']
            if photos:pending += ['Orden de fotos sin numeración editorial expresa: orden natural del filename; revisar.']
            gallery = [asset(p, title) for p in photos]
            main_key = asset(main, title) if main else None
            entry = {'source_id': 'ddna-news-'+hashlib.sha256(str(folder.relative_to(SOURCE)).encode()).hexdigest()[:20], 'origin':str(folder.relative_to(SOURCE)), 'original_group':group.name, 'title':title, 'slug':slug(title), 'original_publication_date':None, 'date_policy':'first_import_time', 'content':content, 'featured_image':main_key, 'carousel':carousel, 'gallery':gallery, 'inline_images':[asset(p,title) for p in inline], 'attachments':[asset(p,title) for p in attachments], 'links':sorted(set(u for u,_ in links)), 'tag':'novedad', 'status':'publish', 'instructions':sorted(set(p for p in paragraphs if 'CARRUSEL' in p or p in ['FOTO','FOTOS'])), 'pending':pending, 'sources':[{'path':str(p.relative_to(SOURCE)), 'sha256':digest(p)} for p in docs], 'available_images':[str(p.relative_to(SOURCE)) for p in images]}
            entries.append(entry)
    seen = set()
    for item in entries:
        if item['slug'] in seen:item['slug'] += '-'+item['source_id'][-8:]
        seen.add(item['slug'])
    result = {'version':'ddna-novedades-2026-v1', 'source_root':'RECURSOS GRÁFICOS - WEB DDNA 2026/novedades', 'editorial_decisions':{'multiple_photos':'carousel approved by user', 'missing_publication_date':'first import timestamp, never event dates', 'main_image':'explicit PORTADA filename/folder; Novedades Web for PRECONGRESO'}, 'assets':assets, 'entries':entries}
    path = ROOT / 'content/novedades-manifest.json'
    path.write_text(json.dumps(result, ensure_ascii=False, indent=2)+'\n')
    print(f'{len(entries)} novedades; {len(assets)} assets únicos; {sum(e["carousel"] for e in entries)} carruseles; {sum(bool(e["featured_image"]) for e in entries)} portadas')
if __name__ == '__main__':build()
