$phpPath = "C:\xampp\php\php.exe"
if (-not (Test-Path $phpPath)) {
    $cmd = Get-Command php -ErrorAction SilentlyContinue
    if ($cmd) {
        $phpPath = $cmd.Source
    } else {
        Write-Error "PHP executable not found at C:\xampp\php\php.exe or in the system PATH. Please ensure PHP is installed."
        Exit
    }
}

Write-Host "Starting Acadivio local server..." -ForegroundColor Green
$serverProc = Start-Process -FilePath $phpPath -ArgumentList "artisan serve" -PassThru -NoNewWindow

Start-Sleep -Seconds 2

Write-Host "Opening browser to http://127.0.0.1:8000..." -ForegroundColor Cyan
Start-Process "http://127.0.0.1:8000"

Write-Host "`n========================================================" -ForegroundColor Yellow
Write-Host " Acadivio is running at http://127.0.0.1:8000" -ForegroundColor Green
Write-Host " Press any key in this window to STOP the server..." -ForegroundColor Yellow
Write-Host "========================================================" -ForegroundColor Yellow

$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

Write-Host "`nStopping Acadivio server..." -ForegroundColor Red
Stop-Process -Id $serverProc.Id -Force
Write-Host "Server stopped successfully." -ForegroundColor Green
Start-Sleep -Seconds 1
