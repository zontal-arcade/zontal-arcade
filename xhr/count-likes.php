<?php
require_once '../config.php';
require_once '../app/includes/constant.php';
require_once '../app/includes/app_start.php';

$type = $_POST['type'];
$game_id = $_POST['game_id'];

// if ($type === "like") {
//     echo count(runner("SELECT * FROM zon_likes WHERE game_id=$game_id"));
// } else if ($type === "dislike") {
//     echo count(runner("SELECT * FROM zon_unlikes WHERE game_id=$game_id"));
// }

$like = count(runner("SELECT * FROM zon_likes WHERE game_id=$game_id"));
$dislike = count(runner("SELECT * FROM zon_unlikes WHERE game_id=$game_id"));

$json = ["like" => formatNumber($like), "dislike" => formatNumber($dislike)];

echo json_encode($json);