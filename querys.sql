-- mysql
CREATE DATABASE ciisa;
CREATE USER 'iDonoso'@'localhost' IDENTIFIED BY 'l4cl4v3';
GRANT ALL PRIVILEGES ON ciisa.* TO 'iDonoso'@'localhost';
FLUSH PRIVILEGES;