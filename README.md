# Test-App
Simple Sf4 exercise

Login to mysql:
mysql -u root -p

Create our user:
GRANT ALL PRIVILEGES ON *.* TO 'sf4'@'%' IDENTIFIED BY 'sf4';

Generate the database:
mysql -u sf4 -p < sf4.sql
