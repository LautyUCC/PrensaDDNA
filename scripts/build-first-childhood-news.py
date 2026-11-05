#!/usr/bin/env python3
"""Build the single first-childhood editorial manifest from the supplied DOCX."""
import hashlib
import html
import json
from pathlib import Path
import xml.etree.ElementTree as ET
from zipfile import ZipFile

ROOT = Path(__file__).resolve().parents[1]
SOURCE = Path('RECURSOS GRÁFICOS - WEB DDNA 2026/NOVEDAD PRIMERA INFANCIA')
DOC = SOURCE / 'Novedad Mesa Provincial de Primera Infancia.docx'
PDF = SOURCE / 'Novedad Mesa Provincial de Primera Infancia.pdf'
PHOTOS = SOURCE / 'FOTOS REDIMENSIONADAS'
FEATURED = PHOTOS / 'PORTADA - Novedad web (8).png'
GALLERY = [PHOTOS / f'{n}.png' for n in range(1, 6)]
W = '{http://schemas.openxmlformats.org/wordprocessingml/2006/main}'


def digest(path):
    data = (ROOT / path).read_bytes()
    return {'path': str(path), 'sha256': hashlib.sha256(data).hexdigest(), 'size': len(data)}


with ZipFile(ROOT / DOC) as archive:
    document = ET.fromstring(archive.read('word/document.xml'))
paragraphs = []
for paragraph in document.iter(W + 'p'):
    runs = []
    for run in paragraph.findall(W + 'r'):
        value = ''.join(node.text or '' for node in run.iter(W + 't'))
        if not value:
            continue
        props = run.find(W + 'rPr')
        bold = props is not None and props.find(W + 'b') is not None
        runs.append((value, bold))
    if runs:
        paragraphs.append(runs)

title = ''.join(value for value, _ in paragraphs[0]).strip()
instruction = ''.join(value for value, _ in paragraphs[1]).strip()
if title != 'La Mesa Provincial de Primera Infancia avanzó en nuevas líneas de trabajo intersectorial' or instruction != 'CARRUSEL DE FOTOS' or len(paragraphs) != 8:
    raise SystemExit('La estructura editorial cambió; revisar antes de importar.')

body = []
for paragraph in paragraphs[2:]:
    parts = []
    for value, bold in paragraph:
        value = html.escape(value, quote=False)
        parts.append(f'<strong>{value}</strong>' if bold else value)
    body.append('<p>' + ''.join(parts) + '</p>')

manifest = {
    'version': 'ddna-first-childhood-news-v1',
    'source_id': 'ddna-news-first-childhood-mesa-provincial-20261105',
    'title': title,
    'slug': 'la-mesa-provincial-de-primera-infancia-avanzo-en-nuevas-lineas-de-trabajo-intersectorial',
    'publication_date': '2026-11-05',
    'date_policy': 'user_confirmed_2026-11-05',
    'content': '\n'.join(body),
    'sources': [digest(DOC), digest(PDF)],
    'featured': digest(FEATURED),
    'gallery': [digest(path) for path in GALLERY],
    'carousel': True,
    'classification': {'post_tag': 'novedad', 'category': 'novedades'},
}
output = ROOT / 'content/novedad-primera-infancia-20261105.json'
output.write_text(json.dumps(manifest, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
print(f'{output}: {len(body)} párrafos, {len(GALLERY)} fotos de carrusel')
