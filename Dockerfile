FROM tutum/apache-php:latest
EXPOSE 80 6379

ADD . /app

CMD ["/run.sh"]