<?php
require "php/publicacion.class.php";

CONST imgDir = 'db/img/';
CONST jsonDir = 'db/json/';

if(isset($_POST['date1'])){
  var_dump($_POST);
}
if(isset($_GET['date1'])){
  var_dump($_GET);
}

// $dir = 'db/json/publicaciones.json';
$dir = jsonDir.'newfile.json';
if(filesize($dir)==0){
  echo '404 error - no DB';
  die();
}
$myfile = fopen($dir, "r") or die("Unable to open file!");
$readedFile = fread($myfile,filesize($dir));
$json =  json_decode($readedFile);
// var_dump($json);
fclose($myfile);

$pubs = [];
// print_r(sizeof($json));

foreach ($json as $i) {
  // $i = json_decode($i);
  // // var_dump($i);
  // $pub = new Publicacion($i->titulo,$i->imagen,$i->contenido,$i->fpublicacion,$i->fvencimiento);
  $pub = new Publicacion($i);
  array_push($pubs,[$pub,preg_replace('/"/','\\"',$i)]);
}



print_r($pubs[0][1]);
// $pubs[1]-> settitulo("Cambio de titulo");
// var_dump($pubs[1]->gettitulo());

// $newJson = json_encode([$pubs[1]->toJSON(),$pubs[0]->toJSON()]);

// $myfile = fopen("db/json/newfile.json", "w") or die("Unable to open file!");
// fwrite($myfile, $newJson);
// fclose($myfile);


?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
<script src="js/index.js"></script>

</head>
<body>
<!-- <br/> -->
<!-- <form action="index.php" method="post">
  <input type="date" name="date1" id="date1">
  <button type="submit">Enviar</button>

</form> -->
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Titulo</th>
      <th scope="col">Fecha Publicacion</th>
      <th scope="col">Fecha Vencimiento</th>
      <th scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($pubs as $k => $i) {?>
      <tr>
      <th scope="row"><?php echo $k+1; ?></th>
      <td><?php echo $i[0]->gettitulo(); ?></td>
      <td><?php echo $i[0]->getfpublicacion(); ?></td>
      <td><?php echo $i[0]->getfvencimiento(); ?></td>
      <td>
        <?php echo '<button type="button" class="btn btn-primary " onclick="setFormValues(\''.$i[1].'\')" ><i class="bi bi-pencil-square"></i></button>'; ?>
        <button type="button" class="btn btn-secondary " onclick="setFormValues()" ><i class="bi bi-trash3" ></i></button>
      </td>
      </tr>
    <?php }?>
  </tbody>
</table>
  
<div class="container">
  <div class="row">
    <?php foreach ($pubs as $k => $i) {?>
      <br><hr><br>
      <div class='col-10 offset-1'>  
        <?php echo '<img width = "300" src="'.imgDir.$i[0]->getimagen()->img1.'" alt="'.imgDir.$i[0]->getimagen()->img1.'" />'; ?>
        <!-- <img src="" alt="" width = "100"> -->
      </div>
    <?php }?>
  </div>
</div>
</body>
</html>