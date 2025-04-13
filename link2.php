<?php

require_once 'app/init.php';
include ("auto-fetch.php");

header("Content-Type: application/json");

$zon['content'] = LoadFile2("home/content");
define("DEFAULT_IMG", $site_url . "static/skeleton.png");

$_SESSION['Loggedin'] = true;
$_SESSION['Loggedin_user'] = "root";

$query = "select * from zon_users where username='root'";
$row = mysqli_fetch_assoc(mysqli_query($socket, $query));

if ($row['is_admin'] == 1) {
    $_SESSION['is_admin_Loggedin'] = true;
}

$url = explode("/", $_GET['u'] ?? '');
// $page = $url[0];
$page = ms_secure($_GET['u'] ?? '');


function theEncoder($value) {
    // $value = urlencode($value);
    $value = preg_replace("/[^a-zA-Z ]+/", "", $value);
    return $value;
}

if ($page == '/') {
    $array = [
        "html" => LoadFile2("home/content"),
        "title" => theEncoder($zon['config']['site_title'])
    ];
    echo json_encode($array);
} else if ($page == '/popular-games') {
    $zon['title'] = $zon['config']['games_title'];
    $array = [
        "html" => LoadFile2("popular-games/content"),
        "title" => theEncoder($zon['title'])
    ];
    echo json_encode($array);
} else if ($page == '/new-games') {
    $zon['title'] = $zon['config']['games_title'];
    $array = [
        "html" => LoadFile2("newest-games/content"),
        "title" => theEncoder($zon['title'])
    ];
    echo json_encode($array);
} else if ($page == '/blogs') {
    $zon['title'] = "Blogs";
    $array = [
        "html" => LoadFile2("blogs/content"),
        "title" => theEncoder($zon['title'])
    ];
    echo json_encode($array);
} else if ($page == '/blog') {
    $blog = blogById($zon['page'][2]);
    $zon['title'] = $blog['blog_title'];
    $array = [
        "html" => LoadFile2("blog/content"),
        "title" => theEncoder($zon['title'])
    ];
    echo json_encode($array);
} else if ($page == '/login') {
    $zon['title'] = "Login";
    $array = [
        "html" => LoadFile2("login/content"),
        "title" => theEncoder($zon['title'])
    ];
    echo json_encode($array);
} else if ($page == '/register') {
    $zon['title'] = "Register";
    $array = [
        "html" => LoadFile2("register/content"),
        "title" => theEncoder($zon['title'])
    ];
    echo json_encode($array);
} else if (strlen($page) != 0 && IsCategory(str_replace("/", "", $page))) {
    $zon['page'][0] = str_replace("/", "", $page);

    $category_title = $zon['config']['category_title'];
    $category_name = $zon['page'][0] ?? $_GET['u'];
    $category = CategoryDataBySlug($category_name);
    $title = str_replace("[name]", $category['name'] ?? '', $category_title);
    $zon['title'] = $title;

    $array = [
        "html" => LoadFile2("category/content"),
        "title" => theEncoder($zon['title'])
    ];
    echo json_encode($array);
} else if (strlen($page) != 0 && IsGame(str_replace("/", "", $page))) {
    if (isset($_GET['u'])) {
        $game_name = str_replace("/", "", $_GET['u']);
    }
    $data = runner("SELECT * FROM zon_games WHERE game_slug='$game_name'")[0];
    $play_title = $zon['config']['play_title'];
    $title = str_replace("[name]", $data['game_name'], $play_title);
    $zon['title'] = $title;
    $array = [
        "html" => LoadFile2("game/content"),
        "title" => theEncoder($zon['title'])
    ];
    echo json_encode($array);
} else if ($page == '/liked-games') {
    $zon['title'] = "Liked Games";
    $array = [
        "html" => LoadFile2("user-profile/content"),
        "title" => theEncoder($zon['title'])
    ];
    echo json_encode($array);
} else if ($page == '/recent-play-games') {
    $zon['title'] = "Recent Play Games";
    $array = [
        "html" => LoadFile2("recent-play-games/content"),
        "title" => theEncoder($zon['title'])
    ];
    echo json_encode($array);
} else if ($page == '/random-play') {
    $zon['title'] = "Random Play - Play Random Games";
    $array = [
        "html" => LoadFile2("autoplay/content"),
        "title" => theEncoder($zon['title'])
    ];
    echo json_encode($array);
}