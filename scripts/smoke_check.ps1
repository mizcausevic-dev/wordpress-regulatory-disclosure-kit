$ErrorActionPreference = "Stop"

$repoRoot = Split-Path -Parent $PSScriptRoot
$router = Join-Path $repoRoot "router.php"
$port = 5436
$process = $null
$routes = @(
    "/",
    "/disclosure-lane",
    "/policy-evidence",
    "/verification",
    "/docs",
    "/api/dashboard/summary",
    "/api/disclosure-lane",
    "/api/policy-evidence",
    "/api/verification",
    "/api/sample"
)

function Wait-ForUrl {
    param([string]$Url)

    for ($i = 0; $i -lt 40; $i++) {
        try {
            Invoke-WebRequest -Uri $Url -UseBasicParsing | Out-Null
            return
        } catch {
            Start-Sleep -Milliseconds 500
        }
    }

    throw "Timed out waiting for $Url"
}

try {
    $process = Start-Process -FilePath "php.exe" `
        -ArgumentList "-S", "127.0.0.1:$port", $router `
        -WorkingDirectory $repoRoot `
        -WindowStyle Hidden `
        -PassThru

    Wait-ForUrl "http://127.0.0.1:$port/"

    foreach ($route in $routes) {
        $response = Invoke-WebRequest -Uri "http://127.0.0.1:$port$route" -UseBasicParsing
        if ($response.StatusCode -ne 200) {
            throw "Unexpected status code $($response.StatusCode) for $route"
        }
    }

    Write-Host "Smoke checks passed for WordPress Regulatory Disclosure Kit routes."
} finally {
    if ($process -and -not $process.HasExited) {
        Stop-Process -Id $process.Id -Force
    }
}
