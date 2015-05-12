<?php
/**
 * Created by PhpStorm.
 * User: Beikes
 * Date: 12-5-2015
 * Time: 14:30
 */
require_once ("bootstrap.php");
if(isset($_POST['manipulateStatus']))
{
    $username = $_POST['userName'];
    $newStatus = $_POST['pietjepuk'];

    //Query om de record te editten
    $queryEdit = "UPDATE player SET Status='$newStatus' WHERE UserName = '$username'";
    $data = mysqli_query($connection, $queryEdit) or die(mysqli_error($connection));
    var_dump($data);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>CMS test</title>
<body>
Status manipuleren


<form action="#" method="post">
    Username:<br /><input type="text" name="userName"></br>
    Nieuwe status:<br /><select name = "pietjepuk">
        <option value="Ready">Ready</option>
        <option value="Waiting for ready">Waiting for ready</option>
        <option value="Waiting">Waiting</option>
        <option value="Playing">Playing</option>
        <option value="Finished">Finished</option>
    </select>
    <input type="submit" name="manipulateStatus" value="test">
</form>
</body>
</head>
</html>