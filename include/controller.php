<?php


class Controller {
	
	public $model;
	public $view;
	
	public function __construct() {
		//echo 'Main controller';
		$this->view = new View();
	}
	
	public function model($name){
		$file_model = 'include/model/'.$name.'_model.php';
		
		if(file_exists($file_model)){
			require $file_model;
			$name = $name.'_model';
			$this->model = 	new $name();	
		}
		else
			echo "The file doesn't exists";
	}
}

?>