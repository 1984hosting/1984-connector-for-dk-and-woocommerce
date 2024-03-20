=== 1984 DK Connection for WooCommerce ===
Stable tag: trunk
Contributors: @1984cto, @aldavigdis, @drupalviking
Tags: DK, dkPlus, Iceland, WooCommerce, Accounting, Inventory, Invoicing
Requires at least: 6.1.5
Tested up to: 6.4.3
Requires PHP: 8.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Sync your WooCommerce store with DK, including prices, inventory status and generate invoices for customers on checkout.

Synchronise products, prices and inventory status between your WooCommerce store and your DK account. Have DK generate invoices automatically on checkout without worrying about setitng up an email connection for your WordPress site.

Made by the largest WordPress hosting company in Iceland, this in a plugin that does its best to stay out of the way and can be used in a “set-it-and-forget-it” way.

== Installation ==

In order to get started, you need to set up an account with DK's dkPlus service, enter your API key into the plugin and correlate your WooCommerce Payment Gateways with the Payment Methods in your DK account.

Once a connection has been established, the plugin will work right away and will register products and other records in DK on creation in WooCommerce.

== WP-CLI and Pulling legacy data from DK ==

If you are not starting a new store from scratch and need to have legacy data in your WooCommerce store; or if you are building a new version of your site, you are going to want to pull the information about things such as categories and products from DK and into your WooCommerce store.

As of now, this is only supported in the plugin's WP-CLI interface and will end up in the user interface when that process as been refined.

== Frequently Asked Questions ==

= What kind of products are supported by the plugin? =

During the current phase, we only officially support “Simple” products. However, it doesn't mean that other product types can't be used with the plugin, but you should expect quirks and caveats. If you use the plugin with other product types and would like to share your experience, you're welcome to contact us to share your experiences.

= Does the plugin support the new WooCommerce Product form? =

As the WooCommerce product form is still under development and does not offer the possibility to add custom form fields to specify if you do not want to sync prices, number of items stock etc. for certain Products, that sort of granularity is not supported.

However, if you are going to synchronise all your products' names, prices and availability anyway, then it will work as long as you enter a SKU that corresponds with the product's Item Code in DK.
