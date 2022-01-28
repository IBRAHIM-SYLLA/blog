<?php
session_start();
require_once('bdd.php');

$idArticle = $_GET['deleteArticle'];
$selectarticle = mysqli_query($connex, "SELECT id, article FROM articles WHERE id = '$idArticle'");
$article = mysqli_fetch_all($selectarticle, MYSQLI_ASSOC);

if (isset($_POST["delete"])) {
    $delete = mysqli_query($connex, "DELETE FROM articles WHERE id = '$idArticle'");
    header("Location: admin.php");
}
else if (isset($_POST["back"])) {
    header("Location: admin.php");
}

?>
<body>
    <header>
        <?php
            require_once('header.php');
        ?>
    </header>
    <main>
        <h1 id="supp">Supprimer des articles</h1>

        <p id="voulez-vous">Voulez-vous supprimer l'article dont l'id est <?php echo $article[0]['id']; ?> et le titre est <?php echo $article[0]['article']; ?> ?</p>
            <form action="" method="post">
                    <input type="submit" name="delete" id="oui" value="oui">
                    <input type="submit" name="back" id="non" value="non">
            </form>
    </main>
    <footer>
        <?php
            require_once('footer.php');
        ?>
    </footer>
</body>
</html>