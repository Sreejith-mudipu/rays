<?php

$mdb = "rays_main";
$sdb = "rays_school";

    class sql_connect{
    var $sqlserver   = DB_SERVER;
    var $sqluser     = DB_USERNAME;
    var $sqlpassword = DB_PASSWORD;
    var $database    = DB_DATABASE;
    var $connect_id;
        //+======================================================+
        function sql_connectsub(){
            $this->connect_id = mysqli_connect($this->sqlserver,$this->sqluser,$this->sqlpassword,$this->database);
            if($this->connect_id){
                if (!isset($this->database)){
                    return $this->connect_id;
                }else{
                    return $this->error();
                }
            }else{
                return $this->error($this->connect_id);
            }
        }
        //+======================================================+
        function error(){
            if(mysqli_error($this->connect_id) != ''){
                echo '<b>MySQL Error</b>: '.mysqli_error().'<br/>';
            }
        }
        //+======================================================+
        function query($query){
            $this->sql_connectsub();
            if ($query != NULL){
                $this->query_result = mysqli_query($this->connect_id,$query);
                if(!$this->query_result){
                    return $this->error();
                }else{
                    return $this->query_result;
                }
            }else{
                return '<b>MySQL Error</b>: Empty Query!';
            }
        }
        //+======================================================+
        function get_num_rows(){            
            return mysqli_num_rows($this->query_result);
        }
        //+======================================================+
        function fetch_row($query_id = ""){
            $this->sql_connectsub();
            if($query_id == NULL){
                $return = mysqli_fetch_array($this->query_result); 
            }else{
                $return = mysqli_fetch_array($query_id);
            }
            if(!$return){
                $this->error();
            }else{
                return $return;
            }
        }    
        //+======================================================+
        function get_affected_rows($query_id = ""){
            if($query_id == NULL){
                $return = mysqli_affected_rows($this->query_result); 
            }else{
                $return = mysqli_affected_rows($query_id);
            }
            if(!$return){
                $this->error();
            }else{
                return $return;
            }
        }
        //+======================================================+
        function sql_close(){
            if($this->connect_id){
                return mysqli_close($this->connect_id);
            }
        }
        function getOne($query)
	{//gets first records first column value				
		$this->sql_connectsub();
		$result = mysqli_query($this->connect_id,$query);
		$dbresult="";
		if ($result!=false) { 
			while($row = mysqli_fetch_array($result, MYSQLI_NUM)){$dbresult= $row['0'];}
			return $dbresult;	
		}
	}
	
	function sqlrows($query)
	{//gets first record
		$this->sql_connectsub();
		$result =  mysqli_query($this->connect_id, $query);
		if ($result!=false) { 
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    return $row;
                }
	}
        //+======================================================+    
  
  
        function selectOption($query,$slctid,$code,$name,$value,$cmbwidth,$link='',$dsbl,$empty)
        {    
            $linkStr="";
            if ($link != "") { $linkStr = 'onchange='.$link; }
            if ($dsbl != "") { $dsbl   = "disabled"; }
            echo "<select  id='$slctid' name='$slctid' style='width:".$cmbwidth."px;' $linkStr $dsbl>";
	    $this->sql_connectsub();
            $result =  mysqli_query($this->connect_id,$query);
            if($empty != ''){
                echo "<option value=''></option>";
            }
            while($row = mysqli_fetch_array($result)) {
                    if(trim($row[$code]) == trim($value)){
                        echo "<option value='".$row[$code]."' selected>".$row[$name]."</option>";}
                    else {echo "<option value='".$row[$code]."'>".$row[$name]."</option>";}
            }
            echo "</select>";        
        } 
        
    }
?>