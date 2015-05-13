<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 11-May-15
 * Time: 20:46
 */

function checkReadySignal ($connection){
    $queryWaiting = "SELECT UserName FROM player WHERE Status = 'waiting for ready'";
    $resultWaiting = mysqli_query($connection , $queryWaiting) or die(mysqli_error($connection));
    if (mysqli_num_rows($resultWaiting) >=1){
        return true;
    }else
    {
        return false;
    }
}