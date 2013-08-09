## Cloning just this theme directory
The best way to clone this repo for working locally is by initializing a new repo in the `wp-content/themes/` directory of your local WordPress installation. You can then do a sparse checkout to clone only this directory.

	# Create the repo
	$ cd /path/to/local/wordpress/wp-content/themes/
	$ git init
	
	# Add this repo as the origin remote
	$ git remote add -f origin https://github.com/CIF-Rochester/CIFWebsite.git
	
	# Configure sparse checkouts to only clone the wp-theme directory and files
	$ git config core.sparseCheckout true
	$ echo wp-theme/* >> .git/info/sparse-checkout
	
	# Pull the theme files
	$ git pull origin master

Be careful not to commit `wp-content/themes/index.php` or any other themes to the repo! If you do, it'll show up in the root of our repo and be a nuisance.

## Theme Architecture
Here's a basic rundown of what does what and where everything is. Each file *should* have a header at the top explaining its purpose and any tips to keep in mind.

### index.php
Generic fallback template, also used for displaying posts. This file loads the appropriate template for the content being handled, and is used for almost every request.

### page.php
Displays pages. Since we rely so heavily on the Advanced Custom Fields plugin's fluid layout extension, this file loads the appropriate page layout from the layouts directory.

### header.php
The site header file. This should be used at the top of every page on the site. Failure to use it might result in broken functionality.

### footer.php
The site footer file. This should be used at the bottom of every page on the site. Failure to use it might result in broken functionality.

### style.css
Used by WordPress to determine theme metadata. Our actual styles are served up from our CDN.

### functions.php
This is where custom functions and code for modifying WordPress should go, or at least be included from.

### 404.php
404 page template.

### constants.php
Not actually a WordPress file. This is where we define important constants for the theme, such as where our CDN files are located.

### templates/
Not actually a WordPress directory. This is where our templates for displaying various content go. See the directory for more information.

### layouts/
Not actually a WordPress directory. This is where our page layouts for use with the Advanced Custom Fields plugin's fluid layout extension go. See the directory for more information.

### shortcodes/
Not actually a WordPress directory. This is where our shortcode functions are collected and registered. It just makes finding them easier.

### forms/
Not actually a WordPress directory. Nate Hart wrote a little framework for handling forms, so this directory is where all forms should be written. Read up on how things should be organized and what needs to be done for the framework to handle any new forms.

### options-pages/
Not actually a WordPress directory. This is where custom options pages that will show up under the Settings menu in WordPress should be placed.

### semesterly-post-types/
Not actually a WordPress directory. This is where custom post types which are organized by semester are located. See the documentation for this directory to understand how to add more semesterly post types.
