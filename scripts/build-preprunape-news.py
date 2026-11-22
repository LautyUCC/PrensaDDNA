#!/usr/bin/env python3
"""Build the PrePRUNAPE news manifest from Prensa's exported Google Doc."""
import argparse
import hashlib
import html
import json
from pathlib import Path
import xml.etree.ElementTree as ET
from zipfile import ZipFile

ROOT = Path(__file__).resolve().parents[1]
SOURCE = Path('content/media/preprunape')
DOC = SOURCE / 'Novedad PREPRUNAPE.docx'
FEATURED = SOURCE / 'PORTADA - Novedad web (10).png'
W = '{http://schemas.openxmlformats.org/wordprocessingml/2006/main}'
TITLE = 'Reunimos a equipos municipales PrePRUNAPE para fortalecer la detección temprana de señales de alerta en los primeros años de vida'


def asset(path):
    data = (ROOT / path).read_bytes()
    return {'path': str(path), 'sha256': hashlib.sha256(data).hexdigest(), 'size': len(data)}


parser = argparse.ArgumentParser()
parser.add_argument('--date', required=True, help='Fecha editorial confirmada: AAAA-MM-DD')
parser.add_argument('--carousel', required=True, choices=('yes', 'no'))
args = parser.parse_args()
if len(args.date) != 10 or args.date[4] != '-' or args.date[7] != '-':
    parser.error('La fecha debe tener formato AAAA-MM-DD.')

with ZipFile(ROOT / DOC) as archive:
    document = ET.fromstring(archive.read('word/document.xml'))
paragraphs = []
for paragraph in document.findall('.//' + W + 'body/' + W + 'p'):
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

plain = [''.join(value for value, _ in runs).strip() for runs in paragraphs]
if len(paragraphs) != 7 or plain[0] != TITLE or plain[1] != 'FOTOS':
    raise SystemExit('La estructura editorial cambió; revisar la fuente antes de importar.')

body = []
for paragraph in paragraphs[2:]:
    pieces = []
    for value, bold in paragraph:
        value = html.escape(value, quote=False)
        pieces.append('<strong>' + value + '</strong>' if bold else value)
    body.append('<p>' + ''.join(pieces) + '</p>')

gallery = [SOURCE / (str(n) + '.png') for n in range(1, 11)] if args.carousel == 'yes' else []
manifest = {
    'version': 'ddna-preprunape-news-v1',
    'source_id': 'ddna-news-preprunape-municipal-teams-2026',
    'source_url': 'https://drive.google.com/drive/folders/10LAVjBGKQ6HAK4HOk899o5KHza4tqcfn?usp=drive_link',
    'title': TITLE,
    'slug': 'reunimos-a-equipos-municipales-preprunape-para-fortalecer-la-deteccion-temprana-de-senales-de-alerta-en-los-primeros-anos-de-vida',
    'publication_date': args.date,
    'date_policy': 'user_confirmed_' + args.date,
    'content': '\n'.join(body),
    'sources': [asset(DOC)],
    'featured': asset(FEATURED),
    'gallery': [asset(path) for path in gallery],
    'carousel': args.carousel == 'yes',
    'classification': {'post_tag': 'novedad', 'category': 'novedades'},
}
output = ROOT / 'content/novedad-preprunape.json'
output.write_text(json.dumps(manifest, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
print(f'{output}: {len(body)} párrafos, {len(gallery)} fotos; fecha {args.date}')
