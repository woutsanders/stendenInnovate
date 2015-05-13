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
    $query = "SELECT UserName FROM player WHERE Status = 'waiting' ORDER BY DateTime ASC LIMIT 1";
    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
    return $result;
}