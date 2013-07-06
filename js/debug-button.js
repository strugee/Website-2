/**
 * Allows stylistic debug mode to be toggled on and off with a button.
 *
 * This is done by attaching an event to all buttons with a "debug-button" class.
 * Clicking the button will set a "debug" class on the <body>.
 *
 *
 * @version 1.0
 * @since 1.0
 */
document.body.addEventListener('click', function(e) {
	'use strict'; // Use ECMAScript 5 strict mode (http://ejohn.org/blog/ecmascript-5-strict-mode-json-and-more/)
	
	// Listen for clicks on elements with a debug-button class
	if (e.target && e.target.className.indexOf('debug-button') !== -1) {
		// Prevent the button from taking us anywhere
		e.preventDefault();
		
		// Toggle the "debug" class on the <body>
		
		var classes = document.body.className.split(' '),
			newClassName = '',
			found = false,
			i;
			
		for (i = 0; i < classes.length; i++) {
			if (classes[i] !== 'debug') {
				newClassName += classes[i] + ' ';
			} else {
				found = true;
			}
		}
		
		if (!found) {
			newClassName += ' debug';
		}
		
		// Toggle the "debug" class
		document.body.className = newClassName;
		
		// Toggle the "selected" class on the button
		
		classes = e.target.className.split(' ');
		newClassName = '';
		found = false;
			
		for (i = 0; i < classes.length; i++) {
			if (classes[i] !== 'selected') {
				newClassName += classes[i] + ' ';
			} else {
				found = true;
			}
		}
		
		if (!found) {
			newClassName += 'selected';
		}
		
		// Toggle the "selected" class
		e.target.className = newClassName;
    }
});