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
$username = $_POST['user'];
if(isset($_POST['submit']))
{
    $data = signUp($connection,$_POST['user']);
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
                $queryUitvoeren = mysql_query($queryInsertStatus) or die(mysql_error());
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
                        changeStatus($connection , $status, $username);
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