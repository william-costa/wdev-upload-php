<?php

namespace App\File;

class Upload{

  /**
   * Nome do arquivo (sem extensão)
   * @var string
   */
  private $name;

  /**
   * Extensão do arquivo (sem ponto)
   * @var string
   */
  private $extension;

  /**
   * Type do arquivo
   * @var string
   */
  private $type;

  /**
   * Nome temporário/Caminho temporário do arquivo
   * @var string
   */
  private $tmpName;

  /**
   * Código de erro do upload
   * @var integer
   */
  private $error;

  /**
   * Tamanho do arquivo
   * @var integer
   */
  private $size;

  /**
   * Contador de duplicação de arquivo
   * @var integer
   */
  private $duplicates = 0;

  /**
   * Construtor da classe
   * @param array $file  $_FILES[campo]
   */
  public function __construct($file){
    $this->type    = $file['type'];
    $this->tmpName = $file['tmp_name'];
    $this->error   = $file['error'];
    $this->size    = $file['size'];

    $info = pathinfo($file['name']);
    $this->name      = $info['filename'];
    $this->extension = $info['extension'];
  }

  /**
   * Método responsável por alterar o nome do arquivo
   * @param string $name
   */
  public function setName($name){
    $this->name = $name;
  }

  /**
   * Método responsável por gerar um novo nome aleatório
   */
  public function generateNewName(){
    $this->name = time().'-'.rand(100000,999999).'-'.uniqid();
  }

  /**
   * Método responsável por retornar o nome do arquivo com sua extensão
   * @return string
   */
  public function getBasename(){
    //VALIDA EXTENSÃO
    $extension = strlen($this->extension) ? '.'.$this->extension : '';

    //VALIDA DUPLICAÇÃO
    $duplicates = $this->duplicates > 0 ? '-'.$this->duplicates : '';

    //RETORNA O NOME COMPLETO
    return $this->name.$duplicates.$extension;
  }

  /**
   * Método responsável por obter um nome possível para o arquivo
   * @param  string  $dir
   * @param  boolean $overwrite
   * @return string
   */
  private function getPossibleBasename($dir,$overwrite){
    //SOBRESCREVER ARQUIVO
    if($overwrite) return $this->getBasename();

    //NÃO PODE SOBRESCREVER ARQUIVO
    $basename = $this->getBasename();

    //VERIFICAR DUPLICAÇÃO
    if(!file_exists($dir.'/'.$basename)){
      return $basename;
    }

    //INCREMENTAR DUPLICAÇÕES
    $this->duplicates++;

    //RETORNO O PRÓPRIO MÉTODO
    return $this->getPossibleBasename($dir,$overwrite);
  }

  /**
   * Método responsável por mover o arquivo de upload
   * @param  string  $dir
   * @param  boolean $overwrite
   * @return boolean
   */
  public function upload($dir, $overwrite = true){
    //VERIFICAR ERRO
    if($this->error != 0) return false;

    //CAMINHO COMPLETO DE DESTINO
    $path = $dir.'/'.$this->getPossibleBasename($dir,$overwrite);

    //MOVE O ARQUIVO PARA A PASTA DE DESTINO
    return move_uploaded_file($this->tmpName,$path);
  }

  /**
   * Método responsável por criar instâncias de upload para multiplos arquivos
   * @param  array $files $_FILES['campo']
   * @return array
   */
  public static function createMultiUpload($files){
    $uploads = [];

    foreach($files['name'] as $key=>$value){
      //ARRAY DE ARQUIVO
      $file = [
        'name'     => $files['name'][$key],
        'type'     => $files['type'][$key],
        'tmp_name' => $files['tmp_name'][$key],
        'error'    => $files['error'][$key],
        'size'     => $files['size'][$key]
      ];

      //NOVA INSTANCIA
      $uploads[] = new Upload($file);
    }

    return $uploads;
  }

}