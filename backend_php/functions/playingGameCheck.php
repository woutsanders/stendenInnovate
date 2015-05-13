<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 11-May-15
 * Time: 20:34
 */

function playingGameCheck($connection)
{
    $queryCheck = "SELECT UserName FROM player WHERE Status = 'playing'";
    $resultCheck = mysqli_query($connection, $queryCheck) or die($connection);
    if (mysqli_num_rows($resultCheck) >= 1) {
        echo "Er zijn nog mensen aan het spelen, één moment geduld";
        return false;
    } else {
        echo "Er zijn geen mensen aan het spelen.";
        return true;
    }
}