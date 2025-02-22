<?php
define("__root__", dirname(__FILE__)."/");
/*
ini_set("session.cookie_httponly", True);
ini_set('memory_limit', '256M');

session_start();
date_default_timezone_set('Europe/Paris');
define("__root__", dirname(__FILE__)."/");


//$_SESSION['Role'] = 3;
include "./sys/class/CMS.php";
new CMS();

*/
$buffFiles = [];
function addFiles($add) {
	$buffF = [];

	$allDownload = scandir(__root__.$add);
	foreach ($allDownload as $value) {
		if(in_array($value, ['.', '..', 'LICENSE', '.git', '.gitattributes', 'branch.json', '.htaccess', 'index.php'])) continue;

		if(is_dir(__root__.$add.'/'.$value)) {
			$buffF = array_merge($buffF, addFiles($add.'/'.$value));
			continue;
		}
		$buffF[] = [
			'name' => $add.'/'.$value,
			'path' => "https://raw.githubusercontent.com/is-omini/omsy-cms/refs/heads/main".$add.'/'.$value
		];
	}

	return $buffF;
}

$buffFiles = array_merge($buffFiles, addFiles(''));

header('Content-Type: text/plain');
$buff = array_reverse($buffFiles);
file_put_contents('branch.json', json_encode($buff, JSON_PRETTY_PRINT));