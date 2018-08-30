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


		echo "<div class='fc-title-rss'><h3><a href='" . $channel_link . "'>" . $channel_title . "</a></h3><br>" . $channel_desc . "</div>";
		echo "<div class='fc-main-text'>";
		echo "<div class='load-url-here'></div>";
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

			echo "<pre> Find link bre";
			$findLink = $main->item($i)->childNodes->length;

			for ( $k = 0; $k < $findLink; $k++) {
				if ( $main->item($i)->childNodes->item($k)->nodeName == "#text" || $main->item($i)->childNodes->item($k)->nodeName == "guid" ) {
					echo $main->item($i)->childNodes->item($k)->textContent;
				}		

			}
			
			//print_r($main->item($i)->childNodes->item(2));
			echo "</pre>";
			// for cases when 'description' tag has more children
			for ( $j = 0; $j <= 1; $j++ ) {
				if ( $main -> item($i) -> getElementsByTagName('description')->item(0)->childNodes->item($j)->nodeValue != "") {
					$item_desc .= "<p>".$main -> item($i) -> getElementsByTagName('description')->item(0)->childNodes->item($j)->nodeValue."</p>";
				}
			}


			echo "<p><a href = '" . $item_link . "'><h2 class='fc-feed-title'>" . $item_title . "</h2></a>" . "<span class='fc-add-to-title'> <a href='".$item_link . "' class='showPage'>Read more</a>" . " Publish </span><br>".$item_desc . "</p>";

			// reset 'description' child node text
			$item_desc = "";
		}
		echo "</div>";
		exit; //wp_die(); prevents 0 output
	} else {
		echo "<div class='fc-title-rss'><h3>Selected RSS feed url's to follow, in Settings section.</h3></div>";
	}
    


}




