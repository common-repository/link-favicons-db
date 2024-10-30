=== Plugin Name ===
Contributors: yun77op
Tags: links, icons, favicons, bookmarks, blogroll, google
Tested up to: 2.9.1

== Description ==
Fill empty link_image and link_description field in table wp_links where link_image is retrieved via Google S2 Converter and link_description is the same as link_name
 
== Installation ==
1. Upload the folder `links-favicons-db` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The plugin will add a new "link favicons db" section under the Options menu
4. Modify the parameters of the template function `wp_list_bookmarks` and use `wp_list_bookmarks('show_name=1');` If you want to display the text of a link.

== Screenshots ==
1. This is a sample screenshot taken from the default wordpress theme

== Changelog ==
= 1.0     2010-03-20 =
* Initial release.
