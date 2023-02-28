<?php
require "res/php/publicacion.class.php";

$id = $_GET["id"];
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

$actualPub = $pubs[$id];
var_dump(preg_split('/(\r\n)+/',$actualPub->getcontenido()));
// print_r($actualPub->getcontenido());
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php echo $actualPub->gettitulo(); ?>
  </title>
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="res/js/index.js"></script>
</head>
<body>
  <nav class="navbar sticky-top navbar-expand-lg bg-body shadow">
    <div class="container">
      <a class="navbar-brand d-none d-lg-block">
        <img src="paginaWeb/images/rguazapa.png" alt="Radio Guazapa" width="125" class="my-1">
      </a>
      <a class="navbar-brand d-lg-none">
        <img src="paginaWeb/images/rguazapa.png" alt="Radio Guazapa" width="50" class="my-3">
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
          <a class="nav-link fs-5" href="paginaWeb/index.html#home" target="_blank">Inicio</a>
          <a class="nav-link fs-5" href="paginaWeb/index.html#services" target="_blank">Nosotros</a>
          <a class="nav-link fs-5" href="paginaWeb/index.html#projects" target="_blank">Programación</a>
          <a class="nav-link fs-5" href="paginaWeb/index.html#team" target="_blank">Talento Humano</a>
          <a class="nav-link fs-5" href="paginaWeb/index.html#contacts" target="_blank">Contactos</a>
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
  <div class="container-fluid">
    <div class="row">
      <div class="offset-2 col-8 my-5">
        <?php
          $arrImg = $actualPub->getimagen();
          $bannImg0 = $arrImg[0] == "**No Imagen**" ? "generic.jpg" : $arrImg[0];
          if($arrImg[1] == "**No Imagen**")
            echo '<img class="img-fluid" src="'.imgDir.$bannImg0.'" alt="'.$bannImg0.'">';
          else{
            echo '<div id="carousel'.$actualPub->getid().'" class="carousel slide" data-bs-ride="carousel">';
            echo '  <div class="carousel-inner">';
            foreach($arrImg as $k => $v){
              echo $k==0?'    <div class="carousel-item active" data-bs-interval="7000">':'    <div class="carousel-item" data-bs-interval="7000">';
              echo '      <img src="'.imgDir.$v.'" class="d-block w-100" alt="'.$v.'">';
              echo '    </div>';
            }
            echo '  <button class="carousel-control-prev" type="button" data-bs-target="#carousel'.$actualPub->getid().'" data-bs-slide="prev">';
            echo '    <span class="carousel-control-prev-icon" aria-hidden="true"></span>';
            echo '    <span class="visually-hidden">Previous</span>';
            echo '  </button>';
            echo '  <button class="carousel-control-next" type="button" data-bs-target="#carousel'.$actualPub->getid().'" data-bs-slide="next">';
            echo '    <span class="carousel-control-next-icon" aria-hidden="true"></span>';
            echo '    <span class="visually-hidden">Next</span>';
            echo '  </button>';
            echo '</div>';
          }
        ?>
        <!-- <img class="img-fluid" .src1imgDir-1674538428.jpg" alt="1-1674538428"> -->
      </div>
      <div class="offset-2 col-8">
        <h1 class="display-5 my-2"><?php echo $actualPub->gettitulo(); ?></h1>
        <p class="fs-5 my-3"><?php echo $actualPub->getresumen(); ?></p>
        <hr>
        <p class="ms-5 ps-5"><strong>Por: <?php echo $actualPub->getautor(); ?></strong></p>
        <?php
        // var_dump($actualPub->getaudios());
        foreach (preg_split('/(\r\n)+/',$actualPub->getcontenido()) as $key => $value) {
          if(preg_match("/#a\d#/",$value)){
            $num = [];
            preg_match_all("/\d+/",$value,$num);
            // var_dump($num[0][0]-1);
            echo '<audio controls id="'.$actualPub->getaudios()[($num[0][0]-1)].'">
            <source src="'.audioDir.$actualPub->getaudios()[($num[0][0]-1)].'">
          El navegador no soporta los elementos de audio.
          </audio>';
          }
          else
            echo '<p class="lh-lg">'.$value."</p>\n";
        }
      ?>
      </div>
        <?php
          if($actualPub->getytURL() != ""){
            //https://youtu.be/
            echo '<div class="offset-3 col-6 my-3"><div class="ratio ratio-16x9">';
            $url = preg_replace("/https:\/\/youtu.be\//",'',$actualPub->getytURL());
            // var_dump($url);
            echo '<iframe src="https://www.youtube.com/embed/'.$url.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
            echo '</div></div>';
          }
        ?>
      <div class="offset-1 col-10">
        <div class="container">
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
      </div>
    </div>
  </div>

  <script src='https://c19.radioboss.fm/w/player.js?u=https%3A%2F%2Fc19.radioboss.fm%3A18090%2Fstream&amp;wid=19724'></script>
  <script src='https://c19.radioboss.fm/w/mplayer.js?u=https%3A%2F%2Fc19.radioboss.fm%3A18090%2Fstream&amp;wid=2696&amp;pw=82&amp;ca=%23111111&amp;cg=%23b3b3b3'></script>
</body>
</html>