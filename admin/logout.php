<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Session.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/admin/index.php');
}
csrfCheckOrDie();
Session::destroy();
redirect(BASE_URL . '/admin/login.php');
