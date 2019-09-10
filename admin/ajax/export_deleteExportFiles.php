<?php
$files = glob('../exports/*');
		foreach($files as $file){
			if(is_file($file)){
					unlink($file);
			}
		}
?>

