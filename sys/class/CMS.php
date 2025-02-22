<?php
class CMS {
    private $Class;
    public $Tilte;
    public $querySearch='';
    public $Page;
    public $Args;
    function __construct() {
        /*
        $zipFile = 'update.zip';
        $filename = 'checksum.txt';
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            if ($zip->locateName($filename) !== FALSE) {
                $entry = $zip->getStream($filename);
                $content = stream_get_contents($entry);
                fclose($entry);
                
                $jsonchecksum = json_decode($content, true);
                foreach ($jsonchecksum as $value) {
                    if ($value["checksum"] !== md5_file($value["path"]) && $value["path"] !== "index.php"){
                        echo $value["path"]." = ".$value["checksum"]."</br></br>";
                        //var_dump("./".$value["path"]);
                        $zip->extractTo("./", $value["path"]);
                    }
                }
            
            }
            // Fermer le fichier ZIP
            $zip->close();
        } else {
            echo 'Impossible d\'ouvrir le fichier ZIP.';
        }
        */
        $Config = simplexml_load_file('./Core.xml');
        if ($Config === false) {
            die('Erreur de chargement du fichier XML.');
        }

        $this->Tilte = $Config->template->title;

        $this->Title = $Config->template->title;
        $this->metaDescription = $Config->template->meta->description;
        $this->metaTags = $Config->template->meta->tags;

        $this->Class["Path"] = (object)[
            "Share" => (object)[
                "upload" => "./share/upload/",
                "Bot" => "./share/bot/",
                "Api" => "./share/api/"
            ],
            "Usr" => (object)[
                "Plugins" => "./usr/plugins/",
                "Template" => "./usr/template/"
            ],
            "Panel" => (object)[
                "Class" => "./panel/class/",
                "Templates" => "./panel/templates/"
            ]
        ];
        $this->Class["Config"] = $Config;

        include("./sys/class/Security.php");
        $this->Class["Security"] = new Security($this);
        include("./sys/class/Session.php");
        $this->Class["Session"] = new Session($this);
        include("./sys/class/Log.php");
        $this->Class["Log"] = new Log($this);
        
        include("./sys/class/DataBase.php");
        $this->Class["DataBase"] = new DataBase($this);

        include("./sys/function/GFunction.php");
        $this->Class["Function"] = new GFunction($this);
        
        include("./sys/class/Members.php");
        $this->Class["Members"] = new Members($this);
        include("./sys/class/Accounts.php");
        $this->Class["Accounts"] = new Accounts($this);

        include("./sys/class/Plugins.php");
        $this->Class["Plugins"] = new Plugins($this);
        
        
        include("./sys/class/Upload.php");
        $this->Class["Upload"] = new Upload($this);

        include("./sys/class/Mail.php");
        $this->Class["Mail"] = new Mail($this);

        include("./sys/class/Snapshot.php");
        $this->Class["Snapshot"] = new Snapshot($this);
                
        include("./sys/class/Template.php");
        $this->Class["Template"] = new Template($this);

        include("./sys/class/PLUGDate.php");
        $this->Class["PLUGDate"] = new PLUGDate($this);

        $this->Class["Session"]->loadSession();
    }

    public function __get($name) {
        return isset($this->Class[$name])?$this->Class[$name]:NULL;
    }

    public function setPage($page){
        $this->Page = $page;
    }
    public function getPage(){
        return $this->Page;
    }
    public function getTitle(){ return $this->Title; }
    public function getMetaDescription(){ return $this->metaDescription; }
    public function getMetaTags(){ return $this->metaTags; }

    public function setTitle($tilte){
        $this->Config->template->title = $tilte;
        $this->Tilte = $tilte;
        //$this->Config->asXML('./Core.xml');
    }
    public function setTemplate($content){
        $getFolder = __root__ . 'usr/template/' . $content . '/template.json';
        if(!file_exists($getFolder)) return;

        $getConfig = json_decode(file_get_contents($getFolder));
        $getConfig->id = $content;

        $this->Config->template->folder = $getConfig->id;
        $this->Config->database->host = $getConfig->install->database->host;
        $this->Config->database->dbname = $getConfig->install->database->dbname;
        $this->Config->database->username = $getConfig->install->database->username;
        $this->Config->database->password = $getConfig->install->database->password;

        file_put_contents(__root__ . 'rewrite.json', json_encode($getConfig->install->rewrite, JSON_PRETTY_PRINT));

        //var_dump($getConfig->install->database);
        $this->Config->asXML('./Core.xml');
    }

    public function setPlguins($content){
        $buff = [];
        $config = [];
        foreach($content as $value) {
            $getFolder = __root__ . 'usr/plugins/' . $value;
            if(!file_exists($getFolder)) continue;
            $buff[] = $value;

            if(!file_exists($getFolder.'/template.json')) continue;

            $getConf = json_decode(file_get_contents($getFolder.'/template.json'));

            $config[] = [
                "id" => $value,
                "name" => $getConf->name,
                "icon" => "<img src=\"$getConf->thumbnail\" height=\"22px\" width=\"22px\">"
            ];
        }
        //file_put_contents(filename, data)
        file_put_contents(__root__.'/panel/interface/config/apps.json', json_encode($config, JSON_PRETTY_PRINT));

        $buff = implode(", ", $buff);
        $this->Config->template->plugins = $buff;
        $this->Config->asXML('./Core.xml');
    }

    public function getNameTemplate() {
        $templateConfig = json_decode(file_get_contents($this->Path->Usr->Template.$this->Config->template->folder. '/template.json'));
        return $templateConfig->name;
    }
}