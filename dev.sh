#!/bin/bash

# Development Script for RinnSan Web
echo "🚀 Starting RinnSan Web Development Environment..."

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "❌ Node.js is not installed. Please install Node.js first."
    exit 1
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP first."
    exit 1
fi

echo "✅ Node.js version: $(node --version)"
echo "✅ PHP version: $(php --version | head -1)"

# Install dependencies
echo ""
echo "📦 Installing dependencies..."
npm install

# Start development servers
echo ""
echo "🎯 Starting development servers..."
echo ""
echo "Frontend (Vite): http://localhost:5173"
echo "Backend (PHP):   http://localhost:8000"
echo ""
echo "Press Ctrl+C to stop"
echo ""

# Run PHP development server
php -S localhost:8000 -t public &
PHP_PID=$!

# Run Vite development server
npm run dev &
VITE_PID=$!

# Cleanup on exit
trap "kill $PHP_PID $VITE_PID" EXIT

# Wait for both processes
wait
