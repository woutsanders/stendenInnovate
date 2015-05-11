<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 28-Apr-15
 * Time: 14:12
 */



    $db_server = "localhost";
    $db_username = "root";
    //$db_username = "inf1a_inf1a";
    $db_pass = "";
    //$db_pass = '$tendenInf1@';
    $db_name = "project_innovate";
    //$db_name = "inf1a_Stenden_Innovatie";
    $db_link = mysqli_connect($db_server , $db_username , $db_pass) OR DIE (mysqli_error());
    $db = mysqli_select_db($db_link , $db_name) OR DIE (mysqli_error());



$SQLquerycheck = "SELECT * FROM `speler` WHERE Status = 'playing'";
$queryresultcheck = mysqli_query($db_link , $SQLquerycheck) OR DIE (mysqli_error());


if (mysqli_num_rows($queryresultcheck) == 1){
    $SQLquery = "SELECT * FROM `speler` WHERE Status = 'wachtend' ORDER BY `Date+Time` ASC LIMIT 1";
    $queryresult = mysqli_query($db_link , $SQLquery) OR DIE (mysqli_error());

} else{
    $SQLquery = "SELECT * FROM `speler` WHERE Status = 'wachtend' ORDER BY `Date+Time` ASC LIMIT 2";
    $queryresult = mysqli_query($db_link , $SQLquery) OR DIE (mysqli_error());

}

//$SQLquery = "SELECT * FROM `speler` WHERE Status = 'wachtend'";
//$SQLquery = "SELECT * FROM `speler` WHERE Status = 'wachtend' ORDER BY `Date+Time` ASC LIMIT 2";
//$SQLquery = "SELECT * FROM `speler`";
//$queryresult = mysqli_query($db_link , $SQLquery) OR DIE (mysqli_error());


echo "<table>";
echo "<tr>";
echo "<th>Gebruikersnaam</th>";
echo "<th>Score</th>";
echo "<th>Date + Time</th>";
echo "<th>Status</th>";
echo "</tr>";

while ($gegevens = mysqli_fetch_assoc($queryresult)){

    echo "<tr>";
    echo "<td>" . $gegevens['GebruikersNaam'] . "</td>";
    echo "<td>" . $gegevens['Score'] . "</td>";
    echo "<td>" . $gegevens['Date+Time'] . "</td>";
    echo "<td>" . $gegevens['Status'] . "</td>";
    echo "</tr>";

}


mysqli_close($db_link);
?>

