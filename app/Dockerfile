# pull official base image
FROM node:current-alpine

# set working directory
WORKDIR /app

# add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

# install app dependencies
ADD files/package.json ./
RUN npm install --silent

# add app
COPY files ./

# start app
CMD ["npm", "start"]
