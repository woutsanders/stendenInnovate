<?php
/**
 * Created by PhpStorm.
 * User: Beikes
 * Date: 28-4-2015
 * Time: 13:01
 */
require_once('bootstrap.php');



// signUp bevat alle checks, de newUser functie is hierin genest
$data = 0;
$username = 0;
if(isset($_POST['submit']))
{
    $username = $_POST['user'];
    $data = signUp($connection,$username);

}


    if ($data)
    {
        $resultcheck = playingGameCheck($connection);
        if ($resultcheck == false)
        {
            echo "Er zijn nog mensen aan het spelen, één moment geduld";
        }
        else
        {   //Query, zijn er nog mensen waarvan wij wachten op ready signaal?
           $resultWaiting = checkReadySignal($connection);
            if ($resultWaiting == true)
            {
                //Koppelen van gebruiker aan gebruikersnaam die aan het wachten is
                //Status van gebruiker wijzigen in 'waiting for ready'
                //Query voor inserten, gebruikersnaam = gebruikersnaam van de persoon die iets invuld
                $queryInsertStatus = "INSERT INTO speler (Status) VALUES ('waiting for ready') WHERE Gebruikersnaam = $userName";
                $queryUitvoeren = mysqli_query($connection , $queryInsertStatus) or die(mysql_error());
                if ($queryUitvoeren)
                {
                    //Ready knop tonen
                }
                else
                {
                    echo "Er is een fout met het bewerken van de database";
                }
            }
            else
            {
                $result = longestWaiting($connection);
                if ($result)
                {
                    while ($rows = mysql_fetch_array($result))
                    {
                        echo($rows[0]);
                        echo "<br>";

                        //Status van speler wijzigen naar 'waiting for ready'
                        $status = "waiting for ready";
                        $queryUitvoeren2 = changeStatus($connection , $status, $username);
                        if ($queryUitvoeren2)
                        {
                            //Ready knop tonen
                        }
                        else
                        {
                            echo "Er is een fout met het bewerken van de database";
                        }
                    }
                }
                else
                {
                    echo "Er is nog geen tegenspeler, één moment geduld";
                }
            }
        }

}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Wachtrij test</title>
<body>
<form action="username.php" method="post">
    <table>
        <tr>
            <td>Username:</td>
            <td><input type="text" name="user" /></td>
        </tr>
        <input type="submit" name="submit" value="Submit">
</form>
<form action="#" method="post">
    <input type="submit" name="test" value="test">
</body>
</head>
</html>