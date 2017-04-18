# Laravel-5-Unsplash

## Installation
Require this package in your composer.json and update composer.

    composer require cbyte/unsplash

### Laravel

After updating composer, add the ServiceProvider to the providers array in config/app.php

    Cbyte\Unsplash\ServiceProvider::class,
    
And publish the config

    artisan verdor:publish

In there just add your Unsplash APP ID (https://unsplash.com/developers)
