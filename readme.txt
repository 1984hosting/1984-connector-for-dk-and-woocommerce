=== 1984 Connector for DK and WooCommerce ===
Stable tag: 0.3.0
Contributors: @1984cto, @aldavigdis, @drupalviking
Tags: DK, dkPlus, Accounting, Inventory, Invoicing
Requires at least: 6.2.4
Tested up to: 6.5
Requires PHP: 8.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Sync your WooCommerce store with DK, including prices, inventory status and generate invoices for customers on checkout.

== About ==

Synchronise products, prices and inventory status between your WooCommerce store and your DK account. Have DK generate invoices automatically on checkout without worrying about setitng up an email connection for your WordPress site.

== Installation ==

In order to get started, you need to set up an account with DK's dkPlus service, enter your API key into the plugin and correlate your WooCommerce Payment Gateways with the Payment Methods in your DK account.

You will also need to finish setting up your WooCommerce shop, including tax rates, wether prices are VAT-inclusive etc.

Once a connection has been established, the plugin will work right away and will register products and other records in DK on creation in WooCommerce, as long as the correct inventory codes are set and a correct SKU is set for each item.

== Screenshots ==

1. The admin interface for the plugin is located under the WooCommerce section in the sidebar and generally stays out of sight otherwise.
2. You can set and synchronise prices, sale prices and sale dates between DK and WooCommerce with the plugin, directly from the WooCommerce product editor.
3. The plugin also supports reading stock status from DK and displaying it in your WooCommerce shop.

== Frequently Asked Questions ==

= Why can I not set a stock quantity in DK for a product? =

The answer is that DK does not support setting a stock quantity manually from 3rd party software.

We urge anyone who wants initial stock quantity to work as expected, without resorting to going through an inventory count process to ask DK about implementing it in their API service, which is what this plugin uses.

= Does the plugin support the new WooCommerce Product form? =

As the WooCommerce product form is still under development and does not offer the possibility to add custom form fields to specify if you do not want to sync prices, number of items stock etc. for certain Products, that sort of granularity is not supported.

However, if you are going to synchronise all your products' names, prices and availability anyway, then it will work as long as you enter a SKU that corresponds with the product's Item Code in DK.
