<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 12-May-15
 * Time: 09:28
 */


function longestWaiting ($connection)
{
//Query, laat alleen de langst wachtende speler zien
    $query = "SELECT Gebruikersnaam FROM speler WHERE Status = 'waiting' ORDER BY tijd ASC LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    return $result;
}