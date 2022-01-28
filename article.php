<?php
    session_start();
    require_once('bdd.php');
    
    $title = 'Article';

    $recupArticle = $_GET['article'];

    $requete_id_article = mysqli_query($connex, "SELECT count(id) as nbr_id from articles where id = '$recupArticle'");
    $id_articles = mysqli_fetch_all($requete_id_article, MYSQLI_ASSOC);

    if ($id_articles[0]['nbr_id'] == '0') {
        header("Location: articles.php");
    }

    if (!empty($_SESSION)) {
        $user = $_SESSION["user"][0]['id'];

        $requete = mysqli_query($connex, "SELECT * FROM utilisateurs WHERE id = '$user'");
        $infoUser = mysqli_fetch_all($requete);
        // intval permet de transformer la string comprise dans le tableau de l'index id en entier.
        $idUser = intval($infoUser[0][0], $base = 10);

         //Requete permettant de récupérer l'id d'un like dislike
        $requete4 = mysqli_query($connex, "SELECT count(etat_like) as etat_like from intermediaire_article_like where id_utilisateur = '$user' and id_article = '$recupArticle' and etat_like = '1'");
        $select_etat_like = mysqli_fetch_all($requete4, MYSQLI_ASSOC);
        $etat_like = $select_etat_like[0]["etat_like"];

        $requete4 = mysqli_query($connex, "SELECT count(dislike) as dislike from intermediaire_article_like where id_utilisateur = '$user' and id_article = '$recupArticle' and dislike ='1'");
        $select_etat_dislike = mysqli_fetch_all($requete4, MYSQLI_ASSOC);
        $etat_dislike = $select_etat_dislike[0]["dislike"];

        // requete permettant de compter un article si il est present ou pas dans la table favoris
        $requete_favoris = mysqli_query($connex, "SELECT count(etat_favoris) as nbr_favoris from favoris where id_article = '$recupArticle' and id_utilisateur = '$user' and etat_favoris = '1'");
        $nbr_favoris = mysqli_fetch_all($requete_favoris, MYSQLI_ASSOC);
        $etat_favoris = $nbr_favoris[0]['nbr_favoris'];

        if (isset($_POST["like"])) {
            if ($etat_like == "0" && $etat_dislike == "1") {
                $requete_update_like = mysqli_query($connex, "UPDATE intermediaire_article_like SET id_article = '$recupArticle' , id_utilisateur = '$user', etat_like =  '1', dislike = '0' where id_article = '$recupArticle' and id_utilisateur = '$user'");
            }
            else if ($etat_like == "1" && $etat_dislike == "0") {
                $requete_delete_like = mysqli_query($connex, "DELETE from intermediaire_article_like where id_article ='$recupArticle' and id_utilisateur = '$user'");
            }
            else {
                $requete_like = mysqli_query($connex, "INSERT into intermediaire_article_like (id_article, id_utilisateur, etat_like, dislike) values ('$recupArticle', '$user', '1', '0')");
            }
        }
        else if (isset($_POST["dislike"])) {
            if ($etat_like == "1" && $etat_dislike == "0") {
                $requete_dislike = mysqli_query($connex, "UPDATE intermediaire_article_like SET id_article = '$recupArticle' , id_utilisateur = '$user', etat_like =  '0', dislike = '1' where id_article = '$recupArticle' and id_utilisateur = '$user'");
            }
            else if ($etat_like == "0" && $etat_dislike == "1") {
                $requete_delete_like = mysqli_query($connex, "DELETE from intermediaire_article_like where id_article ='$recupArticle' and id_utilisateur = '$user'");
            }
            else {
                $requete_dislike = mysqli_query($connex, "INSERT into intermediaire_article_like (id_article, id_utilisateur, etat_like, dislike) values ('$recupArticle', '$user', '0', '1')");
            }
        }
        else if (isset($_POST["favoris"])) {
            if ($etat_favoris == '0') {
                //requete permettant d'inserer un article dans la table favoris
                $requete_insert_favoris = mysqli_query($connex, "INSERT INTO favoris (id_utilisateur,id_article,etat_favoris) values ('$user','$recupArticle','1')");
                header("refresh: 0");
            }
            else if ($etat_favoris == '1') {
                $requete_delete_favoris = mysqli_query($connex, "DELETE from favoris where id_article = '$recupArticle' and id_utilisateur = '$user' and etat_favoris = '1'");
                header("refresh: 0");
            }
        }
    }

    $requete2 = mysqli_query($connex, "SELECT articles.id, article, nom, date, login  from articles inner join utilisateurs inner join categories on articles.id_utilisateur = utilisateurs.id and articles.id_categorie = categories.id where articles.id = '$recupArticle'");
    $articles = mysqli_fetch_all($requete2, MYSQLI_ASSOC);

    if (!empty($_POST["message"])) {
        $msg = $_POST["message"];
        $requete = mysqli_query($connex, "INSERT into commentaires (commentaire, id_article, id_utilisateur) values ('$msg', '$recupArticle', '$idUser')");
    }
