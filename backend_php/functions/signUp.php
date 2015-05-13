<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 11-May-15
 * Time: 20:38
 */

function signUp($connection , $user)
{
        //preg_match forceert dat alle characters moeten bestaan uit a-z, A-Z en 0-9
    if (preg_match("#^[a-zA-Z0-9]+$#", $user))
    {
        //Checkt DB of de username al bestaat
        if (!empty($user)) {
            $SQLquery = "SELECT * FROM player WHERE UserName = '$user'";

            $queryResult = mysqli_query($connection, $SQLquery) or die(mysqli_error($connection));

            //Wanneer er niks terug komt uit de query voer dan newUser uit
            if (mysqli_num_rows($queryResult) ==0) {

                // newUser word uitgevoerd wanneer alle checks gedaan zijn
                $data = newUser($connection , $user);
                return $data;

            } else {
                echo "Sorry, this username already exists!" ;
            }
        } else {
            echo "Please fill in a username!";
        }
    }
    else {
        echo "Invalid characters!";
    }
}
?>