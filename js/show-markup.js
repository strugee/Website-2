/**
 * Allows the markup for design elements to be viewed easily.
 *
 * This is done by listening for clicks on elements with a "show-markup" class.
 * The .show-markup element must have a data-for attribute which contains the id of an element
 * to show the markup for. A "selected" class will be toggled on the .show-markup element, and the
 * markup will either be displayed above its container on the page or removed from the page if it
 * has already been displayed.
 *
 * If the button has an "include-container" class, the markup for the element containing the
 * markup to display will also be included in the displayed markup.
 *
 *
 * @dependency js/prism.js
 *
 * @version 1.0
 * @since 1.0
 */
(function() {

'use strict'; // Use ECMAScript 5 strict mode (http://ejohn.org/blog/ecmascript-5-strict-mode-json-and-more/)

/**
 * Listens for clicks on elements with a "show-markup" class.
 *
 * @version 1.0
 * @since 1.0
 */
document.body.addEventListener('click', function(e) {
	// Listen for clicks on elements with a show-markup class
	if (e.target && e.target.className.indexOf('show-markup') !== -1) {
		// Prevent the button from taking us anywhere
		e.preventDefault();
		
		// Whether to include the container element in the displayed markup
		var includeContainer = (e.target.className.indexOf('include-container') !== -1);
		
		// Toggle the "selected" class on the button
		// Also determine whether we're showing or hiding markup
		
		var classes = e.target.className.split(' '),
			showMarkup = true,
			newClassName = '',
			i;
			
		for (i = 0; i < classes.length; i++) {
			if (classes[i] !== 'selected') {
				newClassName += classes[i] + ' ';
			} else {
				showMarkup = false;
			}
		}
		
		if (showMarkup) {
			newClassName += 'selected';
		}
		
		e.target.className = newClassName;
		
		if (showMarkup) {
			// Display the markup for the child elements of the element whose
			// id is specified by the .show-markup button's data-for attribute
			displayMarkup(e.target.dataset.for, e.target, includeContainer);
		} else {
			hideMarkup(e.target.dataset.for, e.target);
		}
	}
});



/**
 * Displays the markup for the child elements of the element with the given id.
 * The displayed markup will be highlighted with Prism.
 *
 * @param string markupId The id of the element which contains the markup to display.
 * @param element button The button that was clicked.
 * @param bool includeContainer Whether to include the container element in the markup or not.
 * @version 1.0
 * @since 1.0
 */
var displayMarkup = function(markupId, button, includeContainer) {
		// Document fragment for managing DOM nodes in memory
	var fragment = document.createDocumentFragment(),
	
		// Container which contains the markup we want to display
		markupContainer = document.getElementById(markupId),
		
		// Create a <pre><code>...</code></pre> wrapper for the markup
		preElement = fragment.appendChild(document.createElement('pre')),
		codeElement = preElement.appendChild(document.createElement('code')),
		
		markup;
	
	
	// Highlight the code as HTML using the Prism syntax highlighter
	codeElement.className = 'language-markup';
	
	if (includeContainer) {
		// Include the markup container
		var tempContainer = document.createElement('div');
		tempContainer.appendChild(markupContainer.cloneNode(true)); // Clone child elements as well
		markup = tempContainer.innerHTML;
	} else {
		// Don't include the markup container
		markup = markupContainer.innerHTML;
	}
	
	// Insert the markup into the code element
	markup = trimMarkup(markup);
	codeElement.appendChild(document.createTextNode(markup));
	
	// Highlight the markup
	Prism.highlightElement(codeElement);
	
	// Insert the markup code below the clicked button on the page
	button.parentNode.insertBefore(fragment, button.nextSibling);
},



/**
 * Removes the displayed markup for the element with the given id.
 *
 * @param string markupId The id of the element whose displayed markup should be removed.
 * @param element button The button that was clicked.
 * @version 1.0
 * @since 1.0
 */
hideMarkup = function(markupId, button) {
		// The element containing the markup we want to hide
	var markupElement = button.nextSibling;
	
	// Remove the markup element from the DOM
	button.parentNode.removeChild(markupElement);
},



/**
 * Removes extraneous spacing from the given markup.
 *
 * @param string markup The markup to reformat.
 * @return string The reformatted markup.
 * @version 1.0
 * @since 1.0
 */
trimMarkup = function(markup) {
	var reformattedMarkup = '',
		foundTab = false,
		newlinesFound = 0,
		tabsToRemove = '',
		index = 0,
		lines;
	
	// Determine how many extraneous tabs there are
	while (markup.charAt(index) === "\t" || !foundTab) {
		if (markup.charAt(index) === "\t") {
			foundTab = true;
			tabsToRemove += "\t";
		// If we found a newline that wasn't the first character, take note of it
		} else if (markup.charAt(index) === "\n" && index !== 0) {
			newlinesFound += 1;
		}
		
		index++;
	}
	
	// Remove only the same number of tabs as we found newlines before a tab character
	tabsToRemove = tabsToRemove.substr(0, tabsToRemove.length - newlinesFound);

	// Remove any occurences of extraneous tabs once from each line
	lines = markup.split(/\r?\n/);
	for (var i = 0; i < lines.length; i++) {
		reformattedMarkup += lines[i].replace(tabsToRemove, '') + "\n";
	}

	// Strip leading and trailing whitespace
	reformattedMarkup = reformattedMarkup.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	
	return reformattedMarkup;
};

}());