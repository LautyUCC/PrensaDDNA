param([int]$Port=4173)
$ErrorActionPreference='Stop'
if($Port -lt 1024 -or $Port -gt 65535){throw 'Puerto de preview no válido.'}
$ddnaPreview=[IO.Path]::GetFullPath((Join-Path $PSScriptRoot '../github-preview'))
$ddnaMetadata=Get-Content (Join-Path $ddnaPreview 'preview-build.json') -Raw | ConvertFrom-Json
& (Join-Path $PSScriptRoot 'verify-github-preview.ps1') -Directory $ddnaPreview
$ddnaExisting=docker ps -aq --filter 'name=^ddna-github-preview$'
if($LASTEXITCODE -ne 0){throw 'Docker no está disponible.'}
if($ddnaExisting){
	$ddnaOwner=(docker inspect ddna-github-preview | ConvertFrom-Json)[0].Config.Labels.'ddna.preview'
	if($ddnaOwner -ne 'true'){throw 'El contenedor existente no pertenece al servidor de preview; no se reemplaza.'}
	docker rm -f ddna-github-preview | Out-Null
	if($LASTEXITCODE -ne 0){throw 'No se pudo recrear el servidor de preview.'}
}
$ddnaMount='type=bind,source='+$ddnaPreview+',target=/usr/share/nginx/html'+$ddnaMetadata.basePath.TrimEnd('/')+',readonly'
docker run -d --name ddna-github-preview --label ddna.preview=true --label "ddna.preview.port=$Port" --restart unless-stopped -p "127.0.0.1:${Port}:80" --mount $ddnaMount nginx:alpine
if($LASTEXITCODE -ne 0){throw 'No se pudo iniciar el servidor estático.'}
Write-Output ('Preview HTTP local: http://localhost:'+$Port+$ddnaMetadata.basePath)
Write-Output 'Solo se monta github-preview como lectura. WordPress y su base no se modifican.'
