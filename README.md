Fuzzy Keyword Suggestions for YOURLS
====================================

This is a plugin for the [YOURLS](http://yourls.org) link shortener that handles typos and other "near-misses" for any shortened link.  It is helpful when conveying shortlinks via printed or otherwise non-clickable media.

Manually typing shortened URLs often leads to a "1" being confused for an "l", "0" for "O", etc.  This plugin will intercept any non-existent keyword request and find "close" matches in the database using the [Levenshtein string similarity](https://en.wikipedia.org/wiki/Levenshtein_distance) algorithm.  This plugin can also handle requests for the root URL of your YOURLS installation, using the same display template as above.

## Installation
1. Install YOURLS on your server
1. Install/create a ``LEVENSHTEIN()`` function to your MySQL server (See "MySQL Options", below.)
1. Download the code from this repository
1. Place the code into the ``user/plugins/ltc-ltc-fuzzy-keyword-suggestions/`` directory
1. Log into the YOURLS administration page and activate the plugin
1. Optionally customize the results page using the included default template

## MySQL Options
MySQL does not natively provide a ``LEVENSHTEIN()`` function, but you have several options to provide it.

1. Install a User-Defined Function (UDF) module.  This has a 500-1000x performance benefit over the second option, in my testing - but it does require administrative access to your database server host.  The [Levenshtein-MySQL-UDF](https://github.com/jmcejuela/Levenshtein-MySQL-UDF) project on GitHub worked perfectly on a MySQL 5 server in my testing.
1. Create a database-level function.  This only requires ``CREATE ROUTINE`` privileges to your YOURLS MySQL database, but it is much slower.  This repository includes a ``mysql_levenshtein_function.sql`` file that may help, and [this Stack Overflow thread](http://stackoverflow.com/questions/12617348/mysql-levenshtein) may also be of assistance.
Note that the plugin will NOT activate if you do not have an available MySQL function named ``LEVENSHTEIN()``.

## Usage and Customization
No special usage instructions are required - the plugin should work right "out of the box".  However, you may want to take a few steps to optimize and polish the experience.

1. Customize the output template.  Using the ``template-default.php`` file as a guideline, create a file named ``template.php`` in the plugin directory.  There are examples in ``template-default.php`` showing how to provide meaningful output as well as how to reference external resources such as images, stylesheets, etc.
1. Ensure the title values in the YOURLS database are meaningful.  You can choose to include these Title values in the list of suggested links, which will help your users to confirm they are going to the correct intended resource.

## License
Licensed under the [MIT License](http://opensource.org/licenses/mit-license.php).