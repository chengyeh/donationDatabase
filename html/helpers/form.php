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
			<input id="<?= $script_name ?>" type="<?= $type ?>" class="form-control <?= $classes ?>" name="<?= $script_name ?>" placeholder="<?= $placeholder ?>" <?= $extra ?>>
		</div>
	</div>
	<?php
}

/*
 * form_number_field
 */
function form_number_field($script_name, $nice_name, $placeholder, $max,
	$min = 0, $value = 0, $classes = '')
{
	if ($placeholder == '')
		$placeholder = $nice_name;
	$extra = $value ? "value=$value" : '';
	if (substr($nice_name, -1) == '*')
		$extra .= ' required';
	?>
	<div class="form-group">
		<label for="<?= $script_name ?>" class="col-sm-2 control-label">
			<?= $nice_name ?>
		</label>
		<div class="col-sm-10">
			<input id="<?= $script_name ?>" type="number" class="form-control <?= $classes ?>" name="<?= $script_name ?>" placeholder="<?= $placeholder ?>" min="<?= $min ?>" max="<?= $max ?>" <?= $extra ?>>
		</div>
	</div>
	<?php
}

/*
 * form_gender_field
 */
function form_gender_field($required = false, $value = '', $classes = '')
{
	$selected = ['', '', '', ''];
	switch ($value) {
		case 'm': $selected[1] = 'selected="selected"'; break;
		case 'f': $selected[2] = 'selected="selected"'; break;
		case 'o': $selected[3] = 'selected="selected"'; break;
		default: $selected[0] = 'selected="selected"'; break;
	}
	?>
	<div class="form-group">
		<label for="gender" class="col-sm-2 control-label">
			Gender
		</label>
		<div class="col-sm-10">
			<select id="gender" name="gender" class="form-control <?= $classes ?>" <?= $required ? 'required' : '' ?> value="<?= $value ?>">
				<option value="" <?= $selected[0] ?>></option>
				<option value="m" <?= $selected[1] ?>>Male</option>
				<option value="f" <?= $selected[2] ?>>Female</option>
				<option value="o" <?= $selected[3] ?>>Other</option>
			</select>
		</div>
	</div>
	<?php
}

/*
 * form_ethnicity_field
 * Provides a prepopulated dropdown ethnicity field for a form, with options for
 * each of the ethnicities specified in NOT-OD-15-089.
 * @param	boolean	required	Whether to mark this field as required (default
 * 		false).
 * @param	int	value	The default value of the form (as an index) (default 0).
 * @param	string	classes	Additional CSS classes to apply to the field
 * 		(default '').
 */
function form_ethnicity_field($required = false, $value = 0, $classes = '')
{
	$selected = ['', '', '', '', '', '', ''];
	$selected[$value] = 'selected="selected"';
	?>
	<div class="form-group">
		<label for="ethnicity" class="col-sm-2 control-label">
			Ethnicity
		</label>
		<div class="col-sm-10">
			<select id="ethnicity" name="ethnicity" class="form-control <?= $classes ?>" <?= $required ? 'required' : '' ?> value="<?= $value ?>">
				<!-- NOT-OD-15-089 -->
				<option value="0" <?= $selected[0] ?>></option>
				<option value="1" <?= $selected[1] ?>>American Indian or Alaskan Native</option>
				<option value="2" <?= $selected[2] ?>>Asian</option>
				<option value="3" <?= $selected[3] ?>>Black or African American</option>
				<option value="4" <?= $selected[4] ?>>Native Hawaiian or Other Pacific Islander</option>
				<option value="5" <?= $selected[5] ?>>White</option>
			</select>
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

