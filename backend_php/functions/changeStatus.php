<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 12-May-15
 * Time: 09:36
 */

function changeStatus ($connection , $status, $username)
{
    $queryInsertStatus = "INSERT INTO speler (Status) VALUES ($status) WHERE Gebruikersnaam = $username";
    $queryUitvoeren = mysqli_query($connection, $queryInsertStatus) or die(mysql_error());
    return $queryUitvoeren;
}