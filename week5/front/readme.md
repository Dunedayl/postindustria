1) Настроить Apache создав виртуальный хост с адресом http://front.loc/ и указав соурcом свою папку dist
<VirtualHost *:80>
	ServerName front.loc
	ServerAlias www.front.loc
	ServerAdmin webmaster@localhost
	DocumentRoot /home/dunedayl/postindustria/week5/front/dist/

	<Directory /home/dunedayl/postindustria/week5/front/dist/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
	Header set Access-Control-Allow-Origin "*"
	</Directory>

	ErrorLog ${APACHE_LOG_DIR}/resterror.log
	CustomLog ${APACHE_LOG_DIR}/restaccess.log combined
</VirtualHost>