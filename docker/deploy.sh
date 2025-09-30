#!/bin/sh

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Function to print step
print_step() {
    echo "${YELLOW}>>> $1${NC}"
}

# Check if .env file exists
if [ ! -f .env ]; then
    print_step "Creating .env file from .env.example"
    cp .env.example .env
fi

# Build and start containers
print_step "Building and starting containers"
docker-compose up --build -d

# Install dependencies and prepare application
print_step "Installing dependencies and preparing application"
docker-compose exec app sh -c "
    php artisan key:generate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan storage:link
"

print_step "Running database migrations"
docker-compose exec app php artisan migrate --force

print_step "Deployment complete!"
echo "${GREEN}The application is now running at:${NC}"
echo "http://localhost"
echo "https://localhost"