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
    case ''     : first();              break;
    
    case 'sv'   : createusrsv();        break;
    
    case 'dl'   : usrdelete();          break;
        
    default     :                       break;
}

function first()
{
    global $user,$obj;
    
        echo '<h3 style="height:0px">Create User</h3>
              <table width=35% border=0 align="center" cellpadding=5 cellspacing=5 style="border-collapse:collapse">
              <tr><td align="center">Username
              <input type="text" id="txtcusr" value=""></td>
              <tr><td align="center">Password
              <input type="password" id="txtcpwd" value=""></td>
              <tr><td colspan=2 align=center><button class="btn btn-danger btn-sm" onclick=createusrsv();>Save</button></td></tr></table>
                   <br><br><table  border=0 align="center" width=50%>
              <tr><td width=100%>
              <div id=divSV width=50%>';
              userdisplay();
              echo '</div></td></tr></table>';        
}

function createusrsv()
{
    global $user,$obj,$mdb;
    
    $u = $_POST['u'];
    $p = $_POST['p'];
    
    $cnt = $obj->getOne("SELECT count(*) from $mdb.users where username = '$u'");
    if($cnt > 0){
        echo "<div id=divALRT><dialog open>User already exist!</dialog></div>";
        userdisplay();     
        die;
    }    
    $sql = "insert into $mdb.users(username,password) values ('$u','$p')";
    $res = $obj->query($sql);
    userdisplay();    
    echo "<div id=divALRT><dialog open>User Created Successfully</dialog></div>";
}

function userdisplay()
{
    global $user,$obj,$mdb;
    
    $sql = "select id,username from $mdb.users order by username asc";
    $res = $obj->query($sql);
    
    echo '<table width=100% border=0 class ="table-bordered table-sm">
          <tr class=newhd><td align=center><b>Sl.No</b></td><td align=center><b>User</b></td><td align=center><b>Delete</b></td></tr>';
    
    $n = 0;
    while($row = mysqli_fetch_array($res,MYSQLI_ASSOC))
    {
        $n = $n+1;
        $id = $row['id'];
        echo "<tr><td align=center>".$n."</td>
                  <td align=left>".$row['username']."</td>
                  <td align=center>";
        if($row['username'] != 'admin'){
            echo "<button class='btn btn-danger btn-sm' onclick=usrdelete($id)>Delete</button>";
        }
        echo "</td></tr>";
    
    }
}

function usrdelete()
{
    global $user,$obj,$mdb;;
    
    $id = $_POST['id'];
     
    $sql = "delete from $mdb.users where id='$id'";
    $res = $obj->query($sql);    
    userdisplay();
    echo "<div id=divALRT><dialog open>User deleted Successfully</dialog></div>";
}
?>