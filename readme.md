# Michael's Website

This is the source code for the michaels.website application

## Requirements

- PHP 7.4 or newer
- [Composer 2](https://getcomposer.org/download/)
- [NPM](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm)

## Installation

1. Clone this repository (`git clone git@github.com:mbabker/michaels.website.git michaels.website`)
2. Copy the `.env.example` file to `.env`, the defaults are enough to run the application without changes
3. Install the PHP dependencies with `composer install`
4. Generate a new app key with `php artisan key:generate`
5. Install and compile the front-end dependencies with `npm install && npm run dev`
6. Ensure your local webserver is set up to run the application (you can use `php artisan serve` to run the in-built PHP web server)
