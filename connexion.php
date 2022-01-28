<?php
    session_start();
    require_once('bdd.php');
    $title = 'Connexion';
    $msgError = "";

    if (!empty($_POST["login"]) && !empty($_POST["password"])) {
        $login = $_POST["login"];
        $password = $_POST["password"];
        $requete = mysqli_query($connex, "SELECT * FROM utilisateurs WHERE login = '$login'");
        $users = mysqli_fetch_all($requete, MYSQLI_ASSOC);

        if (count($users) != 0) {
            if (password_verify($password, $users[0]["password"])) {
                $_SESSION["user"] = $users;
                header("Location: index.php");
            }
            else {
                $msgError = "* Problem de mot de passe";
            }
        }
        else {
            $msgError = "* Ce login n'existe pas";
        }
    }
    else if (isset($_POST["login"]) && isset($_POST["password"])) {
        $msgError = "* Vous n'avez pas remplis tous les champs";
    }
?>

<html>
<body>
    <header>
        <?php
            require_once('header.php');
        ?>
    </header>
    <main id="main1">
        <div id="centre1">
            <div id="form1">
                <form action="connexion.php" method="post">
                    <input class="connect" type="text" id="login" name="login" placeholder="Login">

                    <input class="connect" type="password" id="password" name="password" placeholder="password">

                    <input type="submit" value="connexion">
                </form>
                <p id="p1">Si vous n'êtes pas inscrit <a href="inscription.php">inscrivez-vous !</a></p>
                <?php
                    echo $msgError;
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