?>

<html>
<body>
    <header>
        <?php
            require_once('header.php');
        ?>
    </header>
    <main id="mainarticle">
        <div class="art">
            <div>
                <?php
                    echo $articles[0]['login'].' '.date_format(date_create($articles[0]['date']), 'd/m/Y H:i:s').' ';
                    echo $articles[0]['nom'];
                    echo $articles[0]['article'];

                    $id_article = $articles[0]["id"];

                    $requeteLike = mysqli_query($connex, "SELECT count(etat_like) as nbr_like from intermediaire_article_like where id_article = '$id_article' and etat_like = 1");
                    $nbr_like = mysqli_fetch_all($requeteLike, MYSQLI_ASSOC);
                    $requeteLike = mysqli_query($connex, "SELECT count(dislike) as nbr_dislike from intermediaire_article_like where id_article = '$id_article' and dislike = 1");
                    $nbr_dislike = mysqli_fetch_all($requeteLike, MYSQLI_ASSOC);
                ?>
            </div>
            <div id="form_fld">

                <form class="form--fld" action="" method="post">
                    <button name="favoris"><i class="fa-regular fa-bookmark"></i></i></button>
                    <button name="like"><i class="fa-regular fa-thumbs-up"></i></button><input type="text" readonly value="<?php echo $nbr_like[0]["nbr_like"] ?>">
                    <button name="dislike"><i class="fa-regular fa-thumbs-down"></i></button><input readonly type="text" value="<?php echo $nbr_dislike[0]["nbr_dislike"] ?>">

                </form>

            </div>

        </div>
        <div>
            <h1 id="titre_comment">Commentaire</h1>
            <?php
                if (!empty($_SESSION)){
                    echo "<h3>Donner votre avis</h3>
                    <div>
                        <form class='avis' action='' method='post'>
                            <textarea name='message' id='msg' cols='1g0' rows='10' placeholder='Donner votre avis ici'></textarea>
                            <input class='submitavis' type='submit' id='buton' value='envoyer'>
                        </form>
                    </div>";
                }
            ?>
        </div>
            <?php
                $requete3 = mysqli_query($connex, "SELECT commentaire, commentaires.date, login, articles.id from articles inner join commentaires on articles.id = commentaires.id_article  inner join utilisateurs on utilisateurs.id = commentaires.id_utilisateur where articles.id = '$recupArticle' ORDER by date DESC");
                $comArticles = mysqli_fetch_all($requete3, MYSQLI_ASSOC);
            ?>
            <div id="grandcontainer">
                <?php
                    foreach ($comArticles as $comArticle){?>
                        <div id="containercomment">
                            <div id="entête">
                                    <div class="login">
                                         <?php echo "<div id ='poster'>Posté le :"." ".date_format(date_create($comArticle['date']), 'd/m/Y H:i:s').' '.'</div><div id="par">Posté par :'.' '.$comArticle['login'].'</div>';?>
                                    </div>
                                    <textarea name="" id="commentaire" cols="30" rows="10" readonly><?php echo $comArticle['commentaire']?></textarea>
                                </fieldset>
                            </div>
                        </div><?php
                    }
                ?>
            </div>
        </div>
    </main>
    <footer>
        <?php
            require_once('footer.php');
        ?>
    </footer>
</body>
</html>