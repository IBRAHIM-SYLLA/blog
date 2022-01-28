<?php
    session_start();
    require_once ('bdd.php');
    
    $title = 'Admin';
    $error = "";

    $dataUsers = mysqli_query($connex, "SELECT * FROM utilisateurs");
    $infoUsers = mysqli_fetch_all($dataUsers, MYSQLI_ASSOC);
    //var_dump($infoUsers);

    $dataArticles = mysqli_query($connex, "SELECT articles.id, article, nom, date, login  from articles inner join utilisateurs on articles.id_utilisateur = utilisateurs.id inner join categories on articles.id_categorie = categories.id ");
    $infoArticles = mysqli_fetch_all($dataArticles, MYSQLI_ASSOC);
    //var_dump($infoArticles);

    if (!empty($_POST["login"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["id_droits"]) && !empty($_POST["confirmPassword"]))
    {
        $login = $_POST["login"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];
        $idDroit = $_POST["id_droits"];
        $requete = mysqli_query($connex, "SELECT login From utilisateurs WHERE login = '$login'");
        $requet2 = mysqli_query($connex, "SELECT email FROM utilisateurs WHERE email = '$email'");
        $verifLogin = mysqli_fetch_all($requete, MYSQLI_ASSOC);
        $verifEmail = mysqli_fetch_all($requet2, MYSQLI_ASSOC);

        if (count($verifLogin) == 0) {
            if (count($verifEmail) == 0) {
                if($password == $confirmPassword) {
                    $hash = password_hash($password, PASSWORD_ARGON2I);
                    $creeUser = mysqli_query($connex, "INSERT into utilisateurs (login, password,  email, id_droits) VALUES ('$login', '$hash', '$email', '$idDroit')");
                    header("Location: admin.php");
                }
                else {
                    $error = "* error password";
                }
            }
            else {
                $error = "* un compte avec cet email existe déjà";
            }
        }
        else {
            $error = "* Ce login existe déjà";
        }
    }
    else if (isset($_POST["login"]) || isset($_POST["email"]) || isset($_POST["password"]) && isset($_POST["id_droits"])) {
        $error = "* oublis de champs";
    }

    if (isset($_POST["creerCatégorie"])) {
        header("Location: categorie.php");
    }
?>

<html>
<body>
    <header>
        <?php
            require_once ('header.php');            
        ?>
    </header>
    <main>
    <div class="page-admin">
        <div class="conteneur-admin">
            <div class ="conteneur-buttons-admin">
                <form action="admin.php" method="post">
                    <input class="butt-site" type="submit" name="createUser" value="Créer un utilisateur">
                    <input class="butt-site"  type="submit" name="afficheUser" value="Afficher les utilisateurs">
                    <input type="submit" name="afficheArticle" value="Afficher les articles">
                    <input type="submit" name="categorie" value="Catégorie">
                    <input class="butt-site" type="submit" name="X" value="X">
                </form>
            </div>
            <?php
                if (isset($_POST["createUser"])) {?>
                    <div id='centre_admin'>
                        <div id='form_admin'>
                            <form action='admin.php' method='post'>
                                <input class='connect' type='text' name='login' placeholder='login'></br>
                                <input class='connect' type='email' name='email' placeholder='defaut@exemple.com'></br>
                                <input class='connect' type='text' name='id_droits' placeholder='id_droits'></br>
                                <input class='connect' type='text' name='password' placeholder='password'></br>
                                <input class='connect' type='text' name='confirmPassword' placeholder='confirPassword'></br>
                                <input type='submit' name='bouton' value='créer'>
                            </form>
                        </div>
                    </div>";
                    <?php
                }
                echo "<p>" . $error . "</p>";
                if (isset($_POST["afficheUser"])) { ?>
                    <div class="parent-table">
                        <table class="table_cat">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>login</th>
                                    <th>email</th>
                                    <th>id_droits</th>
                                    <th>password</th>
                                    <th>Lire</th>
                                    <th>Modifier</th>
                                    <th>Supprimer</th>
                                </tr>
                            </thead>
                            <tbody class="td_cat">
                            <?php
                                foreach($infoUsers as $infoUser) {
                                    $subrstrPass = substr($infoUser['password'],0 ,10);
                                    echo '  <tr>
                                                <td id="idAdmin" class="textAdmin">' . $infoUser["id"] . '</td>
                                                <td class="textAdmin">' . $infoUser["login"] . '</td>
                                                <td class="textAdmin">' . $infoUser["email"] . '</td>
                                                <td class="textAdmin">' . $infoUser["id_droits"] . '</td>
                                                <td class="textAdmin">' . $subrstrPass. '...' . '</td>';
                            ?>
                                                <td>
                                                    <form action="read.php" method="get">
                                                        <button class="input_bnt" type="submit" name="read" id="read" value=<?php echo $infoUser["id"] ?>>Lire</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="update.php" method="get">
                                                        <button class="input_bnt" type="submit" name="update" id="update" value=<?php echo $infoUser["id"]; ?>>modifier</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="delete.php" method="get">
                                                        <button class="input_bnt2" type="submit" name="delete" id="delete" value=<?php echo $infoUser["id"]; ?>>supprimer</button>
                                                    </form>
                                                </td>
                                            </tr>
                                <?php
                            }
                }
                else if (isset($_POST["afficheArticle"])) { ?>
                    <table class="table_cat">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>article</th>
                                <th>nom</th>
                                <th>date</th>
                                <th>login</th>
                            </tr>
                        </thead>
                        <tbody class="td_cat">
                            <?php
                                foreach($infoArticles as $infoArticle){ ?>
                                    <tr>
                                        <td><?php echo $infoArticle['id']; ?></td>
                                        <td><?php echo $infoArticle['article']; ?></td>
                                        <td><?php echo $infoArticle['nom']; ?></td>
                                        <td><?php echo $infoArticle['date']; ?></td>
                                        <td><?php echo $infoArticle['login']; ?></td>
                                        <td>
                                            <form action="deleteArticle.php" method="get">
                                                <button class="input_bnt2" type="submit" name="deleteArticle" value=<?php echo $infoArticle['id']; ?>>Supprimer</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="updateArticle.php" method="get">
                                                <button class="input_bnt" type="submit" name="updateArticle" value=<?php echo $infoArticle['id']; ?>>Modifier</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                  <?php
                }
                else if (isset($_POST["categorie"])) {
                    header("Location: categorie.php");
                }
                ?>
                            </tbody>
                        </table>
                    </div>
        </div>
    </div>
    </main>
    <footer>
        <?php require_once('footer.php'); ?>
    </footer>
</body>
</html>
