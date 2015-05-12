<?php
/**
 * Created by PhpStorm.
 * User: Beikes
 * Date: 12-5-2015
 * Time: 11:31
 */
function checkHighScore ($connection)
{
    $queryTop5 = "SELECT UserName, Score FROM player ORDER BY Score ASC";
    $result = mysqli_query($connection, $queryTop5) or die(mysqli_error($result));
    if (mysqli_num_rows($result) >= 1) {
        while ($rows = mysqli_fetch_array($result)) {
            echo($rows[0]);
            echo "  ";
            echo($rows[1]);
            echo "<br>";
        }
    }
}