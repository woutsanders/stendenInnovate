<?php
/**
 * Created by PhpStorm.
 * User: Beikes
 * Date: 12-5-2015
 * Time: 12:10
 */

function waitingForReadyToReady ($connection)
{

        $queryInsert = "INSERT INTO player (Status) WHERE UserName = 'xxx' VALUES ('ready')";
        $data = mysqli_query($connection, $queryInsert) or die(mysqli_error($data));
        return $data;
}