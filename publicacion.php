<?php
require "php/publicacion.class.php";

print_r($_GET["id"]);

$id = $_GET["id"];
CONST jsonDir = 'db/json/';
$pubs = [];


$dir = jsonDir.'test.json';
if(filesize($dir)==0){
  $pubs[0]=new Publicacion();
}
else{
  $myfile = fopen($dir, "r") or die("Unable to open file!");
  $readedFile = fread($myfile,filesize($dir));
  fclose($myfile);
  $json =  json_decode($readedFile);

  foreach ($json as $k => $i) {
    $pubs[$k] = new Publicacion($i);
  }
}

print_r($pubs[$id]);
?>