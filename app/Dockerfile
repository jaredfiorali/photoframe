# Pull official base image
FROM node:current-alpine

# Set working directory
WORKDIR /app

# Add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

# Install app dependencies
ADD files/package.json ./
RUN npm install --silent && npm install @material-ui/core@next --silent && npm install @material-ui/styles@next --silent && npm install @material-ui/icons@next --silent

# Add app
COPY files ./

# Start app
CMD ["npm", "start"]
