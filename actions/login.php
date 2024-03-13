<?php

include "../classes/User.php";

// Criate an obj
$user =new User;

$user->login($_POST);

//  $_POST - holds the data form the form views> index.php/login page

// $_POST['username'];
// $_POST['password];
?>