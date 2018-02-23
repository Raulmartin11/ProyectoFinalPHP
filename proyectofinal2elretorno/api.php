<?php

  try {
    $lnk = new pdo("mysql:host=localhost;dbname=proyectofinal2elretorno","root","");
  } catch (PDOException $e) {
        echo "No se ha podido establecer conexión con el servidor de bases de datos.<br>";
        die ("Error: " . $e->getMessage());
  }

  // Respuesta
  $ans = [] ;
  // Buscamos en la URL la API_KEY del usuario.
  $api = $_GET["api_key"] ;

  if (empty($api)) {
    $ans["status_message"] = "Invalid API key: You must be granted a valid key." ;
    $ans["success"] = false ;

  } else {

    //codigos de ejemplo:
    //4f6647464a612d8da22ee04fcaf640dfc0d1196e
    //60084079e47ff106644d1b8dc4ccf14f5922217a

    //URL de ejemplo:
    //http://localhost/proyectofinal2elretorno/noticias/4f6647464a612d8da22ee04fcaf640dfc0d1196e
    $sql = "SELECT * FROM noticias WHERE SHA1(CONCAT(noticias.idNoticia,noticias.titulo)) = '$api';";
    $res = $lnk->query($sql) ;
    if ($res->rowCount() == 0) {
      $ans["status_message"] = "No se encuentra la notisia en la base de datos." ;
      $ans["success"] = false ;
    } else {
      $asg = [];
      $data = $res->fetchAll(PDO::FETCH_ASSOC);

      $ans["success"] = true ;
      $ans["data"] = $data ;
    }
  }
  // Respondemos a la petición
  header("Content-Type: application/json;charset=utf-8") ;
  echo json_encode($ans) ;
