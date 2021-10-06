<?php
if (!isset($_SESSION)) session_start();	
if($_SESSION["login_user"]==""){header("location: logout.php");	exit();}
error_reporting(E_ALL | E_STRICT && ~E_NOTICE);
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
    case ''       : first();                break;
    case 'sv'     : sdsave();               break;
    default       :                         break;
}

function first()
{
    global $user,$obj,$mdb,$sdb;
    
    $Y = date('Y');
    
    $str = 'Add Students';
    $srchno  = ''; if(isset($_POST['stno']) != ''){ $srchno = $_POST['stno']; $str = 'Edit Students';}

    $sql = "select * from $sdb.stdetail where stno='$srchno'";
    $res = $obj->query($sql);
    $row = mysqli_fetch_array($res,MYSQLI_ASSOC);
    $cnt = $obj->get_num_rows();
    $stno = $obj->getOne("select ifnull(max(stno),0)+1 from $sdb.stdetail");
    $astno = "";
    
    $cyr = date('Y');    
    $resno    = $obj->sqlrows("select substr(rollno,5,4) as yr,substring(rollno,-3)+1 as no from $sdb.stdetail order by rollno desc limit 1"); 
 
    if($cyr == $resno['yr'])
    {
        $rno   = $resno['no'];
        $rollno = "RKET".$cyr.str_pad((int) $rno,3,"0",STR_PAD_LEFT);
    }else{
        $rollno = "RKET".$cyr.str_pad((int) 1,3,"0",STR_PAD_LEFT);
    }
    if($cnt > 0){
        $Y      = $row['batch'];
        $stno   = $astno = $row['stno'];
        $rollno = $row['rollno'];
    }

    
    
    echo "<h5 style='height:0px'>$str</h5>";
    echo "<p align=center><font color=red size=4 align=right>
                            <b>Student No </b>
                            <input type=text id=txtstno value='$stno' disabled style='width:100px;text-align:center;font-weight:bold'></font></p>";
    
    echo "<div class='container'>";
    echo '<table border=0 class="table table-bordered table-sm">
          <tr class=newhd><td colspan=5 align=center><b>Student Details</b></td></tr>';
    echo "<tr><td align=left>Batch</td><td><input type=number id=txtbatch min=2015 value=$Y>";
    echo "<td align=left>Date of Join</td><td><input type=date id=dtdoj  value='".$row['doj']."'>
          <td rowspan=6 align=center>";
      
    $image = $row['photo'];
    echo '<br><br><img id="my" height="150" width="150" src="data:image;base64,'.base64_encode($image).' "> ';
    echo "</td>";
    
    echo  "<tr>
               <td align=left>Class</td>
               <td>";
    $sql = "select code,fname from $mdb.master where grup='C'";
    $obj->selectOption($sql,"txtclass","code","fname",$row['currentclass'],175,'','','');
    echo "<td>Roll No</td><td><input type=text id=txtrollno value='$rollno' disabled></td>";
    echo  "<tr><td align=left>Student Name</td><td><input type=text id=txtname value='".$row['stname']."'></td>";
    echo  "<td align=left>Gender</td><td align=left><label id=divGN style='display: inline;'>";	    
    if($row['sex']=='F'){		
        echo '<input type="Radio" name="gnd" id="gnd" value="M" style="width: 25px;">Male&emsp;&emsp;<input type="Radio" name="gnd" id="gnd" value="F" style="width: 25px;" checked>Female';
    }else {
	echo '<input type="Radio" name="gnd" id="gnd" value="M" style="width: 25px;" checked>Male&emsp;&emsp;<input type="Radio" name="gnd" id="gnd" style="width: 25px;" value="F">Female';
    }
    echo  "</labe></td></tr>
           <tr><td align=left>Nationality</td><td><input type=text id=txtnlty value='".$row['nationality']."'></td>
           <td align=left>Religion</td><td><input type=text id=txtrelg value='".$row['religion']."'></td></tr>
               
           <tr><td align=left>Date of Birth</td><td><input type=date id=dtdob value='".$row['dob']."'></td>
           <td align=left>Place of Birth</td><td><input type=text id=txtpob value='".$row['placeofbirth']."'></td>
               </tr>
               <tr><td align=left>Blood Group</td>
                <td>";
    $sql = "select code,fname from $mdb.master where grup='B'";
    $obj->selectOption($sql,"txtblood","code","fname",$row['bloodgrup'],175,'','',true);
        
    echo "</td>";
    echo "<td align=left>Aadhar No</td><td><input type=text id=txtadhar value='".$row['aadharno']."'></td></tr>";
    echo  "<tr><td align=left>Mother Tongue</td><td><input type=text id=txtmtongue value='".$row['mtongue']."'></td>
               <td align=left>Other Language</td><td><input type=text id=txtolang value='".$row['otherlang']."'></td>
               <td rowspan=3 align=center style='padding-top: 25px;'>
               <button class='btn btn-danger btn-sm' title='Upload Image' onclick=uploadwindow('$astno','$sdb');>Upload Image</button>
               <a href='#' onclick=editstudent('$astno','$sdb')><img src='../rays/images/refresh.png' height=20 width=30 title=refresh></a></td></tr>";
    echo "<tr><td align=left>Caste</td><td><input type=text id=txtcste value='".$row['caste']."'></td>
           <td align=left>Sub Caste</td><td><input type=text id=txtsubcste value='".$row['subcaste']."'></td></tr>";
    echo "</table>";

    echo '<br><table width=85% border=0 class="table table-bordered table-sm">
          <tr class=newhd><td colspan=6 align=center><b>Family Details</b></td></tr>';
    echo  "<tr><td align=left>Father Name</td><td><input type=text id=txtfname value='".$row['fathername']."'></td>
               <td align=left>Father Income</td><td><input type=number id=txtfincome min=1 value='".$row['fatherincm']."'></td>";
    echo  "<td align=left>Father Qualification</td><td><input type=text id=txtfqual value='".$row['fatherqual']."'></td>";
    echo  "<tr><td align=left>Father Occupation</td><td><input type=text id=txtfoccptn value='".$row['fatherocptn']."'></td>
                <td align=left >Father Contact No</td><td><input type=text id=txtfcont value='".$row['fathercontact']."'></td>
               <td align=left>Father Email</td><td><input type=text id=txtfemail value='".$row['fatheremail']."'></td></tr>";

    echo  "<tr><td align=left>Mother Name</td><td><input type=text id=txtmname value='".$row['mothername']."'></td>
                <td align=left>Mother Income</td><td><input type=text id=txtmincome value='".$row['motherincm']."'></td>
                <td align=left>Mother Qualification</td><td><input type=text id=txtmqual value='".$row['motherqual']."'></td>";
    echo  "<tr><td align=left>Mother Occupation</td><td><input type=text id=txtmoccptn value='".$row['motherocptn']."'></td>
               <td align=left>Mother Contact No</td><td><input type=text id=txtmcont value='".$row['mothercontact']."'></td>
               <td align=left>Mother Email</td><td><input type=text id=txtmmail value='".$row['motheremail']."'></td></tr>";
 
    echo  "<tr><td align=left>Guardian Name</td><td><input type=text id=txtgname value='".$row['gaurdname']."'></td>
               <td align=left>Guardian Contact</td><td><input type=text id=txtgcont value='".$row['guardcont']."'></td></tr></table>";
    
    echo '<table width=85% class="table table-bordered table-sm">
          <tr class=newhd><td colspan=6 align=center><b>Previous School Details</b></td></tr>';
    
    echo "<tr><td align=left>Earlier School</td><td><label id=divES style='display:inline;'>&emsp;";
    if($row['earlierschool']=='N'){
        echo "<input type=Radio name=pschl id=pschl value='Y'>Yes&emsp;&emsp;
              <input type=Radio name=pschl id=pschl value='N' checked>&emsp;No";
      }else {
        echo "<input type=Radio name=pschl id=pschl value='Y' checked>Yes&emsp;&emsp;
              <input type=Radio name=pschl id=pschl value='N'>&emsp;No";
      }
   echo  "</label></td><td align=left>Earlier School Name</td><td><input type=text id=txteschlname value='".$row['earlierschoolname']."'></td></tr>";
   echo  "<tr><td align=left>Sibling Detail</td><td><input type=text id=txtsibdetail value='".$row['sibname']."'></td>
              <td align=left>Sibling Study School</td><td><input type=text id=txtsibschool value='".$row['sibstudy']."'></td>";
   echo "<tr><td align=left>Sibling Age</td><td><input type=text id=txtsibage value='".$row['sibage']."'></td>";
   echo "<td align=left style='display:none'>Pupil Inoculated<td style='display:none'>";
   $sql = "select code,fname from $mdb.master where grup='P'";
   $obj->selectOption($sql,"txtinocult","code","fname",$row['inoculated'],175,'','',true);      
   echo "</td></tr>";
   echo  "<tr><td align=left>Allergy Detail</td><td colspan=3><textarea type=text id=txtalrg cols=100>".$row['allergydetail']."</textarea></tr>";
   echo  "<tr><td align=left>Permanent Address</td><td><textarea type=text id=txtpadd cols=50>".$row['paddress']."</textarea></td>";
   echo  "<td align=left>Correspondent Address</td><td><textarea type=text id=txtcadd cols=50>".$row['caddress']."</textarea></td></tr>";
  
   echo  "<tr height=40px><td colspan=4 align=center ><button id=btnsave class='btn btn-danger btn-sm' title=Save onclick=sdsave('$srchno');>SAVE</button></td></tr>";
   echo "</table>";
   echo "</div>";
   echo "<div id=divSAVE>";
}


