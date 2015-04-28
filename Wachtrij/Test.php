<?php
/**
 * Created by PhpStorm.
 * User: Beikes
 * Date: 28-4-2015
 * Time: 13:01
 */
require('connect.php');

function NewUser()
{
    $userName = $_POST['user'];
    $queryInsert = "INSERT INTO speler (Gebruikersnaam) VALUES ('$userName')";
    $data = mysql_query ($queryInsert)or die(mysql_error());
        if ($data) {
            echo "YOUR REGISTRATION IS COMPLETED!";
    }
}

/**
 *
 */
function SignUp()
{
    $user = $_POST['user'];
    if (preg_match("#^[a-zA-Z0-9]+$#", $user))
    {
        if (!empty($user)) {
            $query = mysql_query("SELECT * FROM speler WHERE Gebruikersnaam = '$user'") or die(mysql_error());
            $row = mysql_fetch_array($query);
            if ($row == false) {
                NewUser();
            } else {

                echo "Sorry, this username already exists!";
            }
        } else {
            echo "Please fill in a username!";
        }
    }
    else {
        echo "Invalid characters!";
    }
}
if(isset($_POST['submit']))
{
    SignUp();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
    <html>
<head>
    <title>Wachtrij test</title>
    <body>
<form action="#" method="post">
    <table>
        <tr>
            <td>Username:</td>
            <td><input type="text" name="user" /></td>
        </tr>
        <input type="submit" name="submit" value="Submit">
</body>
</head>
</html>