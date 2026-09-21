#!/bin/sh
set -eu

cd /var/www/html
wp_run() { wp --allow-root "$@"; }

mkdir -p wp-content/uploads
chown -R 33:33 wp-content/uploads

attempt=0
until [ -f wp-config.php ] && wp_run core version >/dev/null 2>&1; do
	attempt=$((attempt + 1))
	if [ "$attempt" -ge 60 ]; then
		echo "WordPress Core no estuvo disponible a tiempo." >&2
		exit 1
	fi
	sleep 2
done

if ! wp_run core is-installed >/dev/null 2>&1; then
	wp_run core install \
		--url="$WP_URL" \
		--title="$WP_SITE_TITLE" \
		--admin_user="$WP_ADMIN_USER" \
		--admin_password="$WP_ADMIN_PASSWORD" \
		--admin_email="$WP_ADMIN_EMAIL" \
		--skip-email
fi

wp_run plugin activate ddna-core
wp_run theme activate ddna-theme
wp_run option update home "$WP_URL"
wp_run option update siteurl "$WP_URL"
wp_run rewrite structure '/%postname%/' --hard
wp_run eval-file /var/www/html/scripts/setup-home-content.php
wp_run eval-file /var/www/html/scripts/setup-navigation.php
wp_run eval-file /var/www/html/scripts/setup-institutional-settings.php
wp_run eval-file /var/www/html/scripts/apply-final-sept-2026.php
chown -R 33:33 wp-content/uploads

echo "DDNA local quedó instalado y configurado."
