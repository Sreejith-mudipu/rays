<?php
if (!isset($_SESSION)) session_start();	
if($_SESSION["login_user"]==""){header("location: logout.php");	exit();}
error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);	
$user = $_SESSION['login_user'];

include("config.php");
include("lib.php");

$obj = new sql_connect();  
$z = ''; if(isset($_GET['z']) != ''){ $z = $_GET['z']; }

switch($z)
{
    case ''     : first();              break;

    default     :                       break;
}

function first()
{
    global $user,$obj;
    
    echo '<h3>Reports</h3>';
    echo "<button onclick=genreport();>Generate</button>";
}
