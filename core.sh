# /bin/bash

# Check to see if we are planning on building a new image
if [ ! -z "$1" ]; then
	echo "Building Docker image for core server"

	# Build Docker image
	docker build core -t php-dev
else
	echo "Skipping Docker image build for core server"
fi

# Confirm if we were able to build the docker image successfully
if [ $? -ne 0 ]; then
	echo "Failed to build docker image!"
	exit 1;
fi

# Run Docker image
docker run \
--rm \
-p 8080:80 \
--name php-dev \
php-dev:latest
