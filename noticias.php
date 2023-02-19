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

$nRow = 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Noticias Radio Guazapa</title>
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
        <a class="nav-link fs-5" href="index.html#home" target="_blank">Inicio</a>
        <a class="nav-link fs-5" href="index.html#services" target="_blank">Nosotros</a>
        <a class="nav-link fs-5" href="index.html#projects" target="_blank">Programación</a>
        <a class="nav-link fs-5" href="index.html#team" target="_blank">Talento Humano</a>
        <a class="nav-link fs-5" href="index.html#contacts" target="_blank">Contactos</a>
        <a class="nav-link active fs-5" href="noticias.php"><u>Noticias</u></a>
      </div>
    </div>
    <div class="position-relative d-none d-lg-block ms-5">
      <audio id="radio" controls preload src="http://aler.org:8000/radioGuazapa#.ogg"></audio>
      <div id="radionocturna">
        <div id='rbcloud_player19724'></div>
        <span class="position-absolute end-0 start-0 translate-middle badge rounded-pill bg-danger">
          Pruebame
        </span>
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
        <th scope="col" class="text-center" class="text-center">Miniatura</th>
        <th scope="col" class="text-center">Fecha Publicación</th>
        <th scope="col" class="text-center">Resumen / Introducción</th>
        <th scope="col" class="text-center"></th>
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
              echo '<a href="'.MainUrl.'publicacion.php?id='.$i->getid().'" class="btn btn-success btn-sm"><i class="bi bi-eye-fill">visualizar</i></a>';
            ?>
          </td>
        </tr>
      <?php }?>
    </tbody>
  </table>
</div>
<div class="container fixed-bottom">
  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top bg-secondary bg-gradient">
    <div class="col-md-4 d-flex align-items-center">
      <a href="https://radioguazapa.org" class="mb-3 mb-md-0 ms-3 me-2 text-white lh-1 text-decoration-none">Radio Guazapa</a>
      <span class="mb-3 mb-md-0 text-white">&copy; 2023</span>
    </div>
    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
      <li class="ms-3"><a class="text-white" href="#https://www.facebook.com/radioguazapa" target="_blank">
        <i class="bi bi-facebook"></i>
      </a></li>
      <li class="ms-3"><a class="text-white" href="https://twitter.com/radioguazapa" target="_blank">
        <i class="bi bi-twitter"></i>
      </a></li>
      <li class="ms-3"><a class="text-white" href="https://wa.me/50373624088" target="_blank">
        <i class="bi bi-whatsapp"></i>
      </a></li>
      <li class="mx-3"><a class="text-white" href="https://www.instagram.com/radioguazapa" target="_blank">
        <i class="bi bi-instagram"></i>
      </a></li>
    </ul>
  </footer>
</div>
<script src='https://c19.radioboss.fm/w/player.js?u=https%3A%2F%2Fc19.radioboss.fm%3A18090%2Fstream&amp;wid=19724'></script>
<script src='https://c19.radioboss.fm/w/mplayer.js?u=https%3A%2F%2Fc19.radioboss.fm%3A18090%2Fstream&amp;wid=2696&amp;pw=82&amp;ca=%23111111&amp;cg=%23b3b3b3'></script>
</body>
</html>