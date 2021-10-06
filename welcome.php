<link rel="stylesheet" href="sch.css" type="text/css">
<link href="fontawesome/css/all.css" rel="stylesheet">
<link rel="stylesheet" href="css/bootstrap.css" type="text/css">
<script type="text/javascript" src="script.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php

if (!isset($_SESSION)) session_start();	
if($_SESSION["login_user"]==""){header("location: logout.php");	exit();}
$mdb =  $_SESSION['mdb'];
$sdb =  $_SESSION['sdb'];

error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);	
$user = $_SESSION['login_user'];

include("config.php");
include("lib.php");

$obj = new sql_connect();  

$z = ''; if(isset($_GET['z']) != ''){ $z = $_GET['z']; }

switch($z)
{
    case ''  : first();        break;
        
    default  :                 break;
}

function first()
{
    global $user,$obj,$mdb;
    
    $dt = date('Y-m-d');
    
    $strusr =  "Welcome : ".$user;
    
    echo '<div class="tab">';
    $sql = "select modulename,pgname from $mdb.module order by odr asc";
    $res = $obj->query($sql);
    while($row = mysqli_fetch_array($res,MYSQLI_ASSOC))
    {
        echo "<button class=tablinks onclick=moduledisp(event,'".$row['pgname']."');>".$row['modulename']."</button>";
    }  
    echo '<button  onclick=window.location="logout.php";>Logout</button>
          <div style="text-align:end;text-align:end;padding-top:8px;padding-right:200px;color:gold">'.$strusr.'</div>
          </div>';   
    echo"";
    echo '<div id="pgshow">';
    echo '</div>';
}
?>
