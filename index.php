<?php
require "publicacion.class.php";

// if(isset($_POST['date1'])){
//   var_dump($_POST);
// }

$dir = 'publicaciones.json';
$dir = 'newfile.json';
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
  // $pub = new Publicacion($i->gettitulo(),$i->getimagen(),$i->getcontenido(),$i->getfpublicacion(),$i->getfvencimiento());
  $pub = new Publicacion($i);
  array_push($pubs,$pub);
}



// var_dump($pubs);
// $pubs[1]-> settitulo("Cambio de titulo");
// var_dump($pubs[1]->gettitulo());

// $newJson = json_encode([$pubs[1]->toJSON(),$pubs[0]->toJSON()]);

// $myfile = fopen("newfile.json", "w") or die("Unable to open file!");
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
      <td><?php echo $i->gettitulo(); ?></td>
      <td><?php echo $i->getfpublicacion(); ?></td>
      <td><?php echo $i->getfvencimiento(); ?></td>
      <td>actions</td>
      </tr>
    <?php }?>
  </tbody>
</table>
  
</body>
</html>