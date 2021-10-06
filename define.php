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

switch($z)
{
    case ''     : first();                          break;
    
    case 'sv'   : createcodesv();                    break;
    
    case 'dsp'  : masterdisplay($_POST['m']);       break;
    
    case 'dl'   : codedelete();                      break;
        
    default     :                                   break;
}

function first()
{
    global $user,$mdb,$obj;
    
        echo '<h3 style="height:0px">Define</h3>
              <table width=35% border=0 align="center" cellpadding=5 cellspacing=5 style="border-collapse:collapse">
              <tr>
              <td rowspan=2>Master</td>
              <td rowspan=2>';
        
        $sql = "select code,fname from $mdb.master where grup='mast' order by field(code,'F') desc";
        $obj->selectOption($sql,"lstmaster","code","fname","",175,'masterdisplay()','','');
        echo '<td align="center">Code
              <input type="text" id="txtcode" value=""></td>
              <tr><td align="center">Name
              <input type="text" id="txtname" value=""></td>
              <tr><td colspan=4 align=center><button class="btn btn-danger btn-sm" onclick=createcodesv();>Save</button></td></tr></table>
                   <br><br><table  border=0 align="center" width=50%>
              <tr><td width=100%>
              <div id=divSV width=50%>';
              masterdisplay('F');
              echo '</div></td></tr></table>';        
}

function createcodesv()
{
    global $user,$obj,$mdb;
    
    $m = $_POST['m'];
    $c = $_POST['c'];
    $n = $_POST['n'];
    
    $cnt = $obj->getOne("SELECT count(*) from $mdb.master where code = '$c'");
    if($cnt > 0){
        echo "<div id=divALRT><dialog open>Code already exist!</dialog></div>";
        masterdisplay($m);     
        die;
    }    
    $sql = "insert into $mdb.master(grup,code,fname) values ('$m','$c','$n')";
    $res = $obj->query($sql);
    masterdisplay($m);    
    echo "<div id=divALRT><dialog open>Code Created Successfully</dialog></div>";
}

function masterdisplay($m)
{
    global $user,$obj,$mdb;
    
    $sql = "select id,code,fname from $mdb.master where grup='$m' order by code asc";
    $res = $obj->query($sql);
    
    echo '<table width=100% border=0 class ="table-bordered table-sm">
          <tr class=newhd><td align=center><b>Sl.No</b></td><td align=center><b>Code</b></td><td align=center><b>Name</b></td><td align=center><b>Delete</b></td></tr>';
    
    $n = 0;
    while($row = mysqli_fetch_array($res,MYSQLI_ASSOC))
    {
        $n = $n+1;
        $id = $row['id'];
        echo "<tr><td align=center>".$n."</td>
                  <td align=center>".$row['code']."</td>
                  <td align=left>".$row['fname']."</td>
                  <td align=center>";
        if($user == 'admin'){
            echo "<button class='btn btn-danger btn-sm' onclick=codedelete($id)>Delete</button>";
        }
        echo "</td></tr>";
    
    }
}

function codedelete()
{
    global $user,$obj,$mdb;;
    
    $id = $_POST['id'];
    $m  = $_POST['m'];
     
    $sql = "delete from $mdb.master where id='$id'";
    $res = $obj->query($sql);    
    masterdisplay($m);
    echo "<div id=divALRT><dialog open>Code deleted Successfully</dialog></div>";
}
?>