(function(window, document, $, undefined){

	'use strict';

	window.zfTheme = {};

	zfTheme.init = function() {
		// Foundation
		$(document).foundation();
	};

	$(document).ready( zfTheme.init );

})(window, document, jQuery);
