<?php
$mysqli = new mysqli("localhost", "root", "root", "socialnetwork");

$userId =intval($_GET['user_id']);

if ($mysqli->connect_errno) {
    echo ("Échec de la connexion : " . $mysqli->connect_error);
    exit();
};




