<p align="center">
    <img src="lunchbot.png" alt="" width="125">
  <h3 align="center">LunchBot Backend</h3>
</p>

LunchBot Backend is a Symfony based project, which provides suggestions of meals for lunch. It crawls websites from various websites/images/pdfs and provides a suggestion based API. All meals from the locations which are set up in this Backend can also be seen using the API.

This project has been developed initially developed as "Praxis der Softwareentwicklung" at the [KIT](http://ps.ipd.kit.edu).

### General
Not all parts are finished for a perfect suggestion or crawling. PDF Parser or Image Parser commands don't work for every document.

### Installation
* Git Clone this repository.
* Make sure you have a MariaDB/MySQL server running
* Make sure you have installed PHP version >= 7.0 ``php -v`` and update if you have an older version
* Install [Composer](https://getcomposer.org/).
* Run `composer install` in the project's root.
* Define the credentials of yourMySQL server on composer install
* Create the database by running `bin/console doctrine:database:create`
* Create the database schema by running `bin/console doctrine:schema:create`
* Import _Doctrine fixtures_ by runnung ``bin/console initialize:database`` This imports Default Restaurants, Categories, Users. This avoid loading all fixtures.

If you are deploying the application on production server, you have to make sure to install it in production mode. Please read the [Symfony documentation](http://symfony.com/doc/current/index.html#gsc.tab=0). 
Please modify the admin password.


If you want to scrap all available restaurants (websites), use the following command:
``bin/console scrap:all``

If you want to download all available data (images, pdfs) of restaurants, use the following command:
``bin/console download:all``

If you want to parse data (images, pdfs) of restaurants, use the following command:
``bin/console parse:pdf``
``bin/console parse:image``


### Routing
General Nomenclature for routes:  entityName_action
Example of possible actions:
* list
* add
* edit
* detail
Example routes:
* restautant_list
* restautant_add
* restautant_edit

All routes:
`bin/console debug:router`

### Accounts
#### Default account (created in doctrine fixtures)
* Admin Account:
	* Username: admin
	* Password: admin
	
#### Create a new account
* Run `bin/console fos:user:create` and enter a username, email and password.
* Run `bin/console fos:user:promote [created username] ROLE_ADMIN` to promote your user and enable access to `\admin` page.
