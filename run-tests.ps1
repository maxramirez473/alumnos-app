# 🧪 Script para ejecutar suite completa de testing en Windows
# Usar con PowerShell

Write-Host "🧪 INICIANDO SUITE COMPLETA DE TESTING" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan

# Función para mostrar pasos
function Show-Step {
    param($Message)
    Write-Host ""
    Write-Host "📋 $Message" -ForegroundColor Blue
    Write-Host "----------------------------------------" -ForegroundColor Blue
}

# Función para mostrar éxito
function Show-Success {
    param($Message)
    Write-Host "✅ $Message" -ForegroundColor Green
}

# Función para mostrar error
function Show-Error {
    param($Message)
    Write-Host "❌ $Message" -ForegroundColor Red
}

# Función para mostrar warning
function Show-Warning {
    param($Message)
    Write-Host "⚠️  $Message" -ForegroundColor Yellow
}

# 1. Preparar entorno
Show-Step "Preparando entorno de testing"

if (-not (Test-Path ".env.testing")) {
    Show-Warning ".env.testing no encontrado, copiando .env"
    Copy-Item ".env" ".env.testing"
}

# Configurar base de datos de testing
if (-not (Test-Path "database")) {
    New-Item -ItemType Directory -Path "database"
}
New-Item -ItemType File -Path "database/database.sqlite" -Force
php artisan key:generate --env=testing
Show-Success "Entorno preparado"

# 2. Testing Unitario (Caja Blanca)
Show-Step "🔬 TESTING UNITARIO (Caja Blanca)"
Write-Host "Probando componentes individuales conociendo implementación interna..." -ForegroundColor Gray

$unitResult = & vendor/bin/phpunit tests/Unit/ --colors
if ($LASTEXITCODE -eq 0) {
    Show-Success "Testing Unitario completado"
} else {
    Show-Error "Testing Unitario falló"
    exit 1
}

# 3. Testing de Integración
Show-Step "🔗 TESTING DE INTEGRACIÓN"
Write-Host "Probando interacción entre múltiples componentes..." -ForegroundColor Gray

php artisan migrate:fresh --env=testing --seed
$integrationResult = & vendor/bin/phpunit tests/Feature/AlumnoIntegrationTest.php --colors
if ($LASTEXITCODE -eq 0) {
    Show-Success "Testing de Integración completado"
} else {
    Show-Error "Testing de Integración falló"
    exit 1
}

# 4. Testing de API (Caja Negra)
Show-Step "📡 TESTING DE API (Caja Negra)"
Write-Host "Probando endpoints REST sin conocer implementación interna..." -ForegroundColor Gray

$apiResult = & vendor/bin/phpunit tests/Feature/API/ --colors
if ($LASTEXITCODE -eq 0) {
    Show-Success "Testing de API completado"
} else {
    Show-Error "Testing de API falló"
    exit 1
}

# 5. Testing E2E (Comportamiento)
Show-Step "🎭 TESTING E2E (Comportamiento)"
Write-Host "Simulando flujos completos de usuario..." -ForegroundColor Gray

$e2eResult = & vendor/bin/phpunit tests/Feature/AlumnoBehaviorTest.php --colors
if ($LASTEXITCODE -eq 0) {
    Show-Success "Testing E2E completado"
} else {
    Show-Error "Testing E2E falló"
    exit 1
}

# 6. Generar reporte de cobertura
Show-Step "📊 GENERANDO REPORTE DE COBERTURA"
Write-Host "Analizando cobertura de código..." -ForegroundColor Gray

$coverageResult = & vendor/bin/phpunit --coverage-html coverage --coverage-text
if ($LASTEXITCODE -eq 0) {
    Show-Success "Reporte de cobertura generado en ./coverage/"
} else {
    Show-Warning "No se pudo generar reporte de cobertura (requiere Xdebug)"
}

# 7. Resumen final
Write-Host ""
Write-Host "🎉 SUITE DE TESTING COMPLETADA EXITOSAMENTE" -ForegroundColor Green
Write-Host "===========================================" -ForegroundColor Green
Write-Host ""
Write-Host "✅ Testing Unitario (Caja Blanca)     - Componentes aislados" -ForegroundColor Green
Write-Host "✅ Testing de Integración             - Interacción componentes" -ForegroundColor Green  
Write-Host "✅ Testing de API (Caja Negra)        - Endpoints REST" -ForegroundColor Green
Write-Host "✅ Testing E2E (Comportamiento)       - Flujos completos" -ForegroundColor Green
Write-Host "✅ Análisis de Cobertura              - Métricas de calidad" -ForegroundColor Green
Write-Host ""

$currentPath = Get-Location
Write-Host "📊 Reporte de cobertura: file:///$currentPath/coverage/index.html" -ForegroundColor Cyan
Write-Host ""
Write-Host "🎯 TIPOS DE TESTING DEMOSTRADOS:" -ForegroundColor Yellow
Write-Host "   🔬 Caja Blanca: Conocemos implementación interna" -ForegroundColor Gray
Write-Host "   📡 Caja Negra:  Solo evaluamos entrada/salida" -ForegroundColor Gray
Write-Host "   🔗 Integración: Componentes trabajando juntos" -ForegroundColor Gray
Write-Host "   🎭 E2E:         Experiencia completa del usuario" -ForegroundColor Gray
Write-Host ""
Write-Host "Ready for your presentation! 🚀" -ForegroundColor Magenta

# Comandos individuales para la presentación
Write-Host ""
Write-Host "🎤 COMANDOS PARA LA PRESENTACIÓN:" -ForegroundColor Yellow
Write-Host "=================================" -ForegroundColor Yellow
Write-Host ""
Write-Host "# Solo testing unitario (Caja Blanca):" -ForegroundColor Gray
Write-Host "vendor/bin/phpunit tests/Unit/ --verbose" -ForegroundColor White
Write-Host ""
Write-Host "# Solo testing API (Caja Negra):" -ForegroundColor Gray  
Write-Host "vendor/bin/phpunit tests/Feature/API/ --verbose" -ForegroundColor White
Write-Host ""
Write-Host "# Test específico con detalles:" -ForegroundColor Gray
Write-Host "vendor/bin/phpunit tests/Unit/AlumnoTest.php::test_puede_crear_alumno_con_datos_validos --verbose" -ForegroundColor White
Write-Host ""
Write-Host "# Generar datos básicos para desarrollo:" -ForegroundColor Gray
Write-Host "php artisan db:seed" -ForegroundColor White
