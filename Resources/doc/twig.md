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


## Flag

```js
   {{ "DE"|flag }}
```

Returns the HTML Image code to the given flag name (ISO 3166-1 alpha-2). FamFamFam Flag icons are used.


## FOSUser

```html
{% if 'dev'==app.environment %}
    <select class="form-control form-control-solid placeholder-no-fix form-group" name="_username">
        {% for name in "PMCoreUserBundle:User"|fos_user_get_usernames %}
            <option>{{ name }}</option>
        {% endfor %}
    </select>
{% else %}
    <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Benutzername"
           name="_username" required/>
{% endif %}
```

Get choice selection for usernames in dev environment. Be sure your dev environment is not available online!

