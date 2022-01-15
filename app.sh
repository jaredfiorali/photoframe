# /bin/bash

# Check to see if we are planning on building a new image
if [ ! -z "$1" ]; then
	echo "Building Docker image for app server"

	# Build Docker image
	docker build app -t react-dev
else
	echo "Skipping Docker image build for app server"
fi

# Confirm if we were able to build the docker image successfully
if [ $? -ne 0 ]; then
	echo "Failed to build docker image!"
	exit 1;
fi

# Stop any running instances of this container
docker stop react-dev

# Run Docker image
docker run \
--rm \
-p 3000:3000 \
--name react-dev \
--mount type=bind,source=`pwd`/app/files/src,target=/app/src \
--mount type=bind,source=`pwd`/app/files/public,target=/app/public \
-d \
react-dev:latest
