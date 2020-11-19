<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Upload de arquivos WDEV (MULTI)</title>
  </head>
  <body>

    <h1>Upload de arquivos WDEV (MULTI)</h1>

    <form method="post" enctype="multipart/form-data">

      <label>Arquivo</label>
      <input type="file" name="arquivo[]" multiple>

      <br><br>

      <button type="submit">Enviar</button>

    </form>

  </body>
</html>