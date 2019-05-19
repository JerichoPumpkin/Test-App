# Snce Exercise
The exercise requests were as following:
- use Symfony 4 framework with Doctrine 2
- solution based on Docker
- implement 3 pages to list, create and edit Products

The 3 pages specifics are:
- **/product/create** lets you add a new product with the following properties
  - name (mandatory)
  - description
  - image
  - tags (at least one)
- **/product/list** lists every product, in oredr of creation date. Each row shows image, name and creation date. 
  Clicking on image or name takes to the edit page. Also, it's possible to filter products by tag.
- **/product/{product}/edit** edit a product

# Installation Requirements
To proceed with the installation, Php 7.2.3 and MySql 5.7 are needed. 
Also Php needs Composer and GD, along with the libpng-dev and libjpeg62-turbo-dev extensions.

# Installation Guide

- Open the command prompt on your php server and install Symfony 4 with Composer using the command *composer create-project symfony/website-skeleton sf4*
- Copy the project files in the newly created folder /sf4 (overwrite if prompted)
- Go to /sf4 and run *composer install* to install dependencies
- Login to mysql: *mysql -u root -p* and create a new user:
*GRANT ALL PRIVILEGES ON *.* TO 'sf4'@'%' IDENTIFIED BY 'sf4';*
- Exit from mysql and run *mysql -u root -p < sf4.sql* to generate the database

If you plan to stage the project locally with Docker, remeber to swap to the mysql container before executing the last 2 steps.
Also you will need to modifiy these files: 
- **.env** comment line 27 and uncomment line 28   
- **/config/doctrine.yaml** comment line 6 and uncomment line 7

