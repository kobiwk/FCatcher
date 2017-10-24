<?php 

/*Create admin pages*/
function FC_make_admin_menu() 
{
	$page = add_menu_page(
		'Options of FC plugin',
		'Feed Catcher', 
		'manage_options',
		'feedCatcher',
		'FC_main_page');

	add_submenu_page(
		'feedCatcher', 
		'Opcije', 
		'Opcije',
		'manage_options', 
		'fc-options', 
		'FC_options_page') ;

	
}

//add style to admin pages
function add_fc_style () {

	wp_enqueue_style('fc_style', plugin_dir_url(__FILE__) . 'css/fc_style.css');
	//wp_enqueue_style('fc_foundation_style', plugin_dir_url(__FILE__) . 'css/foundation.css');
	//wp_enqueue_style('fc_foundation_custom_style', plugin_dir_url(__FILE__) . 'css/app.css');
}

add_action('admin_enqueue_scripts', 'add_fc_style');



function add_fc_script() {

	wp_enqueue_script('fc_script', plugin_dir_url(__FILE__).'js/script.js' );
	//wp_enqueue_script('foundation-start', plugin_dir_url(__FILE__).'js/app.js', array('jquery') );
	//wp_enqueue_script('foundation', plugin_dir_url(__FILE__).'js/vendor/foundation.js', array('jquery') );
}

add_action('admin_enqueue_scripts', 'add_fc_script');



//save data from Options page

add_action('admin_post_fc-options', 'fc_save_data');

function fc_save_data()
{	
	if (!empty($_POST['ime_rss'] ) && !empty($_POST['link_rss']) )
	{
			
		/*ime i link fida*/
		if (get_option('snimanje'))
		{
			$podaci = get_option('snimanje') ;
		}
		else
		{
			$podaci = array();
		}

		$naslov = sanitize_text_field( $_POST['ime_rss']);
		$link = sanitize_text_field( $_POST['link_rss']);

		//provera pravilnosti linka
		if (!filter_var($link, FILTER_VALIDATE_URL)===false )
		{
			$unos = array($naslov => $link );
			array_push($podaci, $unos);

			update_option('snimanje', $podaci);
			//proveriti update option
			echo "<h2>You have succesufully entered the RSS feed!</h2>";
		}
		else
		{
			echo "<h2 style = \"color: red;\">You didn't entered RSS feed</h2>";
		}	
	}

	//save number of feeds to display
	if (isset($_POST['broj_po_linku']))
	{
		if (is_numeric(sanitize_text_field($_POST['broj_po_linku']) ) )
		{
			update_option('broj_fidova', sanitize_text_field($_POST['broj_po_linku']) );
			echo "<h2 style = \"color: red;\">Thank you!</h2>";
		}
		else
		{
			echo "<h2>Please, enter the number into the field!</h2>";
		}
	}
}

do_action('admin_post_fc-options', 'fc_save_data');



/*DELETE DATA FROM OPTIONS PAGE*/
add_action('admin_post_fc-options', 'fc_delete_data');

function fc_delete_data()
	{
		if ($_GET['akcija']=='obrisi')
		{
			$podaci = get_option('snimanje');
			$brisi = $_GET['link'];

			foreach ($podaci as $p => $pod)
			{
				foreach ($pod as $ime=>$link)
				{
					if ($ime == $brisi)
					{
						unset($podaci[$p][$ime]);
					}
				}
			}

			if (update_option('snimanje', array_filter($podaci)) ) 
			{
				echo "Uspesno obrisano";
			}
		}
	}





?>