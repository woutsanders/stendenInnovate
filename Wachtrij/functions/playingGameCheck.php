<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 11-May-15
 * Time: 20:34
 */

function playingGameCheck($connection)
{
    $queryCheck = "SELECT Gebruikersnaam FROM speler WHERE Status = 'playing'";
    $resultCheck = mysql_query($queryCheck) or die(mysql_error());
    if (mysqli_num_rows($queryCheck) >= 1) {
        echo "Er zijn nog mensen aan het spelen, één moment geduld";
        return false;
    } else {
        echo "Er zijn geen mensen aan het spelen.";
        return true;
    }
}