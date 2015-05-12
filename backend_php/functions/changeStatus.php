<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 12-May-15
 * Time: 09:36
 */

function changeStatus ($connection , $status, $username)
{
    $queryInsertStatus2 = "INSERT INTO speler (Status) VALUES ($status) WHERE Gebruikersnaam = $userName";
    $queryUitvoeren2 = mysql_query($queryInsertStatus2) or die(mysql_error());
    return $queryUitvoeren2;
}