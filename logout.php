<?php
include_once 'config/session.php';

Session::init();
Session::destroy();

header("Location: login.php");
exit;
