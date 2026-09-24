param([string]$LocalUrl='http://localhost:8080',[string]$BasePath='',[string]$PublicOrigin='')
$ErrorActionPreference='Stop'
& (Join-Path $PSScriptRoot 'build-github-preview.ps1') -LocalUrl $LocalUrl -BasePath $BasePath -PublicOrigin $PublicOrigin
$ddnaRunning=docker ps -q --filter 'name=^ddna-github-preview$'
if($LASTEXITCODE -eq 0 -and $ddnaRunning){
	$ddnaInfo=(docker inspect ddna-github-preview | ConvertFrom-Json)[0]
	if($ddnaInfo.Config.Labels.'ddna.preview' -eq 'true'){
		& (Join-Path $PSScriptRoot 'serve-github-preview.ps1') -Port ([int]$ddnaInfo.Config.Labels.'ddna.preview.port')
	}
}
Write-Output 'Revisar git status y commitear manualmente github-preview/, scripts, workflow y documentación. No se hizo commit ni push.'
