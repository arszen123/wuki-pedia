FROM adrianharabula/php7-with-oci8
RUN apt update -y && apt upgrade -y
RUN apt install -y openssh-client
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN chmod 777 -R /var/www
RUN ln -s /var/www/public /var/www/html