<?php

//AJAH PHP
add_action('wp_ajax_my_action', 'fc_ajax_php_action');

do_action( 'wp_ajax_my_action', 'fc_ajax_php_action' );


function fc_ajax_php_action() 
{
	$q = $_GET["q"];

	if (isset($q))
	{
		foreach (get_option('snimanje') as $options)
		{
			foreach ($options as $option_name => $option_link )
			{
				if ($q == $option_name )
				{
					$xml = $option_link;
				}
			}
		}
	}
	
	

	$xmlDoc = new DOMDocument();

	if ($xml)
	{
		$xmlDoc -> load($xml);

		$channel = $xmlDoc -> getElementsByTagName('channel')->item(0);
		$channel_title = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
		@$channel_link = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
		$channel_desc = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

		echo("<p><a href='" . $channel_link . "'>" . $channel_title . "</a>");
		echo("<br>");
		echo ($channel_desc . "</p>");

		$x = $xmlDoc -> getElementsByTagName('item');
		if (get_option('broj_fidova'))
		{
			$feed_number = get_option('broj_fidova');
		}
		else
		{
			$feed_number = 4;
		}
		for ($i = 0; $i <= $feed_number; $i++) 
		{
			$item_title = $x -> item($i) -> getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;

			$item_link = $x -> item($i) -> getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;

			$item_desc = $x -> item($i) -> getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

			echo ("<p style='font-size: 20px;'><a href = '" . $item_link . "'>" . $item_title . "</a>");
			echo ("<br>");
			echo ($item_desc . "</p>");
		}
	}
}

//AJAX JS
add_action('admin_footer', 'fc_ajax_script');

function fc_ajax_script()
{
	?>
	<script>
		function showRSS(str) {
			if (str.length == 0) {
				document.getElementById('rssOutput').innerHTML = '';
				return;
			} 

			if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest;
			}

			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById('rssOutput').innerHTML = this.responseText;
				}
			}

			xmlhttp.open('GET', 'admin-ajax.php?q=' + str, true);
			xmlhttp.send();
		}

	</script>
	<?php
}