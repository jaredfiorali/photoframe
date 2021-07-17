# /bin/bash

# Stop the app server
echo "Stopping app server"
docker stop react-dev

# Stop the core server
echo "Stopping core server"
docker stop rust-dev
