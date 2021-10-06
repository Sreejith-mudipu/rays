<link href='css.css' rel='stylesheet' type='text/css'>
<?php

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    
    error_reporting(E_ALL | E_STRICT);
    ini_set("display_errors", 1);	

    include("config.php");
    include("lib.php");
    $obj = new sql_connect();  
    
    
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    $target_file    = basename($_FILES["fileToUpload"]["name"]);
    $imageFileType  = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
                if ( !($handle = fopen ($_FILES['fileToUpload']['tmp_name'], "r")) ) {
   			die('<p>Error opening temp file</p>');
		} else if ( !($image = fread ($handle, filesize($_FILES['fileToUpload']['tmp_name']))) ) {
   			die('<p>Error reading temp file</p>');
		} else {
   			fclose ($handle);
                        $stno = $_POST['id'];
                        $sdb  = $_POST['sdb'];
                        
   			//update null for existing image
   			$sqld = "update $sdb.stdetail set photo='null' where stno='$stno'";
			$obj->query($sqld);
                        
                        // Commit image to the database
   			$image = mysqli_real_escape_string($obj->connect_id,$image);
    
                        $obj->query("update $sdb.stdetail set photo='$image' where stno='$stno'");                

    	      		echo '<br><p align=center><font color=green size=4><b>Image uploaded Successfully</b></font></p>' ;
   		
		}
    }
}
else{
    $id  = $_GET['id'];
    $sdb = $_GET['sdb'];
    echo "<form action='imguploadpg.php' method='post' enctype='multipart/form-data'>
        <table align=center>
			<tr class=head><td colspan=2 class=head>Upload Student Photo</td>
			<tr><td><input type='hidden' name='id' id='id' readonly value=$id></td>
                            <td><input type='hidden' name='sdb' id='sdb' readonly value='$sdb'></td></tr>
    <tr><td>Select image to upload:<td><input type='file' name='fileToUpload' id='fileToUpload'>
    <input type='submit' value='Upload Image' name='submit'>
</form>";

}

?>