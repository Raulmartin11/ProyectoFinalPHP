<?php

    //Raúl Martín Garcés


    session_start();

    if (isset($_POST["out"])) {
      $_SESSION = "";
      session_destroy();
      header("location: index.php");
    }

    $_SESSION['edit'] = null;

    if(!isset($_SESSION['nombre']) && !isset($_SESSION['logueado'])) {
      $_SESSION['idUsuario'] = " ";
      $_SESSION['nombre'] = " ";
      $_SESSION['contraseña'] = " ";
      $_SESSION['admin'] = " ";
      $_SESSION['logueado'] = false;
    }

    include_once "conexion.php";

    if (isset($_POST['usuario']) && isset($_POST['pwd']) && !isset($_POST['email'])) {
      $idUsuarios = $lnk->query("SELECT * FROM usuarios WHERE nombre = '{$_POST['usuario']}' AND contrasena = '{$_POST['pwd']}';");
      if ($idUsuarios->num_rows) {
        $idUsuario = $idUsuarios->fetch_object();
        $_SESSION['idUsuario'] = $idUsuario->idUsuario;
        $_SESSION['nombre'] = $idUsuario->nombre;
        $_SESSION['contraseña'] = $idUsuario->contrasena;
        $_SESSION['admin'] = $idUsuario->admin;
        $_SESSION['logueado'] = true;
      }
    }
    if (isset($_POST['usuario']) && isset($_POST['pwd']) && isset($_POST['email'])) {
      $idUsuarios = $lnk->query("SELECT * FROM usuarios WHERE nombre = '{$_POST['usuario']}';");
      if ($idUsuarios->num_rows) {
        header("location: index.php");
      } else {
        $sql = "INSERT INTO `usuarios`(`idUsuario`, `nombre`, `contrasena`, `mail`) VALUES (NULL, '{$_POST['usuario']}', '{$_POST['pwd']}', '{$_POST['email']}');";
        $reg = $lnk->query($sql);

        $idUsuarios = $lnk->query("SELECT `idUsuario` FROM usuarios WHERE nombre = '{$_POST['usuario']}' ;");
        $idUsuario = $idUsuarios->fetch_object();

        $_SESSION['idUsuario'] = $idUsuario->idUsuario;
        $_SESSION['nombre'] = $_POST['usuario'];
        $_SESSION['contraseña'] = $_POST['pwd'];
        $_SESSION['admin'] = " ";
        $_SESSION['logueado'] = true;
      }
    }

    if (isset($_POST['texto'])) {
      if (isset($_SESSION['nombre']) == "" && isset($_POST['nombre'])) {
        $_SESSION['nombre'] = $_POST['nombre'];
      }
      $sql = "INSERT INTO `comentarios` (`idComentario`, `idNoticia`, `nombre`, `fecha`, `texto`) VALUES (NULL, '{$_SESSION['idNoticia']}', '{$_SESSION['nombre']}', CURDATE(), '{$_POST['texto']}');";
      $Nuevo = $lnk->query($sql);
      $_SESSION['idNoticia'] = null;
    }
    if (isset($_POST['delete']))
      $Eliminar =  $lnk->query("DELETE FROM comentarios WHERE idComentario = '{$_POST['delete']}';");

    $cantidad_resultados_por_pagina = 5;

    //Comprueba si está seteado el GET de HTTP
    if (isset($_GET["pagina"])) {

    //Si el GET de HTTP SÍ es una string / cadena, procede
    if (is_string($_GET["pagina"])) {

      //Si la string es numérica, define la variable 'pagina'
       if (is_numeric($_GET["pagina"])) {

         //Si la petición desde la paginación es la página uno
         //en lugar de ir a 'principal.php?pagina=1' se iría directamente a 'principal.php'
         if ($_GET["pagina"] == 1) {
           header("Location: index.php");
           die();
         } else { //Si la petición desde la paginación no es para ir a la pagina 1, va a la que sea
           $pagina = $_GET["pagina"];
        };

       } else { //Si la string no es numérica, redirige al principal (por ejemplo: principal.php?pagina=AAA)
         header("Location: index.php");
        die();
       };
    };

    } else { //Si el GET de HTTP no está seteado, lleva a la primera página (puede ser cambiado al principal.php o lo que sea)
    $pagina = 1;
    };

    //Define el número 0 para empezar a paginar multiplicado por la cantidad de resultados por página
    $empezar_desde = ($pagina-1) * $cantidad_resultados_por_pagina;

    $noticias_totales = $lnk->query("SELECT * FROM noticias;") ;

    $total_registros = mysqli_num_rows($noticias_totales);

    $total_paginas = ceil($total_registros / $cantidad_resultados_por_pagina);




    ?>
 <!DOCTYPE html>
 <html lang="es">
 <head>
 	<meta charset="utf-8" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/index.css"/>
  <link rel="stylesheet" type="text/css" href="css/estilo.css"/>
 </head>
 <body>
   <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <?php if ($_SESSION['nombre'] === " "): ?>
     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#login">Log in</button>
     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#register">Register</button>
    <?php else: ?>
     <form action="noticia.php" method="POST">
       <button type="submit" class="btn btn-primary">New notice</button>
     </form>
     <form action="index.php" method="POST">
       <button type="submit" class="btn btn-primary" name="out">Log out</button>
     </form>
     <?php if($_SESSION['admin'] === '1'): ?>
       <form action="admin.php" method="POST">
         <button type="submit" class="btn btn-primary">Administración</button>
       </form>
     <?php endif; ?>
    <?php endif; ?>
    <form action="soon.html" method="POST">
      <button type="submit" class="btn btn-primary" name="out">Video-Noticias</button>
    </form>
     <a class="titulo" href="index.php"><h2>La Notisia</h2><img src="imagenes/1f44c.png" class="imgtitulo"/></a>
     <form class="form-inline" method="get" action="index.php" >
       <input class="form-control" type="text" placeholder="Search" name="search">
       <button class="btn btn-success" type="submit">Search</button>
     </form>
   </nav>

