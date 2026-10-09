#!/usr/bin/env python3
"""Read-only capture of the official archive: actual pagination + linked WP API.
Output is a review snapshot, never automatically merged into an import manifest.
Requires beautifulsoup4. No credentials, no remote writes.
"""
import argparse,hashlib,json,time,urllib.parse,urllib.request
from pathlib import Path
from bs4 import BeautifulSoup
UA='Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36'
ORIGIN='https://ddna.cba.gov.ar'
def get(u):
 assert urllib.parse.urlsplit(u).hostname=='ddna.cba.gov.ar','Outside official source'
 for attempt in range(3):
  try:
   with urllib.request.urlopen(urllib.request.Request(u,headers={'User-Agent':UA}),timeout=60) as r:return r.read(),{k.lower():v for k,v in r.headers.items()}
  except Exception:
   if attempt==2:raise
   time.sleep(attempt+1)
def inventory(out):
 out.mkdir(parents=True,exist_ok=True);url=ORIGIN+'/category/novedades/';pages=[];seen=set();rows=[];category_api=None
 while url:
  assert url not in seen,'Pagination loop';seen.add(url);raw,_=get(url);(out/f'archive-page-{len(pages)+1}.html').write_bytes(raw);soup=BeautifulSoup(raw,'html.parser');posts=[]
  if category_api is None:
   link=soup.select_one('link[type="application/json"][href*="/categories/"]');assert link,'Linked category REST endpoint missing';category_api=link['href']
  for a in soup.select('article[id^="post-"]'):
   h=a.select_one('h2 a');dt=a.select_one('time[datetime]');assert h and dt
   p={'id':int(a['id'].split('-')[1]),'title':h.get_text(' ',strip=True),'url':h['href'],'date':dt['datetime']};posts.append(p);rows.append(p)
  assert posts,'No valid archive posts: '+url
  nxt=soup.select_one('li.next a');next_url=urllib.parse.urljoin(url,nxt['href']) if nxt else None
  pages.append({'url':url,'posts':len(posts),'ids':[p['id'] for p in posts],'sha256':hashlib.sha256(raw).hexdigest(),'next':next_url});url=next_url
  print('Page',len(pages),'total posts',len(rows),flush=True)
 category=json.loads(get(category_api)[0]);api_url=category['_links']['wp:post_type'][0]['href'];api=[];page=1
 while True:
  separator='&' if '?' in api_url else '?';raw,headers=get(api_url+separator+'per_page=100&page='+str(page));(out/f'api-page-{page}.json').write_bytes(raw);items=json.loads(raw);assert isinstance(items,list);api+=items
  if page>=int(headers['x-wp-totalpages']):break
  page+=1
 assert len({r['id'] for r in rows})==len(rows)==len(api)==int(headers['x-wp-total'])==category['count']
 assert {r['id'] for r in rows}=={r['id'] for r in api}
 result={'archive':{'pages':pages,'posts':rows,'count':len(rows)},'posts':api}
 (out/'historical-snapshot.json').write_text(json.dumps(result,ensure_ascii=False,indent=2)+'\n');print('Complete:',len(pages),'pages;',len(rows),'entries; archive/API sets identical')
if __name__=='__main__':
 p=argparse.ArgumentParser();p.add_argument('--output',type=Path,required=True);a=p.parse_args();inventory(a.output)
