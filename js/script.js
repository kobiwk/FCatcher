document.addEventListener('DOMContentLoaded', function() {
		  document.querySelector('select[name="onChange"]').onchange=showRSS;	
		}, false);

function showRSS(event) {

    if (event.target.value.length === 0) {
        document.getElementById('rssOutput').innerHTML = '';
        return;
	} 

	jQuery.post( 
		ajaxurl, 
		{ 'action': 'get_rss',
		  'test': "test",
		  'title': event.target.value
		}, 
		function(response) {
			console.log( 'The server responded: ', response);
			jQuery('#rssOutput').html(response);
		}
	);
	



	//AJAX

	/*
	can't remeber what I wanted to do with this
	if (window.XMLHttpRequest) {

	    xmlhttp = new XMLHttpRequest;

	}
	console.log( xmlhttp );
	xmlhttp.onreadystatechange = function() {
		
	  if (this.readyState == 4 && this.status == 200 ) {

			document.getElementById('rssOutput').innerHTML = this.responseText;

			//Open window with the proper content
			var openLinkInWindow = document.getElementsByClassName('showPage');

			for (var k = 0; k < openLinkInWindow.length; k++) {

	    		openLinkInWindow[k].addEventListener('click', function(e) {
		    		e.preventDefault();
		    		document.getElementById('html-page').innerHTML = 
		    		'<div id="close"><a class="closeRead" href="#">x</a></div><iframe src="' + this.href + '"></iframe>';
		    	
			    	//change background to something, after opening
			    	document.body.style.backgroundColor = "transparent";
			    	

			    	//Close window with full html page
					var closeWindow = document.getElementsByClassName('closeRead');

					for (var i = 0; i < closeWindow.length; i++) {
						closeWindow[i].addEventListener('click', function(event){
							event.preventDefault();
							document.getElementById('html-page').innerHTML='';

						});
					}
			    }); 
		    } //for

	  } //if
	} //onreadystatechange		

	xmlhttp.open('GET', 'admin-ajax.php?q=' + event.target.value, true);
	xmlhttp.send();
	*/

}

var loadElement = document.getElementsByClassName('load-url-here');
jQuery( document ).on('click', '.showPage', function(e) {
	e.preventDefault();
	loadElement[0].innerHTML = "pasmaters";
});



//CHECK THIS

function addClass(elements, myClass) {

  // if there are no elements, we're done
  if (!elements) { return; }

  // if we have a selector, get the chosen elements
  if (typeof(elements) === 'string') {
    elements = document.querySelectorAll(elements);
  }

  // if we have a single DOM element, make it an array to simplify behavior
  else if (elements.tagName) { elements=[elements]; }

  // add class to all chosen elements
  for (var i=0; i<elements.length; i++) {

    // if class is not already found
    if ( (' '+elements[i].className+' ').indexOf(' '+myClass+' ') < 0 ) {

      // add class
      elements[i].className += ' ' + myClass;
    }
  }
}
	

	