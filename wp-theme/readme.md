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
