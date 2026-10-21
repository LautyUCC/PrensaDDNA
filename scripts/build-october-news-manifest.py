#!/usr/bin/env python3
"""Reviewed October batch only; read original sources without modifying them.
PDF extraction dependency: PyMuPDF==1.26.5 (requirements-news-reconciliation.txt).
"""
import hashlib
import html
import json
import re
import unicodedata
import zipfile
import xml.etree.ElementTree as ET
from pathlib import Path
import fitz

ROOT = Path(__file__).resolve().parent.parent
SOURCE = ROOT / 'RECURSOS GRÁFICOS - WEB DDNA 2026'
NS = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
W = '{' + NS['w'] + '}'
R = '{http://schemas.openxmlformats.org/officeDocument/2006/relationships}'
DATE = '2026-10-21'  # Explicit user confirmation for all eight entries.

def norm(s):
    return ' '.join(s.replace('\u200b', '').replace('\u00ad', '').split())

def digest(p):
    return hashlib.sha256(p.read_bytes()).hexdigest()

def slug(s):
    s = unicodedata.normalize('NFKD', s).encode('ascii', 'ignore').decode().lower()
    return re.sub(r'[^a-z0-9]+', '-', s).strip('-')

def docx(p):
    with zipfile.ZipFile(p) as z:
        tree = ET.fromstring(z.read('word/document.xml'))
        rels = {r.attrib['Id']: r.attrib['Target'] for r in ET.fromstring(z.read('word/_rels/document.xml.rels')) if r.attrib.get('TargetMode') == 'External'}
        def inner(node):
            if node.tag == W+'t': return html.escape(node.text or '')
            if node.tag in (W+'br', W+'cr'): return '<br>'
            if node.tag == W+'tab': return ' '
            text = ''.join(inner(c) for c in node)
            if node.tag == W+'r':
                bold = node.find('w:rPr/w:b', NS)
                if bold is not None and bold.attrib.get(W+'val', '1') not in ('0','false'):text = '<strong>'+text+'</strong>'
            if node.tag == W+'hyperlink':
                url = rels.get(node.attrib.get(R+'id'))
                if url:
                    if not url.startswith(('https://','http://','mailto:')):raise ValueError('Unexpected link')
                    text = '<a href="'+html.escape(url,quote=True)+'">'+text+'</a>'
            return text
        return [inner(n) for n in tree.findall('.//w:p', NS) if norm(''.join(n.itertext()))], sorted(set(rels.values()))

def pdf(p):
    paragraphs=[]
    with fitz.open(p) as doc:
        for page in doc:
            for block in page.get_text('dict',sort=True)['blocks']:
                if 'lines' not in block:continue
                lines=[]
                for line in block['lines']:
                    spans=[]
                    for span in line['spans']:
                        text=html.escape(span['text'])
                        if span['flags'] & 16:text='<strong>'+text+'</strong>'
                        spans.append(text)
                    lines.append(''.join(spans))
                text=norm(' '.join(lines))
                if text:paragraphs.append(text)
    # These two PDFs split the centered title over two blocks before FOTOS.
    marker=next(i for i,s in enumerate(paragraphs) if plain(s)=='FOTOS')
    return [' '.join(paragraphs[:marker])] + paragraphs[marker:], []

def plain(s):
    return norm(html.unescape(re.sub('<[^>]*>', '', s)))

