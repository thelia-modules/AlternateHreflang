# Alternate Hreflang

This module generates a alternateHreflang URL for every page of your shop. Once activated, you'll find a `<link rel="alternate" hreflang=".." href="..." />` tag in the header of your pages.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is AlternateHreflang.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/alternate-hreflang-module:~1.1.0
```

### Usage

You just have to activate the module and check the meta tags of your shop.

### Generation example

If your current lang is `en` and your default lang is `fr`

```html
<link rel="alternate" hreflang="en" href=".... en url" />
<link rel="alternate" hreflang="fr" href=".... fr url" />
<link rel="alternate" hreflang="es" href=".... es url" />
<link rel="alternate" hreflang="it" href=".... it url" />
<link rel="alternate" hreflang="x-default" href=".... fr url" />
```

If your current lang is `fr` and your default lang is `it`

```html
<link rel="alternate" hreflang="fr" href=".... fr url" />
<link rel="alternate" hreflang="en" href=".... en url" />
<link rel="alternate" hreflang="es" href=".... es url" />
<link rel="alternate" hreflang="it" href=".... it url" />
<link rel="alternate" hreflang="x-default" href=".... it url" />
```

This module generate hreflang only for languages enabled in the backoffice

If you want the locale `hreflang="...."` on 5 characters, you have to pass the configuration variable `hreflangFormat` to 1

If your current lang is `fr` and your default lang is `it`

```html
<link rel="alternate" hreflang="fr-fr" href=".... fr url" />
<link rel="alternate" hreflang="en-us" href=".... en url" />
<link rel="alternate" hreflang="es-es" href=".... es url" />
<link rel="alternate" hreflang="it-it" href=".... it url" />
<link rel="alternate" hreflang="x-default" href=".... it url" />
```

### Hook

This module uses the hook `main.head-bottom`