# Pull official base image
FROM rust:latest

# Get Rust to print stack traces if there is an error
ENV RUST_BACKTRACE=1

# Set working directory and copy app files
WORKDIR /usr/src/myapp
COPY files .

# Compile application
RUN cargo install --path .

# Run app
CMD ["target/release/photoframe_api"]
