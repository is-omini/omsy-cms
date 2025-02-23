<?php
ini_set("session.cookie_httponly", True);
ini_set('memory_limit', '256M');

session_start();
date_default_timezone_set('Europe/Paris');
define("__root__", dirname(__FILE__)."/");

include "./sys/class/CMS.php";
new CMS();
/*
function addFiles($folder) {
	$skip = ['.', '..', '.git', '.gitattributes', '.htaccess', 'index.php', 'LICENSE'];

	$files = scandir(__root__ . $folder);
	$buff = [];
	foreach ($files as $value) {
		if(in_array($value, $skip)) continue;

		$path = $folder . '/' . $value;

		if(is_dir(__root__ . $path)) {
			$buff = array_merge($buff, addFiles($path));
			continue;
		}

		$buff[] = [
			 "name" => $path,
        	"path" => "https://raw.githubusercontent.com/is-omini/omsy-cms/refs/heads/main".$path
		];
	}

	return $buff;
}
$buff = addFiles('');

header('Content-Type: text/plain');

$buff = array_reverse($buff);
var_dump($buff);*/