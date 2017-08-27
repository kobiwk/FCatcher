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



function FC_main_page()
{
	?>
	<h2> Options page </h2>
	<form>
		<select onchange="showRSS(this.value)">
			<option value=''>Select an RSS feed: </option>
			<?php
				foreach (get_option('snimanje') as $options)
				{
					foreach ($options as $option_name => $option_link)
					{
						?>
						<option value="<?php echo $option_name; ?>">
							<?php echo $option_name; ?>
						</option>
						<?php
					}
				}
			?>
		</select>
	</form>
	<br>
	<div id="rssOutput">
		Rss-feed will be listed here..
	</div>
<?php
}

add_action('admin_menu', 'FC_make_admin_menu');





function FC_options_page() {
	?>
	<div class = "FC-option-page">
		<h4> Podešavanje Feed Catcher plugina </h4>
		  <h5> Izbor RSS fidova </h5>
			<form action = "" method = "post" id="FC-link-form" class="FC-form">
				Unesite ime RSS fida, koji zelite da pratite: <input type = "text" size = "20" name="ime_rss">
				Unesite link RSS fida, koji zelite da pratite: <input type = "text" size = "20" name="link_rss">


			<h5>Podešavanja broja fidova koji želite da pratite na sajtovima</h5>

			Unesite broj fidova koji zelite da pratite na jednom sajtu: 
			<input type="text" size="50" name="broj_po_linku" value="<?php 
				if (get_option('broj_fidova') )
				{
					echo get_option('broj_fidova');
				} 
				else
				{
					echo 'Default: 5. Choose your number';
				}
			?>">

			<?php submit_button(); ?>
			</form>

		<?php
			if (get_option('broj_fidova'))
				{
					?>
						<p> <h6> Izabrali ste da imate pregled <?php echo esc_html( get_option('broj_fidova') );?> fida/ova po strani </h6></p>
					<?php
				}
				else
				{
					echo "You haven't choose any number of feeds to display";
				}



			if (isset($_GET['akcija']) && $_GET['akcija']=='obrisi')
			{
				fc_delete_data();
			}
			?>

	</div>
	<div class = "FC-active-feeds">
		<h5> Trenutno aktivni fidovi </h5>
		<?php  
		if (get_option('snimanje'))
		{ 
			foreach (get_option('snimanje') as $nesto)
			   {
					foreach ($nesto as $ime => $link)
					{
						$ime = esc_html($ime);
						echo "<p>".$ime."<a href = \"admin.php?page=opcije-fida&link=$ime&akcija=obrisi\" > Obrisi </a>"."</p>";
					}
			   } 
		} 
		else
		{
			echo "<p>Niste izabrali ni jedan RSS fid koji želite da pratite. Unesite RSS link u formu sa leve strane.</p>";
		}

		?>
				</div>
	<?php
}