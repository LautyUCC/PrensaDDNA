#!/usr/bin/env python3
"""Build portable historical editorial content from the checked, complete inventory.
Requires beautifulsoup4. GET requests only; no WordPress writes. Cached files are
verified by SHA256. Re-run preserves downloaded originals, never source folders.
"""
import argparse, concurrent.futures, hashlib, html, json, mimetypes, re, time, urllib.parse, urllib.request
from pathlib import Path
from bs4 import BeautifulSoup, NavigableString
ROOT=Path(__file__).resolve().parent.parent
UA='Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36'

def url(u):
    u=html.unescape(u.strip()); p=urllib.parse.urlsplit(u)
    return urllib.parse.urlunsplit(('https' if p.hostname in ('ddna.cba.gov.ar','www.ddna.cba.gov.ar') else p.scheme,p.netloc,urllib.parse.quote(urllib.parse.unquote(p.path),safe='/@:+'),p.query,''))

def get(u):
    for attempt in range(3):
        try:
            with urllib.request.urlopen(urllib.request.Request(url(u),headers={'User-Agent':UA}),timeout=60) as r:return r.read(),r.headers.get_content_type()
        except Exception:
            if attempt==2:raise
            time.sleep(attempt+1)

def image_url(img):
    if img.get('data-ddna-source'):return img['data-ddna-source']
    # Largest declared source, never inferred filenames.
    sources=[]
    for item in img.get('srcset','').split(','):
        v=item.strip().rsplit(' ',1)
        if len(v)==2 and re.fullmatch(r'\d+w',v[1]):sources.append((int(v[1][:-1]),v[0]))
    parent=img.parent
    if parent.name=='a' and re.search(r'\.(?:jpe?g|png|webp|gif)(?:\?|$)',parent.get('href',''),re.I):return url(parent['href'])
    return url(max(sources)[1] if sources else img.get('src') or img.get('data-src') or '')

