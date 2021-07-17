# /bin/bash

# Check to see if we are planning on building a new image
if [ ! -z "$1" ]; then
	echo "Building Docker image"

	# Build Docker image
	docker build core -t rust-dev
else
	echo "Skipping Docker image build"
fi

# Run Docker image
docker run \
--rm \
-p 7878:7878 \
rust-dev:latest
