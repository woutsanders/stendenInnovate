<?php

//Verkrijg alle bestanden in de map...
$functionFiles = scandir(APP_PATH . "/functions");

foreach ($functionFiles as $filename) {
    
    //Check of bestand .php extensie heeft (dirty).
    if (stristr($filename,'.php')) {
        
        //Split extensie van bestandsnaam...
        $fileArr = explode('.',$filename);
        
        //PHP Builtin: check of functie al is gedefinieërd
        if (!function_exists($fileArr[0])) {
            
            //Zorg ervoor dat het bestand maar één keer wordt ge-include...
            require_once(__DIR__ . '/functions/' . strtolower($filename));
        }
    }
}