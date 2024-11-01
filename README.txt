== System Admin ==
Contributors: mcgwier
Donate link: https://mcgwier.com
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: sysadmin,system admin,hide menu,admin,menu,disable updates,disable update,hide plugins
Requires at least: 3.1
Tested up to: 5.3
Stable tag: 1.0

This plugin creates a new role (sysadmin), extending the admin role by offering custom privileges that enable hiding options from other users, including administrators. Does not track, does not use third-party services, is always free.

== Description ==

Extends the admin role with a new, awesomer role called 'Sysadmin'. This new role gives you a menu of options to hide menu and/or sub-menu items in the admin from all other users (e.g. Themes, Theme Editor, Plugin Editor, etc.) Very useful when restricting major changes or updates from clients or administrators.

= Special Features =
* Disable core update
* Disable plugin update
* Disable theme update
* Disable admin bar

== Installation ==

Activate, promote yourself, set your options, high five.

1. Upload `system-admin` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Promote yourself to Sysadmin and visit System Admin, select your desired options, save changes.

== Frequently Asked Questions ==

= Is there a premium version? =

No. All that this plugin does is included in this version. Nothing hidden to be gained.

= Does this plugin add redirects? =

No. Simply hides the menu items. You can still access the direct URI (intentional fallback).

== Uninstall ==
Just deactivate and delete plugin. This will re-assign Sysadmin role as Administrator and remove all options (unhiding hidden menu items, etc.)

== Changelog ==

= 1.0 =
* Init to Winit
