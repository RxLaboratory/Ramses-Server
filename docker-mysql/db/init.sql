ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'rootpassword';
CREATE USER 'admin'@'localhost' IDENTIFIED BY 'rootpassword';
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost' WITH GRANT OPTION;
CREATE USER 'ramses'@'db' IDENTIFIED WITH mysql_native_password BY 'password';
ALTER USER 'ramses'@'db' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON `ramses`.* TO 'ramses'@'db';