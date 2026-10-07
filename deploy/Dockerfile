FROM wordpress@sha256:b2d7e3153c8a96f90305a3102fb6439335237fb1a9655b617d15c5168ce2f7a3 AS plugins
ADD --checksum=sha256:eaff579f54079b72063c4ee67e607228eeb5d7eaff4cb13e889a262a1d3ca7a3 https://downloads.wordpress.org/plugin/elementor.4.2.4.zip /tmp/elementor.zip
RUN php -r '$z=new ZipArchive(); if($z->open("/tmp/elementor.zip")!==true || !$z->extractTo("/tmp/plugins")) exit(1); $z->close();'
FROM wordpress@sha256:c7e33020222e25da03375aa926741d24399f23df74dc11f99e45b373419f08ea AS cli
FROM wordpress@sha256:b2d7e3153c8a96f90305a3102fb6439335237fb1a9655b617d15c5168ce2f7a3
ARG SOURCE_REVISION
LABEL org.opencontainers.image.source="https://github.com/LautyUCC/PrensaDDNA" org.opencontainers.image.revision=$SOURCE_REVISION
COPY --from=cli /usr/local/bin/wp /usr/local/bin/wp
COPY --chown=www-data:www-data app/ /usr/src/wordpress/wp-content/
COPY --from=plugins --chown=www-data:www-data /tmp/plugins/elementor/ /usr/src/wordpress/wp-content/plugins/elementor/
COPY --chown=www-data:www-data deploy/wp-config.php /usr/src/wordpress/wp-config.php
COPY --chown=www-data:www-data deploy/wordpress.htaccess /usr/src/wordpress/.htaccess
RUN echo 'ServerName 179.199.132.207' > /etc/apache2/conf-enabled/server-name.conf
HEALTHCHECK --interval=30s --timeout=5s --start-period=45s --retries=3 CMD php -r '$s=@fsockopen("127.0.0.1",80);exit($s?0:1);'
