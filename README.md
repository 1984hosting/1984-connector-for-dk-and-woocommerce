# Introduction

Woocoo is a WordPress/WooCommerce plugin that can communicate with bookkeeping software known as [DK](https://dk.is/), and others in the future.

While our need is specifically for DK at the moment, we envision support all of the following in the future:

* [DK Hugbúnaður](https://dk.is/)
* [Regla](https://www.regla.is/)
* [Payday](https://payday.is/)
* [Virtus](https://virtus.is/)

## About this README.md

This README.md currently serves as a description of what's needed.

Note that this git repository will eventually become public, so any and all history will also be made public at some point.

## Existing Products

Several solutions exist already, but what they all have in common is to be sold as services. Ours will differ from them, by being open-source and freely available to our customers as indeed anyone else. None of the following companies seem to openly share the code to their products, so they are not necessarily useful for investigation, but are mentioned here for context.

* [Allra átta](https://www.8.is/netverslun/)
* [Netheimur](https://www.netheimur.is/lausnirnar/dkwoo/)
* [Smartmedia](https://smartmedia.is/bokhaldstenging-birgdartenging-woocommerce/)
* [TRS](https://www.trs.is/veflausnir/serforritun/)
* [MR Connect](https://www.mrconnect.is/)

The fact that several companies seem to have implemented this on their own indicates that there is no great challenge with doing this. WooCommerce is apparently designed to communicate with other systems, and DK does indeed have an API.

However, there are other products that cater to non-Icelandic bookkeeping software such as Quickbooks:

* [WP Swings](https://woocommerce.com/products/integration-with-quickbooks/)
* [MyWorks Software](https://myworks.software/integrations/woocommerce-quickbooks-sync/)

We have already tried installing the MyWorks Software solution, and while their website does not explain the code license, the downloaded plugin contains a file called `LICENSE.txt` which contains the [GNU General Public License, version 2](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html), which is the same license that we will be using, although possibly a later version of it such as [GPLv3](https://www.gnu.org/licenses/gpl-3.0.html) or [AGPL](https://www.gnu.org/licenses/agpl-3.0.en.html).

# Management

The project is managed by [Helgi](mailto:helgi@1984.is) on behalf of [1984 Hosting](https://1984.hosting/).

# Project

## Preparatory tasks

- [ ] Investigate current options to connect WooCommerce to inventory and bookkeeping systems.

## Functionality

- [ ] Inventory accounting connected to WooCommerce.
- [ ] Set inventory status when item is placed in cart.
- [ ] Mark product as purchased on payment.
- [ ] Create and send invoice.

### Product synchronization

Products are mostly managed in DK but may be changed in WooCommerce. Changes in WooCommerce are **not** sent back to DK.

For a product to be synchronized, it should be marked in such a way in DK. This option in Icelandic is called "Birta í vefverslun" and Google Translate translates it as "Publish in online store".

Note that there is some discrepancy between field names between systems, particularly with descriptive fields like "name" and "description".

Field synchronization by field:

- SKU (product ID): The common identity between both systems, used to identify what products to add and update.
- Name/Description: Transferred from DK to WooCommerce when product is added, but **not updated** after that, because users may want different descriptors in WooCommerce than in DK.
- Inventory/Price: Always updated from DK to WooCommerce.

### Pricing and taxes

* Only one price field is supported per product, at least in the first version of Woocoo. DK supports multiple prices, for discounts or special deals but only the main price is used by us for now.
* Prices in WooCommerce should be the price as it appears **with [VAT](https://en.wikipedia.org/wiki/Value-added_tax)** in DK. This means that we do not need to worry about taxes for now.

### Cart behavior

We need further information and discussion on how Woocoo should behave with regard to the cart. Specifically, we need to understand how WooCommerce handles the reserving of product items while they are in the cart but before the purchase if complete. This impacts how Woocoo will handle its communication with DK.

### Payment and invoicing

Woocoo uses the DK API to create and send an invoice in DK. This happens when the purchase of an item is complete, including the payment.

# Technical Notes

## DK API

The API is exposed at [`https://api.dkplus.is/api/v1`](https://api.dkplus.is/api/v1).

Documentation can be found at [`https://apidoc.dkplus.is/`](https://apidoc.dkplus.is/), including staging account information (a token) that can be used during development.

### API versions

The current difference between API versions 1 and 2 is only that in version 2, there are advanced options that don't exist in version 1.

We will not concern ourselves with version 2 until we run into a reason to.

### Swagger / OpenAPI

The API is provided in OpenAPI format. A client library may be automatically generated with the [Swagger Editor](https://editor.swagger.io) for most programming languages.

1. Open the [Swagger Editor](https://editor.swagger.io) in a browser.
2. Go to `File` -> `Import URL` and paste the URL [`https://api.dkplus.is/api/v1`](https://api.dkplus.is/api/v1).
3. Browse the API with the generated documentation and experimentation tools.

### WebHooks

Web hooks provide a way for DK to notify an external program of a change that occurs. This can be used instead of constantly pulling new information with a scheduled task runner.

In other words, we can both have WooCommerce speak with DK, and DK speak with WooCommerce.

This is described further in the API documentation available via Swagger.

We have no particular reason to implement web hooks except in cases where they make development easier. While there is nothing wrong with using them, we are currently assuming that the entire process is based on WooCommerce pulling from DK.

### Tokens and authentication

API access is provided by using an access token, typically generated by the DK user inside DK's web interface. However, in the API, there is also a service for generating such a token by providing a username and password. It is therefore possible to make the WooCommerce plugin ask only for a username and password, and generate a token by those means.

This is described further in the API documentation available via Swagger.

## Code commenting

1. The most important aim of commenting is to explain **why** things are done in the way they are. Explaining **what** the code does can be useful if it's complicated and/or messy, but not when the code is already simple enough to read. Explaining **why** is almost always useful, however.

2. When a code segment is best understood in the context of a code segment in a different location, it is helpful to point out the other location in a comment.

3. Comments do not need to explain the programming language or its features. The reader is assumed to know the programming language used and have access to its documentation.

4. In large segments of code, comments are helpful to periodically break up functionality. Examples: `// At this point, the user is logged in.` and `// We are now done with the fancy calculations.`.

## Running process

A running process (crontab) may be necessary for cleaning stale data, such as items still in a cart of a user that has left the website without completing the purchase. If built-in provisions in WordPress can achieve the same effect, that would be preferable.

We need how this is typically implemented in WordPress plugins.
