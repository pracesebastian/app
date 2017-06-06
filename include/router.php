<?php


class Router {
	
	public function __construct() {
		
		(isset($_GET['url'])) ? $url = $_GET['url'] : $url = 'home';
		
		$url = explode('/', $url);
		//print_r($url);
		
		$file_controller = 'include/controller/'.$url[0].'.php';
		if(file_exists($file_controller)){
			require $file_controller;
			$controller = new $url[0]();
			$controller->model($url[0]);
		}
		else
			$this->error();
		
		if(isset($url[2])){
			if(method_exists($url[1]))
				$controller->$url[1]($url[2]);
			else
				$this->error();
		}
		else{
			if(isset($url[1])){
				if (method_exists($controller, $url[1])) {
					$controller->{$url[1]}();
				} else {
					$this->error();
				}
			}
		}
		
			$controller->load();
	}
	
	function error(){
		echo 'error';
	}
}

?>