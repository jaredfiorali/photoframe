# /bin/bash

# Check to see if we are planning on building a new image
if [ ! -z "$1" ]; then
	echo "Building Docker image"

	# Build Docker image
	docker build app -t react-dev
else
	echo "Skipping Docker image build"
fi

# Run Docker image
docker run \
-p 3000:3000 \
--mount type=bind,source=`pwd`/app/files/src,target=/app/src \
--mount type=bind,source=`pwd`/app/files/public,target=/app/public \
react-dev:latest
