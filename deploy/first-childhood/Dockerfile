ARG BASE_IMAGE
FROM ${BASE_IMAGE}
ARG DDNA_REVISION
COPY app/themes/ddna-theme/template-parts/content/content-news.php /usr/src/wordpress/wp-content/themes/ddna-theme/template-parts/content/content-news.php
COPY app/themes/ddna-theme/assets/css/components/news-article.css /usr/src/wordpress/wp-content/themes/ddna-theme/assets/css/components/news-article.css
COPY scripts/import-first-childhood-news.php /opt/ddna-release/scripts/import-first-childhood-news.php
COPY content/novedad-primera-infancia-20261105.json /opt/ddna-release/content/novedad-primera-infancia-20261105.json
COPY ["RECURSOS GRÁFICOS - WEB DDNA 2026/NOVEDAD PRIMERA INFANCIA/", "/opt/ddna-release/RECURSOS GRÁFICOS - WEB DDNA 2026/NOVEDAD PRIMERA INFANCIA/"]
LABEL org.opencontainers.image.revision=${DDNA_REVISION}
