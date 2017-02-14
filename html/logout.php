<?php
session_start();
session_destroy();

header("Location:https://people.eecs.ku.edu/~mbechtel/donationDatabase/html/index.php");
?>