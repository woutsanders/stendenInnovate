<?php
/**
 * Created by PhpStorm.
 * User: Beikes
 * Date: 28-4-2015
 * Time: 13:01
 */
require('connect.php');


// newUser word uitgevoerd wanneer alle checks gedaan zijn
function newUser()
{
    $userName = $_POST['user'];
    $time = time();
    $queryInsert = "INSERT INTO speler (Gebruikersnaam, tijd) VALUES ('$userName', $time)";
    $data = mysql_query ($queryInsert)or die(mysql_error());
    if ($data) {
        echo "YOUR REGISTRATION IS COMPLETED!";
    }
}
// signUp bevat alle checks, de newUser functie is hierin genest
function signUp()
{
    $user = $_POST['user'];
//preg_match forceert dat alle characters moeten bestaan uit a-z, A-Z en 0-9
    if (preg_match("#^[a-zA-Z0-9]+$#", $user))
    {
//Checkt DB of de username al bestaat
        if (!empty($user)) {
            $query = mysql_query("SELECT * FROM speler WHERE Gebruikersnaam = '$user'") or die(mysql_error());
            $row = mysql_fetch_array($query);
//Wanneer er niks terug komt uit de query voer dan newUser uit
            if ($row == false) {
                newUser();
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
    signUp();
}
?>