param([string]$BaseUrl='http://localhost:8080', [int]$DebugPort=9223, [switch]$KeyboardOnly)
$ErrorActionPreference='Stop'
$ddnaBrowserPath='C:\Program Files\Google\Chrome\Application\chrome.exe'
$ddnaProfile=Join-Path ([IO.Path]::GetTempPath()) ('ddna-browser-audit-'+[guid]::NewGuid().ToString('N'))
New-Item -ItemType Directory -Path $ddnaProfile | Out-Null
$ddnaBrowserProcess=Start-Process -FilePath $ddnaBrowserPath -ArgumentList @('--headless=new',"--remote-debugging-port=$DebugPort", "--user-data-dir=$ddnaProfile",'--no-first-run','about:blank') -WindowStyle Hidden -PassThru
$ddnaSocket=New-Object Net.WebSockets.ClientWebSocket
$script:ddnaMessageId=0
$script:ddnaFailureCount=0
function Invoke-DDNACdp([string]$Method, $Parameters=@{}) {
	$script:ddnaMessageId++
	$id=$script:ddnaMessageId
	$json=@{id=$id;method=$Method;params=$Parameters} | ConvertTo-Json -Depth 30 -Compress
	$bytes=[Text.Encoding]::UTF8.GetBytes($json)
	$segment=New-Object 'System.ArraySegment[byte]' -ArgumentList @(,$bytes)
	$ddnaSocket.SendAsync($segment,[Net.WebSockets.WebSocketMessageType]::Text,$true,[Threading.CancellationToken]::None).GetAwaiter().GetResult() | Out-Null
	do {
		$buffer=New-Object byte[] 65536
		$builder=New-Object Text.StringBuilder
		do {
			$receiveSegment=New-Object 'System.ArraySegment[byte]' -ArgumentList @(,$buffer)
			$result=$ddnaSocket.ReceiveAsync($receiveSegment,[Threading.CancellationToken]::None).GetAwaiter().GetResult()
			[void]$builder.Append([Text.Encoding]::UTF8.GetString($buffer,0,$result.Count))
		} until($result.EndOfMessage)
		$message=$builder.ToString() | ConvertFrom-Json
	} until($message.id -eq $id)
	if($message.error){throw ($message.error | ConvertTo-Json -Compress)}
	return $message.result
}
function Invoke-DDNAJs([string]$Expression) {
	$response=Invoke-DDNACdp 'Runtime.evaluate' @{expression=$Expression;returnByValue=$true;awaitPromise=$true}
	if($response.exceptionDetails){throw ($response.exceptionDetails | ConvertTo-Json -Depth 10 -Compress)}
	return $response.result.value
}
try {
	$ddnaPage=$null
	for($i=0;$i -lt 30 -and -not $ddnaPage;$i++) {
		try { $ddnaTargets=Invoke-RestMethod "http://localhost:$DebugPort/json/list"; $ddnaPage=$ddnaTargets | Where-Object type -eq 'page' | Select-Object -First 1 } catch { }
		if(-not $ddnaPage){Start-Sleep -Milliseconds 200}
	}
	if(-not $ddnaPage){throw 'Chrome DevTools no disponible.'}
	$ddnaSocket.ConnectAsync([uri]$ddnaPage.webSocketDebuggerUrl,[Threading.CancellationToken]::None).GetAwaiter().GetResult() | Out-Null
	Invoke-DDNACdp 'Page.enable' | Out-Null
	Invoke-DDNACdp 'Runtime.enable' | Out-Null
	Invoke-DDNACdp 'Page.addScriptToEvaluateOnNewDocument' @{source='window.__ddnaErrors=[];window.addEventListener("error",e=>window.__ddnaErrors.push(e.message));window.addEventListener("unhandledrejection",e=>window.__ddnaErrors.push(String(e.reason)));'} | Out-Null
	$ddnaWidths=if($KeyboardOnly){@()}else{@(320,375,430,768,1024,1366,1440,1920)}
	foreach($width in $ddnaWidths) {
		Invoke-DDNACdp 'Emulation.setDeviceMetricsOverride' @{width=$width;height=900;deviceScaleFactor=1;mobile=($width -lt 768)} | Out-Null
		Invoke-DDNACdp 'Page.navigate' @{url="$BaseUrl/"} | Out-Null
		Start-Sleep -Milliseconds 400
		$homeResult=Invoke-DDNAJs @'
(async function(){
 for(let i=0;i<60 && document.readyState!=='complete';i++) await new Promise(r=>setTimeout(r,100));
 window.__ddnaErrors=window.__ddnaErrors||[];
 const failures=[];const fail=x=>failures.push(x);
 const overflow=()=>document.documentElement.scrollWidth>innerWidth+1;
 if(document.querySelectorAll('h1').length!==1)fail('Home H1');
 if(overflow())fail('Home overflow');
 const triggers=[...document.querySelectorAll('.quick-access-card[data-home-panel-trigger]')];
 if(triggers.length!==6)fail('Cantidad accesos');
 for(const trigger of triggers){
  trigger.click();await new Promise(r=>setTimeout(r,350));
  const panel=document.getElementById(trigger.getAttribute('aria-controls'));
  if(panel.hidden||trigger.getAttribute('aria-expanded')!=='true')fail('Panel '+trigger.dataset.homePanelTrigger);
  if(location.hash!=='#'+trigger.dataset.homePanelTrigger)fail('Hash');
  if(overflow())fail('Panel overflow '+trigger.dataset.homePanelTrigger);
 }
 document.querySelector('[data-home-panel-trigger="territorio"]').click();await new Promise(r=>setTimeout(r,350));
 const markers=[...document.querySelectorAll('[data-territory-marker]')];
 if(markers.length!==6)fail('Cantidad markers');
 for(const marker of markers){
  const before=marker.getBoundingClientRect();marker.click();await new Promise(r=>setTimeout(r,180));
  const popup=document.getElementById(marker.getAttribute('aria-controls'));
  if(popup.hidden||marker.getAttribute('aria-expanded')!=='true')fail('Popup');
  if(document.querySelectorAll('[data-territory-popover]:not([hidden])').length!==1)fail('Multiple popups');
  if(overflow())fail('Popup overflow');
  const image=document.querySelector('.territory-map__image').getBoundingClientRect();
  const expected=image.top+image.height*parseFloat(marker.style.getPropertyValue('--marker-top'))/100;
  const after=marker.getBoundingClientRect();
  if(Math.abs(after.top+after.height*.9+6.4-expected)>8)fail('Marker shifts on popup '+marker.getAttribute('aria-controls'));
  popup.querySelector('[data-territory-close]').click();
  if(document.activeElement!==marker)fail('Focus restore');
 }
 const tracks=[...document.querySelectorAll('[data-carousel-track]')];
 const conhecer=document.querySelector('[data-home-panel-trigger="quiero-conocer"]');conhecer.click();await new Promise(r=>setTimeout(r,350));
 for(const track of tracks.filter(x=>x.getClientRects().length)){
  const root=track.closest('[data-carousel]');const next=root.querySelector('[data-carousel-next]');
  if(next&&!next.disabled){const left=track.scrollLeft;next.click();await new Promise(r=>setTimeout(r,500));if(track.scrollLeft<=left)fail('Carousel next');}
 }
 return {width:innerWidth,failures,errors:window.__ddnaErrors};
})()
'@
		$homeResult | ConvertTo-Json -Depth 10 -Compress
		$script:ddnaFailureCount+=@($homeResult.failures).Count+@($homeResult.errors).Count
		foreach($slug in @('defensoria','asistencia','contacto','normativas','convenios','programas-participacion-nnya','acompanamiento-formacion-adultos','cooperacion-internacional-interinstitucional','agenda','prensa','defensoria-en-los-medios','comunicados')) {
			Invoke-DDNACdp 'Page.navigate' @{url="$BaseUrl/$slug/"} | Out-Null
			Start-Sleep -Milliseconds 100
			$pageResult=Invoke-DDNAJs '(async()=>{for(let i=0;i<60&&document.readyState!=="complete";i++)await new Promise(r=>setTimeout(r,100));return {overflow:document.documentElement.scrollWidth>innerWidth+1,h1:document.querySelectorAll("h1").length,title:document.title}})()'
			if($pageResult.overflow -or $pageResult.h1 -ne 1){$script:ddnaFailureCount++;"PAGE_FAIL width=$width slug=$slug $($pageResult|ConvertTo-Json -Compress)"}
		}
	}
	foreach($width in @(375,1440)) {
		Invoke-DDNACdp 'Emulation.setDeviceMetricsOverride' @{width=$width;height=900;deviceScaleFactor=1;mobile=($width -lt 768)} | Out-Null
		Invoke-DDNACdp 'Page.navigate' @{url="$BaseUrl/#territorio"} | Out-Null
		Start-Sleep -Milliseconds 500
		Invoke-DDNAJs '(async()=>{for(let i=0;i<60&&document.readyState!=="complete";i++)await new Promise(r=>setTimeout(r,100));const m=document.querySelector("[aria-controls=territory-popover-cordoba-capital]");m.scrollIntoView({block:"center"});m.focus()})()' | Out-Null
		Invoke-DDNACdp 'Input.dispatchKeyEvent' @{type='rawKeyDown';key='Tab';code='Tab';windowsVirtualKeyCode=9} | Out-Null
		Invoke-DDNACdp 'Input.dispatchKeyEvent' @{type='keyUp';key='Tab';code='Tab';windowsVirtualKeyCode=9} | Out-Null
		$tabResult=Invoke-DDNAJs 'document.activeElement.getAttribute("aria-controls")==="territory-popover-colonia-caroya"'
		Invoke-DDNACdp 'Input.dispatchKeyEvent' @{type='rawKeyDown';key='Tab';code='Tab';windowsVirtualKeyCode=9;modifiers=8} | Out-Null
		Invoke-DDNACdp 'Input.dispatchKeyEvent' @{type='keyUp';key='Tab';code='Tab';windowsVirtualKeyCode=9;modifiers=8} | Out-Null
		$shiftResult=Invoke-DDNAJs 'document.activeElement.getAttribute("aria-controls")==="territory-popover-cordoba-capital"'
		Invoke-DDNACdp 'Input.dispatchKeyEvent' @{type='keyDown';key='Enter';code='Enter';windowsVirtualKeyCode=13;text="`r"} | Out-Null
		Invoke-DDNACdp 'Input.dispatchKeyEvent' @{type='keyUp';key='Enter';code='Enter';windowsVirtualKeyCode=13} | Out-Null
		$enterResult=Invoke-DDNAJs '!document.getElementById("territory-popover-cordoba-capital").hidden'
		Invoke-DDNACdp 'Input.dispatchKeyEvent' @{type='rawKeyDown';key='Escape';code='Escape';windowsVirtualKeyCode=27} | Out-Null
		Invoke-DDNACdp 'Input.dispatchKeyEvent' @{type='keyUp';key='Escape';code='Escape';windowsVirtualKeyCode=27} | Out-Null
		$escapeResult=Invoke-DDNAJs 'document.getElementById("territory-popover-cordoba-capital").hidden&&!document.getElementById("panel-territorio").hidden'
		Invoke-DDNAJs 'document.querySelector("[aria-controls=territory-popover-cosquin]").focus()' | Out-Null
		Invoke-DDNACdp 'Input.dispatchKeyEvent' @{type='rawKeyDown';key=' ';code='Space';windowsVirtualKeyCode=32} | Out-Null
		Invoke-DDNACdp 'Input.dispatchKeyEvent' @{type='keyUp';key=' ';code='Space';windowsVirtualKeyCode=32} | Out-Null
		$spaceResult=Invoke-DDNAJs '!document.getElementById("territory-popover-cosquin").hidden'
		$focusResult=Invoke-DDNAJs 'getComputedStyle(document.activeElement).outlineStyle!=="none"'
		Invoke-DDNAJs 'document.querySelector("#territory-popover-cosquin [data-territory-close]").click();document.activeElement.blur()' | Out-Null
		$hoverResult='not-applicable-mobile'
		if($width -ge 768) {
			Invoke-DDNAJs 'document.querySelector("[aria-controls=territory-popover-cruz-del-eje]").scrollIntoView({block:"center",behavior:"instant"})' | Out-Null
			$point=Invoke-DDNAJs '(()=>{const r=document.querySelector("[aria-controls=territory-popover-cruz-del-eje]").getBoundingClientRect();return {x:r.x+r.width/2,y:r.y+r.height/3}})()'
			Invoke-DDNACdp 'Input.dispatchMouseEvent' @{type='mouseMoved';x=$point.x;y=$point.y} | Out-Null
			Start-Sleep -Milliseconds 300
			$hoverResult=Invoke-DDNAJs 'parseFloat(getComputedStyle(document.querySelector("[aria-controls=territory-popover-cruz-del-eje] .territory-marker__label")).opacity)>0.9'
		}
		@{keyboardWidth=$width;tab=$tabResult;shiftTab=$shiftResult;enter=$enterResult;space=$spaceResult;escape=$escapeResult;focus=$focusResult;hover=$hoverResult} | ConvertTo-Json -Compress
		if(-not ($tabResult -and $shiftResult -and $enterResult -and $spaceResult -and $escapeResult -and $focusResult) -or ($width -ge 768 -and -not $hoverResult)){$script:ddnaFailureCount++}
		Invoke-DDNAJs 'document.querySelector(".quick-access-card[data-home-panel-trigger=quiero-conocer]").click();document.querySelector(".home-programs").scrollIntoView({block:"start"})' | Out-Null
		Start-Sleep -Milliseconds 400
		$ddnaScreenshot=Invoke-DDNACdp 'Page.captureScreenshot' @{format='png';captureBeyondViewport=$false}
		$ddnaOutput=Join-Path $PSScriptRoot '../backups'
		New-Item -ItemType Directory -Path $ddnaOutput -Force | Out-Null
		[IO.File]::WriteAllBytes((Join-Path $ddnaOutput "final-sept-2026-$width.png"),[Convert]::FromBase64String($ddnaScreenshot.data))
	}
	if($script:ddnaFailureCount -gt 0){throw "Fallos detectados: $script:ddnaFailureCount"}
} finally {
	$ddnaSocket.Dispose()
	if($ddnaBrowserProcess -and -not $ddnaBrowserProcess.HasExited){Stop-Process -Id $ddnaBrowserProcess.Id -ErrorAction SilentlyContinue}
}
