#!/usr/bin/env python3
"""Assert editorial text fidelity against every canonical historical source.
Requires beautifulsoup4. Excludes player URLs added as technical link fallbacks,
old builder controls/styles, and redundant title headings explicitly recorded.
"""
import json,re
from pathlib import Path
from bs4 import BeautifulSoup
root=Path(__file__).resolve().parent.parent
inventory=json.loads((root/'content/novedades-historical-inventory.json').read_text())
m=json.loads((root/'content/novedades-historical-manifest.json').read_text());source={p['id']:p for p in inventory['posts']}
errors=[]
def text(s):return re.sub(r'\s+',' ',s.get_text(' ',strip=True)).strip()
for e in m['entries']:
 old=BeautifulSoup(source[e['historical_id']]['content']['rendered'],'html.parser');new=BeautifulSoup(e['content'],'html.parser')
 for x in old.select('script,style,noscript,.swiper-pagination,.elementor-swiper-button,.su-slider-prev,.su-slider-next,.su-slider-pagination,iframe'):x.decompose()
 for caption in old.select('.su-custom-gallery-title-never .su-custom-gallery-title'):caption.decompose()
 assert len(new.select('.wp-block-gallery'))==len(old.select('.gallery,.su-custom-gallery')),'Static gallery semantics missing: '+str(e['historical_id'])
 for original_gallery,converted_gallery in zip(old.select('.gallery,.su-custom-gallery'),new.select('.wp-block-gallery')):
  assert len(original_gallery.select('img'))==len(converted_gallery.select('img')),'Static gallery image lost: '+str(e['historical_id'])
 if e.get('removed_duplicate_title_heading'):
  heading=old.find(['h1','h2','h3']);assert heading.get_text()==e['removed_duplicate_title_heading'];heading.decompose()
 for x in new.select('.wp-block-embed'):x.decompose()
 if text(old)!=text(new):errors.append({'id':e['historical_id'],'original':text(old),'new':text(new)})
 assert not new.select('script,style,iframe,.elementor,[style]'),'Old builder layout leaked'
 assert not new.select('h1'),'Only the new template owns the page h1'
 assert not re.search(r'<img[^>]+src=["\'][^"\']*ddna\.cba\.gov\.ar',e['content']),'Image hotlink'
assert len(m['entries'])==len(inventory['posts'])==inventory['archive']['count']==219
assert len({e['historical_id'] for e in m['entries']})==219
assert len({e['slug'] for e in m['entries']})==219
assert not errors,json.dumps(errors,ensure_ascii=False,indent=2)
print('219 fuentes: texto editorial íntegro, sin layout/CSS viejo ni imágenes remotas; títulos redundantes documentados; identidades y slugs únicos.')
