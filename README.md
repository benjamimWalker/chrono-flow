![Project logo](https://raw.githubusercontent.com/benjamimWalker/chrono-flow/master/assets/logo.png)

## Overview

Chrono Flow is a Laravel project focused on high-performance backend data processing. It lets you upload a large (≈80 MB) CSV simulating a work log of 1,000,000 entries. Once submitted, the app quickly extracts and inserts all records into a MySQL database. Such a large number of entries would be preferably handled in a queue, but to show the performance it was done in a single request.

## Technology

Key Technologies used:

* Laravel 12
* MySQL
* Nginx
* Docker + Docker Compose
* Alpine.js & Tailwind CSS
* PestPHP

## Getting started

> [!IMPORTANT]  
> You must have Docker and Docker Compose installed on your machine.

* Clone the repository:
```sh
git clone https://github.com/benjamimWalker/chrono-flow.git
```

* Go to the project folder:
```sh
cd chrono-flow
```

* Prepare environment files:
```sh
cp .env.example .env
```

* Build the containers:
```sh
docker compose up -d
```

* Install composer dependencies:
```sh
docker compose exec app composer install
```

* Run the migrations:
```sh
docker compose exec app php artisan migrate
```

* Build the assets:
```sh
docker compose run --rm npm install
```

* You can now execute the tests:
```sh
docker compose exec app php artisan test
```

## How to use

### 1 - Upload the CSV

There is a python script on the root folder that generates a CSV file with 1,000,000 entries. You can run it and upload the file to the application.
Navigate to the home page at `http://localhost` and upload the CSV.

![Content creation image](https://raw.githubusercontent.com/benjamimWalker/chrono-flow/master/assets/home.png)
![Content creation image](https://raw.githubusercontent.com/benjamimWalker/chrono-flow/master/assets/ready.png)

### 2 - Check the contents
After importing, the app redirects to a results page that lists all entries paginated.

![Content creation image](https://raw.githubusercontent.com/benjamimWalker/chrono-flow/master/assets/list.png)

## Features

The main features of the application are:
- High-speed processing of large CSV files using Laravel 12's new concurrency features.
- Bulk insertion of up to 1,000,000 records into MySQL in a few seconds.
- Full test coverage with PestPHP.
- Clean, maintainable Laravel 12 code with proper architecture.

[Benjamim] - [benjamim.sousamelo@gmail.com]
Github: [@benjamimWalker](https://github.com/benjamimWalker) 
