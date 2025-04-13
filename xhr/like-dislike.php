<?php
require_once '../config.php';
require_once '../app/includes/constant.php';
require_once '../app/includes/app_start.php';

$game_id = ms_secure($_POST['game_id']);
$ip = ms_secure($_POST['ip']);
$type = ms_secure($_POST['type']);


if ($type === 'like') {
    mysqli_query($socket, "DELETE FROM zon_unlikes WHERE game_id=$game_id && user_ip='$ip' ");
    mysqli_query($socket, "INSERT INTO zon_likes (game_id, user_ip) VALUES ($game_id, '$ip')");
    echo "okay";
}

if ($type === 'dislike') {
    mysqli_query($socket, "DELETE FROM zon_likes WHERE game_id=$game_id && user_ip='$ip' ");
    mysqli_query($socket, "INSERT INTO zon_unlikes (game_id, user_ip) VALUES ($game_id, '$ip')");
    echo "okay";
}

// if (num_rows(T_ZON_LIKES, "game_id=$game_id && user_id=$ui") > 0) {
//     if (mysqli_query($socket, "DELETE FROM " . T_ZON_LIKES . " WHERE game_id=$game_id && user_id=$ui ")) {
//         echo num_rows(T_ZON_LIKES, "game_id=$game_id");
//     }
// } else {
//     if (mysqli_query($socket, "INSERT INTO " . T_ZON_LIKES . " (user_id, game_id) VALUES ($ui, $game_id) ")) {
//         echo num_rows(T_ZON_LIKES, "game_id=$game_id");
//     }
// }