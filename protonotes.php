<?php
/*
Plugin Name: Protonotes
Plugin URI: http://nilswindisch.de
Feed URI: 
Description: Integrates Protonotes into you WordPress site.
Version: 1.0
Author: Nils K. Windisch
Author URI: http://nilswindisch.de
*/

function nkw_protonotes()
{
	
	global $user_level;
	
	$options = get_option('nkw_protonotes_code');
	
	$protonotes .= "
		<script 
			src='http://www.protonotes.com/js/protonotes.js' 
			type='text/javascript'></script>
		";
	$protonotes .= "
	<script type=\"text/javascript\">
		var groupnumber='".$options['nkw_protonotes_code']."';
	</script>";
	
	if ($user_level) print($protonotes);

}


function nkw_protonotes_option_menu()
{

	if (function_exists('current_user_can'))
	{
		if (!current_user_can('manage_options')) return;
	}
	else
	{
		global $user_level;
		get_currentuserinfo();
		if ($user_level < 8) return;
	}

	if (function_exists('add_options_page'))
	{
		add_options_page(__('Protonotes'), __('Protonotes'), 1, __FILE__, 'nkw_protonotes_option_page');
	}

}


function nkw_protonotes_option_page()
{

	global $wpdb;

	if (isset($_POST['update_options']))
	{
		$options['nkw_protonotes_code'] = trim($_POST['nkw_protonotes_code'],'{}');
		update_option('nkw_protonotes_code', $options);
		echo '<div class="updated"><p>'.__('Options saved').'</p></div>';
	}
	else
	{
		$options = get_option('nkw_protonotes_code');
	}
	
	?>
<div class="wrap">
	<h2><?php echo __('Protonotes Option'); ?></h2>
	<p>Protonotes are notes that you add to your prototype that allow project team members to discuss system functionality, design, and requirements directly on the prototype. You can think of it like a discussion board/wiki in direct context of your prototype.</p>
	<p>If you like to use Protonotes you'll need a code to insert below. <a href="http://www.protonotes.com/sign-up-for-protonotes.html">Get it here</a>.</p>
	<form method="post" action="">
	<fieldset class="options">
	<table class="optiontable">
		<tr valign="top">
			<th scope="row"><?php _e('Protonotes Code:') ?></th>
			<td><input name="nkw_protonotes_code" type="text" id="nkw_protonotes_code" value="<?php echo $options['nkw_protonotes_code']; ?>" size="60" /></td>
		</tr>
	</table>
	</fieldset>
	<div class="submit"><input type="submit" name="update_options" value="<?php _e('Update') ?>"  style="font-weight:bold;" /></div>
	</form>
	<p>View your Protonotes: <a href="http://www.protonotes.com/get-all-group-notes.html?groupnumber=<?php echo $options['nkw_protonotes_code']; ?>">Website</a> | <a href="http://www.protonotes.com/rssExports/rss<?php echo $options['nkw_protonotes_code']; ?>.xml">RSS</a> | <a href="http://www.protonotes.com/csvExports/datafeed<?php $options['nkw_protonotes_code']; ?>.csv">Excel</a></p>
	<p>...made by <a href="http://nilswindisch.de" target="_blank" alt="Nils K. Windisch">Nils K. Windisch</a></p>
</div>
	<?php

}

add_action('admin_menu', 'nkw_protonotes_option_menu');
add_action('wp_head', 'nkw_protonotes');