<?php
  session_start();

  if ($_SESSION['logueado'] == true && $_SESSION['admin']):

    include_once "conexion.php";

    if (isset($_POST['delete']))
      $Eliminar =  $lnk->query("DELETE FROM noticias WHERE idNoticia = '{$_POST['delete']}';");

    if (isset($_POST['search']) || (!isset($_POST['search']) == "")) {
     $Noticias = $lnk->query("SELECT * FROM noticias WHERE titulo LIKE '%{$_POST['search']}%' ORDER BY fecha DESC;");
    } else {
     $Noticias =  $lnk->query("SELECT * FROM noticias ORDER BY fecha DESC;");
    }
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/admin.css"/>
    <link rel="stylesheet" type="text/css" href="css/estilo.css"/>
  </head>
  <body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
      <form action="noticia.php" method="POST">
        <button type="submit" class="btn btn-primary">New notice</button>
      </form>
      <form action="index.php" method="POST">
        <button type="submit" class="btn btn-primary" name="out">Log out</button>
      </form>
        <form action="index.php" method="POST">
          <button type="submit" class="btn btn-primary">Principal</button>
        </form>
      <a class="titulo" href="index.php"><h2>La Notisia</h2><img src="imagenes/1f44c.png" class="imgtitulo"/></a>
      <form class="form-inline" method="POST" action="admin.php" >
        <input class="form-control" type="text" placeholder="Search" name="search">
        <button class="btn btn-success" type="submit">Search</button>
      </form>
    </nav>
    <div id="main">
      <div class="jumbotron" style="text-align: center;">
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th>Titulo</th>
              <th>Autor</th>
              <th>Fecha</th>
              <th>Portada</th>
              <th>Nº Comentarios</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php while ($Noticia = $Noticias->fetch_object()):?>
              <?php
                $usuarios = $lnk->query("SELECT nombre FROM usuarios WHERE idUsuario = $Noticia->idUsuario;");
                $usuario = $usuarios->fetch_object();

                $nComentarios = mysqli_num_rows($lnk->query("SELECT * FROM comentarios WHERE idNoticia = $Noticia->idNoticia;"));
              ?>
            <tr>
              <td><?= $Noticia->titulo ?></td>
              <td><?= $usuario->nombre ?></td>
              <td><?= $Noticia->fecha ?></td>
              <?php if ($Noticia->portada): ?>
              <td>
                <img src="imagenes/<?= $Noticia->portada ?>" class="imgport"/>
              </td>
            <?php else: ?>
              <td>Null</td>
            <?php endif; ?>
            <td><?= $nComentarios ?></td>
              <form action="noticia.php" method="POST">
                <td width="5%"><button type="submit" class="btn btn-primary" name="edit" value="<?= $Noticia->idNoticia ?>">Editar</button></td>
              </form>
              <form action="admin.php" method="POST">
                  <td ><button type="submit" class="close" name="delete" value="<?= $Noticia->idNoticia ?>">&times;</button></td>
              </form>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>
<?php else:
  header("location: index.php");
  endif;
?>
