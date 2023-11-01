source .env.local
docker login -u $GITHUB_USERNAME -p $GITHUB_TOKEN ghcr.io
echo "Pulling latest image..."
docker pull ghcr.io/florentdestremau/bera-watch:latest

echo "Starting containers..."
SERVER_NAME=$SERVER_NAME POSTGRES_PASSWORD=$POSTGRES_PASSWORD docker compose -f compose.yaml -f compose.prod.yaml up -d --wait

echo "Deploy successful!"
