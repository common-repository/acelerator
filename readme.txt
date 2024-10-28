=== Acelerator ===
Contributors: alecksmart
Tags: editor, ace, ace editor, js, javascript, css, php, better
Requires at least: 4.0
Tested up to: 4.9.6
Stable tag: tags/1.1
License: Revised BSD License

Add ace editor to any textarea in admin.

== Description ==

Add ace editor to any textarea in admin.

Works with other plugins. Initially tuned to support 'CUSTOM CSS-JS-PHP' plugin, but far from being limited to it only.

Uses jQuery selectors-like syntax for tuning.

= Selectors =

The script will look for occurences of selectors (separate with "|") and turn them into ace editors.

= Syntaxes =

The script will look for occurences of selectors (separate with "|") and will pick the first existing occurence to define the language syntax for ace editor instances on this page. I.e., _h4:contains('CSS Code')@css_ means it will set the ace editor's syntax to "css" if the page has _h4_ which contains text _"CSS Code"_.

= CSS =

Additional CSS for ace editor holder.

== Installation ==

Regular installation. See tuning options after installation _Settings -> Acelerator Options_.


== Screenshots ==

screenshot-1.png
	A textarea from another plugin enhanced with ace editor.

== Credits ==

https://github.com/ajaxorg/ace-builds

== Changelog ==

= 1.1 =

_Minor enhanvcements and bug fixes_

= 1.0 =

_Initial release_
