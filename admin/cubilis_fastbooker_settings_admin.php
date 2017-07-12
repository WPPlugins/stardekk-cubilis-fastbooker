<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 
	// load jquery
	wp_enqueue_script('jquery');
	
	/*
	 * array languages
	 */
	$languages = array(
		'nl' => __('dutch', 'text_domain'),
		'fr' => __('french', 'text_domain'),
		'en' => __('english', 'text_domain'),
		'de' => __('deutsch', 'text_domain'),
	);
	
	/*
	 * Array bookers
	 */
	$integrations = array(
		'iframe' 	 => __('iframe', 'text_domain'),
		'fullscreen' => __('fullscreen', 'text_domain')
	);

    if($_POST['cubilis_fastbooker_settings_hidden'] == 'Y') {
        //Form data sent
		
		// fastbooker integration, sanitize on text field if false defaultIntegration = iframe
		$integration = sanitize_text_field($_POST['cubilis_fastbooker_integration']);
		if (isset($integration) && $integration != "") {
			if (array_key_exists($integration, $integrations)) {
				$fastbookerIntegration = $integration;
			} else {
				$fastbookerIntegration = "iframe";
			}
		} else {
			$fastbookerIntegration = "iframe";
		}
        update_option('cubilis_fastbooker_integration', $fastbookerIntegration);
		
		// fastbooker id, sanitize on integer
		$fastbookerId = intval($_POST['cubilis_fastbooker_logiesid']);
		update_option('cubilis_fastbooker_logiesid', $fastbookerId);
		
		// fastbooker identifier (slug), sanitize on string
		$fastbookerIdentifier = $_POST['cubilis_fastbooker_identifier'];
		update_option('cubilis_fastbooker_identifier', $fastbookerIdentifier);

		// fastbooker default lang, sanitize on text field if false defaultlang = en
		$lang = sanitize_text_field($_POST['cubilis_fastbooker_lang']);
		if (isset($lang) && $lang != "") {
			if (array_key_exists($lang, $languages)) {
				$fastbookerLang = $lang;
			} else {
				$fastbookerLang = "en";
			}
		} else {
			$fastbookerLang = "en";
		}
        update_option('cubilis_fastbooker_lang', $fastbookerLang);
		
		// fastbooker overview checkbox, sanitize on text field
		$generalOverview = sanitize_text_field($_POST['cubilis_fastbooker_general_overview']);
		if ( isset( $generalOverview ) && $generalOverview != "" ) {
			$fastbookerGeneralOverview  = true;
		} else {
			$fastbookerGeneralOverview  = false;
		}
		update_option('cubilis_fastbooker_general_overview', $fastbookerGeneralOverview);
		
		// fastbooker discount checkbox, sanitize on text field
		$discount =  sanitize_text_field($_POST['cubilis_fastbooker_discount']);
		if ( isset($discount) && $discount != "" ) {
			$fastbookerDiscount = true;
		} else {
			$fastbookerDiscount = false;
		}
		update_option('cubilis_fastbooker_discount', $fastbookerDiscount);

        ?>
		<div class="updated"><p><strong><?php _e('Your changes have been saved.', 'text_domain' ); ?></strong></p></div>
        <?php
    } else {
        //Normal page display
		$fastbookerIntegration      = get_option('cubilis_fastbooker_integration');
		$fastbookerId 				= get_option('cubilis_fastbooker_logiesid');
		$fastbookerIdentifier 		= get_option('cubilis_fastbooker_identifier');
        $fastbookerLang 			= get_option('cubilis_fastbooker_lang');
		$fastbookerDiscount		   	= get_option('cubilis_fastbooker_discount');
		$fastbookerGeneralOverview 	= get_option('cubilis_fastbooker_general_overview');
		
    }
?>

<script type="text/javascript">
	jQuery(document).ready(function(){
		var integration = jQuery( "#cubilis_fastbooker_integration" ).val();
		showCorrectIntegrationFields( integration );
		
		jQuery( "#cubilis_fastbooker_integration" ).on('change', function() {
			showCorrectIntegrationFields( this.value );
		});
	});
	
	function showCorrectIntegrationFields(integration) {
		if (integration == "iframe") {
			jQuery( "#fastbooker_slug" ).hide();
			jQuery( "#fastbooker_logies" ).show();
			jQuery( "#fastbooker_general" ).show();
		} else {
			jQuery( "#fastbooker_logies" ).hide();
			jQuery( "#fastbooker_general" ).hide();
			jQuery( "#fastbooker_slug" ).show();
		}
	}
