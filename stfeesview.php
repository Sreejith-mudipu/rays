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
if($z == ""){ if(isset($_GET['z']) != '') {$z = $_GET['z'];} }
switch($z)
{
    case ''       : first();              break;
    
    case 'srch'   : srchstudent();        break;    
    
    case 'fees'   : stfeesview();         break;
        
    default       :                       break;
}

function first()
{
    global $user,$mdb,$obj;
    
    $Y = date('Y');
    
    echo '<h5 style="height:0px">View Collection</h5>
          <table width=45% border=0 align="center" cellpadding=5 cellspacing=5 style="border-collapse:collapse">';
 
    echo '<tr height=80px>
          <td><input type=number id=txtfyr value ="'.$Y.'" min=2018 style="width:80px"></td>
          <td align="center">Class</td><td>';
        
    $sql = "select code,fname from $mdb.master where grup='C'";
    $obj->selectOption($sql,"txtclsrch","code","fname","",175,'clearsrchdiv()','','');
    echo "</td>
          <td>Name</td>
          <td><input type=text id=txtsrchnm></td>
          <td><button class='btn btn-danger btn-sm' onclick=srchstudent('stfeesview');>Search</button></td></tr></table>";
    echo "<table width=85% border=0 align=center cellpadding=5 cellspacing=5 style='border-collapse:collapse'>
          <tr><td><div id=divSR class='container'></div></td></tr>";
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
        echo "<td align=center><a href=# onclick=viewfees($stno);>".$sname."</a></td>";
        $n = $n+1;
    }
    echo"</table></div>";   
}


function stfeesview()
{
    global $obj,$mdb,$sdb;
    
    $stno = $_GET['stno'];
    $fy   = $_GET['feeyr'];
    $cls  = $_GET['cls'];
            
    echo "<br><div class='container'>";
    echo '<table border=1 align=center cellpadding=3 cellspacing=4 width=75% style="border-collapse:collapse">
          <tr class=newhd><td colspan=5 align=center><b>Student Fee Summary</b></td></tr>';

    $stdnm = $obj->getOne("select stname from $sdb.stdetail where stno='$stno'"); 
    $clsnm = $obj->getOne("select fname from $mdb.master where code='$cls' and grup='C'"); 
    echo "<tr><td align=left>Sudent Name</td><td colspan=2>$stdnm</td></tr>";
    echo "<tr><td align=left>Class</td><td colspan=2>$clsnm</td></tr>";
    
    $sql = "select code,fname,grup from $mdb.master where grup in('F','D') order by field(grup,'D'),code asc";
    $res = $obj->query($sql);   
    $pfees = ""; $tot = 0; $gtot = 0;
    while($row = mysqli_fetch_array($res,MYSQLI_ASSOC))
    {  
        if($row['grup'] != $pfees){
            $feetyp = $obj->getOne("select fname from $mdb.master where code='".$row['grup']."' and grup='mast'");
            if($tot > 0)
            {
                echo "<tr><td align=right>Total</td><td align=right>".number_format($tot,2,'.','')."</td><td></td></tr>";
                $tot = 0; 
            }
            echo "<tr style='font-weight:bold' align=center><td>$feetyp</td><td>Amount</td><td>Receipt No.</td></tr>";
        }
        $resf    = $obj->sqlrows("select sum(amount) as amt,group_concat(rcptno) as rcpts from $sdb.fees where feeyr='$fy' and stno='$stno' and fcomp='".$row['code']."' and cflag is null");
        $rcvdamt = (double)$resf['amt'];
        $rcpts   = $resf['rcpts'];
        
        echo "<tr><td>".$row['fname']."</td>
                  <td align=right>".number_format($rcvdamt,2,'.','')."</td>
                  <td align=left>".$rcpts."</td></tr>";
        $tot  = $tot  + $rcvdamt;
        $gtot = $gtot + $rcvdamt;
        
        $pfees = $row['grup'];
    }
    echo "<tr><td align=right>Total</td><td align=right>".number_format($tot,2,'.','')."</td><td></td></tr>";
    echo "<tr style='font-weight:bold'><td align=right>Grand Total</td><td align=right>".number_format($gtot,2,'.','')."</td><td></td></tr>";
    echo "</table></div>";
}
?>