<?php
require_once('bootstrap.php');
function gameCheck()
{   //Query, checkt of er mensen zijn die aan het spelen zijn
    $queryCheck = "SELECT Gebruikersnaam FROM speler WHERE Status = 'playing'";
    $resultCheck = mysql_query($queryCheck) or die(mysql_error());
    if ($resultCheck)
    {
        echo "Er zijn nog mensen aan het spelen, één moment geduld";
    }
    else
    {   //Query, zijn er nog mensen waarvan wij wachten op ready signaal?
        $queryWaiting = "SELECT Gebruikersnaam FROM speler WHERE Status = 'waiting for ready'";
        $resultWaiting = mysql_query($queryWaiting) or die(mysql_error());
        if($resultWaiting)
        {
            //Koppelen van gebruiker aan gebruikersnaam die aan het wachten is
            //Status van gebruiker wijzigen in 'waiting for ready'
            //Query voor inserten, gebruikersnaam = gebruikersnaam van de persoon die iets invuld
            $queryInsert = "INSERT INTO speler (Status) VALUES ('waiting for ready') WHERE Gebruikersnaam = 'xxx'";
        }
        else
        {   //Query, laat alleen de langst wachtende speler zien
            $query = "SELECT Gebruikersnaam FROM speler WHERE Status = 'waiting' ORDER BY tijd ASC LIMIT 1";
            $result = mysql_query($query) or die(mysql_error());
            if ($result)
            {
                while ($rows = mysql_fetch_array($result))
                {
                    echo($rows[0]);
                    echo "<br>";

                    //Status van beide spelers wijzigen naar 'waiting for ready'
                }
            }
            else
            {
                echo "Er is nog geen tegenspeler, één moment geduld";
            }
        }
    }
}
if(isset($_POST['test']))
{
    gameCheck();
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