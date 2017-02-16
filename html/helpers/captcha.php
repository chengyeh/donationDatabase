<?php
/* helpers/captcha.php
 * implements functions that simplify the process of adding a captcha to a
 * form or validating the text entered into a captcha.
 *
 * Securimage must be installed at the web folder root.
 */

require_once(__DIR__.'/../../config.php');

if ($config['use_captchas']):

require_once($_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php');
$securimage = new Securimage();

endif; // $config['use_captchas']

/*
 * captcha_field
 * Outputs the HTML to create a captcha field for a boostrap form.
 *
 * @param	bool	$asterisk	Whether to append an asterisk to the label text.
 * 		Defaults to false.
 */
function captcha_field($asterisk = false)
{
	global $config;
	if ($config['use_captchas']) {
		?>
		<div class="form-group">
		<label for="captcha" class="col-sm-2 control-label">
		Captcha<?= $asterisk ? '*' : '' ?><br />
		<a href="#" onclick="document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false">(Regenerate)</a></br />
		</label>
		<div class="col-sm-10">
		<img id="captcha" src="<?= $config['path_web'] ?>../securimage/securimage_show.php" alt="CAPTCHA Image" />
		<input type="text" class="form-control" name="captcha_code" size="10" maxlength="6" />
		</div>
		</div>
		<?php
	}
}

/*
 * verify_captcha()
 * Retrieves the user's input for a captcha from $_POST and verifies its
 * correctness. If there is no captcha input or the captcha is incorrect, the
 * user will encounter a 400 error (bad request) and PHP will exit. Otherwise,
 * execution will continue normally.
 */
function verify_captcha()
{
	global $securimage, $config;
	if (!$config['use_captchas'])
		return;
	if (!isset($_POST['captcha_code']) || !$securimage->check($_POST['captcha_code'])) {
		// captcha failed
		http_response_code(400);
		die('Error 400: Bad request (captcha input did not match provided captcha).');
	}
}
