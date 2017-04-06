<?php

$config = parse_ini_file(__DIR__.'/config.ini');

if (basename($_SERVER['REQUEST_URI']) === 'config.php') {
	$dir = __DIR__;
	$path_web = $config['path_web'];
	$link = $path_web . 'html/';
	$homelink = $link . 'index.php';
	$csslink = $link . 'bootstrap/css/custom.css';

	$protocol = 'http';
	if (isset($_SERVER['HTTPS']))
		$protocol .= 's';
	$server_name = $_SERVER['SERVER_NAME'];
	$prefix = $_SERVER['CONTEXT_PREFIX'];
	$folder = substr(__DIR__, strlen($_SERVER['CONTEXT_DOCUMENT_ROOT']) + 1);
	$autopath = "$protocol://$server_name$prefix/$folder/";

	echo('<h3>Config.php debug info</h3>');
	echo("Dir: $dir<br />");
	echo("Path: $path_web<br />");
	echo("Suggested path: $autopath<br/>");
	echo("Home link: <a href=\"$homelink\">$homelink</a><br />");
	echo("CSS link: <a href=\"$csslink\">$csslink</a><br />");

	echo('<hr>');

	if ($config['path_web'] === '') {
		die('<span style=\"color:red\">Error detected: path_web in config.ini is empty</span><br />');
	} else {
		$config_protocol = substr($path_web, 0, strlen($protocol));
		if ($config_protocol !== $protocol)
			echo("Problem detected: path_web in config.ini using incorrect protocol (recommended: $protocol)<br />");
		$lastchar = substr($path_web, -1);
		if ($lastchar !== '/')
			echo('Problem detected: path_web in config.ini does not end in /<br />');
	}
} else if ($config['path_web'] == NULL) {
	die('<span style=\"color:red\">Error detected: path_web in config.ini is empty</span><br />');
}
//phpinfo();
