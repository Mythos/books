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
<!-- * As series covers are stored publicly run 
    * `php artisan storage:link`
    *  or `ln -s ./storage/app/public ./public/storage` if you are on a shared webspace -->

## Contribution

You are very welcome to create a Pull Request. Please consider using proper commit messages describing the change (i.e. not only `fix` or something like that) and assign me as a reviewer.
If you want to bring in new features, please create an issue for discussion first.

## License

MIT

## Screenshots

### Landing page & Gallery

![grafik](https://user-images.githubusercontent.com/416568/146996766-f6c09054-6b96-42a4-9475-0eb0e309cf7c.png)


### Series details page

![grafik](https://user-images.githubusercontent.com/416568/146996593-bae7d4e6-5c6d-47a0-a22b-4adfc3455776.png)
