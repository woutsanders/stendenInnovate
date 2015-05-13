<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 12-May-15
 * Time: 09:36
 */

function changeStatus ($connection , $status, $username)
{
    $queryInsertStatus = "UPDATE player SET Status='$status' WHERE UserName = '$username'";
    $queryUitvoeren = mysqli_query($connection, $queryInsertStatus) or die($connection);
    return $queryUitvoeren;
}
?>