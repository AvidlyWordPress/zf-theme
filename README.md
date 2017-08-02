# ZF Theme

Hi. I'm a starter theme called <em>ZF Theme</em>, which stands for either <a href="http://zeelandfamily.fi">Zeeland Family</a> or <a href="https://foundation.zurb.com">Zurb Foundation</a>, whichever you prefer. I'm a theme meant for hacking so don't use me as a Parent Theme. Instead try turning me into the next, most awesome, WordPress theme out there. That's what I'm here for.

My structure is 95% the same as _s by Automattic, but I integrate the <a href="http://foundation.zurb.com/sites/docs/starter-projects.html">Foundation Zurb Template</a>.

Learn more about the Foundation framework by reading the <a href="http://foundation.zurb.com/sites/docs/">Foundation docs</a>.

* A just right amount of lean, well-commented, modern, HTML5 templates.
* A helpful 404 template.
* A custom header implementation in `functions/custom-header.php` just add the code snippet found in the comments of `functions/custom-header.php` to your `header.php` template.
* Custom template tags in `functions/template-tags.php` that keep your templates clean and neat and prevent code duplication.
* Some small tweaks in `functions/extras.php` that can improve your theming experience.
* The Foundation Framework (version 6.4) using the Foundation Zurb Template in the `_src` directory. 
* Licensed under GPLv2 or later. :) Use it to make something cool.

## Getting Started

To personalise your theme, download `zf-theme` from GitHub. The first thing you want to do is copy the `zf-theme` directory and change the name to something else (like, say, `megatherium`), and then you'll need to do a five-step find and replace on the name in all the templates.

1. Search for `'zf-theme'` (inside single quotations) to capture the text domain.
2. Search for `zf_theme_` to capture all the function names.
3. Search for `Text Domain: zf-theme` in style.css.
4. Search for <code>&nbsp;ZF_Theme</code> (with a space before it) to capture DocBlocks.
5. Search for `zf-theme-` to capture prefixed handles.

OR

* Search for: `'zf-theme'` and replace with: `'megatherium'`
* Search for: `zf_theme_` and replace with: `megatherium_`
* Search for: `Text Domain: zf-theme` and replace with: `Text Domain: megatherium` in style.css.
* Search for: <code>&nbsp; ZF_Theme</code> (with a space before it) and replace with: <code>&nbsp;Megatherium</code>
* Search for: `zf-theme-` and replace with: `megatherium-`

Then, update the stylesheet header in `style.css` and the links in `footer.php` with your own information. Next, update or delete this readme.

There are two routes you can take for development, depending on whether you like Sass, Gulp and friends:

## Development using CSS (without Sass or Gulp)

If you're not into Sass or Gulp, just delete the _src directory and remove the comment from the beginning of this line in `functions/scripts-and-styles.php`:

		// wp_enqueue_style( 'zf-theme-custom-style', get_stylesheet_uri() );

You can then add your own custom styles into `style.css`.

It is highly recommended that you create a custom version of the Foundation JS & CSS using the <a href="http://foundation.zurb.com/sites/docs/style-sherpa.html">online configurator</a> so you have only what you need and nothing else. This will save users' bandwidth, time, batteries, money, headaches and reduce CO2 emissions. If you do this however, you need to do a couple of things after extracting the custom download:

* Move `css/foundation.min.css` from the custom package into `dist/assets/css/` and rename it to `app.css`.
* Move `js/vendor/foundation.min.js` from the custom package into `dist/assets/js/` and rename it to `app.js`.

If you have any custom JS, I suggest creating a new file in `dist/assets/js` and enqueuing it the usual way in `functions/scripts-and-styles.php`.

## Development using Sass, Gulp and Panini templates

Requirements: Node, NPM and Bower. And a local WordPress development server (MAMP, XAMPP, Pressmatic, something else, it's your choice).

Set up a local WordPress development site, and take note of its URL, e.g. 'example.dev'. Drop your theme in the themes directory and activate it. Edit `config.yml` and make sure the BROWSERSYNC options point to the right URL. This is also where you change options regarding which Foundation (and other) JS files should be concatenated, how the autoprefixer works etc.

Run `npm install && bower install` in your theme directory. Go make a cup of tea while this is happening. Then run `npm start` and start developing!

### Compiling assets for production

Run `npm run build` to compile compressed, production-ready CSS and JS.

### Developing HTML mockups without a WordPress installation

If you prefer to start development by building HTML mockups, you can build your templates in `_src/pages` and `_src/partials` and change the BROWSERSYNC type setting in `config.yml` to `html`. Browsersync will start up a simple static file server at localhost:8000 and refresh every time you make a change to your Sass, JS or HTML template files. The benefit of this is you can get going faster without the overhead of a WP site running locally, and it's easier to include front-end devs in your team who might not have a local server setup or WordPress experience.

Read more about the Panini template language in the <a href="http://foundation.zurb.com/sites/docs/panini.html">Panini docs on the Foundation site</a>.

### Styleguides!

You can build a styleguide within your theme in `_src/styleguide/`, extremely handy when passing on development to new theme members. <a href="http://foundation.zurb.com/sites/docs/style-sherpa.html">Instructions for how to edit the styleguide on the Foundation site</a>. The BROWSERSYNC value in `config.yml` needs to be set to `html` and you can view your guide at `localhost:8000/styleguide.html`. 
