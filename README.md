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

![Screenshot 2022-02-05 at 10-54-36 Mythos' Book Inventory System](https://user-images.githubusercontent.com/416568/152637308-217000e4-4411-434e-821c-e7aca395485b.png)

### Statistics

![Screenshot 2022-02-05 at 10-56-05 Mythos' Book Inventory System](https://user-images.githubusercontent.com/416568/152637314-eddaeae4-fe86-4def-b67b-65cf57f72247.png)
