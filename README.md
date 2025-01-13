### --How to initialize frontend project-- ###

1- Clone the project

2- Rename Config_example.php to Config.php

3- Fill in the properties fields in Config.php

4- Rename .env_example to .env

4- Uncomment the DATABASE_URL field that corresponds to your database in .env file

5- Replace PUBLIC_KEY and PRIVATE_KEY with your mailjet credentials

6- Type composer install in the terminal

7- Type symfony console doctrine:database:create

8- Type symfony console doctrine:migrations:migrate

9- Type symfony server:start to start local server!