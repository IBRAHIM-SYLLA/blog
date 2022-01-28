<?php
session_start();
require_once('bdd.php');

$title = 'Inscription';
$msg = "";

$requete= mysqli_query($connex, "SELECT * FROM utilisateurs");

    if( !empty($_POST["email"]) && !empty($_POST["login"]) && !empty($_POST["password"]) && !empty($_POST["confirmPassword"]))
    {
        $email=$_POST["email"];
        $login=$_POST["login"];
        $password=$_POST["password"];
        $confirmPassword=$_POST["confirmPassword"];
        $id_droits= 1;

        $requete3=mysqli_query($connex,"SELECT email FROM utilisateurs WHERE email='$email'");
        $result3=mysqli_fetch_all($requete3);

        if($password==$confirmPassword)
        {
            $passwordCrypted = password_hash($password, PASSWORD_BCRYPT);

            if(count($result3)==0)
            {
                $requete4=mysqli_query($connex,"SELECT login FROM utilisateurs WHERE login='$login'");
                $result4=mysqli_fetch_all($requete4);

                if(count($result4)==0)
                {   
                    $requete2 = mysqli_query($connex ,"INSERT INTO utilisateurs (email,login,password,id_droits) Values ('$email','$login','$passwordCrypted','$id_droits')");  
                    header('location: connexion.php');
                }
                else
                {
                    $msg = "* Ce login est déjà utilisé";
                }
            }
            else
            {
                $msg = "* un compte avec cet email existe déja";
            }  
        }
        else
        {
            $msg = "* Les mots de passe doivent être identiques";
        }
    }
    else
    {
        $msg = "* tout les champs doivent être remplis";
    }
?>

<html>
    <body>
        <header>
            <?php
                require_once('header.php');
            ?>
        </header>
        <main id="main2">
            <div id="centre2">
                <div id = "form2">
                    <form action="" method="post">
                        <input class="connect" type="text" id="email" name="email" placeholder="email">

                        <input class="connect" type="text" id="login" name="login" placeholder="Login">

                        <input class="connect" type="password" id="password" name="password" placeholder="password">

                        <input class="connect" type="password" id="confirmPassword" name="confirmPassword" placeholder="confirm password">

                        <input type="submit" value="inscription">
                    </form>
                    <p id="p2">Si vous êtes déja inscrit <a href="connexion.php">connecter-vous !</a></p>
                    <?php
                        echo $msg;
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