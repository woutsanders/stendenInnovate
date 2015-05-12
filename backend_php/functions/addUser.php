<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 11-May-15
 * Time: 20:24
 */



function newUser($connection)
{
    $userName = $_POST['user'];
    $time = time();
    $queryInsert = "INSERT INTO speler (UserName, Date+Time, Status) VALUES ($userName, $time, 'waiting')";
    $data = mysqli_query($connection, $queryInsert) or die(mysqli_error($data));
    return $data;
}