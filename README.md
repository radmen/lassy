# lassy

Lazy static site generator for Laravel 4

This README needs an update ;)

# Rewrites

## Apache

```
  RewriteCond %{DOCUMENT_ROOT}/_static/%{REQUEST_URI} -f
  RewriteRule ^(.*)$ /_static/$1 [QSA,PT,L]

  RewriteCond %{DOCUMENT_ROOT}/_static/%{REQUEST_URI}/index.html -f
  RewriteRule ^(.*)$ /_static/$1/index.html [QSA,PT,L]
```
