<?php
if (!isset($_SESSION)) session_start();	
if($_SESSION["login_user"]==""){header("location: logout.php");	exit();}
error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);	

$user = $_SESSION['login_user'];
$mdb = $_SESSION['mdb'];
$sdb = $_SESSION['sdb'];

include("config.php");
include("lib.php");

$obj = new sql_connect();  
$z = ''; if(isset($_POST['z']) != ''){ $z = $_POST['z']; }
$t = ''; if(isset($_POST['t']) != ''){ $t = $_POST['t']; }

if($t != ''){
    $z = $t;
}
switch($z)
{
    case ''       : first();              break;
    
    case 'srch'   : srchstudent();        break;    
        
    default       :                       break;
}

function first()
{
    global $user,$mdb,$obj;
    
        echo '<h5 style="height:0px">Edit Studet Details</h5>
              <table width=45% border=0 align="center" cellpadding=5 cellspacing=5 style="border-collapse:collapse">';
        
       echo '<tr height=80px><td align="center">Class</td>
             <td>';
        
        $sql = "select code,fname from $mdb.master where grup='C'";
        $obj->selectOption($sql,"txtclsrch","code","fname","",175,"clearsrchdiv('srchstudent')",'','');
        echo "</td>
              <td>Name</td>
              <td><input type=text id=txtsrchnm></td>
              <td><button class='btn btn-danger btn-sm' onclick=srchstudent('srchstudent');>Search</button></td></tr></table>";
        echo "<table width=85% border=0 align=center cellpadding=5 cellspacing=5 style='border-collapse:collapse'>
              <tr><td><div id=divSR class='container'></div></td></tr>
              <tr><td><div id=divCF class='container'></div></td></tr>";
}

function srchstudent()
{
    global $user,$obj,$mdb,$sdb;

    $cls = $_POST['cls'];
    $snm = $_POST['snm'];
    
    $str = ''; if($snm != ''){ $str = "and stname like '%$snm%'"; }
    
    $sql= "select stno,concat(rollno,' - ',stname) as sname from $sdb.stdetail where currentclass='$cls' $str order by stname asc";
    $res = $obj->query($sql);
    
    echo "<div class='container'>";
    echo "<table border=0 class='table table-bordered table-sm'>
          <tr><td colspan=4 align=center><font color=red size=4>Roll No - Name</font></td></tr>
          <tr>";
    
    $n = 0;
    while($row = mysqli_fetch_array($res,MYSQLI_ASSOC))
    {
        $stno  = $row['stno'];
        $sname = $row['sname'];
        if($n == 4) { echo "</td></tr><tr>"; $n = 0; }
        echo "<td align=center><a href=# onclick=editstudent($stno);>".$sname."</a></td>";
        $n = $n+1;
    }
    echo"</table></div>";   
    
}
?>