def build(cache):
    data=json.loads((ROOT/'content/novedades-historical-inventory.json').read_text());posts=data['posts']
    assert len(posts)==data['archive']['count']==219
    matches={x['historical_id']:x for x in data['matching']['matches']}
    bundle=ROOT/'content/media/historical-novedades';bundle.mkdir(parents=True,exist_ok=True)
    cache.mkdir(parents=True,exist_ok=True);metadata=cache/'media';metadata.mkdir(exist_ok=True)
    def media(p):
        id=p['featured_media']
        if id <= 0:return None
        file=metadata/f'{id}.json'
        try:
            if not file.exists():file.write_bytes(get(f'https://ddna.cba.gov.ar/wp-json/wp/v2/media/{id}')[0])
            return json.loads(file.read_text())['source_url']
        except Exception:
            # Public archive fallback uses actual article thumbnail; no authentication.
            for page in data['archive']['pages']:
                f=cache/('archive-'+hashlib.sha256(page['url'].encode()).hexdigest()+'.html')
                if not f.exists():f.write_bytes(get(page['url'])[0])
                s=BeautifulSoup(f.read_text(),'html.parser');a=s.find(id='post-'+str(p['id']))
                if a and a.find('img'):return image_url(a.find('img'))
            return None
    with concurrent.futures.ThreadPoolExecutor(max_workers=5) as ex:featured=dict(zip([p['id'] for p in posts],ex.map(media,posts)))
    needed={};soups={}
    for p in posts:
        s=BeautifulSoup(p['content']['rendered'],'html.parser');soups[p['id']]=s
        if featured[p['id']]:needed[url(featured[p['id']])]=html.unescape(p['title']['rendered'])
        for img in s.select('img'):
            u=image_url(img)
            img['data-ddna-source']=u
            if u:needed[u]=img.get('alt','')
        for a in s.select('a[href]'):
            if urllib.parse.urlsplit(a['href']).hostname in ('ddna.cba.gov.ar','www.ddna.cba.gov.ar') and re.search(r'\.(pdf|docx?)(?:$|\?)',a['href'],re.I):needed[url(a['href'])]=a.get_text(' ',strip=True)
    downloaded={};failures=[]
    def download(item):
        u,alt=item;f=cache/('asset-'+hashlib.sha256(u.encode()).hexdigest());mimefile=f.with_suffix('.mime')
        try:
            if not f.exists():
                raw,mime=get(u)
                if mime not in ('image/jpeg','image/png','image/webp','image/gif','application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'):raise ValueError('Unexpected MIME '+mime)
                f.write_bytes(raw);mimefile.write_text(mime)
            raw=f.read_bytes();mime=mimefile.read_text();key=hashlib.sha256(raw).hexdigest();ext={'image/jpeg':'.jpg','image/png':'.png','image/webp':'.webp','image/gif':'.gif','application/pdf':'.pdf','application/msword':'.doc','application/vnd.openxmlformats-officedocument.wordprocessingml.document':'.docx'}[mime]
            dest=bundle/(key+ext)
            if not dest.exists():dest.write_bytes(raw)
            return u,{'path':str(dest.relative_to(ROOT/'content')),'sha256':key,'size':len(raw),'mime':mime,'alt':alt,'source_url':u,'filename':urllib.parse.unquote(urllib.parse.urlsplit(u).path.split('/')[-1])},None
        except Exception as e:return u,None,str(e)
    with concurrent.futures.ThreadPoolExecutor(max_workers=5) as ex:
        for n,(u,a,error) in enumerate(ex.map(download,needed.items()),1):
            if a:downloaded[u]=a
            else:failures.append({'url':u,'error':error})
            if n%50==0:print(f'Downloaded/checked {n}/{len(needed)}; unavailable {len(failures)}',flush=True)
    assets={a['sha256']:a for a in downloaded.values()};entries=[]
    for p in posts:
        s=soups[p['id']];gallery=[];pending=[];carousel=False;hidden_gallery_titles=[]
        title=BeautifulSoup(p['title']['rendered'],'html.parser').get_text()
        norm=lambda text: re.sub(r'[\W_]+','',text.casefold())
        removed_title=None
        first_heading=s.find(['h1','h2','h3'])
        if first_heading and norm(first_heading.get_text())==norm(title):
            removed_title=first_heading.get_text();first_heading.decompose()
        for heading in s.select('h1'):heading.name='h2'
        # Remove player controls / builder artifacts, not editorial text.
        for x in list(s.select('script,style,noscript,.swiper-pagination,.elementor-swiper-button,.su-slider-prev,.su-slider-next,.su-slider-pagination')):x.decompose()
        selectors='.elementor-widget-image-carousel,.su-slider,.su-custom-gallery,.gallery'
        for group in list(s.select(selectors)):
            if group.parent is None:continue
            keys=[]
            for img in group.select('img'):
                a=downloaded.get(image_url(img))
                if a and a['sha256'] not in keys:keys.append(a['sha256'])
            cl=' '.join(group.get('class',[]));is_carousel=('image-carousel' in cl or 'su-slider' in cl)
            # Native gallery remains in its original position; explicit carousels
            # use the current editorial template and retain original photo order.
            if is_carousel:
                carousel=True;gallery.extend(k for k in keys if k not in gallery);group.decompose()
            else:
                if 'su-custom-gallery-title-never' in group.get('class',[]):
                    for caption in group.select('.su-custom-gallery-title'):
                        hidden_gallery_titles.append(caption.get_text());caption.decompose()
                for link in group.select('a[href]'):
                    img=link.find('img')
                    if img and not link.get_text(strip=True) and urllib.parse.urlsplit(link['href']).hostname=='ddna.cba.gov.ar':
                        a=downloaded.get(image_url(img))
                        if a:link['href']='{{media:'+a['sha256']+'}}'
                for item in group.select('.su-custom-gallery-slide'):item.name='figure'
                group.name='div';group.attrs={'class':['wp-block-gallery']}
                for item in group.select('dl.gallery-item'):
                    item.name='figure';item.attrs={}
                    for child in item.select('dt'):child.unwrap()
                    for caption in item.select('dd'):caption.name='figcaption'
        for img in list(s.select('img')):
            a=downloaded.get(image_url(img))
            if a:
                img.attrs={'src':'{{media:'+a['sha256']+'}}','alt':img.get('alt',''),'loading':'lazy','decoding':'async'}
            else:
                pending.append('Unavailable source image: '+image_url(img));img.decompose()
        for a in list(s.select('a[href]')):
            u=url(a['href'])
            if u in downloaded:a['href']='{{media:'+downloaded[u]['sha256']+'}}'
            elif u in needed:
                pending.append('Unavailable source document/image: '+u);a.unwrap()
        for iframe in list(s.select('iframe')):
            u=html.unescape(iframe.get('src',''));pu=urllib.parse.urlsplit(u);qs=urllib.parse.parse_qs(pu.query)
            if pu.hostname in ('www.youtube.com','youtube.com') and '/embed/' in pu.path:
                vid=pu.path.rsplit('/',1)[-1];u='https://www.youtube.com/playlist?list='+qs.get('list',[''])[0] if vid=='videoseries' else 'https://www.youtube.com/watch?v='+vid
            elif 'facebook.com' in (pu.hostname or '') and qs.get('href'):u=qs['href'][0]
            # WordPress native embed markup; external video preserved as original link.
            fragment=BeautifulSoup('<figure class="wp-block-embed"><div class="wp-block-embed__wrapper"><a href="'+html.escape(u,quote=True)+'">'+html.escape(u)+'</a></div></figure>','html.parser');iframe.replace_with(fragment)
        for tag in list(s.find_all(True)):
            if tag.parent is None:continue
            if tag.name in ('div','section') and tag.get('class') not in (['wp-block-gallery'],['wp-block-embed__wrapper']):tag.unwrap();continue
            attrs={k:v for k,v in tag.attrs.items() if k in ('href','src','alt','title','loading','decoding','colspan','rowspan')}
            if tag.name in ('figure','div') and any(c in ('wp-block-gallery','wp-block-embed','wp-block-embed__wrapper') for c in tag.get('class',[])):attrs['class']=tag['class']
            tag.attrs=attrs
        title=BeautifulSoup(p['title']['rendered'],'html.parser').get_text()
        content=str(s).strip();match=matches.get(p['id'])
        # Slugs of previously published local entries stay stable.
        f=downloaded.get(url(featured[p['id']])) if featured[p['id']] else None
        if not f and p['featured_media'] > 0:pending.append('Featured source unavailable')
        entries.append({'historical_id':p['id'],'source_id':'ddna-historical-news-'+str(p['id']),'existing_local_id':match['local_id'] if match else 0,**({'existing_local_source_id':match['local_source_id']} if match else {}),'match_method':match['method'] if match else 'missing authoritative archive ID','title':title,'slug':match['local_slug'] if match else p['slug'],'source_url':p['link'],'post_date':p['date'].replace('T',' '),'post_date_gmt':p['date_gmt'].replace('T',' '),'content':content,'featured_image':f['sha256'] if f else None,'carousel':carousel,'gallery':gallery,'pending':pending,**({'hidden_source_gallery_titles':hidden_gallery_titles} if hidden_gallery_titles else {}),**({'removed_duplicate_title_heading':removed_title} if removed_title else {}),'tag':'novedad'})
    manifest={'version':'ddna-historical-novedades-v1','inventory_pages':len(data['archive']['pages']),'entries':entries,'assets':assets,'unavailable_sources':failures}
    (ROOT/'content/novedades-historical-manifest.json').write_text(json.dumps(manifest,ensure_ascii=False,indent=2)+'\n')
    print(f'{len(entries)} entries; {len(assets)} unique files; {len(failures)} unavailable sources',flush=True)
if __name__=='__main__':
    parser=argparse.ArgumentParser();parser.add_argument('--cache',type=Path,required=True);args=parser.parse_args();build(args.cache)
