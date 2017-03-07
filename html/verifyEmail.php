<?php
//active column is null by default. when activated by the users email link
//the column gets set to 1. if an admin wants to ban the account, its set to 0 in the
//admin panel user control page.
require_once(__DIR__.'/helpers/mysqli.php');

if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
    //sanitize the strings
    $email = mysql_escape_string($_GET['email']);
    $hash = mysql_escape_string($_GET['hash']);
    
    //find the unactivated account specified
    $result = $mysqli->query('SELECT * FROM UserTable WHERE Email="'.
        $email.'" AND PassHash="'.$hash.'" AND Active IS NULL');

    //if a result is found, only 1 can match, activate the account
    if(($result->num_rows) > 0){
        // We have a match, activate the account
        $mysqli->query('UPDATE UserTable SET Active="1" WHERE Email="'.
            $email.'" AND PassHash="'.$hash.'"') or die(mysql_error());
        //TODO make this message styled nicely
        echo 'Your account has been activated, you can now login!';
    }else{
        //TODO make this message styled nicely
        echo 'The url is either invalid or you already have activated your account.';
    }

}else{

    echo 'Invalid action. Please use the link that has been sent to your email.';
}
?>
