<?php
//error_reporting(E_ALL);
//ini_set("display_errors",1);

//AJAX PHP
add_action('wp_ajax_get_rss', 'fc_ajax_get_rss');

function fc_ajax_get_rss() 
{

	foreach (get_option('snimanje') as $options) {

		foreach ($options as $option_name => $option_link ) {

			if ( $_POST['title'] == $option_name ) {
				$rss_link = $option_link;
			}
		}
	}

	/* Warning. I had to install DomDocument class firstly, through terminal. */


	if (!empty( $rss_link ) ) {

		// Create a new DOM Document to hold our webpage structure 
		$xmlDoc = new DOMDocument(); 

	    // Load the url's contents into the DOM 
	    $xmlDoc->loadHTMLFile($rss_link); 

	    $channel = $xmlDoc->getElementsByTagName('channel')->item(0);
				
		$channel_title = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
		
		$channel_link = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
		
		$channel_desc = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

		echo("<div class='fc-title-rss'><h3><a href='" . $channel_link . "'>" . $channel_title . "</a></h3><br>" . $channel_desc . "</div>");
		echo ("<div class='fc-main-text'>");
			$main = $xmlDoc -> getElementsByTagName('item');
			
		if (get_option('broj_fidova')) {
			$feed_number = get_option('broj_fidova');
		}
		else {
			$feed_number = 4;
		}

		for ($i = 0; $i < $feed_number; $i++) {
			$item_title = $main -> item($i) -> getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;

			$item_link = $main -> item($i) -> getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;

			$item_desc = $main -> item($i) -> getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

			echo ("<p><a href = '" . $item_link . "'><h2 class='fc-feed-title'>" . $item_title . "</h2></a>" . "<span class='fc-add-to-title'> <a href='".$item_link . "' class='showPage'>Read more</a>" . " Publish </span><br>".$item_desc . "</p>");
		}
		echo ("</div>");
		exit; //wp_die(); prevents 0 output
	}
    


}




