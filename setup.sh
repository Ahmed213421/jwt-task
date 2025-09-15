#!/bin/bash

echo "🚀 Setting up Laravel JWT Booking System..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✅ .env file created"
else
    echo "✅ .env file already exists"
fi

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Install NPM dependencies
echo "📦 Installing NPM dependencies..."
npm install

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Run migrations and seeders
echo "🗄️ Running migrations and seeders..."
php artisan migrate:fresh --seed

# Clear and cache config
echo "🧹 Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

echo ""
echo "🎉 Setup complete! Your application is ready to use."
echo ""
echo "📋 Quick Start:"
echo "1. Start the development server: php artisan serve"
echo "2. Visit: http://localhost:8000"
echo "3. API Documentation: Check README.md for endpoints"
echo ""
echo "👥 Test Users:"
echo "Users: john@example.com, jane@example.com, mike@example.com"
echo "Specialists: sarah.johnson@clinic.com, michael.chen@clinic.com"
echo "Password for all: password"
echo ""
echo "🔧 Additional Commands:"
echo "- Run tests: php artisan test"
echo "- Clear cache: php artisan cache:clear"
echo "- View routes: php artisan route:list"
echo ""
echo "Happy coding! 🚀"
