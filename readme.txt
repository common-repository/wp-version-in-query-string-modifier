=== WP Version in Query String Modifier ===
Contributors: joesat
Tags: query string, remove query string, remove version, version, optimization, url, link
Requires at least: 3.9.3
Tested up to: 4.5.3
Stable tag: 1.0.0.7
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Removes or modifies the version (query string 'ver' parameter) in media resources' url. 

== Description ==
Depending on your needs, this plugin is useful for:

* updating the version value to an incrementing number (with a single click) for cache-busting purposes (this is the default value)
* removing the version parameter for optimization purposes
* option to disable modifying the url without disabling the plugin

The author have dealt with issues when the server is running ATS, Varnish or similar cache utility that the author has no control over. Sometimes cache files stick around for a while and users have no control over caching applications. If you want your css, javascript, image or other asset updates to be reflected instantly, this is the perfect solution for you.

The plugin has been tested extensively with WP_DEBUG enabled and works seamlessly with multi-site network setup.

For questions, suggestions or issues with this plugin, visit the plugin support page or contact me at wp [dot] joesat [at] gmail [dot ]com.

== Installation ==

1. Upload 'wp-version-in-query-string-modifier' folder to '/wp-content/plugins/' directory

2. Activate the plugin through 'Plugins' menu in WordPress Admin

3. You're good to go! Verify static resources' URI via your web page's sourc code

4. (optional) Select your preference via the Settings page (/wp-admin/options-general.php?page=wpvqsm_)

== Frequently Asked Questions ==

* Does this plugin work with multi-site setup?
Yes.

* Was did plugin tested with debug option enabled?
Yes.

* Where is the admin page located?
In the admin panel, go to Settings -> WP Version Modifier.

* Why is the default value for cache buster option set to 007?
007 is an iconic and significant number - James Bond's callsign.

== Upgrade Notice ==

== Screenshots ==

1. WP Version Modifier Settings Page
2. WP Version Modifier Location in the sidebar 

== Changelog ==

= 1.0.0.7 =
* can now generate unique string via time() value appended in the ver query string. Please note that enabling this option may have performance setbacks. Use accordingly.

= 1.0.0.6 =
* now really working for multisite install :)

= 1.0.0.5 =
* yet another update to readme.txt

= 1.0.0.4 =
* updated readme.txt
* removed unused contant WPVQSM_LONG_NAME


= 1.0.0.3 =
* updated plugin screenshots - again (this is my first plugin and am still learning)

= 1.0.0.2 =
* updated plugin screenshots


= 1.0.0.1 =
* updated readme to include more info and corrected typos

= 1.0.0.0 =
* First release