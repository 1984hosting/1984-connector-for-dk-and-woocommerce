# Introduction

We need a WordPress/WooCommerce plugin that can communicate with bookkeeping software known as [DK](https://dk.is/), and others in the future.

While our need is specifically for DK at the moment, we envision support all of the following in the future:

* [DK Hugbúnaður](https://dk.is/)
* [Regla](https://www.regla.is/)
* [Payday](https://payday.is/)
* [Virtus](https://virtus.is/)

## About this README.md

This README.md currently serves as a description of what's needed. It will be replaced with a proper README.md once the project is underway.

Until it is replaced, it should contain all information relevant to developers and project managers.

Note that this git repository will eventually become public, so any and all history will also be made public at some point.

## Existing Products

Several solutions exist already, but what they all have in common is to be sold as services. Ours will differ from them by being open-source and freely available to our customers as indeed anyone else. None of the following companies seem to openly share the code to their products, so they are not necessarily useful for investigation, but are mentioned here for context.

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
- [ ] Mark product as purchesed on payment.
- [ ] Create and send invoice.

### Product synchronization

Products are synchronized between Woocommerce and DK by product ID (SKU). Information about the product is entered into the site, but the quantity and whether an item is in stock, is determined on DK's side. (See [issue #1](https://github.com/1984hosting/woocoo/issues/1).)

### Pricing

* Only one price field is supported per product, at least in the first version. Back-end systems such as DK may support multiple prices, for discounts or special deals but only the main price is used.

### Cart behavior

* Product quantity can be cached for a configurable amount of minutes, to reduce hits to back-end system (DK). The default cache is 0 minutes, which means it's disabled. Product quantity is still always checked when a) a user views a product and b) when the user adds that item to the cart. In listings from then on, cache is used.

* Products are reserved when they are placed in a cart. After a configurable amount of minutes (default 20), the cart is emptied and the item returned into stock.

### Payment and invoicing

* Invoices are sent once the user has completed the purchase, including payment.

# Technical Notes

## DK API

The API is exposed at [`https://api.dkplus.is/api/v1`](https://api.dkplus.is/api/v1).

Documentation can be found at [`https://apidoc.dkplus.is/`](https://apidoc.dkplus.is/), including staging account information (a token) that can be used during development.

### API versions

The current difference between API versions 1 and 2 is only that in version 2, there are advanced options that doesn't exist in version 1.

We will not concern ourselves with version 2 until we run into a reason to.

### Swagger / OpenAPI

The API is provided in OpenAPI format. A client library may be automatically generated with the [Swagger Editor](https://editor.swagger.io) for most programming languages.

1. Open the [Swagger Editor](https://editor.swagger.io) in a browser.
2. Go to `File` -> `Import URL` and paste the URL [`https://api.dkplus.is/api/v1`](https://api.dkplus.is/api/v1).
3. Browse the API with the generated documentation and experimentation tools.

### WebHooks

WebHooks provide a way for DK to notify an external program of a change that occurs. This can be used instead of constantly pulling new information with a scheduled task runner.

In other words, we can both have WooCommerce speak with DK, and DK speak with WooCommerce.

This is described further in the API documentation available via Swagger.

### Tokens and authentication

API access is provided by using an access token, typically generated by the DK user inside DK's web interface. However, in the API, there is also a service for generating such a token by providing a username and password. It is therefore possible to make the WooCommerce plugin ask only for a username and password, and generate a token by those means.

This is described further in the API documentation available via Swagger.

## Code commenting

1. The most important aim of commenting is to explain **why** things are done in the way they are. Explaining **what** the code does can be useful if it's complicated and/or messy, but not when the code is already simple enough to read. Explaining **why** is almost always useful, however.

2. When a code segment is best understood in the context of a code segment in a different location, it is helpful to point out the other location in a comment.

3. Comments do not need to explain the programming language or its features. The reader is assumed to know the programming language used and have access to its documentation.

4. In large segments of code, comments are helpful to periodically break up functionality. Examples: `// At this point, the user is logged in.` and `// We are now done with the fancy calculations.`.

## Running process

A running process (crontab) may be necessary for cleaning stale data, such as items still in a cart of a user that has left the website without completing the purchase.
