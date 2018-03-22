# CIF Website
This repository contains the code for our main public-facing WordPress site, content delivery network,  user and lab access management tool Panel, and media library of CIF assets.

### CIF WordPress Theme
Our main website uses a custom-made WordPress theme to fit the site to our style. The code for that is contained within `main/wp-content/themes/cif/`.

### Panel
Panel allows our users to manage their account information and gives them access to free web hosting, file storage, and other services. Panel is custom-made and uses [Twig](http://twig.sensiolabs.org) for templating. It can be found in `panel/`.

### Media
Media allows our members to upload photos and image assets so that they can be downloaded and used later without having to find out who has the logo SVG ever again. It is custom-made and uses [Twig](http://twig.sensiolabs.org) for templating. It can be found in `cif-media/`.

### CDN
The CDN is a centralized place for our stylesheets, JavaScript, and other related assets. Our stylesheet is written in [Sass](http://sass-lang.com) to make it more manageable than a CSS file. The CDN is intended to be as optimized as possible to serve out our assets quickly. Its files are located in `cdn/`.
