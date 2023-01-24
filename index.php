<?php
require "php/publicacion.class.php";

CONST imgDir = 'db/img/';
CONST jsonDir = 'db/json/';
$pubs = [];

// print_r($_REQUEST);
// var_dump([$_POST, $_FILES]);

// $dir = jsonDir.'publicaciones.json';
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
    // $pub = new Publicacion($i->id,$i->titulo,$i->imagen,$i->fecha,$i->contenido,$i->resumen);
    // $pub = new Publicacion($i);
    // array_push($pubs,$pub);
    $pubs[$k] = new Publicacion($i);
  }
  // usort($pubs,function($a,$b){
  //   return $a->getid() > $b->getid();
  // });
}

//Escribir sobre Archivo JSON de Base de Datos
function editDbFile($d,$c=""){
  $myfile = fopen($d, "w") or die("Unable to open file!");
  fwrite($myfile, $c);
  fclose($myfile);
  return true;
}

//Carga de imagenes
function uploadImg($file = [], $name = null){
  $extencion = [];
  preg_match("/\.\w+/",$file['name'],$extencion);
  $img = $name . $extencion[0];
  // var_dump([$file['name'],$img]);
  // print_r($img);
  if(move_uploaded_file($file['tmp_name'], imgDir.$img)) return $img;
  else return '**No Imagen**';
}

if(isset($_POST['New'])){
  var_dump($_POST);
  $newImg1 = '';
  $newImg2 = '';
  $newPubId = time();
  $newImg1 = $_FILES["image1New"]['name'] != "" ?
    uploadImg($_FILES["image1New"],'1-'.$newPubId) :
    '**No Imagen**';
  $newImg2 = $_FILES["image2New"]['name'] != "" ?
    uploadImg($_FILES["image2New"],'2-'.$newPubId) :
    '**No Imagen**';
  if($pub = new Publicacion(
      $newPubId,
      $_POST['pubTitleNew'],
      [$newImg1,$newImg2],
      date('d/m/Y'),
      $_POST['contenidoNew'],
      $_POST['resumenNew'],
      $_POST['autorNew'],
      $_POST['ytURLNew'])){
    if( isset($pubs[0]) ) unset($pubs[0]);
    // array_push($pubs,$pub);
    $pubs[$newPubId] = $pub;
    echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
      Se ha creado una nueva publicacion: "'. $_POST['pubTitleNew'] .'", con exito
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
  }
  else
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    Ha ocurrido un error, intentar de nuevo mas tarde
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
else if(isset($_POST['Edit'])){
  var_dump($pubs[$_POST["pubEdit"]]);
  $pub = $pubs[$_POST['pubEdit']];
  if($pub->gettitulo() != $_POST["pubTitleEdit"])
    $pub->settitulo($_POST["pubTitleEdit"]);
  if($pub->getresumen() != $_POST["resumenEdit"])
    $pub->setresumen($_POST["resumenEdit"]);
  if($pub->getcontenido() != $_POST["contenidoEdit"])
    $pub->setcontenido($_POST["contenidoEdit"]);
  if($pub->getcontenido() != $_POST["autorEdit"])
    $pub->setcontenido($_POST["autorEdit"]);
  if($pub->getcontenido() != $_POST["ytURLEdit"])
    $pub->setcontenido($_POST["ytURLEdit"]);
  $imgE1 = $pub->getimagen()[0];
  $imgE2 = $pub->getimagen()[1];
  if($_FILES["image1Edit"]["name"] != "")
    $imgE1 = uploadImg($_FILES["image1Edit"],'1-'.$_POST["pubEdit"]);
  if($_FILES["image2Edit"]["name"] != "")
    $imgE2 = uploadImg($_FILES["image2Edit"],'2-'.$_POST["pubEdit"]);
  $pub->setimagen($imgE1,$imgE2);
  $pubs[$_POST["pubEdit"]] = $pub;
  
  var_dump($pubs[$_POST["pubEdit"]]);

  // foreach ($pubs as $k => $e) {
  //   if($e->getid() == $_POST['pubEdit']){
  //     $e->settitulo($_POST['pubTitleEdit']);
  //     $e->setresumen($_POST['resumenEdit']);
  //     if($_FILES['image2Edit'] != '' && $_FILES['image1Edit'] != '')
  //       $e->setimagen([$_FILES['image2Edit'],$_FILES['image1Edit']]);
  //     else if($_FILES['image2Edit'] != '' && $_FILES['image1Edit'] != '')
  //     $e->setcontenido($_POST['contenidoEdit']);
  //   }
  // }
}
else if(isset($_POST['Del'])){
  foreach ($pubs as $k => $e)
    if($e->getid()==$_POST['Del']){
      $img1 = $e->getimagen()[0];
      $img2 = $e->getimagen()[1];
      if($img1 != '**No Imagen**')
        unlink(imgDir.$img1);
      if($img2 != '**No Imagen**')
        unlink(imgDir.$img2);
      unset($pubs[$k]);
    }
  if(sizeof($pubs)==0){
    editDbFile($dir);
    $pubs[0]=new Publicacion();
  }
}


// print_r($pubs);
// $pubs[1]-> settitulo("Cambio de titulo");
// var_dump($pubs[1]->gettitulo());
if( isset($pubs[0]) != TRUE ){
  $newJson = [];
  // json_encode([$pubs[1]->toJSON(),$pubs[0]->toJSON()]);
  foreach($pubs as $i){
    // array_push($newJson,$i->toJSON());
    $newJson[$i->getid()] = $i->toJSON();
  }
  $newJson = json_encode($newJson);
  editDbFile($dir,$newJson);
}

$nRow = 1;
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

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
        <th scope="row"><?php echo $nRow++; ?></th>
        <td><?php echo $i->gettitulo(); ?></td>
        <td><?php echo $i->getfecha(); ?></td>
        <td><?php echo $i->getresumen(); ?></td>
        <td>
          <?php 
          if( isset($pubs[0]) )
            echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newModal"><i class="bi bi-journal-plus me-1" ></i>Publicacion</button>';
          else{
            echo "<form action=\".\" method=\"POST\"><button type=\"button\" class=\"btn btn-primary\" data-bs-toggle='modal' data-bs-target='#editModal' onclick='setFormValues(\"{$i->getid()}\");'><i class=\"bi bi-pencil-square\"></i></button>"; 
            echo '<button type="submit" class="btn btn-bary " id="Del" name="Del" value="'.$i->getid().'"><i class="bi bi-trash3" ></i></button> </form>'; 
            echo '<a href="http://localhost/git/RGuazapa/publicacion.php?id='.$i->getid().'" class="btn btn-primary"><i class="bi bi-eye-fill"></i></a>';
          }
          ?>
          
        </td>
      </tr>
    <?php }?>
  </tbody>
