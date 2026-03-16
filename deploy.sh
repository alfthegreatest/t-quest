#!/bin/bash
PROJECT_DIR="/var/www/t-quest"
BRANCH="main"

cd $PROJECT_DIR

echo "Starting deployment..."

git fetch origin $BRANCH
LOCAL=$(git rev-parse HEAD)
REMOTE=$(git rev-parse origin/$BRANCH)

if [ "$LOCAL" = "$REMOTE" ]; then
    echo "Already up to date"
    exit 0
fi

git reset --hard origin/$BRANCH

#echo "Installing dependencies..."
#composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader
npm ci --prefer-offline --no-audit

echo "Building assets..."
npm run build

echo "Optimizing Laravel..."
php artisan optimize

echo "Deployment completed successfully!"