<?php

session_start();

if (!empty($_SESSION['auth'])) {
    header('Location: dashboard.php');
    exit;
}

header('Location: login.php');
exit;
