# CIF Design Documentation

Documentation and examples of design modules for the University of Rochester's Computer Interest Floor web properties. These files include the stylesheets, examples of the styles in action, documentation for using the styles, and documentation for mainitaining the styles and docs.

## Note For Maintainers

Please add the following to the `[remote "origin"]` section of your local repository's `.git/config` file to keep the master and gh-pages branches in sync when using `git push`.

	push = +refs/heads/master:refs/heads/gh-pages
	push = +refs/heads/master:refs/heads/master

### Icon Font

The CIF icon font was created using the [IcoMoon](http://icomoon.io) web app. The `CIF-Icons font.zip` file contains the generated files and documentation provided by IcoMoon.

### Favicon

The CIF favicon was created from three svg and png files which tailor the CIF logo to 16, 32, and 64 pixel sizes. Those files are in a separate image assets repository. The [x-icon editor](http://www.xiconeditor.com) website was used to create a single ico file which contains all sizes of the logo for high resolution displays and various display contexts (browser UI, Windows task bar, etc).
