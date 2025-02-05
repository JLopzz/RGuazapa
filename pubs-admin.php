<?php
require "res/php/publicacion.class.php";

$pubs = [];
if(filesize(jsonDir)==0){
  $pubs[0]=new Publicacion();
}
else{
  $myfile = fopen(jsonDir, "r") or die("Unable to open file!");
  $readedFile = fread($myfile,filesize(jsonDir));
  fclose($myfile);
  $json =  json_decode($readedFile);
  foreach ($json as $k => $i) {
    $pubs[$k] = new Publicacion($i);
  }
}

//Escribir sobre Archivo JSON de Base de Datos
function editDbFile($d,$c=""){
  $myfile = fopen($d, "w") or die("Unable to open file!");
  fwrite($myfile, $c);
  fclose($myfile);
  return true;
}

/***
 * Carga de Archivos
 * Parametros:
 *    $file : Arreglo de $_FILES[]
 *    $name : Nombre a asignarle al archivo subido.
 *    $type : Tipo de archivo a subir 
 *            1 - imagen
 *            2 - audio
 * Respuesta:
 *    Devuelve nombre del archivo cargado en caso de exito
 *    En caso de fallo, devuelve "**No Imagen**"
 */

 function uploadFiles($file = [], $name = null,$type = 0){
  if($type == 1){
    $extencion = [];
    preg_match("/\.\w+$/",$file['name'],$extencion);
    $img = $name . $extencion[0];
    if(move_uploaded_file($file['tmp_name'], imgDir.$img)) return $img;
    else return '**No Imagen**';
  }
  elseif ($type == 2) {
    $auds = [];
    $c = count($file['name']);
    for( $i=0 ; $i < $c ; $i++ ) {
      $extencion = [];
      preg_match("/\.\w+$/",$file['name'][$i],$extencion);
      $aud = ($i+1) . '-' . $name . $extencion[0];
      if(move_uploaded_file($file['tmp_name'][$i], audioDir.$aud)) array_push($auds,$aud);
      else array_push($auds,'**No Audio**');
    }
    return $auds;
  }
  else return false; 
}

/**
 * Evaluacion de variable de entorno $_POST['New']
 * Manejo de Archivos:
 *  Imagenes:
 *    Se evalua si el archivo tiene algun nombre asignado, para pasar al proceso de carga
 *      pasando como nombre el id de publicacion.
 *  Audios:
 *    Se evalua si el array de nombres es mayor que cero (0), para asi decirle si empezar el
 *      proceso de carga al servidor.
 */
