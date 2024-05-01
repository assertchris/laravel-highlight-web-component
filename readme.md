# `<w-code lang="php" />`

This is a Laravel plugin which introduces a custom [Web Component](https://developer.mozilla.org/en-US/docs/Web/API/Web_components) for highlighting code using [tempest/highlight](https://github.com/tempestphp/highlight).

To get started:

```txt
composer require tempest/w-code
```

Import and use the custom component:

```js
import Wcode from '../../vendor/tempest/w-code/src/w-code.js';
customElements.define('w-code', Wcode);
```

Use it in your templates:

```html
<w-code lang="php">
    print "hello world";
</w-code>
```

> [!WARNING]  
> You must still have [Axios](https://axios-http.com) in your Laravel project for this to work.

## Configuration

You can view and customise the config by publishing the vendor config files:

```txt
php artisan vendor:publish --provider=Tempest\\Wcode\\WcodeProvider
```
