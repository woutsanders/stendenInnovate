<?php
/**
 * Created by PhpStorm.
 * User: Beikes
 * Date: 28-4-2015
 * Time: 13:01
 */
require('connect.php');


// newUser word uitgevoerd wanneer alle checks gedaan zijn
function newUser()
{
    $userName = $_POST['user'];
    $time = time();
    $queryInsert = "INSERT INTO speler (Gebruikersnaam, tijd) VALUES ('$userName', $time)";
    $data = mysql_query($queryInsert) or die(mysql_error());
    if ($data) {
        echo "U wordt in de wachtrij geplaatst, één moment geduld alstublieft.";
        $queryCheck = "SELECT Gebruikersnaam FROM speler WHERE Status = 'playing'";
        $resultCheck = mysql_query($queryCheck) or die(mysql_error());
        if ($resultCheck) {
            echo "Er zijn nog mensen aan het spelen, één moment geduld";
        } else {   //Query, zijn er nog mensen waarvan wij wachten op ready signaal?
            $queryWaiting = "SELECT Gebruikersnaam FROM speler WHERE Status = 'waiting for ready'";
            $resultWaiting = mysql_query($queryWaiting) or die(mysql_error());
            if ($resultWaiting) {
                //Koppelen van gebruiker aan gebruikersnaam die aan het wachten is
                //Status van gebruiker wijzigen in 'waiting for ready'
                //Query voor inserten, gebruikersnaam = gebruikersnaam van de persoon die iets invuld
                $queryInsertStatus = "INSERT INTO speler (Status) VALUES ('waiting for ready') WHERE Gebruikersnaam = $userName";
                $queryUitvoeren = mysql_query($queryInsertStatus) or die(mysql_error());
                if ($queryUitvoeren) {
                    //Ready knop tonen
                } else {
                    echo "Er is een fout met het bewerken van de database";
                }
            } else {   //Query, laat alleen de langst wachtende speler zien
                $query = "SELECT Gebruikersnaam FROM speler WHERE Status = 'waiting' ORDER BY tijd ASC LIMIT 1";
                $result = mysql_query($query) or die(mysql_error());
                if ($result) {
                    while ($rows = mysql_fetch_array($result)) {
                        echo($rows[0]);
                        echo "<br>";

                        //Status van beide spelers wijzigen naar 'waiting for ready'
                        $queryInsertStatus2 = "INSERT INTO speler (Status) VALUES ('waiting for ready') WHERE Gebruikersnaam = $userName";
                        $queryUitvoeren2 = mysql_query($queryInsertStatus2) or die(mysql_error());
                        if ($queryUitvoeren2) {
                            //Ready knop tonen
                        } else {
                            echo "Er is een fout met het bewerken van de database";
                        }
                    }
                } else {
                    echo "Er is nog geen tegenspeler, één moment geduld";
                }
            }
        }
    }
// signUp bevat alle checks, de newUser functie is hierin genest
    function signUp()
    {
        $user = $_POST['user'];
//preg_match forceert dat alle characters moeten bestaan uit a-z, A-Z en 0-9
        if (preg_match("#^[a-zA-Z0-9]+$#", $user)) {
//Checkt DB of de username al bestaat
            if (!empty($user)) {
                $query = mysql_query("SELECT * FROM speler WHERE Gebruikersnaam = '$user'") or die(mysql_error());
                $row = mysql_fetch_array($query);
//Wanneer er niks terug komt uit de query voer dan newUser uit
                if ($row == false) {
                    newUser();
                } else {
                    echo "Sorry, this username already exists!";
                }
            } else {
                echo "Please fill in a username!";
            }
        } else {
            echo "Invalid characters!";
        }
    }

    if (isset($_POST['submit'])) {
        signUp();
    }
}
?>