def build():
    assets={};entries=[]
    def asset(p,title):
        key=digest(p)
        assets.setdefault(key,dict(path=str(p.relative_to(SOURCE)),sha256=key,size=p.stat().st_size,filename=p.name,alt=title))
        return key
    folders=sorted((p for p in (SOURCE/'FALTA PUBLICAR').iterdir() if p.is_dir()),key=lambda p:int(p.name.split(')')[0]))
    assert [int(p.name.split(')')[0]) for p in folders] == list(range(4,12)), 'Unexpected batch: review before extending'
    for folder in folders:
        number=int(folder.name.split(')')[0]);docs=sorted(p for p in folder.rglob('*') if p.suffix.lower() in ('.pdf','.docx'))
        chosen=next((p for p in docs if p.suffix=='.docx'),docs[0])
        paragraphs,links=docx(chosen) if chosen.suffix=='.docx' else pdf(chosen)
        title=plain(paragraphs.pop(0));images=sorted(p for p in folder.rglob('*') if p.suffix.lower() in ('.png','.jpg','.jpeg','.webp'))
        covers=[p for p in images if p.name.startswith('PORTADA')];assert len(covers)==1
        cover=covers[0];body=[];inline=[];instructions=[]
        for text in paragraphs:
            text=norm(text);value=plain(text)
            if not value:continue
            if value in ('FOTO','FOTOS','CARRUSEL DE FOTOS'):
                instructions.append(value);continue
            match=re.fullmatch(r'\(Imagen\s+(\d+)\)',value)
            if match:
                found=[p for p in images if p.stem==match[1]];assert len(found)==1
                key=asset(found[0],title);inline.append(found[0]);body.append('{{image:'+key+'}}');continue
            # Source hard line breaks separate dated school headings and body.
            parts=re.split(r'<br\s*/?>',text)
            if len(parts)>1 and re.match(r'^\d+ de agosto',plain(parts[0])):
                body.append('<h2>'+plain(parts[0])+'</h2>')
                description=' '.join(parts[1:])
                # Word wraps the heading AND its break in one bold run. Its
                # closing tag belongs to the heading, not the following p.
                if parts[0].count('<strong>') > parts[0].count('</strong>'):
                    assert description.startswith('</strong>'), 'Review unexpected heading/run boundary'
                    description=description[len('</strong>'):]
                if plain(description):body.append('<p>'+description+'</p>')
            elif value=='Primer encuentro con adolescentes':body.append('<h2>'+html.escape(value)+'</h2>')
            else:body.append('<p>'+text+'</p>')
        other=[p for p in images if p!=cover and p not in inline]
        other.sort(key=lambda p:[int(t) if t.isdigit() else t.lower() for t in re.split('(\\d+)',p.name)])
        seen={digest(cover)};photos=[]
        for p in other:
            if digest(p) not in seen:photos.append(p);seen.add(digest(p))
        origin=str(folder.relative_to(SOURCE));content='\n'.join(body)
        assert all(html.escape(u,quote=True) in content for u in links)
        assert (number not in (8,9) or len(inline)=={8:7,9:4}[number]), 'Missing inline instruction'
        entries.append(dict(source_id='ddna-news-'+hashlib.sha256(origin.encode()).hexdigest()[:20],origin=origin,original_group='FALTA PUBLICAR',title=title,slug=slug(title),original_publication_date=DATE,date_policy='user_confirmed_2026-10-21_all_eight',content=content,featured_image=asset(cover,title),carousel=bool(photos),gallery=[asset(p,title) for p in photos],inline_images=[asset(p,title) for p in inline],attachments=[],links=links,tag='novedad',status='publish',instructions=instructions,pending=[],sources=[dict(path=str(p.relative_to(SOURCE)),sha256=digest(p)) for p in docs],available_images=[str(p.relative_to(SOURCE)) for p in images]))
    manifest=dict(version='ddna-novedades-2026-v1',batch_id='ddna-news-october-2026',source_root=str(SOURCE.relative_to(ROOT)),editorial_decisions=dict(publication_date='2026-10-21 explicitly confirmed by user for all eight',featured='Unique PORTADA file in each supplied folder',photos='Numbered inline images at source positions; otherwise natural filename order in existing editorial carousel; featured not repeated in gallery',source_documents='Editorial sources only, not public PDF attachments'),assets=assets,entries=entries)
    dest=ROOT/'content/novedades-octubre-2026.json';dest.write_text(json.dumps(manifest,ensure_ascii=False,indent=2)+'\n')
    print(f'{len(entries)} entries; {len(assets)} unique assets')
    for e in entries:print(e['source_id'],e['title'],len(e['gallery']),len(e['inline_images']))

if __name__=='__main__':build()
