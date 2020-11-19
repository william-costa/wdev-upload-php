<?php

require __DIR__.'/vendor/autoload.php';

use \App\File\Upload;

if(isset($_FILES['arquivo'])){

  //INSTANCIA DE UPLOAD
  $obUpload = new Upload($_FILES['arquivo']);

  //ALTERA O NOME DO ARQUIVO
  // $obUpload->setName('novo-arquivo-com-nome-alterado');

  //GERA UMA NOME ALEATÓRIO
  $obUpload->generateNewName();

  //MOVE OS ARQUIVOS DE UPLOAD
  $sucesso = $obUpload->upload(__DIR__.'/files',false);
  if($sucesso){
    echo 'Arquivo <strong>'.$obUpload->getBasename().'</strong> enviado com sucesso!';
    exit;
  }

  die('Problemas ao enviar o arquivo');

}

include __DIR__.'/includes/formulario.php';