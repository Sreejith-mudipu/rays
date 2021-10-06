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
    
    case 1        : admfees();            break;    
    
    case 2        : yearlyfees();         break;    
        
    case 3        : daycare();            break;
    
    case 4        : admfeesv();           break;   
    
    case 5        : yrfeesv();            break;   
    
    case 6        : daycaresv();          break;
        
    default       :                       break;
}

function first()
{
    global $user,$mdb,$obj;
    
        echo '<h5 style="height:0px">Fee Collection</h5>
              <table width=45% border=0 align="center" cellpadding=5 cellspacing=5 style="border-collapse:collapse">';
        
        $style = "style= 'height: 25px;width: 25px;border-radius: 50%'";
        //<input type=radio $style name=rad id=rad value=1 checked onclick=clearsrchdiv();>Admission Fees&emsp;
        echo "<tr style='font-weight:bold'><td colspan=5 align=center>
              <label id=divFTYP style='display:inline'>
                                                       <input type=radio $style name=rad id=rad value=2 checked onclick=clearsrchdiv();>Regular&emsp;
                                                       <input type=radio $style name=rad id=rad value=3         onclick=clearsrchdiv();>Day Care</label></td></tr>";

       echo '<tr height=80px><td align="center">Class</td>
             <td>';
        
        $sql = "select code,fname from $mdb.master where grup='C'";
        $obj->selectOption($sql,"txtclsrch","code","fname","",175,"clearsrchdiv('srchstdfees')",'','');
        echo "</td>
              <td>Name</td>
              <td><input type=text id=txtsrchnm></td>
              <td><button class='btn btn-danger btn-sm' onclick=srchstudent('srchstdfees');>Search</button></td></tr></table>";
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

function admfees()
{
    global $user,$obj,$mdb,$sdb,$z;
    
    $stno = $_POST['stno'];

    $row = $obj->sqlrows("select stname,currentclass,stno from $sdb.stdetail where stno ='$stno'");

    echo "<table width=50%  align=center class='table-bordered table-sm'  id='afeestable' cellpadding=5 cellspacing=5>";
    echo '<tr><td colspan=2><b>'.$row['stname'].' - '.$row['stno'].' - '.$row['currentclass'].'</b></td></tr>';//Admission Fees
    echo "<tr class=newhd><th>Fees Component</th><th>Amount</th></tr>";

    $sql = "select fcomp,sum(amount) as amt from $sdb.admfees where stno='$stno' group by fcomp order by id asc";
    $res = $obj->query($sql);   
    $i   = 0;
   while($row = mysqli_fetch_array($res,MYSQLI_ASSOC))
   {  
      echo "<td>";      
      $sql = "select code,fname from $mdb.master where grup='F'";
      $obj->selectOption($sql,"R".$i."C1","code","fname",$row['fcomp'],250,'',true,'');
      echo "<td><input id='R".$i."C2' name='R".$i."C2' type='text' value='".$row['amt']."' disabled>
                <input id='R".$i."C3' name='R".$i."C3' type='hidden' value='1' disabled></td></tr>";
      $i = $i + 1;
   }
   echo '<tr id = "admf">';
   echo "<td>";
   $sql = "select code,fname from $mdb.master where grup='F'";
   $obj->selectOption($sql,"R".$i."C1","code","fname","",250,'','',''); 
   echo "<td><input type=text id='R".$i."C2' name='R".$i."C2'>
             <input id='R".$i."C3' name='R".$i."C3' type='hidden' value='' disabled></td>";

   echo "</td></tr></table><input type=hidden id='feeTabRowCnt' value=$i>";            
   echo "<table border=0 width=50% align=center><tr><td align=right>
         <button class='btn btn-danger btn-sm' onclick=TableRowAdd('afeestable','admf','feeTabRowCnt');>+</button>";
   echo '</td></tr></table>';
   echo "<p align=center><button class='btn btn-danger btn-sm' onclick=admfeesv($stno);>Save</button></p>";  
   
   echo "<br>";
   $sqlr = "select fcomp,sum(amount) as amt,rcptno,date_format(rcptdate,'%d-%m-%Y') as rdt from $sdb.admfees where stno='$stno' and cflag is null group by rcptno order by id desc";
   $resr = $obj->query($sqlr);   

   echo '<table border=0 class="table table-bordered table-sm">
         <tr><td colspan=4>Receipt Date - Receipt No.</td></tr>';
   $j = 0;
   while($rowr = mysqli_fetch_array($resr,MYSQLI_ASSOC))
   {  
        $rno = $rowr['rcptno'];
        $rdt = $rowr['rdt'];
        if($j == 4) { echo "</td></tr><tr>"; $j = 0; }
        echo "<td align=center><a href=# onclick=feeprint($stno,1,'','$rno','$rdt');>".$rdt." - ".$rno."</a></td>";
        $j = $j+1;
   }
   echo "</table>";
}

function yearlyfees()
{
    global $user,$obj,$mdb,$sdb,$z;
    
    $Y = date('Y'); if(isset($_POST['y']) != ''){ $Y = $_POST['y']; }
    
    $stno = $_POST['stno'];
    $stnm = $obj->getOne("select stname from $sdb.stdetail where stno ='$stno'");        
    echo "<b>Name :</b>".$stnm;
    
    echo "<p align=center>Year<input type=number id=txtfyr value ='$Y' min=2018 style='width:80px' onclick=yrfeechng($stno,2); onchange=yrfeechng($stno,2);></p>";
    echo "<table width=50%  align=center class='table-bordered table-sm'  id='yrfeestable' cellpadding=5 cellspacing=5>";
    echo "<tr class=newhd align=center><th>Fees Component</th><th>Amount Received</th><th>Collect</th></tr>";

    $sql = "select code,fname from $mdb.master where grup like 'F%' order by code asc";
    $res = $obj->query($sql);   
    $i   = 0;
   while($row = mysqli_fetch_array($res,MYSQLI_ASSOC))
   {  
    $i = $i+1;
    $rcvdamt = (double)$obj->getOne("select sum(amount) from $sdb.fees where feeyr='$Y' and stno='$stno' and fcomp='".$row['code']."'");
    echo "<td><input id='R".$i."C1' name='R".$i."C1' type='hidden' min=1 value='".$row['code']."'>".$row['fname']."</td>";
    echo "<td align=right>".number_format($rcvdamt,2,'.','')."</td>
          <td><input id='R".$i."C2' name='R".$i."C2' type='number' min=1 value=''></td></tr>";
   }
   echo "</table><br>
         <p align=center><button class='btn btn-danger btn-sm' onclick=yrfeesv($stno);>Save</button></p>";   
   
   echo "<br>";
   $sqlr = "select fcomp,sum(amount) as amt,rcptno,date_format(rcptdate,'%d-%m-%Y') as rdt 
           from $sdb.fees 
           where stno='$stno' 
           and feeyr='$Y' 
           and cflag is null 
           and fcomp like 'F%'
           group by rcptno order by id desc";
   $resr = $obj->query($sqlr);   

   echo '<table border=0 class="table table-bordered table-sm">
         <tr><td colspan=4>Receipt Date - Receipt No.</td></tr>';
   $j = 0;
   while($rowr = mysqli_fetch_array($resr,MYSQLI_ASSOC))
   {  
        $rno = $rowr['rcptno'];
        $rdt = $rowr['rdt'];
        if($j == 4) { echo "</td></tr><tr>"; $j = 0; }
        echo "<td align=center><a href=# onclick=feeprint($stno,2,'$Y','$rno','$rdt');>".$rdt." - ".$rno."</a></td>";
        $j = $j+1;
   }
   echo "</table>";
}


function daycare()
{
    global $obj,$mdb,$sdb,$user; 
    
    $Y = date('Y'); if(isset($_POST['y']) != ''){ $Y = $_POST['y']; }
    
    $stno = $_POST['stno'];
    $stnm = $obj->getOne("select stname from $sdb.stdetail where stno ='$stno'");        
    echo "<b>Name :</b>".$stnm;
    
    echo "<p align=center>Year<input type=number id=txtfyr value ='$Y' min=2018 style='width:80px' onclick=yrfeechng($stno,3); onchange=yrfeechng($stno,3);>";
    echo "<p></p>";
    echo '<table border=1 width=50% align=center class="table-bordered table-sm">
            <tr class=newhd align=center><th>Month</th>
            <th>Amount Received</th>
            <th>Collect</th></tr>';    

    $sql = "select code,fname from $mdb.master where grup like 'D%' order by code asc";
    $res = $obj->query($sql);
    while($row = mysqli_fetch_array($res,MYSQLI_ASSOC))
    {
        $num = (int)substr($row['code'],1,2);
        
        $rcvdamt = (double)$obj->getOne("select sum(amount) from $sdb.fees where feeyr='$Y' and stno='$stno' and fcomp='".$row['code']."'");
        echo "<tr><td><input type=hidden style='text-align: right;' id=txtc$num value='".$row['code']."'>".$row['fname']."</td>
                  <td align=right>".number_format($rcvdamt,2,'.','')."</td>
                  <td align=center><input type=number style='text-align: right;' id=txtf$num  min=1 value=></td></tr>";
    }
    echo '</table>';
    echo "<br><p align=center><button class='btn btn-danger btn-sm' onclick=daycaresv($stno);>Save</button></p>";  
       
    echo "<br>";
    $sqlr = "select fcomp,sum(amount) as amt,rcptno,date_format(rcptdate,'%d-%m-%Y') as rdt 
            from $sdb.fees 
            where stno='$stno' 
            and feeyr='$Y'
            and cflag is null 
            and fcomp like 'D%'
            group by rcptno order by id desc";
   $resr = $obj->query($sqlr);   

   echo '<table border=0 class="table table-bordered table-sm">
         <tr><td colspan=4>Receipt Date - Receipt No.</td></tr>';
   $j = 0;
   while($rowr = mysqli_fetch_array($resr,MYSQLI_ASSOC))
   {  
        $rno = $rowr['rcptno'];
        $rdt = $rowr['rdt'];
        if($j == 4) { echo "</td></tr><tr>"; $j = 0; }
        echo "<td align=center><a href=# onclick=feeprint($stno,2,'$Y','$rno','$rdt');>".$rdt." - ".$rno."</a></td>";
        $j = $j+1;
   }
   echo "</table>";
}

function admfeesv()
{
    global $obj,$sdb,$user; 

    $stno = $_POST['stno'];
    $cls  = $_POST['cls'];
    $fc   = explode("^",$_POST['fcomp']); 
    $str  = "";
    
    $rcptno = getreciptno();

    foreach ($fc as $f) {
           $fcmp = explode(',',$f);
           $str .= "('".$stno."','".$cls."','".$fcmp[0]."','".$fcmp[1]."',$rcptno,now(),'$user'),"; 		
    }
   $str =  substr($str,0,-1);
   if ($_POST['fcomp'] != ""){
           $sqlsave = "insert into $sdb.admfees (stno,class,fcomp,amount,rcptno,rcptdate,entryby) values ".$str;
           $obj->query($sqlsave);
   }
   echo "<div id=divALRT><dialog open>Fees Updated Successfully</dialog></div>";
   admfees();
}

function yrfeesv()
{
    global $user,$obj,$mdb,$sdb;
    
    $stno   = $_POST['stno'];
    $cls    = $_POST['cls'];
    $feeyr  = $_POST['feeyr'];

    $rcptno = getreciptno();
    $fc   = explode("^",$_POST['fcomp']); 
    $str  = "";
    foreach ($fc as $f) {
           $fcmp = explode(',',$f);
           $str .= "('".$stno."','".$cls."','".$fcmp[0]."','".$fcmp[1]."','".$feeyr."',$rcptno,now(),'$user'),"; 		
    }
   $str =  substr($str,0,-1);
   if ($_POST['fcomp'] != ""){
           $sqlsave = "insert into $sdb.fees (stno,class,fcomp,amount,feeyr,rcptno,rcptdate,entryby) values ".$str;
           $obj->query($sqlsave);
   }
   echo "<div id=divALRT><dialog open>Fees Updated Successfully</dialog></div>";
   yearlyfees();
}


function daycaresv()
{
    global $user,$obj,$mdb,$sdb;
    
    $stno   = $_POST['stno'];
    $cls    = $_POST['cls'];
    $feeyr  = $_POST['feeyr'];

    $rcptno = getreciptno();
    $fc   = explode("^",$_POST['fcomp']); 
    $str  = "";
    foreach ($fc as $f) {
           $fcmp = explode(',',$f);
           $str .= "('".$stno."','".$cls."','".$fcmp[0]."','".$fcmp[1]."','".$feeyr."',$rcptno,now(),'$user'),"; 		
    }
   $str =  substr($str,0,-1);
   if ($_POST['fcomp'] != ""){
           $sqlsave = "insert into $sdb.fees (stno,class,fcomp,amount,feeyr,rcptno,rcptdate,entryby) values ".$str;
           $obj->query($sqlsave);
   }
   echo "<div id=divALRT><dialog open>Fees Updated Successfully</dialog></div>";
   daycare();
}


function getreciptno()
{
    global $mdb,$obj;
    
    $obj->query("update $mdb.receiptno set rcptno=rcptno+1 limit 1;");
    $rcptno = $obj->getOne("select rcptno from $mdb.receiptno limit 1");
    return $rcptno;    
}
?>