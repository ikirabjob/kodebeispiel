FROM nginx:1.21-alpine

ARG USERID
ARG USERNAME=www-data

COPY docker/nginx/nginx.conf  /etc/nginx/
COPY docker/nginx/default.conf /etc/nginx/conf.d/

#RUN echo "upstream php-upstream { server backend:9000; }" > /etc/nginx/conf.d/upstream.conf

EXPOSE 8080