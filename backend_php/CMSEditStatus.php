<?php
/**
 * Created by PhpStorm.
 * User: Beikes
 * Date: 12-5-2015
 * Time: 15:12
 */
require_once ("bootstrap.php");
if(isset($_POST['manipulateStatus']))
{
    $username = $_POST['userName'];
    $newStatus = $_POST['pietjepuk'];

    //Query om de record te editten
    $queryEdit = "UPDATE player SET Status='$newStatus' WHERE UserName = '$username'";
    $data = mysqli_query($connection, $queryEdit) or die(mysqli_error($connection));
    return $data;
}
?>