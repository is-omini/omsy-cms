<?php
class ClassOmsy {
	private $folderClass = "./sys/class/";

	public function __construct($CMS) {
		$allFiles = scandir(__root__.$this->folderClass);

		foreach ($allFiles as $key => $value) {
			if(in_array($value, ['.', '..', 'Request.php'])) continue;

			$basename = pathinfo(__root__.$this->folderClass.$value, PATHINFO_FILENAME);
			//var_dump($basename);

        	$CMS->addClass($basename);
		}
	}
}