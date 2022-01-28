<?php
    session_start();
    require_once('bdd.php');
    
    $title = 'Admin Update';

    $user = $_GET["update"];
    $requete = mysqli_query($connex, "SELECT utilisateurs.id, login, email, id_droits, droits.nom as droits from utilisateurs inner join droits on  id_droits = droits.id WHERE utilisateurs.id = '$user'");
    $infos = mysqli_fetch_all($requete, MYSQLI_ASSOC);

    $errorLogin = "";
    $errorEmail = "";
    $errorPassword = "";
    $errorDroit = "";
    
    if (!empty($_POST["login"])) {
        $login = $_POST["login"];       

        $requete3 = mysqli_query($connex, "SELECT login FROM utilisateurs WHERE login = '$login'");
        $verifLogin = mysqli_fetch_all($requete3, MYSQLI_ASSOC);

        if (count($verifLogin) == 0) {
            $update = mysqli_query($connex, "UPDATE utilisateurs set login = '$login' WHERE id = '$user'");
            header("Refresh:0");
        }
        else {
            $errorLogin = "* Ce login est déjà pris";
        }
    }
    else if (!empty($_POST["email"])) {
        $email = $_POST["email"];
        
        $requete4 = mysqli_query($connex, "SELECT email FROM utilisateurs WHERE email = '$email'");
        $verifEmail = mysqli_fetch_all($requete4, MYSQLI_ASSOC);
        
        if (count($verifEmail) == 0) {            
            $update = mysqli_query($connex, "UPDATE utilisateurs set email = '$email' WHERE id = '$user'");
            header("Refresh:0");
        }
        else {
            $errorEmail = "* un compte avec cet email existe déjà";
        }
    }

    else if (!empty($_POST["id_droits"])) 
    {
        $droit = $_POST["id_droits"];
        
        if ($droit == "1" || $droit == "42" || $droit == "1337") {
            $update = mysqli_query($connex, "UPDATE utilisateurs set id_droits = '$droit' where id = '$user'");
            header("Refresh: 0");
        }
        else {
            $errorDroit = "les droits que vous donnez n'existe pas";
        }
    }

    else if (!empty($_POST["password"]) && !empty($_POST["confirmPassword"])) {
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];

        if ($password == $confirmPassword) {
            $hash = password_hash($password, PASSWORD_ARGON2I);
            $update = mysqli_query($connex, "UPDATE utilisateurs set password = '$hash' where id = '$user'");
        }
        else {
            $errorPassword = "* problème entre le mot de passe et la confirmation mot de passe";
        }
    }
    
    if (isset($_POST["back"])) {
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
            <fieldset>
                <legend>
                    <p>login</p>
                </legend>
                <form action="" method="post">
                    <input type="text" name="login" placeholder="login" value=<?php echo $infos[0]['login']; ?>>
                    <input type="submit" name="update_login" value="modifier">
                    <?php
                        echo $errorLogin;
                    ?>
                </form>
            </fieldset>            
            <fieldset>
                <legend>
                    <p>email</p>
                </legend>
                <form action="" method="post">
                    <input type="text" name="email" placeholder="email" value=<?php echo $infos[0]['email']; ?>>
                    <input type="submit" name="update_email" value="modifier">
                    <?php
                        echo $errorEmail;
                    ?>
                </form>
            </fieldset>
            <fieldset>
                <legend>
                    <p>Droits utilisateur</p>
                </legend>
                <form action="" method="post">
                    <input type="text" name="id_droits" placeholder="id_droits" value=<?php echo $infos[0]['id_droits']; ?>>
                    <!--<input type="text" name="droits" placeholder="droits" value=<?php echo $infos[0]['droits']; ?>>-->
                    <input type="submit" name="update_droits" value="modifier">
                    <?php
                        echo $errorDroit;
                    ?>
                </form>
            </fieldset>
            <fieldset>
                <legend>
                    Mot de passe
                </legend>
                <form action="" method="post">
                    <input type="text" name="password"  placeholder="password">
                    <input type="text" name="confirmPassword" placeholder=confirmPassword>
                    <input type="submit" name="update_password" value="modifier">
                    <?php
                        echo $errorPassword;
                    ?>
                </form>
            </fieldset>
        <form action="" method="post">     
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