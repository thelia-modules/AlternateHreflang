# Alternate Hreflang

This module generates a alternateHreflang URL for every page of your shop. Once activated, you'll find a `<link rel="alternate" hreflang=".." href="..." />` tag in the header of your pages.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is CanonicalUrl.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/alternate-hreflang-module:~0.2.0
```

## Usage

You just have to activate the module and check the meta tags of your shop.