<?php


class Home_model {
		
	public function __construct() {
		//echo 'Model of Home';
	}
	
	function csv_sql($new_file_name){
		$csv = new Csv();
		$csv->csv_to_sql_model($new_file_name);
	}
	
	function csv_xml($new_file_name){
		$csv = new Csv();
		$csv->csv_to_xml_model($new_file_name);		
	}
		
	function xml_csv($new_file_name){
		$xml = new Xml();
		$xml->xml_to_csv_model($new_file_name);		
	}
			
	function xml_sql($new_file_name){
		$xml = new Xml();
		$xml->xml_to_sql_model($new_file_name);		
	}
	
	function save_file($file, $name, $extension){
		$dir = 'data/files/';
		$tmp = $file['tmp_name'];
		$name = $name.'.'.$extension;
		$upload = $dir.basename($name);
		move_uploaded_file($tmp, $upload);
	}
	
	function download_file($extension, $name){
		$url = 'data/files/'.$name.'.'.$extension;
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($url).'"');
		header('Content-Length: ' . filesize($url));
		readfile($url);
		exit;
	}
		
	function delete_file($extension, $name){	
		return $delete = unlink('data/files/'.$name.'.'.$extension);
	}
	
}

class Csv {
	
	
	
	function csv_to_sql_model($new_file_name){
		
		$table_name = $new_file_name;
		$inputFilename    = 'data/files/'.$new_file_name.'.csv';
		$outputFilename    = 'data/files/'.$new_file_name.'.sql';
		
		$file = fopen($inputFilename,"r");
		$num_of_cols = 0;
		$num_of_rows = 0;

		$type = array();
		$array= array();

		while (($getData = fgetcsv($file, ",")) !== FALSE){
			$num_of_cols = count($getData);
			$num_of_rows++;
			
			$array[] = $getData;
		}


		$num_of_rows = ($num_of_rows-1);
		$num_of_cols = ($num_of_cols-1);

		// Data type
		for($i=0; $i<=$num_of_cols; $i++){

			for($x=0; $x<=$num_of_rows; $x++){
				$value = $array[$x][$i];
				$valuec[$i][] = $value;
			}
			$value = $valuec[$i][0];
			if(is_numeric($value))
				$value = 'INT';
			elseif(is_Date($value))
				$value = 'datetime';
			elseif(is_string($value)){
				$value = strlen($value);
				if($value <= 255)
					$value = 'VARCHAR(255)';
				else
					$value = 'longtext NOT NULL';
			}
				
			
			$type[] = $value;
		}
		// Tworzy nowy plik
		$new = fopen($outputFilename, 'w');
		
		// Tworzenie tabeli
		fwrite($new, 'CREATE TABLE table_name (');

			for($i=0; $i<=$num_of_cols; $i++){
							
					if($i == $num_of_cols)
						$last = '';
					else
						$last = ', ';
					
				fwrite($new, '		`Column_'.$i.'` '.$type[$i].$last.PHP_EOL);
			}

		fwrite($new, ') ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'.PHP_EOL);


		fwrite($new, 'INSERT INTO table_name (');		

		for($i=0; $i<=$num_of_cols; $i++){
				($i == $num_of_cols) ? $last = '' :	$last = ', ';
			fwrite($new, '`column_'.$i.'`'.$last);		
		}

		fwrite($new, ') VALUES ');	
		// Dodawanie rekordów
		for($x=0; $x<=$num_of_rows; $x++){
			fwrite($new, '(');	
				for($c=0; $c<=$num_of_cols; $c++){
					if($c == $num_of_cols)
						$last = '';
					else
						$last = ', ';
					
					$value = $array[$x][$c];
													
					if($value == "")
						$value = 'NULL';
					if(!is_numeric($value))
						$value = '\''.$value.'\'';
						//$value = mysql_real_escape_string($value);
					else
						$value;
					
					fwrite($new, $value.$last);		
				}
			if($x==$num_of_rows)
				fwrite($new, ');'.PHP_EOL);	
			else
				fwrite($new, '),'.PHP_EOL);	
		}
			fclose($new);
			fclose($file);
			unlink($inputFilename);
	}
	
