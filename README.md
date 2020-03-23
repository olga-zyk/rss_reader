# Web Application RSS Feed Reader

After cloning the repository to your local machine, use:

```
To start the local server
  sudo symfony server:start
  
To stop the local server
  sudo symfony server:stop
  
Connect to Database:
  mysql -h 127.0.0.1 -P 3306 -u root
  
Create database
  php bin/console doctrine:database:create
  
Create User table in database
  php bin/console make:migration
  
Execute migrations
  php bin/console doctrine:migrations:migrate
  
To load a fake set of data into a database 
  php bin/console doctrine:fixtures:load
  
Compile asssets once 
  yarn encore dev
  
or, recompile files automatically when files change
  yarn encore dev --watch
```
