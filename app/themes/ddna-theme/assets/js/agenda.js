/** Progressive monthly enhancement. Dates remain YYYY-MM-DD wall-clock strings. */
(() => {
 const root = document.querySelector('[data-agenda]');
 if (!root) return;
 const select = root.querySelector('select'), form = root.querySelector('form');
 const prev = root.querySelector('[data-agenda-prev]'), next = root.querySelector('[data-agenda-next]');
 const grid = root.querySelector('.agenda-grid-wrap'), details = root.querySelector('[data-agenda-details]');
 const heading = root.querySelector('h2'), status = root.querySelector('.agenda-status');
 let year = Number(root.dataset.year), month = Number(root.dataset.month), today = root.dataset.today, request;
 const months = [...select.options].map(o => o.textContent);
 const node = (tag, text, cls) => { const e = document.createElement(tag); if (text !== undefined) e.textContent = text; if (cls) e.className = cls; return e; };
 const iso = (day, m = month, y = year) => `${y}-${String(m).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
 function navigation() {
  select.value = String(month);
  for (const [link, boundary, value] of [[prev, month === 1, month-1],[next, month === 12, month+1]]) {
   link.setAttribute('aria-disabled', String(boundary)); link.tabIndex = boundary ? -1 : 0;
   const url = new URL(location.href); url.searchParams.set('agenda_month', String(Math.max(1,Math.min(12,value)))); link.href = url.href;
  }
 }
 function render(events) {
  const table = node('table', undefined, 'agenda-grid'), caption = node('caption', `${months[month-1]} ${year}`, 'screen-reader-text'); table.append(caption);
  const thead = node('thead'), week = node('tr');
  for (const day of ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo']) { const th=node('th'); th.scope='col'; const abbr=node('abbr',day[0]); abbr.title=day; th.append(abbr);week.append(th); } thead.append(week);table.append(thead);
  const body=node('tbody'), first=new Date(year,month-1,1), offset=(first.getDay()+6)%7, days=new Date(year,month,0).getDate();
  const groups=new Map(); for (const event of events) { const list=groups.get(event.date)||[];list.push(event);groups.set(event.date,list); }
  let row;
  for (let cell=0;cell<Math.ceil((offset+days)/7)*7;cell++) {
   if(cell%7===0){row=node('tr');body.append(row);} const td=node('td'), day=cell-offset+1; row.append(td);
   if(day<1||day>days){td.className='agenda-day--empty';continue;} const date=iso(day);td.className=`agenda-day${date===today?' is-today':date<today?' is-past':''}`;
   const number=node('span',String(day),'agenda-day__number'); if(date===today)number.append(node('span',', hoy','screen-reader-text'));td.append(number);
   for(const event of groups.get(date)||[]){const a=node('a',undefined,'agenda-event');a.href=`#agenda-event-${event.id}`;a.dataset.agendaEvent=String(event.id);const time=node('time',event.time);time.dateTime=`${event.date}T${event.time}`;a.append(time,node('span',event.title));td.append(a);}
  }table.append(body);grid.replaceChildren(table);details.replaceChildren();
  for(const event of events){const d=node('details',undefined,'agenda-detail');d.id=`agenda-event-${event.id}`;d.append(node('summary',event.title));const dl=node('dl');for(const [label,value] of [['Fecha',event.date.split('-').reverse().join('/')],['Hora',event.time],['Lugar',event.location]])dl.append(node('dt',label),node('dd',value));d.append(dl);const description=node('div',undefined,'agenda-description');description.innerHTML=event.description; // HTML allowlist enforced by our same-origin WordPress endpoint.
   d.append(description);details.append(d);
  }
  heading.textContent=`${months[month-1]} ${year}`;
  status.textContent=events.length?'Seleccioná una actividad para consultar sus detalles.':'No hay actividades publicadas para este mes.';
 }
 async function load(wanted) {
  if(wanted<1||wanted>12)return;
  request?.abort();const current=new AbortController();request=current;root.setAttribute('aria-busy','true');status.textContent='Cargando actividades…';
  try {const url=new URL(root.dataset.endpoint);url.searchParams.set('year',String(year));url.searchParams.set('month',String(wanted));const response=await fetch(url,{signal:current.signal,credentials:'omit'});if(!response.ok)throw new Error('agenda');const data=await response.json();if(current!==request)return;
   year=data.year;month=data.month;today=data.today;render(data.events);navigation();const page=new URL(location.href);page.searchParams.set('agenda_month',String(month));history.replaceState(null,'',page); // Month selection only; no page reload.
  }catch(error){if(error.name!=='AbortError'){select.value=String(month);status.textContent='No pudimos cargar el mes. Probá otra vez o abrilo con los enlaces de mes en otra pestaña.';}}
  finally{if(current===request)root.removeAttribute('aria-busy');}
 }
 form.addEventListener('submit',e=>{e.preventDefault();load(Number(select.value));});select.addEventListener('change',()=>load(Number(select.value)));
 for(const [link,delta] of [[prev,-1],[next,1]])link.addEventListener('click',e=>{if(e.ctrlKey||e.metaKey||e.shiftKey||e.altKey)return;e.preventDefault();load(month+delta);});
 root.addEventListener('click',e=>{const a=e.target.closest('[data-agenda-event]');if(!a||e.ctrlKey||e.metaKey||e.shiftKey||e.altKey)return;const detail=document.getElementById(`agenda-event-${a.dataset.agendaEvent}`);if(detail){e.preventDefault();detail.open=true;detail.querySelector('summary').focus();detail.scrollIntoView({behavior:matchMedia('(prefers-reduced-motion: reduce)').matches?'instant':'smooth',block:'nearest'});}});
 navigation();
})();
