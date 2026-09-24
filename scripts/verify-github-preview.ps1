param([string]$Directory=(Join-Path $PSScriptRoot '../github-preview'),[string]$BasePath='',[string]$PublicOrigin='')
$ErrorActionPreference='Stop'
$ddnaDirectory=[IO.Path]::GetFullPath($Directory)
$ddnaManifest=Get-Content (Join-Path $ddnaDirectory 'preview-build.json') -Raw | ConvertFrom-Json
if(-not $BasePath){$BasePath=$ddnaManifest.basePath}
if(-not $PublicOrigin){$PublicOrigin=$ddnaManifest.publicOrigin}
foreach($required in @('index.html','.nojekyll','novedades/index.html')){if(-not(Test-Path (Join-Path $ddnaDirectory $required))){throw 'Falta: '+$required}}
$ddnaFailures=New-Object 'Collections.Generic.List[string]'
$ddnaFiles=Get-ChildItem -LiteralPath $ddnaDirectory -Recurse -File
foreach($file in $ddnaFiles){
	if($file.Name -match '^(\.env|wp-config\.php)' -or $file.Extension -match '^\.(php|sql|log|gz|zip)$' -or $file.FullName -match '[\\/](wp-admin|wp-json)[\\/]'){$ddnaFailures.Add('Archivo no permitido: '+$file.Name)}
	if($file.Extension -notin @('.html','.css','.js','.json','.txt','.xml')){continue}
	$text=Get-Content -LiteralPath $file.FullName -Raw -Encoding UTF8
	if($text -match '(?i)localhost|127\.0\.0\.1|wp-config\.php|DB_PASSWORD|AUTH_KEY'){$ddnaFailures.Add('Referencia local/secreto en '+$file.Name)}
	$pattern='(?:\b(?:href|src|poster|content|data-src)\s*=\s*[''""]|url\([""'']?)(?<url>(?:https?://[^/""''<>\s]+)?'+[regex]::Escape($BasePath)+'[^""''<>\s)]*)'
	$references=@([regex]::Matches($text,$pattern))
	foreach($srcset in [regex]::Matches($text,'\bsrcset\s*=\s*[''""](?<value>[^''""]+)[''""]')){
		$references+=@([regex]::Matches($srcset.Groups['value'].Value,'(?<url>'+[regex]::Escape($BasePath)+'[^,\s]+)'))
	}
	foreach($match in $references){
		$url=[Net.WebUtility]::HtmlDecode($match.Groups['url'].Value)
		if($url.StartsWith('http')){if(-not $url.StartsWith($PublicOrigin+'/')){continue};$url=([uri]$url).AbsolutePath}
		$path=($url -split '[?#]')[0]
		if(-not $path.StartsWith($BasePath)){continue}
		$relative=[uri]::UnescapeDataString($path.Substring($BasePath.Length))
		if(-not $relative -or $relative.EndsWith('/')){$relative+='index.html'}
		if(-not (Test-Path -LiteralPath (Join-Path $ddnaDirectory $relative))){$ddnaFailures.Add('Enlace/asset faltante: '+$url+' en '+$file.Name)}
	}
}
if($ddnaFailures.Count){throw ($ddnaFailures -join "`n")}
$ddnaBytes=($ddnaFiles | Measure-Object Length -Sum).Sum
if($ddnaBytes -gt 1GB){throw 'Preview supera 1GB; revisar antes de publicar.'}
Write-Output ('Validación estática OK: '+$ddnaFiles.Count+' archivos, '+[Math]::Round($ddnaBytes/1MB,2)+' MB; sin referencias locales, endpoints administrativos ni enlaces internos/asset faltantes.')
