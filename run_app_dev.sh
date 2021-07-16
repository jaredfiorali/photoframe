# /bin/bash

# Build Docker image
docker build app -t react-dev

# Run Docker image
docker run -p 3000:3000 react-dev
