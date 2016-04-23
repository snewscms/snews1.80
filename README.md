# sNews 1.80

Welcome to [sNews] 1.80 a single engine file, template independant, PHP and Database powered, standards compliant Content Management System (CMS). 

sNews consists of one engine file (snews.php), one admin file (admin.php), one language file (EN.php), one XHTML file (index.php) for content presentation, one stylesheet (style.css) for content styling and one javascript file for functionality. This makes sNews the lightest and one of the most user-friendly CMSs available today.

**Before installing, please check that your server meets the minimum system requirements:**

  - [Apache Server] with mod_rewrite module enabled
  - One Database : [MySQL] / [sQLite] / [PostgreSQL] / [Firebird] / [sqlExpress]
  - [PHP] : Hypertext Preprocessor, minimum version 5.1
 


### Fits any OS (Linux, Mac, Windows)

Apache, MySQL and PHP are all Open Source applications and freely available. They come preinstalled on Mac OS X 10.2.8 and later; they can be installed both on Windows and Linux OS (detailed info can be found on their respective websites) and they form the most widely used database server setup on the Internet today. For best result in a local server environment though, we suggest that you use a dedicated server stack (applications bundle): LAMP (Linux), MAMP (Mac) and XAMPP (Windows) are the recommended stacks at this time.

# Install sNews by following these simple steps:

**1. Setup the Database**

> 1.1 - Create the Database: Start by creating a database and user/password (if required by the server), using the database administration tool available on your server (phpMyAdmin for example). If you already have a database set up, skip this step.

> 1.2 - Populate (Create tables in) the Database: You will find an SQL folder in your sNews package. There are two ready-made SQL data files in the folder. With your empty database selected, (using phpMyAdmin, for example), click the IMPORT tab and browse to the file you wish ti import... then hit the 'GO' button to create your tables. If you have created a fresh (emty) database for a new installation, import the snews17.sql file. If you are updating an existing sNews 1.6 installation, import the snews16-18.sql file.

# IMPORTANT NOTE: 

If you need to use a prefix, for example if your domain host only allows you to use one database, you should add that prefix to each table-name e.g. prefix_articles, prefix_extras, prefix_categories etc, as well as the table-names in all data INSERT strings, before running the code. You must also add that prefix into snews.php in step 2. Be aware that this prefix is something that you choose - bob_ or sitename_ - it is entirely separate from any prefix that your host may add.

Make a back-up copy of the SQL files in a safe place, so you always have spares on hand. Open the file you wish to use in your good code editor... add your prefix as noted above... and save your file with a unique file-name. Import the modified file to populate (or modify) your database with the table-data.

**2. Edit Settings**

>Edit "snews.php" and enter your settings at the top of the file:

>Please Note: The $db['variable'] strings within the Database variables function, as previously used in sNews 1.6, have been replaced with new strings. Also note that the $db['website'] variable that was needed in previous versions has been removed; it no longer needs to be manually entered, as the path is automatially detected. Example values (the parts that may need changing to suit your installation) are shown in red italics.

**MySQL Host: provided by hosting company. This is usually "localhost":**
* 'dbhost' => 'localhost',

**Database Name: created in phpMyAdmin or similar database editor:**
* 'dbname' => 'snews17',

**Database Username: created in phpMyAdmin or similar database editor:**
* 'dbuname' => 'root',

**Database password: created in phpMyAdmin or similar database editor:**
* 'dbpass' => 'root',

Database prefix: should end with an underscore (ex: 'snews_'); created in phpMyAdmin or similar database editor, usually used when a hosting company provides only one database:
* 'prefix' => ''

**3. Upload files**
Copy all the files from the sNews 1.8 package to your server - excluding the SQL and Patches Log folders (and their files) - and CHMOD 777 (= rwx-rwx-rwx) the folder(s) you'll upload your images (e.g. img) to. If you don't know how to change permissions, contact your server administrator.

Done!
You are ready to go! Login to start adding content and managing your site default username is test and password is test.
Bug reports, suggestions, comments, questions: sNews Forum





**Additional info**

> IMPORTANT! sNews makes use of the Apache module mod_rewrite and the system file .htaccess to create the Search Engine Friendly (SEF) links that are essential to the CMS. Using SEF or pretty links makes your site much more search engine friendly and gives it better rankings on Google and other popular search engines. If you install the sNews files in a subfolder on your domain root (i.e. http://mydomain.com/subfolder/), you must edit the included .htaccess file to match the path to your installation. On a web server, use your FTP client to locate the .htaccess file in your sNews installation folder (you will need to enable "show hidden files" or similar feature in the client);

> Open the .htaccess file in the FTP client or your text editor of choice and find the line: #RewriteBase /sNews18.
Change this line to look like the following, with the second part matching the path to your sNews installation:
RewriteBase /name-of-sNews-folder
> If you need to do the same operation locally on a Mac using Mac OS X or a PC using Windows Vista and can't find the .htaccess file, use the included "htaccess.txt" file to make your changes, then save it as .htaccess in your sNews installation folder. Mac OS X and Windows Vista (some editions) hide files that start with a period (.) from plain view.

More information about Using sNews on your local computer can be found in the Help Section at snewscms.com.

Codename: sNews Reborn!


Enjoy! **The sNews Team**








[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen. Thanks SO - http://stackoverflow.com/questions/4823468/store-comments-in-markdown-syntax)


   [snews]: <http://www.snewscms.com>
   [Apache Server]: <http://www.apache.org>
   [MySQL]: <https://www.mysql.com> 
   [sQLite]: <https://www.sqlite.org>
   [PostgreSQL]: <http://www.postgresql.org>
   [Firebird]: <http://www.firebirdsql.org>
   [sqlExpress]: <https://www.microsoft.com/en-us/server-cloud/Products/sql-server-editions/sql-server-express.aspx>
   [PHP]: <http://www.php.net>
   [git-repo-url]: <>
  

