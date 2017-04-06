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
 * @param	string	$value	The value to place in the field. Defaults to ''
 * @return	nothing
 */
function form_field($script_name, $nice_name, $type = 'text', $placeholder = '',
	$value = '', $classes = '')
{
	if ($placeholder == '')
		$placeholder = $nice_name;
	$extra = '';
	if ($value != '') {
		if ($type == 'text')
			$extra = "value=\"$value\"";
		else if ($type != 'number' || $value != 0)
			$extra = "value=$value";
	}
	if (substr($nice_name, -1) == '*')
		$extra .= ' required';
	?>
	<div class="form-group">
		<label for="<?= $script_name ?>" class="col-sm-2 control-label">
			<?= $nice_name ?>
		</label>
		<div class="col-sm-10">
			<input type="<?= $type ?>" class="form-control <?= $classes ?>" name="<?= $script_name ?>" placeholder="<?= $placeholder ?>" <?= $extra ?>>
		</div>
	</div>
	<?php
}

/*
 * form_hidden_field
 * Factory function that produces a hidden (invisible) form field.
 * @param	string	$script_name	The label for this hidden field.
 * @param		$value	The value to place in this hidden field.
 */
function form_hidden_field($script_name, $value)
{
	?>
	<input type="hidden" name="<?= $script_name ?>" value="<?= $value ?>">
	<?php
}

/*
 * form_submit_button
 * Factory function that produces a button to submit a form.
 * @param	string	$text	Text to use on the button. Defaults to "Submit".
 * @return	nothing
 */
function form_submit_button($text = 'Submit')
{
	?>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button class="btn btn-default"><?= $text ?></button>
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

