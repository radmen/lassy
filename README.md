# About

[![Build Status](https://api.travis-ci.org/radmen/lassy.png)](https://travis-ci.org/radmen/lassy)

Lassy is lazy static site generator. It means that every request (well not every.. check out [Filters section](#filters)) will generate a HTML file with its contents inside.

Every next request will be redirected to this file.

# Installation

Add `radmen/lassy` to `composer.json`:

```json
{
  "require": {
    "radmen/lassy": "1.0.*"
   }
}
```

Then in `app/config/app.php` add:

* `'Radmen\Lassy\LassyServiceProvider'` to `providers`
* `'Lassy' => 'Radmen\Lassy\Facade'` to `aliases`

And publish config: `php artisan config:publish radmen/lassy`

## Rewrites

Lassy only genereates HTML files. All requests must be rewrited to this files.
Here are some example configs for such rewrites.

Note that `_static` is only example dirname. It can be changed in config file.

### Apache

```
RewriteCond %{DOCUMENT_ROOT}/_static/%{REQUEST_URI} -f
RewriteRule ^(.*)$ /_static/$1 [QSA,PT,L]

RewriteCond %{DOCUMENT_ROOT}/_static/%{REQUEST_URI}/index.html -f
RewriteRule ^(.*)$ /_static/$1/index.html [QSA,PT,L]
```

# Filters
  
In some situations static file should not be generated. That's where filters come in action.  
Filter is closure which returns boolean. If it's `FALSE` Lassy will be disabled.

Lassy provides some basic filters:

* `Radmen\Lassy\Filter\AjaxRequest` checks if request is an AJAX call. If `TRUE` disable Lassy
* `Radmen\Lassy\Filter\GetRequest` enables Lassy only for `GET` requests
* `Radmen\Lassy\Filter\HtmlResponse` enables Lassy only for valid (`response code = 200`) HTML responses
* `Radmen\Lassy\Filter\QueriedRequest` disables Lassy when request has data in query

Filters can be specified in package config file.

Lassy can be enabled / disabled manually during the request. To do this run `Lassy::enable()`, or `Lassy::disable()` in your code.

# Clearing files

If you want to delete generated files just run `php artisan lassy:clear` in console.

# License 

The Lassy package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
