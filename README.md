# Grace
Multi Language App Builder and API Generator For Laravel Framework

Grace is a powerful automation less-code-more-results package in which a Laravel developers can build, modify, configure and maintain their apps through GUI interface instead of writing code evey now and then.
the main purpose for this package is not to quit writing at all, but to start where the machine can't go further.

# installation

you can install Grace using this command:
> composer require hani221b/grace

The next step is to register a service provider.
To do so in your Laravel application, go to config/app.php and paste this in **providers** array:
> Hani221b\Grace\Providers\GraceServiceProvider::class,

Now the package is registered successfully.

The last step is to run the following command:
> php artisan grace:install

This one will publish config file and some other necessary files to your app. Please make sure to connent your app to a database before running this command.

It is highly recommended to run this package only in development mode, so assuming you will run with **php artisan serve** vist the following route to access Grace controller panel:
> 127.0.0.1/grace_cp
