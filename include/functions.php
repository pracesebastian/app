<?php


	function check_extantion($file){
		$name = $file["name"];
		$ext = end((explode(".", $name))); # extra () to prevent notice
		return $ext;
	}
	
	function is_Date($str){ 
		$str=str_replace('/', '-', $str);  //see explanation below for this replacement
		return is_numeric(strtotime($str));
	}

?>