</script>

<div class="wrap">
    <?php    echo "<h2>" . __( 'Cubilis Fastbooker Settings', 'text_domain' ) . "</h2>"; ?>
	
	<div class="message">
			<p>
				<?php _e('Please enter the Cubilis settings correct. If you need any help. You can always contact us on the <a href="http://stardekk.com/en/online-support-1" alt="helpdesk" title="helpdesk" target="_blank">help desk</a>', 'text_domain');?>
			</p>
			<p>
				<?php _e('<a href="http://www.stardekk.be/en" target="_blank">Stardekk</a> can secure your PC remotely, So we can help over the phone and via your own pc screen.', 'text_domain'); ?>
			</p>
			<p style="color: #ff741f;">
				<?php _e('Important! Please make an appointment with an employee via +32 (0) 50/38.38.68 before you start a session.', 'text_domain'); ?>
			</p>
		</div>
     
    <form name="csettings_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="cubilis_fastbooker_settings_hidden" value="Y">
		<table>
			<tr>
				<td colspan="3">
					 <h3><?php _e( 'General', 'text_domain' );  ?></h3>
				</td>
			</tr>
			<tr>
				<td><?php _e("Fastbooker integration: ", 'text_domain' ); ?></td>
				<td>
					<select name="cubilis_fastbooker_integration" id="cubilis_fastbooker_integration" style="width:99%;">
						<?php foreach($integrations as  $key => $integration): ?>
							<option value="<?php echo esc_attr($key); ?>" <?php if($key === $fastbookerIntegration): ?>selected="selected"<?php endif; ?>>
								<?php echo esc_html($integration); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr id="fastbooker_logies">
				<td width="20%"><?php _e("Logies ID: ", 'text_domain'); ?></td>
				<td><input type="text" name="cubilis_fastbooker_logiesid" value="<?php echo esc_attr($fastbookerId); ?>" size="35" /></td>
				<td><?php _e(" ex: 270, This is the identifier of your fastbooker iframe integration.", 'text_domain' ); ?></td>
			</tr>
			<tr id="fastbooker_slug">
				<td width="20%"><?php _e("Booker perma link: ", 'text_domain'); ?></td>
				<td><input type="text" name="cubilis_fastbooker_identifier" value="<?php echo esc_attr($fastbookerIdentifier); ?>" size="35" /></td>
				<td><?php _e(" ex: stardekk-development-sint-kruis in https://reservations.cubilis.eu/stardekk-development-sint-kruis. This is the identifier of your fastbooker fullscreen integration.", 'text_domain' ); ?></td>
			</tr>
			<tr>
				<td><?php _e("Default Language: ", 'text_domain' ); ?></td>
				<td>
					<select name="cubilis_fastbooker_lang" style="width:99%;">
						<?php foreach($languages as  $key => $lang): ?>
							<option value="<?php echo esc_attr($key); ?>" <?php if($key === $fastbookerLang): ?>selected="selected"<?php endif; ?>>
								<?php echo esc_html($lang); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			
			<tr>
				<td colspan="3">
					<h3><?php _e( 'Features', 'text_domain' ); ?></h4>
				</td>
			</tr>
			
			<tr id="fastbooker_general">
				<td><?php _e("General availability?", 'text_domain' ); ?></td>
				<td><input type="checkbox" name="cubilis_fastbooker_general_overview" <?php if ($fastbookerGeneralOverview): ?>checked="checked"<?php endif; ?>/></td>
				<td><?php _e("The link to the general availability will be shown if you check this box.", 'text_domain' ); ?></td>
			</tr>
			<tr>
				<td><?php _e("Discount?", 'text_domain' ); ?></td>
				<td><input type="checkbox" name="cubilis_fastbooker_discount" <?php if ($fastbookerDiscount): ?>checked="checked"<?php endif; ?>/></td>
				<td><?php _e("The text field of the discount will be shown if your check this box.", 'text_domain' ); ?></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
		</table>
		<hr />
        <p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Save changes', 'text_domain' ) ?>" />
        </p>
    </form>	
</div>