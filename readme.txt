=== 1984 Connector for DK and WooCommerce ===
Stable tag: 0.4.2
Contributors: @1984cto, @aldavigdis
Tags: DK, dkPlus, Accounting, Inventory, Invoicing
Requires at least: 6.2.4
Tested up to: 6.6
Requires PHP: 8.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Sync your WooCommerce store with DK, including product prices, inventory status and generate invoices for customers on checkout.

== About ==

Synchronise products, prices and inventory status between your WooCommerce store and your DK account. Have DK generate invoices automatically on checkout without worrying about setitng up an email connection for your WordPress site.

Variant products, sale prices and stock quantity can be set to sync globally and on a per-product basis.

== Installation ==

You will need to finish setting up your WooCommerce shop, including tax rates, payment methods, whether prices are VAT-inclusive etc in accordance with how things are set up in your DK installation before you install, activate and configure the plugin.

In order to get started, you need to set up an account with DK's dkPlus web service. Enter your API key in the form provided for a user with sufficient privileges under WooCommerce -> Connector for DK, correlate your WooCommerce Payment Gateways with the Payment Methods in your DK account and make sure that other settings are in accordance with how they are set up in DK.

Once a connection has been established, the plugin will work right away and will register products and other records in DK on creation in WooCommerce, as long as the correct inventory codes are set and a correct SKU is set for each item.

== Screenshots ==

1. The admin interface for the plugin is located under the WooCommerce section in the sidebar and generally stays out of sight otherwise.
2. You can set and synchronise prices, sale prices and sale dates between DK and WooCommerce with the plugin, directly from the WooCommerce product editor.
3. The plugin also supports reading stock status from DK and displaying it in your WooCommerce shop.

== Frequently Asked Questions ==

= Is data synchronisation fully bi-directional? =

Product infromation is generally synced bidirectionally. Some functionality, such as variant products and stock quantity only works downstream (from DK to WooCommerce), while invoicing works upstream (from WooCommerce to DK), with some information being retained in WooCommerce.

= Can my DK product records be affected by the plugin? =

In short, yes. As long as price and name sync are enabled and the API key is assigned to a user with sufficient privileges, price and name changes in WooCommerce are reflected in DK. This can be disabled by disabling those sync options.

= Do I need to set up email delivery for invoices? =

The plugin does not depend on WordPress or your web server being able to send emails. As we are leveraging DK's own email functionality, you need to enter the correct settings into DK and set the appropriate DNS settings such as your domain's SPF record in order for invoice delivery to work.

= Does the plugin support the new WooCommerce Product form? =

As the WooCommerce product form is still under development and does not offer the possibility to add custom form fields to specify if you do not want to sync prices, number of items stock etc. for certain Products, that sort of granularity is not supported.

However, if you do not need that granularity anyway and can make do with global settings, then it will work as long as you enter a SKU that corresponds with the product's Item Code in DK.

== Disclaimer ==

This plugin's functionality depends on connecting to the dkPlus API, provided by DK Hugbúnaður ehf (DK). DK provides its services as per their own terms, conditions and policies. This plugin is developed, maintained and supported on goodwill basis by 1984 Hosting as free software without any guarantees or obligations and is not affiliated with or supported by DK hugbúnaður ehf.
