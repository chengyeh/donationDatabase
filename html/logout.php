<?php

require_once('../config.php');

session_start();
session_destroy();

$redirect_url = $config['path_web'] . 'html/index.php';
header("Location:$redirect_url");
exit();
?>
