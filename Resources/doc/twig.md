# TWIG

## File Version

Add Version path to assets (file.css?v=74nf). The version changes when the file is changed.

```js
{% stylesheets 
            '@ACMEDemoBundle/Resources/public/css/*'

         output='compiled/css/style.css' 
 %}
<link rel="stylesheet" href="{{ asset_url }}{{ "compiled/css/style.css"|cssversion }}" type="text/css" />{% endstylesheets %}

```
This generates "?v=XXXX"

```js
  {{ "compiled/css/style.css"|cssversion }}
```

Works with images and JavaScript files too.

## Hash

```js
   {{ ""|md5 }} {{ ""|sha1 }}
```

Returns random Sha1 and MD5 Hash. If not empty the value will be hashed.