if(isset($_POST['New'])){
  $newPubId = time();
  $newImg1 = $_FILES["image1New"]['name'] != "" ?
    uploadImg($_FILES["image1New"],'1-'.$newPubId) :
    '**No Imagen**';
  $newImg2 = $_FILES["image2New"]['name'] != "" ?
    uploadImg($_FILES["image2New"],'2-'.$newPubId) :
    '**No Imagen**';
    $newAudio = sizeof($_FILES["audioNew"]['name']) > 0 ?
      uploadFiles($_FILES["audioNew"],$newPubId,2) :
      ['**No Audio**'];
  if($pub = new Publicacion(
      $newPubId,
      $_POST['pubTitleNew'],
      [$newImg1,$newImg2],
      date('d/m/Y'),
      $_POST['contenidoNew'],
      $_POST['resumenNew'],
      $_POST['autorNew'],
      $_POST['ytURLNew'],
      $newAudio)){
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
  $pub = $pubs[$_POST['pubEdit']];
  if($pub->gettitulo() != $_POST["pubTitleEdit"])
    $pub->settitulo($_POST["pubTitleEdit"]);
  if($pub->getresumen() != $_POST["resumenEdit"])
    $pub->setresumen($_POST["resumenEdit"]);
  if($pub->getcontenido() != $_POST["contenidoEdit"])
    $pub->setcontenido($_POST["contenidoEdit"]);
  if($pub->getautor() != $_POST["autorEdit"])
    $pub->setautor($_POST["autorEdit"]);
  if($pub->getytURL() != $_POST["ytURLEdit"])
    $pub->setytURL($_POST["ytURLEdit"]);
  $imgE1 = $pub->getimagen()[0];
  $imgE2 = $pub->getimagen()[1];
  if($_FILES["image1Edit"]["name"] != "")
    $imgE1 = uploadImg($_FILES["image1Edit"],'1-'.$_POST["pubEdit"]);
  if($_FILES["image2Edit"]["name"] != "")
    $imgE2 = uploadImg($_FILES["image2Edit"],'2-'.$_POST["pubEdit"]);
  $pub->setimagen($imgE1,$imgE2);
  $audE = $pub->getaudios();
  if(sizeof($_FILES["audioEdit"]['name']) > 0 && $_FILES["audioEdit"]['name'][0] != "")
    $audE = uploadFiles($_FILES["audioEdit"],$_POST["pubEdit"],2);
  $pub->setaudios($audE);
  $pubs[$_POST["pubEdit"]] = $pub;
}
else if(isset($_POST['Del'])){
  foreach ($pubs as $k => $e)
    if($e->getid()==$_POST['Del']){
      $imgs = $e->getimagen();
      // $img2 = $e->getimagen()[1];
      if($imgs[0] != '**No Imagen**')
        unlink(imgDir.$imgs[0]);
      if($imgs[1] != '**No Imagen**')
        unlink(imgDir.$imgs[1]);
      if(sizeof($e->getaudios()) > 0)
        foreach($e->getaudios() as $e)
          unlink(audioDir.$e);
      unset($pubs[$k]);
    }
  if(sizeof($pubs)==0){
    editDbFile(jsonDir);
    $pubs[0]=new Publicacion();
  }
}

if( isset($pubs[0]) != TRUE ){
  $newJson = [];
  krsort($pubs);
  foreach($pubs as $i){
    // array_push($newJson,$i->toJSON());
    $newJson[$i->getid()] = $i->toJSON();
  }
  $newJson = json_encode($newJson);
  editDbFile(jsonDir,$newJson);
}
$nRow = 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administracion de publicaciones | Radio Guazapa</title>
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="res/js/index.js"></script>
</head>
<body>
<nav class="navbar sticky-top navbar-expand-lg bg-body shadow">
  <div class="container">
    <a class="navbar-brand d-none d-lg-block">
      <img src="images/rguazapa.png" alt="Radio Guazapa" width="125" class="my-1">
    </a>
    <a class="navbar-brand d-lg-none">
      <img src="images/rguazapa.png" alt="Radio Guazapa" width="50" class="my-3">
    </a>
    <div class="position-relative d-lg-none">
      <audio id="radiom" controls preload src="http://aler.org:8000/radioGuazapa#.ogg"></audio>
      <div id="radionocturnam">
        <div id='rbcloud_mplayer2696'></div>
        <span class="position-absolute end-0 start-0 translate-middle badge rounded-pill bg-danger">
          Pruebame
        </span>
      </div>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link fs-5 me-5" href="noticias.php" target="_blank">Noticias</a>
        <button type="button" class="btn btn-success fs-5 mx-5" data-bs-toggle="modal" data-bs-target="#newModal"><i class="bi bi-journal-plus me-3"></i>Nueva Publicación</button>
      </div>
    </div>
  </div>
</nav>
<div class="container mt-5">
  <table class="table align-middle">
    <thead>
      <tr>
        <th scope="col" class="text-center">#</th>
        <th scope="col" class="text-center">Título</th>
        <th scope="col" class="text-center">Miniatura</th>
        <th scope="col" class="text-center">Fecha Publicación</th>
        <th scope="col" class="text-center">Resumen / Introducción</th>
        <th scope="col" class="text-center">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($pubs as $k => $i) {?>
        <tr>
          <th scope="row"><?php echo $nRow++; ?></th>
          <td><?php echo $i->gettitulo(); ?></td>
          <td><div class="d-flex justify-content-center">
            <?php
              $arrImg = $i->getimagen();
              $miniatura = $arrImg[1] == "**No Imagen**" ? "generic.jpg" : $arrImg[1];
              echo '<img height="75" src="'.imgDir.$miniatura.'" alt="'.$miniatura.'">';
            ?>
          </div></td>
          <td><?php echo $i->getfecha(); ?></td>
          <td><?php echo $i->getresumen(); ?></td>
          <td class="display-flex">
            <?php 
              if( isset($pubs[0]) )
                echo '<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newModal"><i class="bi bi-journal-plus me-1" ></i> Nueva Publicación</button>';
              else{
                // echo '<form action="./pubs-admin.php" method="POST">';//<div class="btn-group">';
                // echo '<a href="'.MainUrl.'publicacion.php?id='.$i->getid().'" class="btn btn-outline-success  w-100 my-1"><i class="bi bi-eye-fill"></i> Visualizar</a>';
                echo '<a href="publicacion.php?id='.$i->getid().'" class="btn btn-outline-success  w-100 my-1" target="_blank"><i class="bi bi-eye-fill"></i> Visualizar</a>';
                echo '<button type="button" class="btn btn-outline-success w-100 my-1" data-bs-toggle="modal" data-bs-target="#editModal" onclick="setFormValues(\''.$i->getid().'\');"><i class="bi bi-pencil-square"></i> Editar</button>'; 
                echo '<button type="button" class="btn btn-outline-danger w-100 my-1" data-bs-toggle="modal" data-bs-target="#delModal'.$i->getid().'"><i class="bi bi-trash3" ></i> Eliminar</button>';
                echo '<div class="modal fade" id="delModal'.$i->getid().'" name="delModal'.$i->getid().'" tabindex="-1" aria-labelledby="delModalLabel" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Confirme por favor</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      ¿Eliminar la publicacion con titulo <br><b> `'.$i->gettitulo().'`</b>?
                    </div>
                    <div class="modal-footer">
                      <form action="./pubs-admin.php" method="POST">
                        <button type="button" class="btn btn-danger w-100 my-1" id="Del" name="Del" value="'.$i->getid().'"><i class="bi bi-trash3" ></i> Eliminar</button>
                      </form>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>';
                // echo /*'</div>*/'</form>'; 
              }
            ?>
          </td>
        </tr>
      <?php }?>
    </tbody>
  </table>

  <div class="modal fade" id="editModal" name="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel" name="editModalLabel">Informacion de Publicacion</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearForm('Edit')"></button>
        </div>
        <div class="modal-body">
          <form action="pubs-admin.php" method="POST" class="row g-3" enctype="multipart/form-data">
            <div class="col-12">
              <label for="pubTitleEdit" class="form-label">Titulo de Publicación*</label>
              <input type="text" class="form-control" id="pubTitleEdit" name="pubTitleEdit" onchange="validateSubmitTxt('Edit')">
            </div>
            <div class="col-12">
              <label for="autorEdit" class="form-label">Autor de Publicación*</label>
              <input type="text" class="form-control" id="autorEdit" name="autorEdit" onchange="validateSubmitTxt('Edit')">
            </div>
            <div class="col-12">
              <hr>
              <div class="row">
                <div class="col-md-6 col-12">
                  <label for="image1Edit" class="form-label">Imagen principal</label>
                  <input type="file" class="form-control" id="image1Edit" name="image1Edit" accept="image/*" onchange="previewImg(event,'previewImg1Edit')" onblur="validateSubmitTxt('Edit')">
                  <br>
                  <p>Vista Previa:</p>
                  <div class="container my-2">
                    <img src="" id="previewImg1Edit" name="previewImg1Edit" alt="" class="img-fluid">
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <label for="image2Edit" class="form-label">Miniatura de publicacion</label>
                  <input type="file" class="form-control" id="image2Edit" name="image2Edit" accept="image/*" onchange="previewImg(event,'previewImg2Edit')" onblur="validateSubmitTxt('Edit')">
                  <br>
                  <p>Vista Previa:</p>
                  <div class="container my-2">
                    <img src="" id="previewImg2Edit" name="previewImg2Edit" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12">
              <hr>
              <label for="audioEdit" class="form-label">Audios de la Publicacion</label>
              <input type="file" class="form-control" id="audioEdit" name="audioEdit[]" accept="audio/wav, audio/mp3, audio/ogg" multiple="multiple" onchange="previewAudio(event,'Edit')" onblur="validateSubmitTxt('Edit')">
              <br>
              <p>Vista Previa:</p>
              <div class="container my-2">
                <div id="previewAudioEdit" name="previewAudioEdit" ></div>
              </div>
              <hr>
            </div>
            <div class="col-12">
              <label for="ytURLEdit" class="form-label">Enlace de video en YouTube</label>
              <input type="text" class="form-control" id="ytURLEdit" name="ytURLEdit" onchange="validateSubmitTxt('Edit')">
            </div>
            <div class="col-12">
              <label for="resumenEdit" class="form-label">Resumen*</label>
              <textarea style="resize: none;" rows="3" class="form-control" id="resumenEdit" name="resumenEdit" onchange="validateSubmitTxt('Edit')"></textarea>
            </div>
            <div class="col-12">
              <label for="contenidoEdit" class="form-label">Contenido principal*</label>
              <textarea style="resize: none;" rows="5" class="form-control" id="contenidoEdit" name="contenidoEdit" onchange="validateSubmitTxt('Edit')"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <div class="col-12 d-flex flex-row-reverse">
              <button type="submit" class="btn btn-primary mx-2 disabled" data-bs-dismiss="modal" name="Edit" id="Edit">Guardar</button>
              <button type="button" class="btn btn-bary" data-bs-dismiss="modal" onclick="clearForm('Edit')" >Cancelar</button>
            </div>
            <input type="text" name="pubEdit" id="pubEdit" class="d-none">
          </div>
        </form>
      </div>
    </div>
  </div>
    
  <div class="modal fade" id="newModal" name="newModal" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="newModalLabel" name="newModalLabel">Informacion de Publicacion</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="clearForm('New')"></button>
        </div>
        <div class="modal-body">
          <form action="pubs-admin.php" method="POST" class="row g-3" enctype="multipart/form-data">
            <div class="col-12">
              <label for="pubTitleNew" class="form-label">Titulo de Publicación*</label>
              <input type="text" class="form-control" id="pubTitleNew" name="pubTitleNew" onchange="validateSubmitTxt('New')">
            </div>
            <div class="col-12">
              <label for="autorNew" class="form-label">Autor de Publicación*</label>
              <input type="text" class="form-control" id="autorNew" name="autorNew" onchange="validateSubmitTxt('New')">
            </div>
            <div class="col-md-6 col-12">
              <label for="image1New" class="form-label">Imagen principal</label>
              <input type="file" class="form-control" id="image1New" name="image1New" accept="image/*" onchange="previewImg(event,'previewImg1New')"> 
               <!-- onblur="validateSubmitTxt('New')"> -->
              <br>
              <p>Vista Previa:</p>
              <hr>
              <div class="container my-2">
                <img src="" id="previewImg1New" name="previewImg1New" alt="" class="img-fluid">
              </div>
              <hr>
            </div>
            <div class="col-md-6 col-12">
              <label for="image2New" class="form-label">Miniatura de publicacion</label>
              <input type="file" class="form-control" id="image2New" name="image2New" accept="image/*" onchange="previewImg(event,'previewImg2New')">
               <!-- onblur="validateSubmitTxt('New')"> -->
              <br>
              <p>Vista Previa:</p>
              <hr>
              <div class="container my-2">
                <img src="" id="previewImg2New" name="previewImg2New" alt="" class="img-fluid">
              </div>
              <hr>
            </div>
            <div class="col-12">
              <hr>
              <label for="audioNew" class="form-label">Audios de la Publicacion</label>
              <input type="file" class="form-control" id="audioNew" name="audioNew" accept="audio/wav, audio/mp3, audio/ogg" onchange="previewAudio(event,'New')" multiple="multiple">
              <br>
              <p>Vista Previa:</p>
              <div class="container my-2">
                <div id="previewAudioNew" name="previewAudioNew" ></div>
              </div>
              <hr>
            </div>
            <div class="col-12">
              <label for="ytURLNew" class="form-label">Enlace de video en YouTube</label>
              <input type="text" class="form-control" id="ytURLNew" name="ytURLNew" onchange="validateSubmitTxt('New')">
            </div>
            <div class="col-12">
              <label for="resumenNew" class="form-label">Resumen*</label>
              <textarea style="resize: none;" rows="3" class="form-control" id="resumenNew" name="resumenNew" onchange="validateSubmitTxt('New')"></textarea>
            </div>
            <div class="col-12">
              <label for="contenidoNew" class="form-label">Contenido principal*</label>
              <textarea style="resize: none;" rows="5" class="form-control" id="contenidoNew" name="contenidoNew" onchange="validateSubmitTxt('New')"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <div class="col-12 d-flex flex-row-reverse">
              <button type="submit" class="btn btn-primary mx-2 disabled" data-bs-dismiss="modal" name="New" id="New">Guardar</button>
              <button type="button" class="btn btn-bary" data-bs-dismiss="modal" onclick="clearForm('New')">Cancelar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Button trigger modal -->
  <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#delModal'.$i->getid().'">
    Launch demo modal
  </button> -->

  <!-- Modal -->
  <!-- <div class="modal fade" id="delModal'.$i->getid().'" name="delModal'.$i->getid().'" tabindex="-1" aria-labelledby="delModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div> -->
</div>
</body>
</html>