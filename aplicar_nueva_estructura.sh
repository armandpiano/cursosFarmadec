#!/bin/bash

echo "🚀 Aplicando nueva estructura al proyecto cursosPlataformaMin..."
echo ""

# Directorio del proyecto destino
DESTINO="/c/xampp/htdocs/cursosPlataformaMin"

# Verificar si existe el directorio destino
if [ ! -d "$DESTINO" ]; then
    echo "❌ No se encontró el directorio: $DESTINO"
    echo "🔍 Busca el directorio correcto donde tienes tu proyecto en XAMPP"
    exit 1
fi

echo "📁 Proyecto encontrado en: $DESTINO"
echo ""

# Crear respaldo del proyecto actual
BACKUP_DIR="$DESTINO-backup-$(date +%Y%m%d_%H%M%S)"
echo "💾 Creando respaldo en: $BACKUP_DIR"
cp -r "$DESTINO" "$BACKUP_DIR"
echo "✅ Respaldo creado"
echo ""

# Eliminar carpeta public si existe
if [ -d "$DESTINO/public" ]; then
    echo "🗑️ Eliminando carpeta public/..."
    rm -rf "$DESTINO/public"
    echo "✅ Carpeta public/ eliminada"
fi

# Copiar archivos de la nueva estructura
echo "📋 Aplicando nueva estructura..."
cp .htaccess "$DESTINO/"
cp index.php "$DESTINO/"
cp google-callback.php "$DESTINO/"

# Copiar carpetas de assets
echo "📁 Copiando assets..."
cp -r assets "$DESTINO/" 2>/dev/null || echo "⚠️ No se encontró carpeta assets"
cp -r css "$DESTINO/" 2>/dev/null || echo "⚠️ No se encontró carpeta css"
cp -r img "$DESTINO/" 2>/dev/null || echo "⚠️ No se encontró carpeta img"
cp -r videos "$DESTINO/" 2>/dev/null || echo "⚠️ No se encontró carpeta videos"

echo ""
echo "🎉 ¡REORGANIZACIÓN COMPLETADA!"
echo ""
echo "📍 Accede ahora a:"
echo "   http://localhost/cursosPlataformaMin/"
echo ""
echo "🔗 URLs limpias sin /public/"
echo "   http://localhost/cursosPlataformaMin/app"
echo "   http://localhost/cursosPlataformaMin/courses"
echo ""
echo "💡 Si hay algún problema, tu respaldo está en:"
echo "   $BACKUP_DIR"