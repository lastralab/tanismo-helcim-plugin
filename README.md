# Tanismo Helcim Payment

- Contributors: tanismo
- Requires at least: 6.3
- Tested up to: 6.4
- Requires PHP: 7.4
- Stable tag: 1.0.0
- License: GPLv2 or later
- License URI: [GPLv2](https://www.gnu.org/licenses/gpl-2.0.html)
- Tags: helcim payment, 3% convenience fee, credit card payment, helcim payment page

## Description

This plugin integrates the Helcim Payment Gateway with WooCommerce, allowing customers to be redirected to the Helcim Payment Terminal for secure payment processing. This plugin will allow you to apply the 3% convenience fee on payments with credit card (currently not supported by the [Helcim official plugin for WooCommerce](https://wordpress.org/plugins/helcim-commerce-for-woocommerce/)).

## Installation

1. Upload the `tanismo-helcim` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to WooCommerce > Settings > Payments to configure the Helcim Payment Gateway settings.

## Configuration

1. Enable the "Custom Helcim Payment Gateway" in WooCommerce settings.
2. Add the landing pages in the Helcim Payment Page configuration: {yourURL}/wp-json/helcim/v1/approved and {yourURL}/wp-json/helcim/v1/cancelled so the users can be forwarded back to WordPress.
3. Set the "Terminal URL" provided by Helcim when creating a payment page.
4. Enter the "Test Token" and "Token" for the Helcim Payment Page.

## Features

- Customize the payment gateway title and description.
- Add instructions for customers.
- Redirect customers to the Helcim Payment Terminal.
- Apply a 3% convenience fee to orders on Helcim Payment Page.

## Compatibility

- Requires at least: 5.0
- Tested up to: 5.9.3
- WooCommerce requires at least: 5.0
- WooCommerce tested up to: 6.1.0

## Frequently Asked Questions

### Does this plugin support refunds?

Yes, you can process refunds for orders paid with the Helcim Payment Gateway through the Helcim Platform.

## Changelog

### 1.0.0
- Initial release.

## Screenshots ##
Checkout
![image](https://github.com/lastralab/tanismo-helcim-plugin/assets/22894897/3a3c5fd4-f6b2-417c-93d9-f99de5d8189a)


Helcim Payment Page
![image](https://github.com/lastralab/tanismo-helcim-plugin/assets/22894897/99ca9f74-cc83-4a70-ad22-df162eb871ff)
![image](https://github.com/lastralab/tanismo-helcim-plugin/assets/22894897/e0b121bf-b39f-4586-b016-e1833b625c53)

Admin settings
![image](https://github.com/lastralab/tanismo-helcim-plugin/assets/22894897/daa8eabc-9d92-4a9f-abca-79d73b4da994)
![image](https://github.com/lastralab/tanismo-helcim-plugin/assets/22894897/30376194-27bb-40b3-b388-cafd440b129d)

