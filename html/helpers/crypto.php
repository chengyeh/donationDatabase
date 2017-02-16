<?php
/* helpers/crypto.php
 * implements several helper functions used to make the site more secure.
 */

session_start(); // ensure active session

/*
 * cs_prng
 * Produces a cryptographically secure 16-byte pseudo random hex string. In PHP
 * versions before 5.4.44, a bug in the implementation of
 * openssl_random_pseudo_bytes will not guarantee that this value is random.
 * @return	A 16-byte (length 32) hex string.
 */
function cs_prng() {
	$prng = openssl_random_pseudo_bytes(16, $secure);
	if (!$secure || !$prng)
		die('cs_prng: could not generate secure random number');
	return bin2hex($prng);
}

/*
 * csrf_token
 * Produces or retrieves a CSRF token from the active session. The token will
 * be set in the session if the session does not already have an active token.
 * @return	A 16-byte (length 32) hex string.
 */
function csrf_token()
{
	if (!isset($_SESSION['csrf_token'])) {
		$token = cs_prng();
		$_SESSION['csrf_token'] = $token;
		$_SESSION['csrf_token_time'] = time();
	} else {
		$token = $_SESSION['csrf_token'];
	}
	return $token;
}

/*
 * verify_csrf_token
 * Checks a provided CSRF token against the one set in the session. If the
 * tokens don't match, exit immediately with error 498.
 */
function verify_csrf_token()
{
	if (!isset($_POST['csrf_token'])) {
		http_response_code(499);
		die('Error 499: CSRF token not supplied.');
	}
	else if (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $_POST['csrf_token']) {
		http_response_code(498);
		die('Error 498: CSRF token mismatch.');
	}
}

/*
 * hash_password
 * @param	string	$password	The password to be hashed.
 * @param	string	$salt	The salt to use to hash this password.
 * @return	string	A SHA-256 hash.
 */
function hash_password($password, $salt = '')
{
	return hash('sha256', $salt . $password);
}

