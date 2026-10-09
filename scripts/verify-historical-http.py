#!/usr/bin/env python3
"""Read-only HTTP audit of the actual complete local archive and its media.
Requires beautifulsoup4 and a JSON export of verify-historical-novedades.php.
"""
import argparse, concurrent.futures, json, re, urllib.parse, urllib.request
from pathlib import Path
from bs4 import BeautifulSoup

def run(path,out):
 data=json.loads(path.read_text());base=urllib.parse.urlsplit(data['first15'][0]['url']);base=base.scheme+'://'+base.netloc;assert data['count']==222
 host=urllib.parse.urlsplit(base).hostname
 expected={x['id']:x for x in data['rows']};expected.update({x['id']:x for x in data['first15']})
 assets=set();errors=[];pages=[];cards=[]
 def read(u,method='GET'):
  with urllib.request.urlopen(urllib.request.Request(u,method=method),timeout=30) as r:
   assert r.status==200,(u,r.status)
   return r.read().decode() if method=='GET' else ''
 n=1
 while True:
  u=base+'/category/novedades/'+('' if n==1 else '?paged='+str(n));s=BeautifulSoup(read(u),'html.parser');rows=[]
  for a in s.select('article.news-archive-card'):
   id=int(a['id'].split('-')[1]);d=a.select_one('time')['datetime'];rows.append(id);cards.append((id,d))
   for img in a.select('img[src]'):assets.add(img['src'])
  pages.append({'url':u,'ids':rows})
  if not any(a.get_text(' ',strip=True)=='Siguiente' for a in s.select('.news-archive__pagination a')):break
  n+=1;assert n<30
 assert len(cards)==222 and len({id for id,d in cards})==222,'Duplicate/missing archive IDs'
 assert [d for id,d in cards]==sorted([d for id,d in cards],reverse=True),'Actual archive chronology'
 assert [id for id,d in cards[:15]]==[x['id'] for x in data['first15']],'Actual first15 differs from WP query'
 home=BeautifulSoup(read(base+'/'),'html.parser')
 homeurls=[a.select_one('.news-card__title a')['href'] for a in home.select('article.news-card')]
 assert homeurls==[x['url'] for x in data['first15'][:len(homeurls)]],'Home order differs'
 # All published articles, with dates shown once and images hosted locally.
 def article(item):
  try:
   id=item[0];e=expected[id];s=BeautifulSoup(read(e['url']),'html.parser');a=s.select_one('article.news-article');assert a,'Editorial template missing'
   assert len(a.select('.entry-meta time'))==1,'Publication date repeated'
   assert a.select_one('.entry-meta time')['datetime'][:10]==e['date'][:10],'Visible date differs'
   assert a.select_one('h1').get_text()==e['title'],'Title differs'
   urls=set()
   for img in a.select('img[src]'):
    assert urllib.parse.urlsplit(img['src']).hostname==host,'Image hotlink'
    urls.add(img['src'])
    for part in img.get('srcset','').split(','):
     if part.strip():urls.add(part.strip().rsplit(' ',1)[0])
   for link in a.select('a[href]'):
    if '/wp-content/uploads/' in link['href'] and urllib.parse.urlsplit(link['href']).hostname==host:urls.add(link['href'])
   return urls,None
  except Exception as e:return set(),{'id':item[0],'error':str(e)}
 with concurrent.futures.ThreadPoolExecutor(max_workers=6) as pool:
  for urls,error in pool.map(article,cards):assets.update(urls);errors.extend([error] if error else [])
 def asset(u):
  try:read(u,'HEAD');return None
  except Exception as e:return {'url':u,'error':str(e)}
 with concurrent.futures.ThreadPoolExecutor(max_workers=8) as pool:errors.extend(x for x in pool.map(asset,sorted(assets)) if x)
 policy=json.loads((Path(__file__).resolve().parent.parent/'content/novedades-reconciliation-policy.json').read_text())
 excluded=data.get('excluded',policy['retire']+policy['review'])
 for e in excluded:
  assert e['id'] not in {i for i,d in cards}
  search=BeautifulSoup(read(base+'/category/novedades/?buscar='+urllib.parse.quote(e['title'])),'html.parser')
  assert not search.select_one('#post-'+str(e['id'])),'Excluded result in search'
 report={'articles':len(cards),'pages':len(pages),'local_media_urls':len(assets),'errors':errors,'archive_pages':pages,'first15':data['first15'],'home_count':len(homeurls),'excluded_searches':len(policy['retire'])+len(policy['review'])}
 out.write_text(json.dumps(report,ensure_ascii=False,indent=2)+'\n');print(json.dumps({k:v for k,v in report.items() if k not in ['archive_pages','first15']},ensure_ascii=False));assert not errors,'HTTP/media errors'
if __name__=='__main__':
 p=argparse.ArgumentParser();p.add_argument('--verified',type=Path,required=True);p.add_argument('--output',type=Path,required=True);a=p.parse_args();run(a.verified,a.output)
