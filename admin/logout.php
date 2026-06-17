<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Session.php';

Session::destroy();
redirect(BASE_URL . '/admin/login.php');
