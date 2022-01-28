<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/connexion.css">
  <link rel="stylesheet" href="css/inscription.css">
  <link rel="stylesheet" href="css/profil.css">
  <link rel="stylesheet" href="css/categorie.css">
  <link rel="stylesheet" href="css/article.css">
  <link rel="stylesheet" href="css/articles.css">
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="css/delete.css">

  <title>MUGIWARA</title>
</head>
<html>
  <header>
    <div id="header">
        <div>
          <h1 id="spacenet">LE BLOG DES MUGIWARAS</h1>
        </div>
        <div id="h3">
            <p>Le blog consacrer a ONE PIECE</p>
        </div>
    </div>
    <?php
      if (!empty($_SESSION)) {
        echo "<p id='bonjour_user'>Bonjour " .$_SESSION['user'][0]['login']." !</p>";
      }
    ?>
  </header>
  <?php
      require_once('navbar.php');
  ?>
