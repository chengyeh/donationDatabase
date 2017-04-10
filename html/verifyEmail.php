<?php
//active column is null by default. when activated by the users email link
//the column gets set to 1. if an admin wants to ban the account, its set to 0 in the
//admin panel user control page.
require_once(__DIR__.'/helpers/mysqli.php');

//TODO use post? Taylor needs to fix this.
if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
    //sanitize the strings
    $email = mysql_escape_string($_GET['email']);
    $hash = mysql_escape_string($_GET['hash']);
    
    //find the unactivated account specified
    $result = $mysqli->query('SELECT * FROM UserTable WHERE Email="'.
        $email.'" AND PassSalt="'.$hash.'" AND Active IS NULL');

    //if a result is found, only 1 can match, activate the account
    if(($result->num_rows) > 0){
        // We have a match, activate the account
        $mysqli->query('UPDATE UserTable SET Active="1" WHERE Email="'.
            $email.'" AND PassSalt="'.$hash.'"') or die(mysql_error());
        //output good message
        header('Location:login.php?msg=5');
        exit;
    }else{
        //output error message for account already activated or invalid fields
        header('Location:login.php?err=10');
        exit;
    }

}else{
    //output error message for invalid action
    header('Location:login.php?err=11');
    exit;
}
?>
