window.$ = window.jQuery = require('jquery');

// Import Bootstrap components individually
require('bootstrap-sass/assets/javascripts/bootstrap/transition');
require('bootstrap-sass/assets/javascripts/bootstrap/collapse');

// Import Cookie Consent library
require('cookieconsent');

// Import template components
require('./components/navbar');
require('./components/cookieconsent');
