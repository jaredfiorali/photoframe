# Pull official base image
FROM node:latest

# Set working directory
WORKDIR /app

# Add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

# Install app dependencies
ADD files/package.json ./
RUN npm install --silent

# Add app
COPY files ./

# Start app
CMD ["npm", "start"]
