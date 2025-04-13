<?php
require_once '../config.php';
require_once '../app/includes/constant.php';
require_once '../app/includes/app_start.php';

$ip = $_POST['ip'];
$game_id = $_POST['game_id'];

$sql = "SELECT * FROM zon_recent_play WHERE user_ip='$ip' && game_id=$game_id";

if (count(runner($sql)) > 0) {
    $socket->query("DELETE FROM zon_recent_play WHERE user_ip='$ip' && game_id=$game_id");
    echo "ok";
}