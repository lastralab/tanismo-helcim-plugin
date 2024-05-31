=== Tanismo Helcim Payment ===
Contributors: tanismo
Requires at least: 6.3
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tags: helcim payment, 3% convenience fee, credit card payment, helcim payment page

Short Description
Redirects customers to your Helcim Payment Page. It allows you to apply the 3% convenience fee on payments with credit cards.

== Installation ==
1. Upload the `tanismo-helcim` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to WooCommerce > Settings > Payments to configure the Helcim Payment Gateway settings.

== Configuration ==
1. Enable the "Custom Helcim Payment Gateway" in WooCommerce settings.
2. Add the landing pages in the Helcim Payment Page configuration: `{yourURL}/wp-json/helcim/v1/approved` and `{yourURL}/wp-json/helcim/v1/cancelled` so the users can be forwarded back to WordPress.
3. Set the "Terminal URL" provided by Helcim when creating a payment page.
4. Enter the "Test Token" and "Token" for the Helcim Payment Page.

== Features ==
* Customize the payment gateway title and description.
* Add instructions for customers.
* Redirect customers to the Helcim Payment Terminal.
* Apply a 3% convenience fee to orders on the Helcim Payment Page.

== Compatibility ==
* Requires at least: 5.0
* Tested up to: 5.9.3
* WooCommerce requires at least: 5.0
* WooCommerce tested up to: 6.1.0

== Changelog ==
= 1.0.0 =
* Initial release.

== Upgrade Notice ==
If you upgrade the WooCommerce plugin, make sure the file tanismo-helcim/woocommerce/checkout/review-order.php will be compatible and only persist this plugin modifications

== Frequently Asked Questions ==
= Does this plugin support refunds? =
Yes, you can process refunds for orders paid with the Helcim Payment Gateway through the Helcim Platform.

== Screenshots ==
1. Backend Settings
assets/backend-settings-tanismo-helcim.png
assets/backend-tanismo-helcim.png
2.  Checkout
assets/checkout-tanismo-helcim.png
assets/redirect-tanismo-helcim.png
