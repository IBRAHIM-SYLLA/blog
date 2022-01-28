<?php
    session_start();
    require_once ('bdd.php');
    
    $title = 'Articles';

    //Requete servant à récuperer le nbr d'article
    $requete2 = mysqli_query($connex, "SELECT count(id) as compteur_article from articles");
    $nbr_articles_totales = mysqli_fetch_all($requete2, MYSQLI_ASSOC);

    
    // création d'une variable pour fixer le nbr d'article par page
    $nbr_articles_par_pages = 5;

    // la fonction ceil permet d'arrondir au supérieur
    $nbr_pages =  ceil($nbr_articles_totales[0]["compteur_article"] / $nbr_articles_par_pages);
    //$nbr_page_par_categorie_articles = ;

    if (isset($_GET["start"])) {
        $page = $_GET["start"];
    }    
    else {
        $page = 0;
    }

    // requete pour récupérer les infos de la categorie
    $requete3 = mysqli_query($connex, "SELECT * from categories");
    $categories = mysqli_fetch_all($requete3, MYSQLI_ASSOC);

    if (isset($_GET["categorie"])) {
        $id_categorie = $_GET["categorie"];   
        //requete permettant de récupérer les articles par catégorie par date decroissante limité à 5
        $requete = mysqli_query($connex, "SELECT articles.id, article, nom, date, login  from articles inner join utilisateurs on articles.id_utilisateur = utilisateurs.id inner join categories on articles.id_categorie = categories.id  where id_categorie = $id_categorie ORDER BY date desc limit $nbr_articles_par_pages OFFSET $page");
        $articles = mysqli_fetch_all($requete, MYSQLI_ASSOC);
    }
   
    else {
        //requete permettant de récupérer tous les articles par date decroissante limité à 5
        $requete = mysqli_query($connex, "SELECT articles.id, article, nom, date, login  from articles inner join utilisateurs on articles.id_utilisateur = utilisateurs.id inner join categories on articles.id_categorie = categories.id ORDER BY date desc limit  $nbr_articles_par_pages OFFSET $page");
        $articles = mysqli_fetch_all($requete, MYSQLI_ASSOC);
    }
?>
<html>
<body class="body--articles">
    <header>
        <?php
            require_once('header.php');
        ?>
    </header>
    <main>
        <div class="choix--cat">
                <form action="" method="get">
                    <select class="connect" name="categorie">
                        <option>Choisir une catégorie d'article</option>
                        <?php
                            foreach($categories as $categorie) { ?>
                            <option value="<?=$categorie['id']?>"> <?= $categorie['nom']?></option>;
                        <?php }
                        ?>
                        <input type="submit"  value="executer">
                    </select>
                </form>
            </div>
            <div id="grande_div">
                <?php
                    foreach ($articles as $article) { ?>
                        <form action="article.php" method="get">
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
                        </form><?php
                    }
                ?>
            </div>
            <div id="pagination">
                <?php
                    if (isset($_GET["categorie"])) {
                        $id_categorie = $_GET["categorie"];
                        //Requete servant à compter le nbr d'articles par categorie
                        $requete_count = mysqli_query($connex, "SELECT count(article) as nbr_article_categorie, categories.nom from articles inner join categories on id_categorie = categories.id where id_categorie = $id_categorie LIMIT 5;");
                        $nbr_article_par_categorie = mysqli_fetch_all($requete_count, MYSQLI_ASSOC);
                        $nbr_pages =  ceil($nbr_article_par_categorie[0]["nbr_article_categorie"] / $nbr_articles_par_pages);

                        $five = 0;
                        for ($i = 1; $i <= $nbr_pages; $i++)
                        {
                            echo " <a href=articles.php?categorie=".$id_categorie."&start=".$five.">$i</a>&nbsp";
                            $five = $five + 5;
                        }
                    }
                    else {
                        $i = 1;
                        $five = 0;
                        while ($i <= $nbr_pages )
                        {
                            echo " <a href='?start=$five'>$i</a>&nbsp";
                                $five = $five + 5;
                                $i++;
                        }
                    }
                ?>
            </div>
    </main>
    <footer>
        <?php require_once('footer.php') ?>
    </footer>
</body>
</html>