<?php
/* helpers/csrf.php
 * implements several helper functions that protect against cross-site request
 * forgery (CSRF) attacks.
 */

session_start(); // ensure active session

/*
 * csrf_token
 * Produces a CSRF token. Not yet secure! Will need more information about the
 * environments the site will be run in to implement properly.
 * @return	An MD5 hash.
 */
function csrf_token()
{
	if (!isset($_SESSION['csrf_token'])) {
		$token = md5(uniqid(rand(), TRUE));
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

