## Shortcodes
Shortcodes are useful tags that can be used within the WordPress editor to provide new functionality.

All of our shortcodes should be defined in this directory. Simply create a file for your shortcode's callback function (say, `my-shortcode.php`), write the appropriate code (the [WordPress docs](http://codex.wordpress.org/Shortcode_API) should get you started), and then edit `registered-shortcodes.php` to import your function code and add it in the `cif_register_shortcodes` function.

Be sure to document your shortcode throughly so developers and editors understand how it works and how to use it!