</table>

<div class="modal fade" id="editModal" name="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel" name="editModalLabel">Informacion de Publicacion</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="disableSubmit('Edit')"></button>
      </div>
      <div class="modal-body">
        <form action="." method="POST" class="row g-3" enctype="multipart/form-data">
          <div class="col-12">
            <label for="pubTitleEdit" class="form-label">Titulo de Publicación</label>
            <input type="text" class="form-control" id="pubTitleEdit" name="pubTitleEdit" onchange="validateSubmitTxt('Edit')">
          </div>
          <div class="col-12">
            <label for="autorEdit" class="form-label">Autor de Publicación</label>
            <input type="text" class="form-control" id="autorEdit" name="autorEdit" onchange="validateSubmitTxt('Edit')">
          </div>
          <div class="col-md-6">
            <label for="image1Edit" class="form-label">Imagen principal</label>
            <input type="file" class="form-control" id="image1Edit" name="image1Edit" accept="image/*" onchange="previewImg(event,'previewImg1Edit')" onblur="validateSubmitTxt('Edit')">
            <br>
            <p>Vista Previa:</p>
            <hr>
            <div class="container my-2">
              <img src="" id="previewImg1Edit" name="previewImg1Edit" alt="" class="img-fluid">
            </div>
            <hr>
          </div>
          <div class="col-md-6">
            <label for="image2Edit" class="form-label">Miniatura de publicacion</label>
            <input type="file" class="form-control" id="image2Edit" name="image2Edit" accept="image/*" onchange="previewImg(event,'previewImg2Edit')" onblur="validateSubmitTxt('Edit')">
            <br>
            <p>Vista Previa:</p>
            <hr>
            <div class="container my-2">
              <img src="" id="previewImg2Edit" name="previewImg2Edit" alt="" class="img-fluid">
            </div>
            <hr>
          </div>
          <div class="col-12">
            <label for="ytURLEdit" class="form-label">Enlace de video en YouTube</label>
            <input type="text" class="form-control" id="ytURLEdit" name="ytURLEdit" onchange="validateSubmitTxt('Edit')">
          </div>
          <div class="col-12">
            <label for="resumenEdit" class="form-label">Resumen</label>
            <textarea class="form-control" id="resumenEdit" name="resumenEdit" onchange="validateSubmitTxt('Edit')"></textarea>
          </div>
          <div class="col-12">
            <label for="contenidoEdit" class="form-label">Contenido principal</label>
            <textarea class="form-control" id="contenidoEdit" name="contenidoEdit" onchange="validateSubmitTxt('Edit')"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <div class="col-12 d-flex flex-row-reverse">
            <button type="submit" class="btn btn-primary mx-2 disabled" data-bs-dismiss="modal" name="Edit" id="Edit">Guardar</button>
            <button type="button" class="btn btn-bary" data-bs-dismiss="modal" onclick="disableSubmit('Edit')" >Cancelar</button>
          </div>
          <input type="text" name="pubEdit" id="pubEdit" class="d-none">
        </div>
      </form>
    </div>
  </div>
