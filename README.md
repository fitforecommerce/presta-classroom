# PrestaClassroom
A platform for installing multiple Prestashop instances easily for use in school classrooms. This software is one of the results of the  project [Fit for E-Commerce](https://fitforecommerce.github.io) which is co-funded by the Erasmus+ programme of the European Union.

## Installation with Docker
1. Make sure you have got [Docker](https://www.docker.com/get-started) installed.
2. Download the current release of Presta-Classroom from the [release page](https://github.com/fitforecommerce/presta-classroom/releases)
2. Open the command line and go to the presta-classroom directory
4. Enter the command ``docker-compose up``
5. You should now be running a webserver. Point your browser to localhost:8000 to access the Presta-Classroom installation.

## Installation on an Apache Webserver
1. Download the current release from the [release page](https://github.com/fitforecommerce/presta-classroom/releases)
2. Upload the directory to your webserver
2. Edit the ```lib/config/config.yml``` to match your configuration
3. Edit the ```.htaccess``` file in the root folder and fix the base url
4. Open your browser and go to the server address

## Presta-Classroom Usage
1. Open the root url of your PrestaClassroom installation.
2. Download a Prestashop version for installation via the Download button.
3. Configure the installation under via the Install button.
4. Press Start and wait while the installation progresses.

## Notes for Developers (just ignore)
* Fixing issues with MAMP and cli installer: https://stackoverflow.com/questions/22188026/sqlstatehy000-2002-no-such-file-or-directory: ```sudo mkdir /private/var/mysql; cd /private/var/mysql && sudo ln -s /Applications/MAMP/tmp/mysql/mysql.sock`

* Add access rights to user ```GRANT CREATE USER, RELOAD on *.* TO presta WITH GRANT OPTION; GRANT CREATE USER, RELOAD on *.* TO presta@localhost WITH GRANT OPTION; flush privileges;```

* Info on installing Prestashop modules from the CLI: https://github.com/nenes25/prestashop_eiinstallmodulescli/blob/master/README.md
* Error 500 when running Prestashop Installer on MAMP server: ```[Sun Feb 10 15:28:54 2019] [error] [client ::1] FastCGI: comm with server "/Applications/MAMP/fcgi-bin/php7.2.8.fcgi" aborted: idle timeout (30 sec), referer: http://localhost:8888/classroom/public/install/execute
[Sun Feb 10 15:28:54 2019] [error] [client ::1] FastCGI: incomplete headers (0 bytes) received from server "/Applications/MAMP/fcgi-bin/php7.2.8.fcgi", referer: http://localhost:8888/classroom/public/install/execute``` read here for fix: https://stackoverflow.com/questions/24715426/how-do-you-increase-the-apache-fastcgi-timeout-on-mamp-mamp-pro

## Todo
- [ ] Change User to allow login of multiple users
- [ ] Catch errors if database connection is not working when running: the /configure action

## Disclaimer

![Co-funded by the Erasmus+ Programme of the European Union](https://fitforecommerce.github.io/img/co-funded-erasmus+.jpg)

The European Commission support for the production of this publication does not constitute an endorsement of the contents which reflects the views only of the authors, and the Commission cannot be held responsible for any use which may be made of the information contained therein.