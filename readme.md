# 1984 Connector for DK and WooCommerce

> [!CAUTION]
> **Do not open an issue ticket if you are reporting a security vulnerability. Contact 1984 Hosting directly via 1984@1984.is or the WordPress Security Team instead.**

> [!IMPORTANT]
> This is a code repository used for the development of **1984 Connector for DK and WooCommerce**, a WordPress plugin. Using the raw source files without installing dependencies using Composer will not work and the `main` branch is considered unstable.

> [!NOTE]
> To download a working version of the plugin, please visit the **[Releases Page](https://github.com/1984hosting/1984-dk-woo/releases)** and download the `1984-dk-woo.zip` file for a current version. In the future, you will be able visit [https://wordpress.org/plugins/1984-dk-woo/](https://wordpress.org/plugins/1984-connector-for-dk-and-woocommerce/) to get the currently stable release and get automatic updates.

## Developer Documentation

Please note that there are two readme files. This one (reamde.md) and the user-facing readme ([readme.txt](https://github.com/1984hosting/1984-connector-for-dk-and-woocommerce/blob/main/readme.txt)) used for providing metadata to the WordPress.org plugin repository as well as a general introduction to the plugin.

This file is mainly intended for development and contribution purposes.

### System Requirements

We use Github actions as a continious integration process to automatically test the plugin using the following PHP and WordPress versions:

* PHP 8.2 and above
* WordPress 6.8 and above

We generally assume that the most recent version of WooCommerce is in use and use that for testing across the supported PHP and WordPress versions. This is done using a test martrix in the Github CI process.

### Introduction and main concepts

In the most simple terms this WordPress plugin syncs information between a WooCommerce store and the DK accounting software. It syncs product data, creates invoices for fulfilled orders and sync stock levels for products between the two.

#### Two sources of truth

* The WooCommerce store is the source of truth for product prices and availability towards the customer
* The DK setup is the source of truth for accounting transactions

### The DK API

#### The DK API documentation

The DK API documentation is well known for being inaccurate and it seems to be written by two different programmers. They also don't seem to like each other very much.

One version of it can be found at https://apidoc.dkplus.is/ and is generated using Postman.

The other version is generated using Swagger and is available at https://api.dkplus.is/swagger/ui/index.

Either version documents certain endpoints at different states. Some features are missing and some of the specifics described seem to be documented ahead of time and don't actually exsist (as they may only be working internally, with the public version trailing behind). There are also spelling errors and other discrepencies in some JSON properties that are not reflected in the documentation.

#### Caveats

DK will cut off some string values that exceed its limits without warning. This is not well documented by them.

* Product Codes (SKU): The DK API is unable to accept longer values than 20.
* Product variations need to be treated as separate products
* Product variation SKUs have to be unique and not the same as the parent.
* Product descriptions: The DK API is unable to accept longer textual values than 40.

This means that syncing product descriptions etc. may not be possible using orthodox methodologies and that string values should always be assumed to be either 20 or 40 characters long. (It may be possible to do so via "attachments" but the documentation for those remains questionable.)

## Contributing

The main code repository for the plugin is at https://github.com/1984hosting/. The Subversion account for the WordPress plugin respository is used for "built" releases of the plugin.

We are happy to receive patches and pull requests for new feature ideas, bug fixes and translations. **Please contact us directly at 1984@1984.is or the WordPress Security Team in case of security voulnerabilities.**

If you are reporting a bug, please describe the steps needed to be taken so that we can replicate it, if possible.

And last but not least, be nice to us and others.

### Coding Style and Best Practices

A WPCS-based coding style is enforced using PHPCodeSniffer. We have done some modifications and exceptions that are documented in the `phpcs.xml` file.

Our coding style rules apply to PHP, CSS and JS files. Please make sure that your editor supports and respects `.editorconfig` and that it integrates with the version of PHPCodeSniffer that is installed by Composer, in our `vendor` directory.

We also use PHP Intelephense to enable autocompletion and syntax highlighting for WordPress and WooCommerce specific functions. Please facilitate it by using and defining object classes specific to your use case.

* We are PHP 8.2 compliant, use strict mode, type hinting, strong typing and PSR-4 autoloading
* Due to the nature of WordPress' hooks, while the code is written in an object oriented style, classes are written using static functions to a large extent
* Functions, objects, variables etc. are named using Ruby conventions (i.e. snake case and no shorthand names)
* Please keep runtime code within the `src` directory and install external dependencies using Composer
* The linter rules enforce docblocks for PHP functions, methods, classes etc. using the same rules as in PSR-12
* We try to stay within a soft 80-character line limit if we can, but we will not enforce it until a 120-character limit
* Please run `composer lint` and `composer lint:fix` in order to check and fix your code before sending in a pull request
* Please make sure that the few tests that we have work by running `composer test`
* We do not have unit tests for everything, but we do appreciate them being written

We use a Github Actions based CI process to check if pull requests adhere to the enforced coding standards. Pull requests may be rejected, re-written or re-done from scratch if they do not adhere to the coding standards and modern industry best practices.

If you think your code warrants a modification to or exception from the PHPCodeSniffer rules, please let us know.

#### Views

The coding style for views and view partials, residing in the `views` directory varies a little bit from the rest of the codebase:

* Views use ["colon syntax"](https://www.php.net/manual/en/control-structures.alternative-syntax.php) for control structures
* No maximum line length is enforced for views to account for things such as long sentences within i18n functions, but please stay within sensible limits
* For block-level HTML elements, attributes and text nodes are to be indented and kept on separate lines in order to limit line lengths

## Contact Us

You can contact 1984 Hosting directly by email via 1984@1984.is. The communication lines are short, so you will reach the right person that way, even when it comes to security related issues.

The main author can be contacted via aldavigdis@aldavigdis.is. She is available for hire, some of her previous work can be found at and she can be supported via Github Sponsors at https://github.com/sponsors/aldavigdis.

## License

This plugin is provided to you as free software under the GPLv3 license. Runtime dependencies are provided under the MIT and Apache licenses, which are compatible with the GPLv3.

1984 Connector for DK and WooCommerce

Copyright (C) 2024 1984 Hosting and contributors

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see <https://www.gnu.org/licenses/>.

---

<p align="right">
<img src="https://raw.githubusercontent.com/1984hosting/1984-dk-woo/main/style/1984-logo-semitrans.svg" height="52" width="220" alt="1984 Hosting">
</p>
