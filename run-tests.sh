#!/bin/bash

# 🧪 Script para ejecutar suite completa de testing
# Para sistemas Unix/Linux/Mac

echo "🧪 INICIANDO SUITE COMPLETA DE TESTING"
echo "======================================"

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para mostrar paso
show_step() {
    echo ""
    echo -e "${BLUE}📋 $1${NC}"
    echo "----------------------------------------"
}

# Función para mostrar éxito
show_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

# Función para mostrar error
show_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Función para mostrar warning
show_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# 1. Preparar entorno
show_step "Preparando entorno de testing"
if [ ! -f .env.testing ]; then
    show_warning ".env.testing no encontrado, copiando .env"
    cp .env .env.testing
fi

# Configurar base de datos de testing
touch database/database.sqlite
php artisan key:generate --env=testing
show_success "Entorno preparado"

# 2. Testing Unitario (Caja Blanca)
show_step "🔬 TESTING UNITARIO (Caja Blanca)"
echo "Probando componentes individuales conociendo implementación interna..."

if vendor/bin/phpunit tests/Unit/ --colors; then
    show_success "Testing Unitario completado"
else
    show_error "Testing Unitario falló"
    exit 1
fi

# 3. Testing de Integración
show_step "🔗 TESTING DE INTEGRACIÓN"
echo "Probando interacción entre múltiples componentes..."

php artisan migrate:fresh --env=testing --seed
if vendor/bin/phpunit tests/Feature/AlumnoIntegrationTest.php --colors; then
    show_success "Testing de Integración completado"
else
    show_error "Testing de Integración falló"
    exit 1
fi

# 4. Testing de API (Caja Negra)
show_step "📡 TESTING DE API (Caja Negra)"
echo "Probando endpoints REST sin conocer implementación interna..."

if vendor/bin/phpunit tests/Feature/API/ --colors; then
    show_success "Testing de API completado"
else
    show_error "Testing de API falló"
    exit 1
fi

# 5. Testing E2E (Comportamiento)
show_step "🎭 TESTING E2E (Comportamiento)"
echo "Simulando flujos completos de usuario..."

if vendor/bin/phpunit tests/Feature/AlumnoBehaviorTest.php --colors; then
    show_success "Testing E2E completado"
else
    show_error "Testing E2E falló"
    exit 1
fi

# 6. Generar reporte de cobertura
show_step "📊 GENERANDO REPORTE DE COBERTURA"
echo "Analizando cobertura de código..."

if vendor/bin/phpunit --coverage-html coverage --coverage-text; then
    show_success "Reporte de cobertura generado en ./coverage/"
else
    show_warning "No se pudo generar reporte de cobertura (requiere Xdebug)"
fi

# 7. Resumen final
echo ""
echo "🎉 SUITE DE TESTING COMPLETADA EXITOSAMENTE"
echo "==========================================="
echo ""
echo -e "${GREEN}✅ Testing Unitario (Caja Blanca)     - Componentes aislados${NC}"
echo -e "${GREEN}✅ Testing de Integración             - Interacción componentes${NC}"
echo -e "${GREEN}✅ Testing de API (Caja Negra)        - Endpoints REST${NC}"
echo -e "${GREEN}✅ Testing E2E (Comportamiento)       - Flujos completos${NC}"
echo -e "${GREEN}✅ Análisis de Cobertura              - Métricas de calidad${NC}"
echo ""
echo "📊 Reporte de cobertura: file://$(pwd)/coverage/index.html"
echo ""
echo "🎯 TIPOS DE TESTING DEMOSTRADOS:"
echo "   🔬 Caja Blanca: Conocemos implementación interna"
echo "   📡 Caja Negra:  Solo evaluamos entrada/salida"
echo "   🔗 Integración: Componentes trabajando juntos"
echo "   🎭 E2E:         Experiencia completa del usuario"
echo ""
echo "Ready for your presentation! 🚀"
