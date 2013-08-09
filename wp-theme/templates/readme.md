## Templates
These files are used as templates throughout WordPress for displaying various content. WordPress itself knows nothing about how to use these templates, there are merely loaded with `get_template_part()` in our theme.

Templates are loaded based on specificity; our theme attempts to load a template for the current post type. If no template for that post type exists, then a generic template is tried, or nothing happens if no generic template exists.

For example, assume we are displaying posts for a "custom" post type. We are attempting to load a loop template, so our template loader looks something like this:

	$post_type = "custom";
	get_template_part( 'templates/loop', $post_type );

WordPress will then search for `templates/loop-custom.php` and use that if it exists. If it doesn't exist, it will try `templates/loop.php`, and if *that* doesn't exist nothing is loaded.

##### Why not place each template type (content, content-header, loop, etc.) in its own subdirectory in the templates directory?
Because WordPress seems to require a prefix on the filename of template files, it made little sense to move them into subdirectories if they were already prefixed with their template type. It would only make the template string in `get_template_part` calls even longer. It's nicer to write `get_template_part( 'templates/content-header' )` than `get_template_part( 'templates/content-headers/content-header' )`.

### Loop Templates
Loop template filenames must begin with `loop-`. Loop templates are used to loop through and display each post in the current query.

### Page Templates
Page template filenames must begin with `page-`. Page templates are a little special in that they're not usually called with `get_template_part`. Instead, a comment in the form `// Template Name: My Custom Page` should be placed somewhere at the top of this file. Then, when editing a page from within WordPress, you can select "My Custom Page" as the template to use for that page.

### Content Templates
Content template filenames must begin with `content-`. These templates are used when content exists, such as for custom archive pages.

### No Content Templates
No content template filenames must begin with `no-content-`. These templates are used when no content exists, such as an empty custom archives page.

### Content Header Templates
Content header template filenames must begin with `content-header-`. These templates are displayed before the main content template, and are useful for displaying headers or opening tags for container elements.

### Content Footer Templates
Content footer template filenames must begin with `content-footer-`. These templates are displayed after the main content template, and are useful for footers and closing any tags left open by a content header.
