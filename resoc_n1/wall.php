<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mur</title>
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>
            <img src="resoc.jpg" alt="Logo de notre réseau social"/>
            <nav id="menu">
                <a href="news.php">Actualités</a>
                <a href="wall.php?user_id=5">Mur</a>
                <a href="feed.php?user_id=5">Flux</a>
                <a href="tags.php?tag_id=1">Mots-clés</a>
            </nav>
            <nav id="user">
                <a href="#">Profil</a>
                <ul>
                    <li><a href="settings.php?user_id=5">Paramètres</a></li>
                    <li><a href="followers.php?user_id=5">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=5">Mes abonnements</a></li>
                </ul>

            </nav>
        </header>
        <div id="wrapper">
            <?php
            /**
             * Etape 1: Le mur concerne un utilisateur en particulier
             * La première étape est donc de trouver quel est l'id de l'utilisateur
             * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
             * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
             * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
             */
            // Etape 2: se connecter à la base de donnée
            include 'variables.php';
            ?>

            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
                // echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <?php
               // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                //     // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                $verification = isset($_POST['abonnement']);
                // $check = isset($_POST['désabonnement']);
                if($verification) {
                  // on ne fait ce qui suit que si un formulaire a été soumis.
                  // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                  // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                  $followerId = $_SESSION['connected_id'];
                //   $followingId = $user['id'];

                //Etape 3 : Petite sécurité
                // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                //   $authorId = intval($mysqli->real_escape_string($authorId));
                //   $postContent = $mysqli->real_escape_string($postContent);

                  //Etape 4 : construction de la requete
                  $lInstructionSql = "INSERT INTO followers (id, followed_user_id, following_user_id) "
                                . "VALUES (NULL, " . $followerId . ", " . "'" . $userId . "');"
                                ;
                // echo $lInstructionSql;

                $SQLFollowers = "SELECT * FROM followers WHERE `followed_user_id`= $followerId AND `following_user_id`= $userId";
                $Appel = $mysqli->query($SQLFollowers);

                $SQLDelete = "DELETE FROM `followers` WHERE `followed_user_id`= $followerId AND `following_user_id`= $userId";
                $AppelDelete = $mysqli->query($SQLDelete);

                  // Etape 5 : execution
                  $ok = $mysqli->query($lInstructionSql);
                  // echo "<pre>" . print_r($ok, 1) . "</pre>";
                  if ( ! $ok)
                  {
                      echo "Impossible de s'abonner" . $mysqli->error;
                  } else
                  {
                      echo "Vous êtes bien abonné à " . $user['alias'];
                      echo $_SESSION['connected_id'];
                      echo $userId;
                  }
                }
                if (!("wall.php?user_id=" . $userId == "wall.php?user_id=" . $_SESSION['connected_id'])) {
                ?> 
                <?php 
                    if (($Appel)) {
                        

                ?>
                        <form action="wall.php?user_id=<?php echo $user['id']; ?>" method="post">
                        <input type="submit" name="désabonnement" class="button" value="Se désabonner" >
                        </form>
                        
                    
                <?php
                echo "<pre>"  . print_r($AppelDelete,1) . "</pre>";
                    } else {
                ?>
                        <form action="wall.php?user_id=<?php echo $user['id']; ?>" method="post">
                        <input type="submit" name="abonnement" class="button" value="S'abonner">
                        </form>
                <?php
                    }
                }
                ?>
                    <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias']; ?>
                        (n° <?php echo $userId ?>)
                    </p>
                </section>
            </aside>
            <main>
               <?php
               // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                //     // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                $enCoursDeTraitement = isset($_POST['post']);
                if($enCoursDeTraitement) {
                  // on ne fait ce qui suit que si un formulaire a été soumis.
                  // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                  // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                  $authorId = $_POST['id'];
                  $postContent = $_POST['post'];

                //Etape 3 : Petite sécurité
                // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                  $authorId = intval($mysqli->real_escape_string($authorId));
                  $postContent = $mysqli->real_escape_string($postContent);

                  //Etape 4 : construction de la requete
                  $lInstructionSql = "INSERT INTO posts (id, user_id, content, created, parent_id) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW(), "
                                . "NULL);"
                                ;
                        // echo $lInstructionSql;

                  // Etape 5 : execution
                  $ok = $mysqli->query($lInstructionSql);
                  // echo "<pre>" . print_r($ok, 1) . "</pre>";
                  if ( ! $ok)
                  {
                      echo "Impossible d'ajouter le message: " . $mysqli->error;
                  } else
                  {
                      echo "Message posté en tant que :" . $user['alias'];
                  }
                }

                if ("wall.php?user_id=" . $user['id'] == "wall.php?user_id=" . $_SESSION['connected_id']) {
               ?>
                  <form action="wall.php?user_id=<?php echo $user['id']; ?>" method="post">
                  <input type='hidden' name='id' value="<?php echo $user['id']; ?>">
                      <dl>
                          <dt><label for="auteur">Bonjour <?php echo $user['alias']; ?></label></dt>
                          <dt><label for="post">Quoi de neuf ?</label></dt>
                          <dd><textarea name="post" cols="30" rows="10"></textarea></dd>
                      </dl>
                      <input type="submit">
                  </form>
                <?php } else {
                    echo "vous ne pouvez pas publier sur ce mur";
                    echo $_SESSION['connected_id'];

                  }
                ?>

                <?php
                /**
                 * Etape 3: récupérer tous les messages de l'utilisatrice
                 */
                $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name,
                    count(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id
                    LEFT JOIN likes      ON likes.post_id  = posts.id
                    WHERE posts.user_id='$userId'
                    GROUP BY posts.id
                    ORDER BY posts.created DESC
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                /**
                 * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
                 */
                while ($post = $lesInformations->fetch_assoc())
                {

                    // echo "<pre>" . print_r($post, 1) . "</pre>";
                    ?>

                    <article>
                        <h3>
                            <time datetime='2020-02-01 11:12:13' > <?php echo $post['created']; ?></time>
                        </h3>
                        <address>par <a href="wall.php?user_id=<?php echo $userId; ?>"><?php echo $post['author_name']; ?></a></address>
                        <div>
                            <p><?php echo $post['content']; ?></p>
                        </div>
                        <footer>
                            <small>♥ <?php echo $post['like_number']; ?></small>
                            <a href="">#<?php echo $post['taglist']; ?></a>
                        </footer>
                    </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
