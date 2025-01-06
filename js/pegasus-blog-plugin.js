/**
 * cbpViewModeSwitch.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyr ight 2013, Codrops
 * http://www.codrops.com
 */
(function() {

	var containers = document.querySelectorAll('.pegasus-blog-switcher');

	containers.forEach(function(container) {
		var optionSwitch = Array.prototype.slice.call(container.querySelectorAll('div.pegasus-blog-options > a'));

		function init() {
			optionSwitch.forEach(function(el, i) {
				el.addEventListener('click', function(ev) {
					ev.preventDefault();
					_switch(this);
				}, false);
			});
		}

		function _switch(opt) {
			// remove other view classes and any selected option
			optionSwitch.forEach(function(el) {
				classie.remove(container, el.getAttribute('data-view'));
				classie.remove(el, 'pegasus-blog-selected');
			});
			// add the view class for this option
			classie.add(container, opt.getAttribute('data-view'));
			// this option stays selected
			classie.add(opt, 'pegasus-blog-selected');
		}

		init();
	});

})();
