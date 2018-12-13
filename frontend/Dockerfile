FROM node:10

WORKDIR /app

COPY . .

RUN touch .env

RUN npm install

COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /app

EXPOSE 3000

CMD ["entrypoint.sh"]