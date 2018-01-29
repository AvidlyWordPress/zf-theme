// jQuery is configured as a webpack external dependency, see gulpfile.babel.js
import $ from 'jquery';
import whatInput from 'what-input';

window.$ = $;

// See lib/foundation-explicit-pieces for including / excluding specific stuff.
import './lib/foundation-explicit-pieces';

// Add your own code as separate files (preferably separate files for each feature / component) under js/ and import here
// e.g.
// import './my-custom-feature';

// Initialize Foundation
$(document).foundation();
