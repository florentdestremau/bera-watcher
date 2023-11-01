#!/bin/sh


if [ ! -f .env ]
then
  export "$(cat .env | xargs)"
fi

echo $GITHUB_TOKEN | docker login --username $GITHUB_USER --password-stdin ghcr.io
echo "Pulling latest image..."
docker pull ghcr.io/florentdestremau/bera-watch:latest

echo "Starting containers..."
docker compose -f compose.yaml -f compose.prod.yaml --env-file=.env up -d --wait

echo "Deploy successful!"
