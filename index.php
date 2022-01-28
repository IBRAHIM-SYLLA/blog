<?php
    session_start();
    require_once('bdd.php');
    
    $title = 'Accueil';

    $requete = mysqli_query($connex, "SELECT articles.id, article, date, login, nom from articles inner join utilisateurs on id_utilisateur = utilisateurs.id inner join categories on id_categorie = categories.id  order by  articles.id desc limit 3");
    $articles = mysqli_fetch_all($requete, MYSQLI_ASSOC);
?>

<html>
    <main>
        <?php
            require_once('header.php');
        ?>
        <div id="bloc">
            <img id="one" src="images/drapeaux.jpg" alt="">
        </div>
        <div id="h1">
            <div>
                <h1 id="titre">Nos derniers articles</h1>
            </div>
        </div>
        <div class="flex">
            <?php
                foreach ($articles as $article) { ?>
                    <form id="index" action="article.php" method="get">
                                <div id="container-articles">
                                    <div id="container-articles2">
                                        <div class="postepar">
                                            <p>Posté par:
                                            <?php echo $article['login']; ?></p>
                                        </div>
                                        <div class="postele">
                                            <p>Posté le :
                                            <?php echo $article['date']; ?></p>
                                        </div>
                                    </div>
                                    <div class="cat_art">
                                        <p>Catégorie : <?php echo $article['nom']; ?></p>
                                    </div>
                                    <div class="art_bnt">
                                        <?php echo $article['article']; ?></p>
                                    </div>
                                    <div class="position--bnt">
                                        <button class="bnt_a" name="article" value='<?php echo $article['id']; ?>'>Article</button>
                                    </div>
                                </div>
                            </form>
                    <?php
                } ?>
        </div>
    </main>
    <footer>
        <?php 
            require_once('footer.php')
        ?>
    </footer>        
</body>
</html>