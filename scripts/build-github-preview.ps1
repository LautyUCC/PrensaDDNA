param(
	[string]$LocalUrl='http://localhost:8080',
	[string]$BasePath='',
	[string]$PublicOrigin='',
	[int]$MaxPages=80
)
$ErrorActionPreference='Stop'
$ddnaRoot=[IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
$ddnaDestination=Join-Path $ddnaRoot 'github-preview'
$ddnaLocal=[uri]($LocalUrl.TrimEnd('/')+'/')
if($ddnaLocal.Host -notin @('localhost','127.0.0.1','::1')){throw 'El exportador solo consulta WordPress LOCAL.'}
$ddnaRemote=git -C $ddnaRoot remote get-url origin
if($ddnaRemote -match 'github\.com[:/](?<owner>[^/]+)/(?<repo>[^/]+?)(?:\.git)?$'){
	if(-not $BasePath){$BasePath='/'+$Matches.repo+'/'}
	if(-not $PublicOrigin){$PublicOrigin='https://'+$Matches.owner.ToLowerInvariant()+'.github.io'}
}
if(-not $BasePath -or -not $PublicOrigin){throw 'Configurar -BasePath y -PublicOrigin, o un remoto GitHub válido.'}
$BasePath='/'+$BasePath.Trim('/')+'/'
if($BasePath -eq '//'){$BasePath='/'}
if($BasePath -notmatch '^/(?:[a-zA-Z0-9_.-]+/)*$'){throw 'BasePath no válido.'}
$ddnaPublic=[uri]$PublicOrigin
if($ddnaPublic.Scheme -ne 'https' -or $ddnaPublic.Host -in @('localhost','127.0.0.1')){throw 'PublicOrigin debe ser una URL HTTPS pública.'}
$PublicOrigin=$PublicOrigin.TrimEnd('/')
if(Test-Path $ddnaDestination){
	$marker=Join-Path $ddnaDestination 'preview-build.json'
	if(-not (Test-Path $marker) -or (Get-Content $marker -Raw | ConvertFrom-Json).generator -ne 'ddna-github-preview-v1'){throw 'github-preview existente no tiene marcador de build; no se reemplaza.'}
}
$ddnaCheck=Invoke-WebRequest -Uri $ddnaLocal.AbsoluteUri -UseBasicParsing -TimeoutSec 30
if($ddnaCheck.StatusCode -ne 200){throw 'WordPress local no responde HTTP 200.'}
$ddnaBuild=Join-Path $ddnaRoot ('backups/github-preview-build-'+[guid]::NewGuid().ToString('N'))
New-Item -ItemType Directory -Path $ddnaBuild -Force | Out-Null
$ddnaUtf8=New-Object Text.UTF8Encoding($false)
$script:ddnaPages=New-Object 'Collections.Generic.Queue[uri]'
$script:ddnaAssets=New-Object 'Collections.Generic.Queue[uri]'
$script:ddnaPageSeen=New-Object 'Collections.Generic.HashSet[string]'
$script:ddnaAssetSeen=New-Object 'Collections.Generic.HashSet[string]'
$script:ddnaPagePaths=New-Object 'Collections.Generic.List[string]'
$ddnaAssetExtensions=@('.css','.js','.png','.jpg','.jpeg','.webp','.gif','.svg','.ico','.avif','.woff','.woff2','.ttf','.otf','.mp4','.webm','.pdf')
function Get-DDNAUri([string]$Value,[uri]$Context){
	$Value=[Net.WebUtility]::HtmlDecode($Value.Trim())
	if(-not $Value -or $Value -match '^(#|data:|blob:|mailto:|tel:|javascript:)'){return $null}
	try{return [uri]::new($Context,$Value)}catch{return $null}
}
function Test-DDNALocal([uri]$Uri){return $Uri -and $Uri.Scheme -in @('http','https') -and $Uri.Authority -eq $ddnaLocal.Authority}
function Test-DDNABlocked([uri]$Uri){
	return $Uri.AbsolutePath -match '(?i)^/(wp-admin|wp-json|wp-login|xmlrpc|wp-sitemap|wp-config|feed|comments|author|hello-world|sample-page)(/|\.|$)' -or $Uri.AbsolutePath -match '(?i)\.php$|/feed/?$' -or ($Uri.Query -and $Uri.Query -notmatch '^\?ver=')
}
function Get-DDNARoute([string]$Path){
	if($Path -match '^/category/novedades(?<rest>/.*)?$'){return '/novedades'+$Matches.rest}
	return $Path
}
function Get-DDNAFile([string]$Path){
	$relative=[uri]::UnescapeDataString($Path).TrimStart('/')
	if($relative -match '(^|/)\.\.?(/|$)' -or $relative -match '[\\:]'){throw 'Ruta insegura: '+$Path}
	$resolved=[IO.Path]::GetFullPath((Join-Path $ddnaBuild $relative))
	if(-not $resolved.StartsWith($ddnaBuild+[IO.Path]::DirectorySeparatorChar,[StringComparison]::OrdinalIgnoreCase)){throw 'Ruta fuera del build.'}
	return $resolved
}
function Add-DDNAPage([uri]$Uri){
	if(-not (Test-DDNALocal $Uri) -or (Test-DDNABlocked $Uri)){return}
	$clean=[uri]($ddnaLocal.GetLeftPart([UriPartial]::Authority)+$Uri.AbsolutePath)
	if($script:ddnaPageSeen.Add($clean.AbsoluteUri)){$script:ddnaPages.Enqueue($clean)}
}
function Convert-DDNAUrl([string]$Value,[uri]$Context){
	$uri=Get-DDNAUri $Value $Context
	if(-not (Test-DDNALocal $uri)){return $Value}
	if(Test-DDNABlocked $uri){return ''}
	$extension=[IO.Path]::GetExtension($uri.AbsolutePath).ToLowerInvariant()
	if($extension -in $ddnaAssetExtensions){
		$clean=[uri]($ddnaLocal.GetLeftPart([UriPartial]::Authority)+$uri.AbsolutePath)
		if($script:ddnaAssetSeen.Add($clean.AbsoluteUri)){$script:ddnaAssets.Enqueue($clean)}
		return $BasePath+$uri.AbsolutePath.TrimStart('/')+$uri.Fragment
	}
	Add-DDNAPage $uri
	$route=Get-DDNARoute $uri.AbsolutePath
	if(-not $route.EndsWith('/')){$route+='/' }
	return $BasePath+$route.TrimStart('/')+$uri.Fragment
}
function Convert-DDNACss([string]$Text,[uri]$Context){
	$pattern='url\(\s*(?<q>[''""]?)(?<url>[^)''""\s]+)\k<q>\s*\)'
	return [regex]::Replace($Text,$pattern,[Text.RegularExpressions.MatchEvaluator]{param($m)
		$value=Convert-DDNAUrl $m.Groups['url'].Value $Context
		return 'url("'+$value+'")'
	})
}
function Convert-DDNAHtml([string]$Html,[uri]$Context){
	$Html=[regex]::Replace($Html,'(?is)<link\b[^>]*(?:wp-json|xmlrpc|oEmbed|shortlink)[^>]*>','')
	$Html=[regex]::Replace($Html,'(?is)<script\b[^>]*>[\s\S]*?</script>',[Text.RegularExpressions.MatchEvaluator]{param($m)
		if($m.Value -match 'wp-emoji|_wpemoji|speculationrules'){return ''};return $m.Value
	})
	$Html=[regex]::Replace($Html,'(?is)<form\b[^>]*>(?<inner>[\s\S]*?)</form>',[Text.RegularExpressions.MatchEvaluator]{param($m)
		return '<div class="preview-disabled-form"><p role="note">Demo temporal: este formulario o buscador no procesa información.</p><fieldset disabled aria-disabled="true">'+$m.Groups['inner'].Value+'</fieldset></div>'
	})
	$Html=[regex]::Replace($Html,'(?is)<a\b(?<attrs>[^>]*\bhref\s*=\s*(?<q>[''""])(?<url>.*?)\k<q>[^>]*)>(?<body>[\s\S]*?)</a>',[Text.RegularExpressions.MatchEvaluator]{param($m)
		$uri=Get-DDNAUri $m.Groups['url'].Value $Context
		if((Test-DDNALocal $uri) -and (Test-DDNABlocked $uri)){return '<span aria-disabled="true" title="Función no disponible en la demo estática">'+$m.Groups['body'].Value+'</span>'}
		return $m.Value
	})
	$Html=[regex]::Replace($Html,'(?is)\b(?<attr>href|src|poster|data-src|content)\s*=\s*(?<q>[''""])(?<url>.*?)\k<q>',[Text.RegularExpressions.MatchEvaluator]{param($m)
		$value=$m.Groups['url'].Value
		if($m.Groups['attr'].Value -eq 'content' -and $value -notmatch '^https?://'){return $m.Value}
		$converted=Convert-DDNAUrl $value $Context
		if($m.Groups['attr'].Value -eq 'content' -and $converted.StartsWith($BasePath)){$converted=$PublicOrigin+$converted}
		return $m.Groups['attr'].Value+'='+$m.Groups['q'].Value+$converted+$m.Groups['q'].Value
	})
	$Html=[regex]::Replace($Html,'(?is)\bsrcset\s*=\s*(?<q>[''""])(?<value>.*?)\k<q>',[Text.RegularExpressions.MatchEvaluator]{param($m)
		$items=foreach($part in $m.Groups['value'].Value.Split(',')){
			if($part.Trim() -match '^(\S+)(.*)$'){(Convert-DDNAUrl $Matches[1] $Context)+$Matches[2]}
		}
		return 'srcset='+$m.Groups['q'].Value+($items -join ', ')+$m.Groups['q'].Value
	})
	$Html=[regex]::Replace($Html,'(?is)\bstyle\s*=\s*(?<q>[''""])(?<value>.*?)\k<q>',[Text.RegularExpressions.MatchEvaluator]{param($m)
		$css=Convert-DDNACss ([Net.WebUtility]::HtmlDecode($m.Groups['value'].Value)) $Context
		return 'style="'+[Net.WebUtility]::HtmlEncode($css)+'"'
	})
	$Html=[regex]::Replace($Html,'(?is)<style\b(?<attrs>[^>]*)>(?<css>[\s\S]*?)</style>',[Text.RegularExpressions.MatchEvaluator]{param($m)
		return '<style'+$m.Groups['attrs'].Value+'>'+(Convert-DDNACss $m.Groups['css'].Value $Context)+'</style>'
	})
	$Html=$Html.Replace($ddnaLocal.GetLeftPart([UriPartial]::Authority),$PublicOrigin+$BasePath.TrimEnd('/'))
	$Html=[regex]::Replace($Html,'(?is)<meta\b[^>]*\bname\s*=\s*[''""]robots[''""][^>]*>','')
	$Html=$Html.Replace('</head>','<meta name="robots" content="noindex,nofollow"></head>')
	$Html=[regex]::Replace($Html,'(?m)[\t ]+$','')
	return $Html
}

Add-DDNAPage $ddnaLocal
Add-DDNAPage ([uri]::new($ddnaLocal,'category/novedades/'))
$ddnaContentManifest=Join-Path $ddnaRoot 'content/final-sept-2026.json'
if(Test-Path $ddnaContentManifest){
	$manifest=Get-Content $ddnaContentManifest -Raw -Encoding UTF8 | ConvertFrom-Json
	foreach($page in $manifest.pages){Add-DDNAPage ([uri]::new($ddnaLocal,$page.slug+'/'))}
}
while($script:ddnaPages.Count){
	if($script:ddnaPagePaths.Count -ge $MaxPages){throw 'Límite de páginas alcanzado; revisar rutas, no ampliar el crawl silenciosamente.'}
	$uri=$script:ddnaPages.Dequeue()
	$response=Invoke-WebRequest -Uri $uri.AbsoluteUri -UseBasicParsing -TimeoutSec 30
	if($response.StatusCode -ne 200){throw 'Página no disponible: '+$uri.AbsolutePath}
	if($response.BaseResponse.ResponseUri.Authority -ne $ddnaLocal.Authority){throw 'Redirección fuera del entorno local.'}
	$route=Get-DDNARoute $uri.AbsolutePath
	if(-not $route.EndsWith('/')){$route+='/' }
	$html=Convert-DDNAHtml $response.Content $uri
	$file=Get-DDNAFile ($route+'index.html')
	New-Item -ItemType Directory -Path ([IO.Path]::GetDirectoryName($file)) -Force | Out-Null
	[IO.File]::WriteAllText($file,$html,$ddnaUtf8)
	$script:ddnaPagePaths.Add($route)
	Write-Output ('Exportada: '+$route)
}
while($script:ddnaAssets.Count){
	$uri=$script:ddnaAssets.Dequeue()
	$file=Get-DDNAFile $uri.AbsolutePath
	New-Item -ItemType Directory -Path ([IO.Path]::GetDirectoryName($file)) -Force | Out-Null
	$extension=[IO.Path]::GetExtension($file).ToLowerInvariant()
	if($extension -in @('.css','.js')){
		$text=(Invoke-WebRequest -Uri $uri.AbsoluteUri -UseBasicParsing -TimeoutSec 30).Content
		if($extension -eq '.css'){$text=Convert-DDNACss $text $uri}
		$text=$text.Replace($ddnaLocal.GetLeftPart([UriPartial]::Authority),$PublicOrigin+$BasePath.TrimEnd('/'))
		[IO.File]::WriteAllText($file,$text,$ddnaUtf8)
	}else{
		Invoke-WebRequest -Uri $uri.AbsoluteUri -OutFile $file -TimeoutSec 60
	}
	if((Get-Item $file).Length -ge 100MB){throw 'Asset demasiado grande para versionar sin revisión: '+$uri.AbsolutePath}
}
[IO.File]::WriteAllText((Join-Path $ddnaBuild '.nojekyll'),'',$ddnaUtf8)
[IO.File]::WriteAllText((Join-Path $ddnaBuild 'robots.txt'),"User-agent: *`nDisallow: /`n",$ddnaUtf8)
$ddnaSummary=@{generator='ddna-github-preview-v1';basePath=$BasePath;publicOrigin=$PublicOrigin;generatedAt=[DateTime]::UtcNow.ToString('o');pages=@($script:ddnaPagePaths);assetCount=$script:ddnaAssetSeen.Count}
[IO.File]::WriteAllText((Join-Path $ddnaBuild 'preview-build.json'),($ddnaSummary | ConvertTo-Json -Depth 8),$ddnaUtf8)
& (Join-Path $PSScriptRoot 'verify-github-preview.ps1') -Directory $ddnaBuild -BasePath $BasePath -PublicOrigin $PublicOrigin
if(Test-Path $ddnaDestination){
	$ddnaPrevious=Join-Path $ddnaRoot ('backups/github-preview-previous-'+[guid]::NewGuid().ToString('N'))
	if([IO.Path]::GetFullPath($ddnaDestination) -ne (Join-Path $ddnaRoot 'github-preview') -or -not $ddnaPrevious.StartsWith($ddnaRoot+'\',[StringComparison]::OrdinalIgnoreCase)){throw 'Destino de reemplazo inseguro.'}
	Move-Item -LiteralPath $ddnaDestination -Destination $ddnaPrevious
}
Move-Item -LiteralPath $ddnaBuild -Destination $ddnaDestination
Write-Output ('Preview lista: '+$PublicOrigin+$BasePath+' — '+$script:ddnaPagePaths.Count+' páginas / '+$script:ddnaAssetSeen.Count+' assets. Sin commit ni push.')
