# PowerShell deployment script for Windows

# Function to write colored output
function Write-Step {
    param([string]$message)
    Write-Host ">>> $message" -ForegroundColor Yellow
}

# Check if .env file exists
if (-not (Test-Path .env)) {
    Write-Step "Creating .env file from .env.example"
    Copy-Item .env.example .env
}

# Build and start containers
Write-Step "Building and starting containers"
docker-compose up --build -d

# Install dependencies and prepare application
Write-Step "Installing dependencies and preparing application"
docker-compose exec app sh -c "php artisan key:generate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan storage:link"

Write-Step "Running database migrations"
docker-compose exec app php artisan migrate --force

Write-Step "Deployment complete!"
Write-Host "The application is now running at:" -ForegroundColor Green
Write-Host "http://localhost"
Write-Host "https://localhost"

# Add a pause to keep the window open if script is double-clicked
Write-Host "`nPress any key to exit..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")