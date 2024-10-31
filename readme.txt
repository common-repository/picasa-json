=== Picasa Web Albums sidebar widget ===
Contributors: sparkos
Donate link: http://www.sparkos.com/downloads/wordpress/picasawebalbumssidebarwidget/
Tags: picasa, widget, json, gallery, album, photo, video
Requires at least: 2.0.2
Tested up to: 2.7.1
Stable tag: trunk

Sidebar widget which displays photos/videos as a list or thumbnails from public & unlisted Picasa Web Albums.

== Description ==

Sidebar widget which displays photos/videos as a list or thumbnails from both public & unlisted [Picasa Web Albums](http://picasaweb.google.com/) using [JSON](http://en.wikipedia.org/wiki/Json) rather than RSS.

Works with [Shadowbox JS](http://wordpress.org/extend/plugins/shadowbox-js/) and [Lightbox 2](http://wordpress.org/extend/plugins/lightbox-2/) plugins to display full-size versions of photos & videos. Shadowbox JS is preferred as it supports video playback.

== Installation ==

1. Upload `picasa-json` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add one or more copies of the widget to sidebar
4. Enter a `title` for the widget
5. Paste the RSS feed for the Picasa Web Album you which to use
6. Configure any of the other options as required
7. Save

== Frequently Asked Questions ==

= Where do I find the RSS URL? =

When you view an album in Picasa there is a link on the right hand side for RSS feed. If you paste this into the `RSS URL` field the username, albumid and authKey fields will be populated automatically.

== Screenshots ==

1. Example showing thumbnail and list views using [Fusion theme](http://wordpress.org/extend/themes/fusion)
2. Widget administration screen

== Version History ==

#0.1
* Initial release
#0.2
* Archive and article dates are now used to filter the photos/videos to provide a more logical correlation.