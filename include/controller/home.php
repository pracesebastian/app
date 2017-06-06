<?php


class Home extends Controller{
	
	public function __construct() {
		parent::__construct();
	//	echo 'Controller of Home';
	}
	
	function load(){
		$this->view->render('home/home');
	}
	
	function convert(){
		if(isset($_FILES['file'])){
			$file = $_FILES['file'];
			$input_extension = check_extantion($_FILES['file']);
			$new_file_name = $_POST['outout_name'];
			$output_extension = $_POST['output_extension'];
			
			if($output_extension != 'choose'){
				if($input_extension != $output_extension){
					if($input_extension == 'csv'){
						if($output_extension == 'sql'){				
							$model = new Home_model();
							$model->save_file($file, $new_file_name, $input_extension);
							$model->csv_sql($new_file_name);
							$model->download_file($output_extension, $new_file_name);
							$model->delete_file($output_extension, $new_file_name);	
						}
						else {
							$model = new Home_model();
							$model->save_file($file, $new_file_name, $input_extension);
							$model->csv_xml($new_file_name);
							$model->download_file($output_extension, $new_file_name);
							$model->delete_file($output_extension, $new_file_name);
						}
					}
					elseif($input_extension == 'xml'){
						if($output_extension == 'csv'){				
							$model = new Home_model();
							$model->save_file($file, $new_file_name, $input_extension);
							$model->xml_csv($new_file_name);
							$model->download_file($output_extension, $new_file_name);
							$model->delete_file($output_extension, $new_file_name);	
						}
						else {
							$model = new Home_model();
							$model->save_file($file, $new_file_name, $input_extension);
							$model->xml_sql($new_file_name);
							$model->download_file($output_extension, $new_file_name);
							$model->delete_file($output_extension, $new_file_name);
						}
					}
					elseif($input_extension == 'sql'){
						switch($output_extension){
							case 'sql':
								echo 'sql';
							break;
							
							case 'csv':
								echo 'csv';
							break;
						}
					}
					else {
						header("Location: ../home?err=25");
					}
				}
				else {
					header("Location: ../home?err=30");
				}
			}
			else
				header("Location: ../home?err=33");
		}
	}
	
	function show_error(){
		if(isset($_GET['err'])){
			$err = $_GET['err'];
			if($err == 30)
				echo '<h4>Nie możesz skonwertować pliku do takiego samego formatu ! Wybierz inny format !</h4>';
			if($err == 33)
				echo '<h4>Wybierz do jakiego formatu chcesz skonwertować plik !</h4>';
			if($err == 25)
				echo '<h4>Obsługiwane formaty to .csv, .xml, .sql</h4>';
		}
	}

}

?>