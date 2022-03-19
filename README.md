# Mythos' Book Inventory System

This project is a little helper to keep track of my personal manga collection. It allows me to save series with their ISBNs and publish dates and set a status for each book. (New, Ordered or Delivered)

I also use this project to improve my Laravel skills as I'm mainly a .NET developer. So please expect bugs, bad code style and probably some hacks.

## Installation

* Make sure to have [Laravel 8.X requirements](https://laravel.com/docs/8.x/deployment#server-requirements) installed
* Clone this repository
* Run `composer install --no-dev`
* Set your credentials and settings in `.env`
* Run `php artisan migrate`
* Set up the `DocumentRoot` correctly (points to `/public/` folder)
* As series covers are stored publicly run 
    * `php artisan storage:link`
    *  or `ln -s ./storage/app/public ./public/storage` if you are on a shared webspace

## Contribution

You are very welcome to create a Pull Request. Please consider using proper commit messages describing the change (i.e. not only `fix` or something like that) and assign me as a reviewer.
If you want to bring in new features, please create an issue for discussion first.

## License

MIT

## Screenshots

### Landing page & Gallery

![Screenshot 2022-02-05 at 10-28-32 Mythos' Book Inventory System](https://user-images.githubusercontent.com/416568/152637303-94a354d9-c717-47b3-b238-c1a732339507.png)


### Series details page

![Screenshot 2022-03-19 at 06-33-45 Mythos' Book Inventory System](https://user-images.githubusercontent.com/416568/159108622-7a53bb94-652f-4868-b1fb-20d46a3495b8.png)

### Statistics

![Screenshot 2022-03-19 at 06-33-51 Mythos' Book Inventory System](https://user-images.githubusercontent.com/416568/159108629-2f578831-720f-403f-baf2-40f3ada6d5eb.png)

