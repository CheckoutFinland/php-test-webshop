FROM debian:10

# suppress "debconf: unable to initialize frontend: Dialog" messages on apt-get
RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections

# php-xml is required by php_codesniffer
RUN apt update -y && \
    apt-get install php libapache2-mod-php php-xml composer -y

# /var/log/apache2/other_vhosts_access.log

COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf
