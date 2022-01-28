<?php
    session_start();
    require_once('bdd.php');
    
    $title = 'Categorie';

    $requeteCategorie = mysqli_query($connex, "SELECT * FROM categories");
    $dataCategories = mysqli_fetch_all($requeteCategorie, MYSQLI_ASSOC);

    if(isset($_POST["categorie"])) {
        $categorie = $_POST["categorie"];

        $requete = mysqli_query($connex, "INSERT into categories (nom) VALUES ('$categorie')");
    }
?>

<html>
<body class="page_cat">
    <header>
        <?php
            require_once('header.php');
        ?>
    </header>
    <main class="categorie">
        <div class="form_cat">
            <form action="categorie.php" method="post">
                <input class="input_cat" type="submit" name="affichCategorie" value="afficher les catégories">
                <input class="input_cat" type="submit" name="creatCategorie" value="Créer une categorie">
                <input class="input_cat" type="submit" name="X" value="X">
                <input class="input_cat" type="submit" name="back" value="Retour">
            </form>
        </div>
            <?php
                if (isset($_POST["creatCategorie"])) { ?>
                        <div id="containeur_cat">
                            <form class="form_creat_cat" action="categorie.php" method="post">
                                <div class="divp">
                                <input class="connect" type="text" name="categorie" placeholder="categorie">
                                <input type="submit" name="creerCategorie" value="creer">
                                </div>
                            </form>
                        </div>
                <?php
            }
                else if (isset($_POST["back"])) {
                    header("Location: admin.php");
                }
                else if (isset($_POST["affichCategorie"]))
                {?>
                    <table class="table_cat">
                        <thead>
                            <th>ID</th>
                            <th>NOM</th>
                        </thead>
                        <tbody class="td_cat">
                            <?php
                                foreach($dataCategories as $dataCategorie)
                                {
                                    echo "<tr><td>" . $dataCategorie['id'] . "</td>";
                                    echo "<td>" . $dataCategorie['nom'] . "</td>";?>
                                          <td>
                                            <form action="updateCategorie.php" method="get">
                                                    <button class="input_bnt" name='update'  value=<?php echo $dataCategorie["id"]; ?>>Modifier</button>
                                                </form>
                                          </td>
                                          <td>
                                            <form action="deleteCategorie.php" method="get">
                                                <button class="input_bnt2" name="delete" value=<?php echo $dataCategorie["id"]; ?> >Supprimer</button>
                                            </form>
                                          </td></tr>
                                    <?php
                                }
                }
                            ?>
                        </tbody>
                    </table>
    </main>
    <footer>
        <?php
            require_once('footer.php');
        ?>
    </footer>
</body>
</html>