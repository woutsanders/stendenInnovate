<?php
require('connect.php');
function gameCheck()
{
$query = "SELECT Gebruikersnaam, tijd FROM speler WHERE Status = 'Wachtrij' ORDER BY tijd ASC";
$result = mysql_query($query) or die(mysql_error());
while ($rows = mysql_fetch_array ($result))
{
echo ($rows[0]);
    echo ($rows[1]);
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