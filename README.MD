# Exercice noté: réserve ton coach
### Deadline: 20 avril 2022, 23:59
### Mipam Guillot


Check it live : [ticket.mipamguillot.com](https://ticket.mipamguillot.com)



##Command to Run :
Clone
```sh
 git clone git@gitlab.com:Cocosmos/symfony.git
 cd symfony
```
Migrations
```sh
 php bin/console doctrine:migrations:migrate
```
Fixtures
```sh
 APP_ENV=dev php bin/console doctrine:fixtures:load -n
```