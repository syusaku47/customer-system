FROM mysql:8.0

ENV HOSTNAME db-server

RUN apt-get -y update; \
    apt-get -y install vim; \
    mkdir -p /var/log/mysql \
    chown mysql:mysql /var/log/mysql \
    chmod 777 /var/log/mysql
