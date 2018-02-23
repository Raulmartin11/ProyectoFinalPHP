<?php
session_start();

if ($_SESSION['logueado'] == true ):

  include_once "conexion.php";
  if (isset($_FILES['portada']['name'])) {
    $nombre_archivo = $_FILES['portada']['name'];
    $tipo_archivo = $_FILES['portada']['type'];
    $tamano_archivo = $_FILES['portada']['size'];

    $destino=$_SERVER['DOCUMENT_ROOT'] . '/proyectofinal2elretorno/imagenes/';

    move_uploaded_file($_FILES['portada']['tmp_name'],$destino.$nombre_archivo);
  } else {
    $nombre_archivo = null;
  }
  if (isset($_POST['titulo']) && isset($_POST['texto']) && isset($_SESSION['edit'])) {
    $Editado = $lnk->query("UPDATE noticias SET titulo = '{$_POST['titulo']}', texto = '{$_POST['texto']}', portada = '$nombre_archivo' WHERE idNoticia = '{$_SESSION['edit']}';");
    header("Location: index.php");
  } elseif (isset($_POST['titulo']) && isset($_POST['texto']) ) {
    $sql = "INSERT INTO `noticias` (`idNoticia`, `idUsuario`, `fecha`, `titulo`, `texto`, `portada`) VALUES (NULL, '{$_SESSION['idUsuario']}', CURDATE(), '{$_POST['titulo']}', '{$_POST['texto']}', '$nombre_archivo');";
    $Nuevo = $lnk->query($sql);
    header("Location: index.php");
  }

  if (isset($_POST['edit'])) {
    $Editar =  $lnk->query("SELECT * FROM noticias WHERE idNoticia = '{$_POST['edit']}';");
    $editable = $Editar->fetch_object();
    $_SESSION['edit'] = $_POST['edit'];
  }
 ?>
 <!DOCTYPE html>
 <html lang="es">
  <head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/estilo.css"/>
    <link rel="stylesheet" type="text/css" href="css/noticia.css"/>
    <style media="screen">
      .header {
    color: #36A0FF;
    font-size: 27px;
    padding: 10px;
    }

    .bigicon {
        font-size: 35px;
        color: #36A0FF;
    }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
      <?php if (isset($_POST['edit'])): ?>
      <form action="noticia.php" method="POST">
        <button type="submit" class="btn btn-primary">New notice</button>
      </form>
      <?php endif; ?>
      <form action="index.php" method="POST">
        <button type="submit" class="btn btn-primary" name="out">Log out</button>
      </form>
        <form action="index.php" method="POST">
          <button type="submit" class="btn btn-primary">Principal</button>
        </form>
      <a class="titulo" href="index.php"><h2>La Notisia</h2><img src="imagenes/1f44c.png" class="imgtitulo"/></a>
    </nav>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="well well-sm">
            <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="noticia.php">
              <fieldset>
                <legend class="text-center header">New Notice</legend>
                <div class="form-group">
                  <span class="col-md-1 col-md-offset-2 text-center"><i class="fa fa-user bigicon"></i></span>
                  <div class="col-md-12">
                    <?php if (isset($_POST['edit'])): ?>
                      <input type="text" name="titulo" placeholder="Titulo" class="form-control" value="<?=$editable->titulo ?>">
                    <?php else: ?>
                      <input type="text" name="titulo" placeholder="Titulo" class="form-control">
                    <?php endif; ?>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                    <?php if (isset($_POST['edit'])): ?>
                      <textarea class="form-control" name="texto" rows="15"><?=$editable->texto ?></textarea>
                    <?php else: ?>
                      <textarea class="form-control" name="texto" rows="15"></textarea>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-1">
                    <label for="portada">Foto</label>
                  </div>
                  <div class="col-md-12">
                    <input type="file" name="portada" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
<?php endif; ?>
