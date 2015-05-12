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
    $username = $_POST['forminput'];

    $data = signUp($connection,$username);
echo $data;
    if ($data)
    {
        $resultcheck = playingGameCheck($connection);
        echo $resultcheck;
        if ($resultcheck == false)
        {
            echo "Er zijn nog mensen aan het spelen, één moment geduld";
        }
        else
        {   //Query, zijn er nog mensen waarvan wij wachten op ready signaal?
           $resultWaiting = checkReadySignal($connection);
            echo $resultWaiting;
            if ($resultWaiting == true)
            {
                //Koppelen van gebruiker aan gebruikersnaam die aan het wachten is
                //Status van gebruiker wijzigen in 'waiting for ready'
                //Query voor inserten, gebruikersnaam = gebruikersnaam van de persoon die iets invuld
                $status = "waiting for ready";
                $queryUitvoeren = changeStatus($connection, $status, $username);
                echo $queryUitvoeren;
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
                echo $result;
                if ($result)
                {
                    while ($rows = mysqli_fetch_array($result))
                    {
                        echo($rows[0]);
                        echo "<br>";

                        //Status van speler wijzigen naar 'waiting for ready'
                        $status = "waiting for ready";
                        $queryUitvoeren = changeStatus($connection , $status, $username);
                        echo $queryUitvoeren;
                        if ($queryUitvoeren)
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

