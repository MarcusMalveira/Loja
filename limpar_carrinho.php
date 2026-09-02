<?php

session_start();

$_SESSION['carrinho'] = [];

header("Location: carrinho.php");

exit;

?>