/**
 * Finds all <h2> elements with id attributes and creates a navigation menu to them.
 *
 * There should be a menu element on the page with an id of "document-submenu" which will be
 * populated by <li>s linking to <h2>s within the document. All <h2> elements with an id set
 * will automatically be linked to when this script is invoked.
 *
 *
 * @version 1.0
 * @since 1.0
 */
(function() {
	'use strict'; // Use ECMAScript 5 strict mode (http://ejohn.org/blog/ecmascript-5-strict-mode-json-and-more/)
	
		// The document submenu element in the DOM
	var menu = document.getElementById('document-submenu'),
		
		// Get all headings that we could be interested in
		headings = document.getElementsByTagName('h2'),
		
		// Document fragment for managing DOM nodes in memory
		fragment = document.createDocumentFragment();
	
	// Loop through all headings, adding a link to any with an id to the menu
	for (var i = 0; i < headings.length; i++) {
		var heading = headings[i],
			li,
			a;
		
		if (heading.id !== '') {
			li = fragment.appendChild(document.createElement('li'));
			a = li.appendChild(document.createElement('a'));
			
			a.href = '#'+heading.id;
			a.textContent = heading.textContent;
		}
	}
	
	// Append the menu links to the menu in the DOM
	menu.appendChild(fragment);

}());