function sdsave()
{
    global $user,$obj,$mdb,$sdb;
    
    $stno       = $_POST['stno'];
    $rlno       = $_POST['rlno'];
    $batch      = 'null'; if($_POST['bt'] !='')     { $batch    = "'".$_POST['bt']."'";     }
    $class      = 'null'; if($_POST['c'] !='')      { $class    = "'".$_POST['c']."'";      }
    $sname      = 'null'; if($_POST['sn'] !='')     { $sname    = "'".$_POST['sn']."'";     }
    $nty        = 'null'; if($_POST['nty'] !='')    { $nty      = "'".$_POST['nty']."'";    }
    $rlg        = 'null'; if($_POST['rlg'] !='')    { $rlg      = "'".$_POST['rlg']."'";    }
    $sx         = 'null'; if($_POST['sx'] !='')     { $sx       = "'".$_POST['sx']."'";     }  
    $doj        = 'null'; if($_POST['doj'] !='')    { $doj      = "'".$_POST['doj']."'";    } 
    $dob        = 'null'; if($_POST['dob'] !='')    { $dob      = "'".$_POST['dob']."'";    } 
    $pob        = 'null'; if($_POST['pob'] !='')    { $pob      = "'".$_POST['pob']."'";    }
    $bldgrup    = 'null'; if($_POST['bg'] !='')     { $bldgrup  = "'".$_POST['bg']."'";     }
    $adhr       = 'null'; if($_POST['adhr'] !='')   { $adhr     = "'".$_POST['adhr']."'";   }
    $cst        = 'null'; if($_POST['cst'] !='')    { $cst      = "'".$_POST['cst']."'";    }
    $sbcst      = 'null'; if($_POST['sbcst'] !='')  { $sbcst    = "'".$_POST['sbcst']."'";  }
    $fname      = 'null'; if($_POST['fn'] !='')     { $fname    = "'".$_POST['fn']."'";     }
    $fincome    = 'null'; if($_POST['fi'] !='')     { $fincome  = "'".$_POST['fi']."'";     }
    $fqual      = 'null'; if($_POST['fq'] !='')     { $fqual    = "'".$_POST['fq']."'";     }
    $focptn     = 'null'; if($_POST['fo'] !='')     { $focptn   = "'".$_POST['fo']."'";     }
    $fcontact   = 'null'; if($_POST['fc'] !='')     { $fcontact = "'".$_POST['fc']."'";     }
    $femail     = 'null'; if($_POST['fe'] !='')     { $femail   = "'".$_POST['fe']."'";     }
    $mname      = 'null'; if($_POST['mn'] !='')     { $mname    = "'".$_POST['mn']."'";     }
    $mincome    = 'null'; if($_POST['mi'] !='')     { $mincome  = "'".$_POST['mi']."'";     }
    $mqual      = 'null'; if($_POST['mq'] !='')     { $mqual    = "'".$_POST['mq']."'";     }
    $mocptn     = 'null'; if($_POST['mo'] !='')     { $mocptn   = "'".$_POST['mo']."'";     }
    $mcontact   = 'null'; if($_POST['mc'] !='')     { $mcontact = "'".$_POST['mc']."'";     }
    $memail     = 'null'; if($_POST['me'] !='')     { $memail   = "'".$_POST['me']."'";     }
    $gname      = 'null'; if($_POST['gn'] !='')     { $gname    = "'".$_POST['gn']."'";     }
    $gcontact   = 'null'; if($_POST['gc'] !='')     { $gcontact = "'".$_POST['gc']."'";     }
    $mt         = 'null'; if($_POST['mt'] !='')     { $mt       = "'".$_POST['mt']."'";     }
    $ol         = 'null'; if($_POST['ol'] !='')     { $ol       = "'".$_POST['ol']."'";     }
    $ps         = 'null'; if($_POST['ps'] !='')     { $ps       = "'".$_POST['ps']."'";     }
    $pn         = 'null'; if($_POST['pn'] !='')     { $pn       = "'".$_POST['pn']."'";     }
    $sd         = 'null'; if($_POST['sd'] !='')     { $sd       = "'".$_POST['sd']."'";     }
    $ss         = 'null'; if($_POST['ss'] !='')     { $ss       = "'".$_POST['ss']."'";     }
    $ad         = 'null'; if($_POST['ad'] !='')     { $ad       = "'".$_POST['ad']."'";     }
    $sa         = 'null'; if($_POST['sa'] !='')     { $sa       = "'".$_POST['sa']."'";     }
    $ad         = 'null'; if($_POST['ad'] !='')     { $ad       = "'".$_POST['ad']."'";     }
    $ic         = 'null'; if($_POST['ic'] !='')     { $ic       = "'".$_POST['ic']."'";     }
    $pa         = 'null'; if($_POST['pa'] !='')     { $pa       = "'".$_POST['pa']."'";     }
    $ca         = 'null'; if($_POST['ca'] !='')     { $ca       = "'".$_POST['ca']."'";     }
    $id         = $_POST['id'];   
    
    $strmsg = "Student added Successfully";
    if($id == ''){
        $obj->query("insert into $sdb.stclass(stno,batch,class) VALUES ($stno,$batch,$class)");
        $fcid = mysqli_insert_id($obj->Link_ID);
        if($fcid == ''){
              $fcid =  $obj->getOne("select id from $sdb.stclass where stno='$stno'");
        }       
        
        $sql  ="insert into $sdb.stdetail(batch,doj,stno,rollno,joiningclass,currentclass,stname,nationality,religion,sex,dob,placeofbirth,bloodgrup,aadharno,caste,subcaste,fathername,fatherqual,fatherocptn,fatherincm,fathercontact,fatheremail,
                mothername,motherincm,motherqual,motherocptn,mothercontact,motheremail,gaurdname,guardcont,mtongue,otherlang,earlierschool,earlierschoolname,sibname,sibstudy,sibage,allergydetail,inoculated,paddress,caddress,stclassid) 
                values ($batch,$doj,$stno,'$rlno',$class,$class,$sname,$nty,$rlg,$sx,$dob,$pob,$bldgrup,$adhr,$cst,$sbcst,$fname,$fqual,$focptn,$fincome,$fcontact,$femail,$mname,$mincome,$mqual,$mocptn,$mcontact,$memail,$gname,$gcontact,$mt,$ol,$ps,$pn,$sd,$sa,$ss,$ad,$ic,$pa,$ca,$fcid)";
    }else{
        $sql = "update $sdb.stdetail set batch=$batch,doj=$doj,currentclass=$class,stname=$sname,nationality=$nty,religion=$rlg,sex=$sx,
                dob=$dob,placeofbirth=$pob,bloodgrup=$bldgrup,aadharno=$adhr,caste=$cst,subcaste=$sbcst,
                fathername=$fname,fatherqual=$fqual,fatherocptn=$focptn,fatherincm=$fincome,fathercontact=$fcontact,fatheremail=$femail,
                mothername=$mname,motherincm=$mincome,motherqual=$mqual,motherocptn=$mocptn,mothercontact=$mcontact,motheremail=$memail,
                gaurdname=$gname,guardcont=$gcontact,mtongue=$mt,otherlang=$ol,
                earlierschool=$ps,earlierschoolname=$pn,sibname=$sd,sibstudy=$ss,sibage=$sa,allergydetail=$ad,inoculated=$ic,paddress=$pa,caddress=$ca
                where stno='$stno'";
        $fcid =  $obj->getOne("select stclassid from $sdb.stdetail where stno='$stno'");

        $obj->query("update $sdb.stclass set batch=$batch,class=$class where id='$fcid'");
        $strmsg = "Updated Successfully";
    }
    $res = $obj->query($sql);
    echo "<div id=divALRT><dialog open>$strmsg</dialog></div>";
    first(); 
}
?>