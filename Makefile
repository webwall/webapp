SITE_LESS = ./assets/style/site.less
ADMIN_LESS = ./assets/style/admin.less
SITE_CSS = ./webroot/css/site.css
ADMIN_CSS = ./webroot/css/admin.css
SITE_CSS_MIN = ./webroot/css/site.css
ADMIN_CSS_MIN = ./webroot/css/admin.css

build:
	@echo "Building .. "
	recess --compile ${SITE_LESS} > ${SITE_CSS}
	# recess --compile ${SITE_LESS} > ${SITE_CSS}
	recess --compile ${ADMIN_LESS} > ${ADMIN_CSS}

site-css: webroot/css/*.css

webroot/css/*.css: app/assets/style/*.less
	mkdir -p webroot/css
	recess --compile ${SITE_LESS} > ${SITE_CSS}
	recess --compress ${SITE_LESS} > ${SITE_CSS_MIN}

watch:
	echo "Watching less files..."; \
	watchr -e "watch('assets/style/.*\.less') { system 'make build' }"

dumpdb:
  mysqldump -u root -p  --compact -d webwall > db/schema.sql

.PHONY: watch site-css dumpdb
