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

![Screenshot 2022-05-29 at 10-06-09 Mythos' Book Inventory System](https://user-images.githubusercontent.com/416568/170858541-f8f696e1-540c-424c-90df-5f3dc727d85a.png)

### Series details page

![Screenshot 2022-05-29 at 10-06-19 More than a Doll - Mythos' Book Inventory System](https://user-images.githubusercontent.com/416568/170858547-117a7906-36ee-4e50-83f4-398850120e7d.png)

### Statistics

![Screenshot 2022-05-29 at 10-06-26 Statistiken - Mythos' Book Inventory System](https://user-images.githubusercontent.com/416568/170858550-a48e81d1-b91e-4601-8874-51ed682882ed.png)
