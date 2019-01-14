# openpayroll
Openpayroll is a free, online and open source payroll software designed for small businesses. It is currently designed for and conforms to  the Kenyan tax standards but can easily be adapted to any tax regime.

Requirements

PHP 7

Database (eg: MySQL, PostgreSQL)

Web Server (eg: Apache, Nginx, IIS)

<h2>Pre-requisites</h2>

The system requirements for installing OpenPayroll are described below. Make sure your system meets these requirements.

a. Built for PHP 7.0 and above. It can work with PHP 5.3 or later but no guarantees can be given. To install PHP 7.x on Linux, please follow the below links:

 For Ubuntu: https://www.digitalocean.com/community/questions/how-to-install-php7-0-fpm-on-ubuntu-18-04-server
 For Redhat and CentOS: http://www.thetechnicalstuff.com/install-php7.0-in-centos-and-redhat/
b. PDO MySQL (for MySQL connection) To install OpenPayroll on Linux, you can compile php with --with-pdo-mysql in your php.ini, and add the following lines:

 1. extension=pdo.so
 2. extension=pdo_mysql.so
c. Rewrite module (for working of MVC architecture) activate mod_rewrite in linux, open the terminal and add the below line:

 1. sudo a2enmod rewrite
 
 You also need to make sure that in your httpd.conf, AllowOverride is enabled:
 2. AllowOverride All
d. GD library (for images) To install GD library in Linux, open the terminal and add the below lines:

 1. #apt-get install php7-gd
e. Open SSL (For SSL and TSL Protocols) Download the OpenSSL 1.0.1c tarball archive from the OpenSSL web site at http://www.openssl.org/source/

<h2>Copying files</h2>

Move OpenPayroll zip file into the document root of Apache HTTP server.
If you used XAMPP for windows, document root is \htdocs\
For example: C:\xampp\htdocs\

<h2>Extracting</h2>

Extract the OpenPayroll zip file in the document root of Apache HTTP server

Once xtracted, edit the file /assets/classes/class.db.php with your respective details

The database dump file is located in teh /database directory

User details are:
info@democompany.org
123456

<h2>Contributing</h2></h2>

Fork the repository, make the code changes then submit a pull request.

Please, be very clear on your commit messages and pull requests, empty pull request messages may be rejected without reason.

When contributing code, you must follow the PSR coding standards. The golden rule is: Imitate the existing code.

Please note that this project is released with a Contributor Code of Conduct. By participating in this project you agree to abide by its terms.
