<?php
/**
 * Bootstrapper bestand voor het inladen c.q. prepareren
 * van alle functiebestanden en databasegegevens.
 */

//Stel het absolute pad in voor de applicatie d.m.v. een constante,
//mocht dit nog niet zijn gebeurd.
if (!defined('APP_PATH')) define('APP_PATH', __DIR__);
if (!defined('CSS_PATH')) define('CSS_PATH', __DIR__);

//Laad alle functies in a.d.h.v. de autoloader.
require_once('autoload.php');


//Database connectie op zetten
$server = "localhost";
$userName = "root";
$passWord = "";
$DBName = "project_innovate";

//verbind met de DBMS
$connection = mysqli_connect($server , $userName , $passWord) OR DIE (mysqli_error($connection));
$select_db = mysqli_select_db($connection , $DBName) OR DIE (mysqli_error($select_db));

?>