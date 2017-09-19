<?php 
/*
Plugin Name: Feed Catcher Plugin
Plugin URI:  https://slobodari.org/plugins/the-basics/
Description: Develop your web admin experience with Feed Catcher!
Version:     0.1
Author:      MUTUALITY
Author URI:  https://slobodari.org/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: ???
Domain Path: /languages
*/

defined('ABSPATH') or die ('No script kiddies please!');


require('fc_ajax.php');
require('fc_display.php');


function FC_make_admin_menu() 
{
	$page = add_menu_page(
		'Options of FC plugin',
		'Feed Catcher', 
		'manage_options',
		'fc_options',
		'FC_main_page');

	add_submenu_page('fc_options', 'Opcije', 'Opcije','manage_options', 'opcije-fida', 'FC_options_page') ;

	add_action('load-' . $page, 'load_FC_js');
	add_action('load-' . $page, 'load_FC_style');
}

function load_FC_js()
{
	add_action('admin_enqueue_scripts', 'enqueue_fc_js');
}


function enqueue_fc_js()
{/*
  wp_enqueue_script( 'fc_script', plugin_dir_path(__FILE__) . 'js/script.js', array( 'jquery' ), '1', true );*/
  
}


function add_fc_style () {

	wp_enqueue_style('fc_style', plugin_dir_url(__FILE__) . 'css/fc_style.css');
	wp_enqueue_style('fc_foundation_style', plugin_dir_url(__FILE__) . 'css/foundation.css');
}

add_action('admin_enqueue_scripts', 'add_fc_style');




function load_FC_style()
{
	
	
}


add_action('admin_post_opcije-fida', 'fc_save_data');

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
			echo "<h2>Uspesno ste uneli RSS fid!</h2>";
		}
		else
		{
			echo "<h2 style = \"color: red;\">Niste uneli pravilnu adresu RSS fida</h2>";
		}	
	}

	//save number of feeds to display
	if (isset($_POST['broj_po_linku']))
	{
		if (is_numeric(sanitize_text_field($_POST['broj_po_linku']) ) )
		{
			update_option('broj_fidova', sanitize_text_field($_POST['broj_po_linku']) );
			echo "<h2 style = \"color: red;\">Hvala vam!</h2>";
		}
		else
		{
			echo "<h2>Molimo vas da unesete broj u obrazac!</h2>";
		}
	}
}

do_action('admin_post_opcije-fida', 'fc_save_data');

add_action('admin_post_opcije-fida', 'fc_delete_data');



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


