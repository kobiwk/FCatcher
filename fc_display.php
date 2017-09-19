<?php
function FC_main_page()
{
	?>
	<h2 class="fc-title"> Feed catcher </h2>
		<div class="grid-x grid-margin-x fc-container-title fc-top">
			<div class="large-4 cell fc-control-form">
				<form>
					<select onchange="showRSS(this.value); showRSSTitle(this.value)">
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
				<div id="rssOutputTitle"> Ovde ide naslov RSS fida </div>
			</div>
			
		<br>

		<div id="rssOutput" class="auto cell">
			Rss-feed will be listed here..
		</div>
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