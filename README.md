### Task Desription
#### Requirements for a news subscription form

  * News are subscribed when a user fills in his name, email and chooses relevant news categories
(feel free to choose HTML template of your taste);
  * All subscription fields are mandatory (the user has to fill in his name, email and choose at least
one category);
  * The list of categories is defined, but new categories may be required in the future. This has to
be taken into account when coding;
  * Data has to be saved in a file (not in a database).
  
#### Requirements for subscriber list management

 * A subscriber list is accessible via login page (username and password) ;
 * Subscriber names, emails and chosen categories are presented in a list;
* List can be sorted by registration date, email and subscriber name.
* There is possibility to edit subscriber name and email, however the validation rules should
apply.
* It is possible to delete subscriber from the list.


#### Required setup/environment:
* PHP 7.2
* Symfony 4
* Mysql

#### Project dependencies
[Composer](https://getcomposer.org/) is used for managing dependencies.

Open a terminal window & run ```composer install``` command at root of the project.
It will install all required dependencies of the project which are specified in ```composer.lock``` file.
It will also create ```vendor``` directory on file system.


#### DB Connection
Open ```.env``` file and update this line as per your setup
DATABASE_URL=mysql://root:@127.0.0.1:3306/news_subscription

#### DB Schema
DB schema for ```User``` entity is defined in  App\Entity\
To generate the necessary schema in database, run ```php bin/console doctrine:schema:update --force``` at root of the project.


#### Web server:
If you don't have apache/nginx already configured, you can use php built-in web server as well.
Open a terminal window & run ```php bin/console server:start``` at root of the project to start the server. 
Then open a browser with address & port where web
server started in terminal window.

#### Login: 
To login the system, first create a new account using ```Register``` link
