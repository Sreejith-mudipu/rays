<?php
   include("config.php");
   session_start();
   
   $mdb = "rays_main";
   $sdb = "rays_school";
   
   $error = '';
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']); 
      $sql = "SELECT id FROM $mdb.users  WHERE username = '$myusername' and password = '$mypassword'";
     
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        
      $count = mysqli_num_rows($result);
    
      // If result matched $myusername and $mypassword, table row must be 1 row
	                                                                                                       	
      if($count > 0) {
	session_start();
        session_regenerate_id(true);
         
         $_SESSION['login_user'] = $myusername;
         $_SESSION['mdb']        = $mdb;
         $_SESSION['sdb']        = $sdb;
         
         header("location: welcome.php");
      }else {
         $error = "Your Login Name or Password is invalid";
      }
   }
?>
<html>  
   <head>
      <title>Login Page</title>
     <link rel="stylesheet" href="sch.css" type="text/css">
      <link href="/rays/fontawesome/css/all.css" rel="stylesheet">
      
   </head>
   <table border="0" width="100%"  STYLE="BACKGROUND-COLOR: #bf3b48;"><tr height="16px"><td VALIGN="TOP"></td></tr></table>
          <img src="/rays/images/school.png" alt="Rays" width="170" height="100" style="position:absolute;padding-left:330px;padding-top: 20px">
   <table border = 0 width=100% height=93% align =center><tr height="100px"><td>
   <body bgcolor = "#FFFFFF">
      <div align = "center">
         <div style = "width:400px; padding-top: 85px;border: solid 0px #333333;" align = "left">
             <div style = " color:black; padding:15px;text-align:center"><b><small>Login</small></b></div><!--style = "background-color:#ef8f57; color:#FFFFFF; padding:15px;text-align:center"-->
				
            <div style = "margin:50px">
                <a href="../rays/login.php"></a>
         
               <form action = "" method = "post">
                   <label align="center" style="width:100%">
                       <label><i class='fa fa-user icon'></i>&emsp;<input type = "text" name = "username" placeholder="Username" class = "box"/></label><br /><br />
                       <label><i class='fa fa-key icon'></i>&emsp;<input type = "password" placeholder="Password" name = "password" class = "box" /></label><br/><br />
                  <p align="left"><input type = "submit" class="btnlg" value = " Submit "/></p><br />
               </form>
             
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
					
            </div>
				
         </div>
			
      </div>
       </tr></td></table>
   <table border="0" width="100%"  STYLE="BACKGROUND-COLOR: #bf3b48;"><tr height="5px"><td align="center">Welcome</td></tr></table>
   </body>
</html>