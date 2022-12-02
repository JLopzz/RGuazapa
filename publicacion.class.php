<?php
class Publicacion{
  private $titulo;
  private $imagen;
  private $contenido;
  private $fpublicacion;
  private $fvencimiento;

  public function __construct() {
    $get_arguments       = func_get_args();
    $number_of_arguments = func_num_args();

    if (method_exists($this, $method_name = '__construct'.$number_of_arguments)) {
        call_user_func_array(array($this, $method_name), $get_arguments);
    }
}

public function __construct1($json) {
  $arr = json_decode($json);
  $this->titulo=$arr->titulo;
  $this->imagen=$arr->imagen;
  $this->contenido=$arr->contenido;
  $this->fpublicacion=$arr->fpublicacion;
  $this->fvencimiento=$arr->fvencimiento;
  
}
  public function __construct5($titulo = '',$imagen = ['',''],$contenido = '',$fpublicacion,$fvencimiento){
    $this->titulo=$titulo;
    $this->imagen=$imagen;
    $this->contenido=$contenido;
    $this->fpublicacion=$fpublicacion;
    $this->fvencimiento=$fvencimiento;
  }
  /** Setters */
  public function settitulo($titulo){
    $this->titulo = $titulo;
  }
  public function setimagen($img1,$img2){
    $this->imagen = [$img1,$img2];
  }
  public function setcontenido($contenido){
    $this->contenido = $contenido;
  }
  public function setfpublicacion($fpublicacion){
    $this->fpublicacion = $fpublicacion;
  }
  public function setfvencimiento($fvencimiento){
    $this->fvencimiento = $fvencimiento;
  }

  /** Getters */
  public function gettitulo(){
    return $this->titulo;
  }
  public function getimagen(){
    return $this->imagen;
  }
  public function getcontenido(){
    return $this->contenido;
  }
  public function getfpublicacion(){
    return $this->fpublicacion;
  }
  public function getfvencimiento(){
    return $this->fvencimiento;
  }
  
  /**Conversion en varios formatos */
  /** a JSON */
  public function toJSON() {
    return json_encode(get_object_vars($this));
  }
  public function toArray() {
    return get_object_vars($this);
  }
} 
/**
    public function __construct() {
        $get_arguments       = func_get_args();
        $number_of_arguments = func_num_args();

        if (method_exists($this, $method_name = '__construct'.$number_of_arguments)) {
            call_user_func_array(array($this, $method_name), $get_arguments);
        }
    }

    public function __construct1($argument1) {
        echo 'constructor with 1 parameter ' . $argument1 . "\n";
    }

    public function __construct2($argument1, $argument2) {
        echo 'constructor with 2 parameter ' . $argument1 . ' ' . $argument2 . "\n";
    }

    public function __construct3($argument1, $argument2, $argument3) {
        echo 'constructor with 3 parameter ' . $argument1 . ' ' . $argument2 . ' ' . $argument3 . "\n";
    }
     */