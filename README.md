# Grace
Multi-Language App Builder and API Generator For Laravel Framework.

Grace is a powerful automation less-code-more-results package in which Laravel developers can generate code (controllers, models, routes, views, etc…) through a GUI interface to fasten the development process instead of doing all the routine work over and over again.

# Prerequisites

PHP >= 7.4

Larvael >= 10

# installation

1- Install Grace using this command:

 `composer require hani221b/grace`

2- Register the service provider. To do so in the Laravel app, go to **config/app.php**, and in the **providers** array paste:

> Hani221b\Grace\Providers\GraceServiceProvider::class,

  then clear config cache:

  `php artisan optimize`

3- Connect your app to a database. **PostgreSQL** or **MySQL**.

4- Install the package:

 `php artisan grace:install`

This command will publish the necessary files for the package to function well.
It is a good practice to run `php artisan optimize` if the routes do not work well.

5- navigate to Grace controller panel URL. Assuming you are running the project in localhost on port 8000:

> http://localhost:8000/grace_cp

# Usage

You can use the package to create Full Resource, which includes the following:
- Controller class.
- Model class.
- Request class (for validation).
- Resource class.
- append file system disk in config/filesystem.php to store files.
- migration file with desired fields (can be auto-migrated).
- append routes in routes/grace.php

This method is more easy and preferable.

The other method is to create each file separately. 

# Configuration options to consider

- **Mode**: the package provides the ability to choose between two modes: **blade** and **api**.
If **blade** mode is activated, views files will be generated whenever a Full Resource is created, and the routes will be under **web** middleware.
If **api** mode is activated, no view files will be generated, and the routes will be under “api” middleware, and prefixed with “/api”.
- **Status**: the option can enable or disable the routes for Grace controller panel, therefore, it cannot be accessed.

# ⚠️ WARNING ⚠️

**use Grace only in a development environment. Turn the status option to `disabled` in a production environment.**
Due to its create-files nature, a security vulnerability can occur if Grace is activated on production. Please consider setting the status config to `disabled` before deploying. 

