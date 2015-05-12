<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>CMS</title>
<body>
Status manipuleren


<form action="CMSEditStatus.php" method="post">
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