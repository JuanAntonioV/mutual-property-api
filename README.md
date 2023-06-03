# Mutual Property API

## Introduction

This application is a REST API that contain a list apis to Mutual Property Application. This application 
is developed using [Laravel](https://laravel.com/) framework.

## Installation

### Prerequisites

- PHP >= 8.2.5
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/)
- [Postman](https://www.postman.com/)
- [Git](https://git-scm.com/)

### Clone the repository

```bash
git clone
```

### Install dependencies

```bash
composer install
```

### Create a database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE mutual_property;
```

### Configure environment variables

```bash
cp .env.example .env
```

```bash
php artisan key:generate
```

### Run migrations

```bash
php artisan migrate
```

### Run the application

```bash
php artisan serve
```

### Production

```bash
php artisan serve --env=production
```

```bash
git add .
```

```bash
git commit -m "Commit message"
```

```bash
git push
```

```bash
git checkout main && git merge dev && git push && git checkout dev
```

## Versioning

- PHP: ^8.2.5
- Laravel: ^10.10
- Node: ^18.16.0
- NPM: ^9.6.4

## Authors

- [Juan Antonio Vivaldy](https://juanantoniovivaldy.vercel.app/)
