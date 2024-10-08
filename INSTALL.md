# CONTENTS OF THIS FILE

* Requirements
* Installation
* Additional Information

# REQUIREMENTS

**Oxwall Requires:**

- Apache WEB server, version 2.0 or higher. ( http://httpd.apache.org/download.cgi )
- PHP 8.3 or higher. ( http://www.php.net/ )
- MySQL 8.0 or higher. ( http://dev.mysql.com/downloads/ )
- Mail Server installed: SendMail, Exim or other. ( http://www.exim.org/mirrors.html )
- Cron ( http://en.wikipedia.org/wiki/Cron )

For more detailed information about Oxwall requirements, including the list of necessary PHP extensions and configurations, visit Oxwall Hosting page and see System Requirements. ( http://www.oxwall.org/hosting )

# INSTALLATION

### 1. Download and Extract Oxwall.

The latest Oxwall release can be found at http://www.oxwall.org/download . The main package is available in zip format, which can be extracted using most compression tools.

a. FTP Upload 
Extract Oxwall archive to a temporary directory and rename the unpacked folder to ```oxwall_x.y.z/```;
Upload contents of ```oxwall_x.y.z/``` into your site’s public directory (typically ```public_html/```) using an FTP client. Do not forget about ```.htaccess``` file.
 
b. Shell Mode
Enter following commands using a typical Unix/Linux command line to download and extract files:

- ```wget http://www.oxwall.org/dl/ow_x.y.z.zip```
- ```unzip oxwall_x.y.z.zip```

This will create a new directory ```oxwall_x.y.z/``` containing all Oxwall files and directories. Enter following command to move contents of this directory to your public HTML directory: 

- ```mv oxwall_x.y.z/* oxwall_x.y.z/.htaccess /path/to/your/public_html/```
 
### 2. Create Database.

Oxwall stores all site information in a MySQL database, which means you must create this database on your hosting and assign it a user with certain privileges, such as create/drop and modify tables.
Note the username, password, database name, and hostname given during the database creation, since you will need it during the installation.

If you encounter problems creating the database, please contact your hosting provider for support and specific instructions. 

### 3. Run Installation.

Go to http://www.mysite.com/install to run the install script. Follow on-screen instructions to set up the database connection info, add additional plugins, create the site admin account, and provide basic website settings.

Most common steps required include:

a. Write permissions

The install script will attempt to write to these folders:
 
 - ```ow_pluginfiles/```
 - ```ow_userfiles/```
 - ```ow_static/```
 - ```ow_smarty/template_c```
 
A notification will appear if this process fails. In this case set writable permissions recursively. For instance, go to folder with Oxwall and enter the following command for ```ow_pluginfiles/``` directory:
 
 - ```chmod -R 777 ow_pluginfiles/```

or use native tools of your FTP client to change permissions for selected folders. Repeat for other folders. 
 
b. Create config file

During the installation you must manually replace contents of ```ow_includes/config.php``` file with the code provided by the installation guide.
 
### 4. Setup Cron Tasks.

Many Oxwall features include tasks that will be run periodically, such as updating profile's online status, processing memberships, mass mailing, importing data, etc. This is why it is strongly recommended to setup Oxwall Cron Tasks immediately after the main installation. 

As an example of how to setup this automated process, you can use the Unix/Linux crontab utility. The following crontab line uses PHP command to execute the run.php file, and runs every minute (recommended):

- ```* * * * * /usr/local/bin/php /your/path/to/oxwall/ow_cron/run.php```

where ```/usr/local/bin/php``` is the path to the PHP binary file. You may contact your hosting to determine the exact path to the PHP binary file on your hosting.

# MORE INFORMATION

- See full Oxwall installation manual: http://docs.oxwall.org/install:manual_installation

- Install Oxwall on your local PC: http://docs.oxwall.org/install:local_installation

- Install Oxwall without any downloads: http://docs.oxwall.org/install:auto_installation

- Cron configuration guide: http://docs.oxwall.org/install:cron

- Connect your site with Cloud Storage: http://docs.oxwall.org/install:cloud_hosting
