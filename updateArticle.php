<?php
    session_start();
    require_once('bdd.php');


    $article = $_GET["updateArticle"];

    $requete = mysqli_query($connex, "SELECT article FROM articles WHERE id = '$article'");
    $articles = mysqli_fetch_all($requete, MYSQLI_ASSOC);

    if (isset($_POST["back"])) {
        header("Location: admin.php");
    }
    else if (isset($_POST["article"])) {
        $nomArticle = $_POST["article"];
        $update = mysqli_query($connex, "UPDATE articles SET article = '$nomArticle' WHERE id = '$article'");
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
            <input type="text" name="article"  value=<?php echo $articles[0]['article']; ?>>
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