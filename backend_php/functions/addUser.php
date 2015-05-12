<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 11-May-15
 * Time: 20:24
 */



function newUser($connection , $userName)
{

    $time = date(DATE_ATOM);
    $queryInsert = "INSERT INTO `player` (UserName, DateTime, Status) VALUES ('$userName', '$time', 'waiting')";
    $data = mysqli_query($connection, $queryInsert) or die($connection);

    return $data;
}