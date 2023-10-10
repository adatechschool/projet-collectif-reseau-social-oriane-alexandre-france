<?php
// Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                //     // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                $verificationClickAbonnement = isset($_POST['abonnement']);
                $check = isset($_POST['désabonnement']);
                $followerId = $_SESSION['connected_id'];
                // if($verification) {
                  // on ne fait ce qui suit que si un formulaire a été soumis.
                  // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                  // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                //   $followerId = $_SESSION['connected_id'];
                //   $followingId = $user['id'];
​
                //Etape 3 : Petite sécurité
                // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                //   $authorId = intval($mysqli->real_escape_string($authorId));
                //   $postContent = $mysqli->real_escape_string($postContent);
​
                  //Etape 4 : construction de la requete
                $lInstructionSql = "INSERT INTO followers (id, followed_user_id, following_user_id) "
                                . "VALUES (NULL, " . $followerId . ", " . "'" . $userId . "');"
                                ;
                // echo $lInstructionSql;
                // Etape 5 : execution
                $ok = $mysqli->query($lInstructionSql);
​
                $SQLFollowers = "SELECT * FROM followers WHERE `followed_user_id`= $followerId AND `following_user_id`= $userId";
                $Appel = $mysqli->query($SQLFollowers);
​
                // $SQLDelete = "DELETE FROM followers WHERE `followed_user_id`= $followerId AND `following_user_id`= $userId";
                // $AppelDelete = $mysqli->query($SQLDelete);
​
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
                // }
                if($check){
                    $SQLDelete = "DELETE FROM followers WHERE `followed_user_id`= $followerId AND `following_user_id`= $userId";
                    $AppelDelete = $mysqli->query($SQLDelete);
​
                    if ( ! $AppelDelete)
                    {
                        echo "Impossible de se désabonner" . $mysqli->error;
                    } else
                    {
                        echo "Vous êtes bien désabonné de " . $user['alias'];
                        echo $_SESSION['connected_id'];
                        echo $userId;
                    }
                }
​
                if (!("wall.php?user_id=" . $userId == "wall.php?user_id=" . $_SESSION['connected_id'])) {
                ?>
                <?php
                    if (($Appel)) {

​
                ?>
                        <form action="wall.php?user_id=<?php echo $user['id']; ?>" method="post">
                        <input type="submit" name="désabonnement" class="button" value="Se désabonner" >
                        </form>


                <?php
                // echo "<pre>"  . print_r($AppelDelete,1) . "</pre>";
                    } else {
                ?>
                        <form action="wall.php?user_id=<?php echo $user['id']; ?>" method="post">
                        <input type="submit" name="abonnement" class="button" value="S'abonner">
                        </form>
                <?php
                    }
                }
                ?>
Réduire





Envoyer un message France Huon, Oriane Da Silva













































                  <!-- CREATION DE L'ABONNEMENT -->
                <?php
                $verificationClickLikes = isset($_POST['like']);
                $postId = Select

                if ($verificationClickLike) {
                    //construction de la requête pour insérer une nouvelle ligne dans la table likes
                    $insertRowInFollowers = "INSERT INTO likes (id, user_id, post_id) "
                    . "VALUES (NULL, " . $followerId . ", " . "'" . $userId . "');"
                    ;
                    //execution de la requête
                    $checkRowInsert = $mysqli->query($insertRowInFollowers);
                    if ($checkRowInsert) {
                        echo "Vous êtes abonné à " . $user['alias'];
                    } else {
                        echo "Impossible de s'abonner à " . $user['alias'];
                    }
                }
                ?>


                <form method="post">
                <input type="submit" name="like" class="button" value="Likez">
                </form>
