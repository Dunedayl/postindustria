1) Поднять базу данных MySQL, прописать данные для подключения в файл config
2) Создать таблици в базе данных из файла tables.sql
3) Настроить Apache создав виртуальный хост с адресом restapi.loc и указав соурс папку back  
<VirtualHost *:80>
	ServerName restapi.loc
	ServerAlias www.restapi.loc
	ServerAdmin webmaster@localhost
	DocumentRoot /home/dunedayl/postindustria/week5/back/

	<Directory /home/dunedayl/postindustria/week5/back/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
	Header set Access-Control-Allow-Origin http://front.loc
	</Directory>

	ErrorLog ${APACHE_LOG_DIR}/resterror.log
	CustomLog ${APACHE_LOG_DIR}/restaccess.log combined
</VirtualHost>

4) Прописать в файле /etc/hosts адреса виртуальных хостов
127.0.0.1       restapi.loc     www.restapi.loc
::1     restapi.loc     www.restapi.loc
127.0.0.1       front.loc       www.front.loc
::1     front.loc       www.front.loc