<div class="modal fade" id="login">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Log in</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form method="POST" action="index.php">
          <div class="form-group">
            <label for="usuario">usuario:</label>
            <input type="text" class="form-control" name="usuario">
          </div>
          <div class="form-group">
            <label for="pwd">Contraseña:</label>
            <input type="password" class="form-control" name="pwd">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="register">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">register</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <form method="POST" action="index.php">
          <div class="form-group">
            <label for="usuario">Name:</label>
            <input type="text" class="form-control" name="usuario">
          </div>
          <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" name="pwd">
          </div>
          <div class="form-group">
            <label for="email">Email address:</label>
            <input type="email" class="form-control" name="email">
          </div>
          <button type="submit" class="btn btn-primary">Register</button>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="Comentario">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Comentarios</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form method="POST" action="index.php">
          <div class="form-group">
            <label for="texto">Comentario:</label>
            <textarea class="form-control" name="texto" rows="5"></textarea>
          </div>
          <button type="submit"class="btn btn-primary">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
 	<!-- Cuerpo -->
 	<div id="main">
 		<div class="jumbotron" style="text-align: center;">

      <?php if (isset($_GET['search']) && $_GET['search'] !== "") {
        $Noticias = $lnk->query("SELECT * FROM noticias WHERE titulo LIKE '%{$_GET['search']}%' ORDER BY fecha DESC LIMIT 1;");

      } else {
        $Noticias = $lnk->query("SELECT * FROM noticias ORDER BY fecha DESC LIMIT $empezar_desde, $cantidad_resultados_por_pagina ;");
      }
      while ($Noticia = $Noticias->fetch_object()):

      $usuarios = $lnk->query("SELECT nombre FROM usuarios WHERE idUsuario = $Noticia->idUsuario;");

      $usuario = $usuarios->fetch_object(); ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th colspan="2">
            <a href="<?= 'index.php?search='.$Noticia->titulo ?> " class="navbar-brand"><h3><?= $Noticia->titulo ?></h3></a><?php $_SESSION['idNoticia'] = $Noticia->idNoticia;?>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php if ($Noticia->portada): ?>
          <td>
            <img src="imagenes/<?= $Noticia->portada ?>" />
          </td>
          <?php endif; ?>
          <td>
            <p class="texto"><?= $Noticia->texto ?></p>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <p><?= $Noticia->fecha ?></p>
            <p><?= $usuario->nombre ?></p>
          </td>
        </tr>
        <?php if (isset($_GET['search']) && $_GET['search'] !== ""):
          $comentarios = $lnk->query("SELECT * FROM comentarios WHERE idNoticia = $Noticia->idNoticia;"); ?>
        <tr align="center" class="thead-dark" >
          <th colspan="2">Comentarios</th>
        </tr>
        <?php while ($comentario = $comentarios->fetch_object()): ?>
        <tr align="center">
          <td colspan="2">
            <form action="<?= 'index.php?search='.$Noticia->titulo ?>" method="post">
              <?php if($_SESSION['admin'] === '1'): ?>
                <button type="submit" class="close" name="delete" value="<?= $comentario->idComentario ?>">&times;</button>
              <?php endif; ?>
              <p class="texto"><?= $comentario->texto ?></p>
              <p class="textoNombre"><?= $comentario->nombre ?></p>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
        <?php if($_SESSION['nombre']): ?>
          <tfoot>
            <td colspan="2"><button type="button" class="form-control" data-toggle="modal" data-target="#Comentario">Deja tu comentario</button></td>
          </tfoot>
        <?php endif; ?>
      <?php endif; ?>
      </tbody>
    </table>
    <br>
  <?php endwhile; ?>
  <div id="pager">
    <?php
    if (!isset($_GET['search'])) {
      for ($i=1; $i<=$total_paginas; $i++) {
        //En el bucle, muestra la paginación
        echo "<a href='?pagina=".$i."' class='numpag'>".$i."</a> | ";
      };
    }
     ?>
  </div>
 		</div> <!-- jumbotron -->
 	</div> <!-- main -->
 </body>
 </html>
