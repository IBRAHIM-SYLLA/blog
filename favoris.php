<?php
    session_start();

    require_once('bdd.php');
    
    $user = $_SESSION['user'][0]['id'];
        
    // requete permetant de compter le nombre d'article favoris pour l'utilisateur connecté
    $requete_nbr_article_favoris = mysqli_query($connex, "SELECT count(etat_favoris) as nbr_articles from favoris where id_utilisateur = '$user'");
    $nbr_article_favoris = mysqli_fetch_all($requete_nbr_article_favoris, MYSQLI_ASSOC);

    $nbr_article_par_page = 5; 

    $nbr_pages =  ceil($nbr_article_favoris[0]["nbr_articles"] / $nbr_article_par_page);

    if (isset($_GET["start"])) {
        $page = $_GET["start"];
    }    
    else {
        $page = 0;
    }

    $requete_favoris = mysqli_query($connex, "SELECT articles.id, article, login, nom, date from articles inner join utilisateurs on articles.id_utilisateur = utilisateurs.id inner join categories on articles.id_categorie = categories.id inner join favoris on favoris.id_article = articles.id where favoris.id_utilisateur = '$user' order by date desc limit $nbr_article_par_page OFFSET $page");
    $favoris = mysqli_fetch_all($requete_favoris, MYSQLI_ASSOC);
?>    

<html>
<body class="body--articles">
    <header>
        <?php
            require_once('header.php');            
        ?>
    </header>
    <main>
        <div id="flex_favoris">
            <?php  
                foreach ($favoris as $favori) {?>
                    <form class="favoris" action="article.php" method="get">
                        <div id="container-articles">
                            <div id="container-articles2">
                                <div class="postepar">
                                    <p>Posté par:<?php echo $favori['login']; ?></p>
                                </div>
                                <div class="postele">
                                    <p>Posté le :
                                        <?php echo $favori['date']; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="cat_art">
                                <p>Catégorie : <?php echo $favori['nom']; ?></p>
                            </div>
                            <div class="art_bnt">
                                <p><?php echo $favori['article']; ?></p>
                            </div>
                            <div class="position--bnt">
                                <button class="bnt_a" name="article" value='<?php echo $favori['id']; ?>'>Article</button>
                            </div>
                        </div>
                    </form>
                    <?php
                }
                $i = 1;
                $five = 0;
                while ($i <= $nbr_pages ) 
                { 
                    echo " <a href='?start=$five'>$i</a>&nbsp";
                    $five = $five + 5;
                    $i++;
                }
               
            ?>
        </div>
    </main>
    <footer>
        <?php require_once('footer.php'); ?>
    </footer>
</body>    
</html>
