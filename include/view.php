<?php


class View {
	
	
	public function __construct() {
		//echo 'Main View';
	}
	
	public function render($name){
		$file = 'include/view/'.$name.'.php';
		
		if(file_exists($file)){
			require 'include/view/header.php';
			require $file;
			require 'include/view/footer.php';
		}
		else
			echo "The file doesn't exists";
	}
}

?>