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

## Required functionality

- [ ] Inventory accounting connected to WooCommerce.
- [ ] Set inventory status when item is placed in cart.
- [ ] Mark product as purchesed on payment.
- [ ] Create and send invoice.

## Technical Notes

### Running process

A running process (crontab) may be necessary for cleaning stale data, such as items still in a cart of a user that has left the website without completing the purchase.

### "Product"
A product is currently believed to be consist of the following properties.

* Product number
* Name
* Description
* Price
* Inventory status