</div>
  
<div class="modal fade" id="newModal" name="newModal" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newModalLabel" name="newModalLabel">Informacion de Publicacion</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="disableSubmit('Edit')"></button>
      </div>
      <div class="modal-body">
        <form action="." method="POST" class="row g-3" enctype="multipart/form-data">
          <div class="col-12">
            <label for="pubTitleNew" class="form-label">Titulo de Publicación</label>
            <input type="text" class="form-control" id="pubTitleNew" name="pubTitleNew" onchange="validateSubmitTxt('New')">
          </div>
          <div class="col-12">
            <label for="autorNew" class="form-label">Autor de Publicación</label>
            <input type="text" class="form-control" id="autorNew" name="autorNew" onchange="validateSubmitTxt('New')">
          </div>
          <div class="col-md-6">
            <label for="image1New" class="form-label">Imagen principal</label>
            <input type="file" class="form-control" id="image1New" name="image1New" accept="image/*" onchange="previewImg(event,'previewImg1New')">
            <br>
            <p>Vista Previa:</p>
            <hr>
            <div class="container my-2">
              <img src="" id="previewImg1New" name="previewImg1New" alt="" class="img-fluid">
            </div>
            <hr>
          </div>
          <div class="col-md-6">
            <label for="image2New" class="form-label">Miniatura de publicacion</label>
            <input type="file" class="form-control" id="image2New" name="image2New" accept="image/*" onchange="previewImg(event,'previewImg2New')">
            <br>
            <p>Vista Previa:</p>
            <hr>
            <div class="container my-2">
              <img src="" id="previewImg2New" name="previewImg2New" alt="" class="img-fluid">
            </div>
            <hr>
          </div>
          <div class="col-12">
            <label for="ytURLNew" class="form-label">Enlace de video en YouTube</label>
            <input type="text" class="form-control" id="ytURLNew" name="ytURLNew" onchange="validateSubmitTxt('New')">
          </div>
          <div class="col-12">
            <label for="resumenNew" class="form-label">Resumen</label>
            <textarea class="form-control" id="resumenNew" name="resumenNew" onchange="validateSubmitTxt('New')"></textarea>
          </div>
          <div class="col-12">
            <label for="contenidoNew" class="form-label">Contenido principal</label>
            <textarea class="form-control" id="contenidoNew" name="contenidoNew" onchange="validateSubmitTxt('New')"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <div class="col-12 d-flex flex-row-reverse">
            <button type="submit" class="btn btn-primary mx-2 disabled" data-bs-dismiss="modal" name="New" id="New">Guardar</button>
            <button type="button" class="btn btn-bary" data-bs-dismiss="modal" onclick="disableSubmit('Edit')">Cancelar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <?php foreach ($pubs as $k => $i) {?>
      <br><hr><br>
      <div class='col-10 offset-1'>  
        <!-- <?php echo '<img width = "300" src="'.imgDir.$i->getimagen()->img1.'" alt="'.imgDir.$i->getimagen()->img1.'" />'; ?> -->
        <!-- <img src="" alt="" width = "100"> -->
      </div>
    <?php }?>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newModal">Nuevo</button>
  </div>
</div>
</body>
</html>