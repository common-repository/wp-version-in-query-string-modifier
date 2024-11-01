<?php
    global $wp_version;
    $site_url = get_site_url();
    $check_i = $check_r = $check_d = '';
    $disable_increment = true;
    switch($options['selection']) {
        case 'r': 
            $check_r = ' checked="checked"'; 
            break;
        case 'd': 
            $check_d = ' checked="checked"'; 
            break;
        case 'i': 
            // fall thru default
        default: 
            $check_i = ' checked="checked"';
            $disable_increment = false;
    }
?>
<div class='wrap'>
    <h2><?php echo WPVQSM_LONG_NAME; ?></h2>
    <form method='post' action='admin-post.php'>
        <input type="hidden" name="action" value="<?php echo WPVQSM; ?>">
        
        <?php if ($updated) {  // a better way of doing this via plugin? ?>
        <div class="updated settings-error" id="setting-error-settings_updated"> 
            <p><strong>Settings saved.</strong></p>
        </div>
        <?php } ?>
        
        <table class='form-table permalink-structure'>
            <tbody>
                <tr>
                    <th><label><input type='radio' <?php echo $check_i; ?> value='i' name='selection'>Cache Buster</label></th>
                    <td><code><?php echo $site_url; ?>/wp-includes/js/jquery/jquery.js?ver=<span id='inc_index'><?php echo $ndx; ?></span></code> <input type='button' value='Increment' id='increment' class='button-primary'></td>
                </tr>
                <tr>
                    <th><label><input type='radio' <?php echo $check_r; ?> value='r' name='selection'> Remove Version</label></th>
                    <td><code><?php echo $site_url; ?>/wp-includes/js/jquery/jquery.js</code></td>
                </tr>
                <tr>
                    <th><label><input type='radio' <?php echo $check_d; ?> value='d' name='selection'> Do Nothing</label></th>
                    <td><code><?php echo $site_url; ?>/wp-includes/js/jquery/jquery.js?ver=<?php echo $wp_version; ?></code></td>
                </tr>
				<tr>
                    <td colspan='2'><hr></td>
                </tr>
				<tr>
                    <th><label><input type='checkbox' name='addTime' value='1'<?php if ((int) $addTime === 1) echo ' checked';?>>Add time randomizer</label></th>
                    <td><code><?php echo $site_url; ?>/wp-includes/js/jquery/jquery.js?ver=<?php echo $wp_version; ?>1441121962</code>
					<br><small>Useful for generating unique string (but may affect performance). Thanks for bkjproductions for <a target='_blank' href='https://wordpress.org/support/topic/works-at-mediatemple-so-far'>pointing this out!</a></small>
					</td>
                </tr>
            </tbody>
        </table>
		<div>
		</div>
        <p class='submit'>
            <input type='submit' value='Save Changes' class='button button-primary' id='submit' name='submit'>
        </p>
    </form>
	<div>
	For questions, feedback or requests, please post on the <a target='_blank' href='https://wordpress.org/support/plugin/wp-version-in-query-string-modifier'>plugin support page</a>.
	</div>
</div>
<script type='text/javascript'>
(function($) {
    <?php if ($disable_increment) { ?>
        $('#increment').attr('disabled', 'disabled');
    <?php } ?>
    $('input[name=selection]:radio').on('change', function() {
        if ('i' === $(this).val()) {
            $('#increment').removeAttr('disabled');
        }
        else {
            $('#increment').attr('disabled', 'disabled');
        }
    });
    $('#increment').on('click', function() {
        var data = {
			'action': '<?php echo WPVQSM; ?>'
		};

		$.post(ajaxurl, data, function(response) {
			if (response) {
                $('#inc_index').html(response);
            }
		});
    });
})(jQuery);
</script>