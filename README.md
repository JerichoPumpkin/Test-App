# Snce Exercise
The exercise requests were as following:
- use Symfony 4 framework with Doctrine 2
- solution based on Docker
- implement 3 pages to list, create and edit Products

The 3 pages specifics are:
- "/product/create" lets you add a new product with the following properties
  - name (mandatory)
  - description
  - image
  - tags (at least one)
- "/product/list" lists every product, in oredr of creation date. Each row shows image, name and creation date.
  Clicking on image or name takes to the edit page. Also, it's possible to filter products by tag.
- "/product/{product}/edit" edit a product

# Installation Requirements
To proceed with the installation, Php 7.2.3 and MySql 5.7 are needed. 
Also Php needs Composer and GD with the libpng-dev and libjpeg62-turbo-dev extensions.

# Installation Guide



Login to mysql:
mysql -u root -p

Create our user:
GRANT ALL PRIVILEGES ON *.* TO 'sf4'@'%' IDENTIFIED BY 'sf4';

Generate the database:
mysql -u sf4 -p < sf4.sql
