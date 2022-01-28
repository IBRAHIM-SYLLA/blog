<?php
    session_start();
    require_once('bdd.php');
    
    $title = 'Admin Update Categorie';

    $categorie = $_GET["update"];
    
    $requete = mysqli_query($connex, "SELECT nom FROM categories WHERE id = '$categorie'");
    $categories = mysqli_fetch_all($requete, MYSQLI_ASSOC);

    if (isset($_POST["back"])) {
        header("Location: categorie.php");
    }
    else if (isset($_POST["categorie"])) {
        $nom = $_POST["categorie"];
        $update = mysqli_query($connex, "UPDATE categories SET nom = '$nom' WHERE id = '$categorie'");
        header("Refresh:0"); 
    }
    
?>

<body>
    <header>
        <?php
            require_once('header.php');
        ?>
    </header>
     <main>
        <form action="" method="post">
            <input type="text" name="categorie"  value=<?php echo $categories[0]['nom']; ?>>
            <input type="submit" name="update" value="modifier">
            <input type="submit" name="back" value="retour">
        </form>
    </main>
    <footer>
        <?php
            require_once('footer.php');
        ?>
    </footer>
</body>
</html>