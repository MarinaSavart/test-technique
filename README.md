# Test technique Api Platform 3 et Symfony 6.4

## Table of Contents

- [About](#about)
- [Getting Started](#getting_started)
- [Installing](#installing)

## About <a name = "about"></a>

Welcome to my awesome technical test 😁

## Getting Started <a name = "getting_started"></a>

Clone the repository.

```
git clone https://github.com/MarinaSavart/test-technique.git
cd test-technique
```


### Installing <a name = "installing"></a>

Install composer
```
composer install
```

Generate your keys config/jwt/private.pem and config/jwt/public.pem 
```
php bin/console lexik:jwt:generate-keypair
```

Add the .env file to your database access, and run :
```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

Finally I added Fixtures to make testing easier for you, there are 5 categories created and two users with two different roles. You will need to create the articles using the API

```
php bin/console doctrine:fixtures:load
```

### Enjoy It ! 🚀 