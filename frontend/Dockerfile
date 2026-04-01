FROM node:20-alpine

RUN apk add --no-cache libc6-compat

WORKDIR /app

# Copy package files first (better layer caching)
COPY package*.json ./

# Install dependencies (node_modules/ est gitignorés, cette étape les recrée)
RUN npm install

# Copy source code
COPY . .

EXPOSE 3000

CMD ["npm", "run", "dev"]
