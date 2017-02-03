<?php
/* helpers/form.php
 * Provides helper methods for form building.
 */

require_once('crypto.php');

/*
 * form_field
 * Factory function that produces a single form field. Should be used within
 * a form tag.
 * @param	string	$script_name	The name used by the script for this field,
 * 		e.g. 'first_name'.
 * @param	string	$nice_name	The name displayed to the user, used for the
 * 		field's label, e.g. 'First name'.
 * @param	string	$type	The type of field, default 'text'
 * @param	string	$placeholder	The placeholder to display when the form's
 * 		input is empty. Defaults to $nice_name.
 * @return	nothing
 */
function form_field($script_name, $nice_name, $type = 'text', $placeholder = '')
{
	if ($placeholder == '')
		$placeholder = $nice_name;
	?>
	<div class="form-group">
		<label for="<?= $script_name ?>" class="col-sm-2 control-label">
			<?= $nice_name ?>
		</label>
		<div class="col-sm-10">
			<input type="<?= $type ?>" class="form-control" name="<?= $script_name ?>" placeholder="<?= $placeholder ?>">
		</div>
	</div>
	<?php
}

/*
 * csrf_token_field
 * Produces a hidden field for a CSRF token. Should be used in any form that,
 * when submitted, will modify the database somehow.
 * @return	nothing
 */
function csrf_token_field()
{
	?>
	<input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
	<?php
}

