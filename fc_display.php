<?php
function FC_main_page()
{
	?>
	<div class="fc-main">
		<h2 class="fc-title"> Feed catcher </h2>
			<div class="fc-container-title fc-top">
				<div class="fc-control-form">
					<form>
						<select id="onChange" name="onChange">
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
					<div id="rssOutputTitle"> </div>
				</div>
				
				<br>

				<div id="rssOutput">
					Rss-feed will be listed here..
				</div>

				<div id="html-page"></div>
		</div>
	</div>
<?php
}

add_action('admin_menu', 'FC_make_admin_menu');





function FC_options_page() {
	?>
	<div class = "FC-option-page">
		<h4> Settings of Feed Catcher plug-in </h4>
		  <h5> Catch your RSS feeds </h5>
			<form action = "" method = "post" id="			FC-link-form" class="FC-form">
				Enter the name of the RSS feed that you want to follow: <input type = "text" size = "20" name="ime_rss">
				Enter the link of RSS feed, that you want to follow: <input type = "text" size = "20" name="link_rss">

				<h5> Number of RSS feeds from web site you follow</h5>

				Enter the number: 
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
						<p> <h6> You choosed to have  <?php echo esc_html( get_option('broj_fidova') );?> number of RSS feeds per page </h6></p>
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
		<h5> Currently active RSS feeds </h5>
		<?php  
		if (get_option('snimanje'))
		{ 
			foreach (get_option('snimanje') as $nesto)
			   {
					foreach ($nesto as $ime => $link)
					{
						$ime = esc_html($ime);
						echo "<p>".$ime."<a href = \"admin.php?page=fc-options&link=$ime&akcija=obrisi\" > Delete </a>"."</p>";
					}
			   } 
		} 
		else
		{
			echo "<p>You haven't choose any RSS feed to follow. Please enter the RSS URL in form on the left side of the screen.</p>";
		}

		?>
				</div>
	<?php
}