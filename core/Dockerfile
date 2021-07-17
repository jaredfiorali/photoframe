# Pull official base image
FROM rust:alpine

# Set working directory and copy app files
WORKDIR /usr/src/myapp
COPY files .

# Compile application
RUN cargo install --path .

# Run app
CMD ["target/release/photoframe_api"]
