Smalldb REST Demo
=================

Example application of Smalldb REST API. This example should be able to provide
complete backend for small single page applications.

The repository contains only script to build the example from current versions
of required libraries (see Setup and Usage below).


Setup
-----

  1. Checkout the repository.
  2. Run `./import-example.php` (or `php import-example.php`) from command line.
  3. Make `database.sqlite` writable for web server.


Usage
-----

The entry point of REST API is `api-v1.php`. See 
[documentation of `smalldb-rest` library](https://smalldb.org/doc/smalldb-rest/master/) 
for examples how to use the API.


License
-------

Import script is licensed under Apache 2.0 License.

The imported example, excluding imported libraries, is public domain with no
warranty of any kind. The example is meant as base of your development, so feel
free to use it as you wish.


