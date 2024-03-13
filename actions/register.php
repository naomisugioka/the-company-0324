<?php

include "../classes/User.php";

// Criate an obj
$user =new User;

$user->store($_POST);

//  $_POST - holds the data form the form views> register.php

// $_POST['first_name'];
// $_POST['last_name'];
// $_POST['username'];
// $_POST['password];





?>