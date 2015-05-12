<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 11-May-15
 * Time: 20:24
 */



function newUser($connection , $userName)
{

    $time = time();
    $queryInsert = "INSERT INTO `player` (UserName, DateTime, Status) VALUES ('$userName', '$time', 'waiting')";
    $data = mysqli_query($connection, $queryInsert) or die();

    return $data;
}