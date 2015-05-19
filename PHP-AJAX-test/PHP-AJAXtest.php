<?php
/**
 * Created by PhpStorm.
 * User: Jeroen
 * Date: 19-Apr-15
 * Time: 19:59
 */
?>

<!DOCTYPE html>
<html>
<head>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $(document).keydown(function(event){
                $("div").html("Key: " + event.which);
            });
            $(document).keyup(function(event){
                $("div").html("Key: " + "undefined");
            });
        });
    </script>
    <script>
        function mouseEvent(nummer){
            document.getElementById("div").innerHTML = nummer;
        }
    </script>


</head>
<body>
Click the buttons:

<button id="button1" onmousedown="mouseEvent(37)" onmouseup="mouseEvent()"  >Links</button>
<button id="button2" onmousedown="mouseEvent(39)" onmouseup="mouseEvent()">Rechts</button>


<div>

</div>
<p id="div"></p>
</body>
</html>
