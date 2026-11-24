<?php require '../config/database.php';$_SESSION=[];session_destroy();header('Location: '.BASE.'/auth/login.php');
