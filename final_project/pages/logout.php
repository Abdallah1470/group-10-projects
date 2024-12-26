<?php
require_once '../includes/auth.php';
logout();
header('Location: /web_project/index.php');
exit;