	function csv_to_xml_model($new_file_name) {
		
		$inputFilename    = 'data/files/'.$new_file_name.'.csv';
		$outputFilename    = 'data/files/'.$new_file_name.'.xml';

		$inputFile  = fopen($inputFilename, 'r');

		$i=0;
		while (($row = fgetcsv($inputFile)) != FALSE)
		{
			$col_number = count($row);
			$array[] = $row;
		}

		$row_number = count($array);
		$num_of_rows = ($row_number-1);
		$num_of_cols = ($col_number-1);


		// Data type
		for($i=0; $i<=$num_of_cols; $i++){

			for($x=0; $x<=$num_of_rows; $x++){
				$value = $array[$x][$i];
				$valuec[$i][] = $value;
			}
			$value = $valuec[$i][0];
			if(is_numeric($value))
				$value = 'INT';
			elseif(is_Date($value))
				$value = 'datetime';
			elseif(is_string($value)){
				$value = strlen($value);
				if($value <= 255)
					$value = 'VARCHAR(255)';
				else
					$value = 'longtext NOT NULL';
			}
				
			
			$type[] = $value;
		}


		// Tworzy nowy plik
		$new = fopen($outputFilename, 'w');

		// Tworzenie tabeli
		fwrite($new, 
		'
		<?xml version="1.0" encoding="utf-8"?>
		<pma_xml_export version="1.0" xmlns:pma="http://www.phpmyadmin.net/some_doc_url/">
			<!--
			- Structure schemas
			-->
			<pma:structure_schemas>
				<pma:database name="'.$new_file_name.'" collation="utf8_unicode_ci" charset="utf8">
					<pma:table name="base">
		');

		fwrite($new,  'CREATE TABLE '.$new_file_name.' (');

			for($i=0; $i<=$num_of_cols; $i++){				
				if($i == $num_of_cols)
					$last = '';
				else
					$last = ', ';	
				fwrite($new, '`Column_'.$i.'` '.$type[$i].$last.PHP_EOL);
			}

		fwrite($new,  ') ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'.PHP_EOL );
		fwrite($new, 
		'
					</pma:table>
				</pma:database>
			</pma:structure_schemas>
		'
		);

		// Tworzenie rekordu
		fwrite($new, '<database name="'.$new_file_name.'">');
		for($i=0; $i<=$num_of_rows; $i++){
			
			fwrite($new, '	<table name="table_'.$new_file_name.'">'.PHP_EOL);
				for($x=0; $x<=$num_of_cols; $x++){
					fwrite($new, '		<column name="Column_'.$x.'">'.$array[$i][$x].'</column>'.PHP_EOL);
				}
			fwrite($new, '	</table>'.PHP_EOL);
		}

		fwrite($new,' </database>
		</pma_xml_export>');
		fclose($new);
		fclose($inputFile);
		unlink($inputFilename);
	}
}

class Xml {
	
	function xml_to_csv_model($new_file_name){
		$filexml = 'data/files/'.$new_file_name.'.xml';    
		$xml = simplexml_load_file($filexml);
		$new_file = 'data/files/'.$new_file_name.'.csv';
		$file = fopen($new_file, 'w');

		$cols = count($xml->database->table[0]->column);
		$rows = count($xml->database->table);
		$cols = $cols-1;
		$rows = $rows-1;

		for($i=0; $i<=$rows; $i++){
			
			for($x=0; $x<=$cols; $x++){
				($x == $cols) ? $last = '' : $last = ',';
				fwrite($file, '"'.$xml->database->table[$i]->column[$x].'"'.$last);
			}
			fwrite($file, PHP_EOL);
		}

		fclose($file);
		unlink($filexml);
	}
	
	function xml_to_sql_model($new_file_name){
				
		$filexml = 'data/files/'.$new_file_name.'.xml';    
		$xml = simplexml_load_file($filexml);
		$new_file = 'data/files/'.$new_file_name.'.sql';
		$file = fopen($new_file, 'w');

		$cols = count($xml->database->table[0]->column);
		$rows = count($xml->database->table);
		$num_of_cols = $cols-1;
		$num_of_rows = $rows-1;

		$type = 'VARCHAR (255)';

		// Tworzy tabele
		fwrite($file, 'Create TABLE '.$new_file_name.' (');
			for($i=0; $i<=$num_of_cols; $i++){
				fwrite($file, '		Column_'.$i.' '.$type.PHP_EOL);
			}
		fwrite($file, ');'.PHP_EOL);

		// Rekordy
		for($i=0; $i<=$num_of_rows; $i++){
			fwrite($file, 'INSERT INTO '.$new_file_name.' VALUES (');
				for($x=0; $x<=$num_of_cols; $x++){
					($x == $num_of_cols) ? $last = '' : $last = ', ';
					$value = $xml->database->table[$i]->column[$x];
					
					
					$value = '\''.$value.'\'';
					
					
					
					fwrite($file, $value.$last);
				}
			fwrite($file, ')'.PHP_EOL);
		}


	}
}
?>