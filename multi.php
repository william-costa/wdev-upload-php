<?php

require __DIR__.'/vendor/autoload.php';

use \App\File\Upload;

if(isset($_FILES['arquivo'])){

  $uploads = Upload::createMultiUpload($_FILES['arquivo']);

  foreach($uploads as $obUpload){
    //NOVO NOME
    $obUpload->generateNewName();
    
    //MOVE OS ARQUIVOS DE UPLOAD
    $sucesso = $obUpload->upload(__DIR__.'/files',false);
    if($sucesso){
      echo 'Arquivo <strong>'.$obUpload->getBasename().'</strong> enviado com sucesso!<br>';
      continue;
    }

    echo 'Problemas ao enviar o arquivo <br>';
  }

  exit;
}

include __DIR__.'/includes/formulario-multi.php';