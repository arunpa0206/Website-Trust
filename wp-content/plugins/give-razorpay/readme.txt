=== Give - Razorpay Gateway ===
Contributors: givewp
Tags: donations, donation, ecommerce, e-commerce, fundraising, fundraiser, razorpay, gateway
Requires at least: 4.8
Tested up to: 5.6
Stable tag: 1.4.5
Requires Give: 2.6.0
License: GPLv3
License URI: https://opensource.org/licenses/GPL-3.0

Razorpay Gateway Add-on for Give.

== Description ==

This plugin requires the Give plugin activated to function properly. When activated, it adds a payment gateway for razorpay.com.

== Installation ==

= Minimum Requirements =

* WordPress 4.8 or greater
* PHP version 5.3 or greater
* MySQL version 5.0 or greater
* Some payment gateways require fsockopen support (for IPN access)

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install of Give, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "Give" and click Search Plugins. Once you have found the plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading our donation plugin and uploading it to your server via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Changelog ==

= 1.4.5: January 20th, 2020 =
* Fix: Indefinite subscriptions work again and are capped to RazorPay's limit of 100 years

= 1.4.4: June 16th, 2020 =
* Fix: Added a check to verify GiveWP Core is installed and activated in order for Razorpay to work to prevent errors if it becomes inactive.

= 1.4.3: June 5th, 2020 =
* New: Added compatibility with the upcoming release of GiveWP 2.7.0 and the new form template layout.
* Fix: Resolved an issue with Recurring Donation's "Admin Defined" setting not properly working with Razorpay.

= 1.4.2: April 16th, 2020 =
* Fix: There was an issue with compatibility with the Fee Recovery add-on not properly adding the fee to the Razorpay checkout.

= 1.4.1: February 27th, 2020 =
* Fix: Razorpay was incorrectly recording webhooks from other gateways. Now it will only log Razorpay webhooks.
* Fix: Resolved an issue with compatibility with "Donor's Choice" recurring donation period selection which would record an incorrect frequency for certain configurations.
* Fix: Resolved an issue with session usage and recurring donations. Now if a modal window is closed accidentally the session will remain..
* Tweak: Hardened security for the AJAX handler within Razorpay.

= 1.4.0: February 20th, 2020 =
* New: Added the ability for admins to set specific Razorpay accounts per donation form.

= 1.3.1: December 23rd, 2019 =
* Fix: Resolved an issue with large amounts being formatted incorrectly and passing a wrong amount to the Razorpay popup to complete the donation.

= 1.3.0: November 15th, 2019 =
* New: Added support for Recurring Donations. Update the Recurring Donations add-on to 1.9.6+ to enable this functionality.

= 1.2.1: December 13th, 2018 =
* Fix: Ensure that the Razorpay modal display above the Give modal when using that display mode.

= 1.2.0: July 30th, 2018 =
* Important: You must be running Give Core 2.2.0+ in order to use this add-on update. Please update Give Core to the latest version prior to completing this update.
* Tweak: Changed how Razorpay uses Give Sessions to use the new 2.2.0+ database sessions.

= 1.1.4: June 5th, 2018 =
* Tweak: Removed the legacy payment method label setting since it's now supported by Give Core natively.
* Tweak: Replaced .data() with .attr() to resolve conflict with add-ons like Fee Recovery and Currency Switcher.

= 1.1.3: March 1st, 2018 =
* Fix: Resolved issue with decimal amounts being rounded off incorrectly. This resolves a conflict with Fee Recovery as well.

= 1.1.2: February 28th, 2018 =
* Fix: Added compatiblity with Give's newly release Currency Switcher add-on.
* Tweak: Bumped up Razorpay's minimum Give version to 2.0+. Please update before upgrading to this version.

= 1.1.1: November 1, 2017 =
* New: Improved the output if an error occurs for failed payments and other issues so the donor knows what happened and the admin can view logs for additional details.
* Fix: Resolved issue with excessive session checks causing payments to not process as expected.

= 1.1.0 =
* New: The plugin now uses the Razorpay order API to process donations.

= 1.0.1 =
* New: Added an uninstall.php file that removes settings if admin chooses when removing the plugin.
* Fix: Renamed function that was incorrectly named.

= 1.0 =
* Initial plugin release. Yippee!
