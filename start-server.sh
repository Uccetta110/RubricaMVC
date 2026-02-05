#!/bin/bash

# Script per avviare il server PHP per RubricaMVC

echo "🚀 Avvio server PHP per RubricaMVC..."
echo "📍 Indirizzo: http://localhost:8000"
echo "⏹  Premi Ctrl+C per fermare il server"
echo ""
cd "$(dirname "$0")/public"
php8.3 -S localhost:8000
