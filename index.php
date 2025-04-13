<?php
// +------------------------------------------------------------------------+
// | @author: coder_mvn
// | @name: Zontal - The Arcade Online HTML5 Game Playing Platform
// | @author_email: mvk62015@gmail.com   
// | @version: 1.0v
// +------------------------------------------------------------------------+
// | Zontal - The Arcade Online HTML5 Game Playing Platform
// | Copyright (c) 2023 Zontal. All rights reserved.
// +------------------------------------------------------------------------+
require_once 'app/init.php';
include ("auto-fetch.php");


if ($zon['config']['theme'] === 'dorado') {
    $zon['content'] = LoadFile2("home/content");
    define("DEFAULT_IMG", $site_url . "static/skeleton.png");

    define("POPULAR_GAMES", count(runner("SELECT * FROM zon_games WHERE game_played > " . $zon['config']['popular_views'])));
    define("NEW_GAMES", count(runner("SELECT * FROM zon_games WHERE game_played=0")));
    define("BLOGS", count(runner("SELECT * FROM zon_blog")));
    define("LIKES", 0);
    define("GAME_HISTORY", count(runner("SELECT * FROM zon_recent_play WHERE user_ip='" . $_SERVER['REMOTE_ADDR'] . "'")));
    define("IS_DEV", "");

    $page = $zon['page'];

    $zon['title'] = $zon['config']['site_title'];

    if (strlen($page[0]) != 0 && IsGame($page[0])) {
        $game_name = $zon['page'][0];
        $data = runner("SELECT * FROM zon_games WHERE game_slug='$game_name'")[0];
        $play_title = $zon['config']['play_title'];
        $title = str_replace("[name]", $data['game_name'], $play_title);
        $zon['title'] = $title;
        $zon['content'] = LoadFile2("game/content");
    } else if ($page[0] === 'page') {
        $page = getPageById($zon['page'][1]);
        $zon['title'] = $page['title'];
        $zon['content'] = LoadFile2("page/content");
    } else if ($page[0] === 'popular-games') {
        $zon['title'] = $zon['config']['games_title'];
        $zon['content'] = LoadFile2("popular-games/content");
    } else if ($page[0] === 'new-games') {
        $zon['title'] = $zon['config']['games_title'];
        $zon['content'] = LoadFile2("newest-games/content");
    } else if ($page[0] === 'blogs') {
        $zon['title'] = "Blogs";
        $zon['content'] = LoadFile2("blogs/content");
    } else if ($page[0] === 'blog') {
        $blog = blogById($zon['page'][2]);
        $zon['title'] = $blog['blog_title'];
        $zon['content'] = LoadFile2("blog-post/content");
    } else if ($page[0] === 'login') {
        $zon['title'] = "Login";
        $zon['content'] = LoadFile2("login/content");
    } else if ($page[0] === 'register') {
        $zon['title'] = "Register";
        $zon['content'] = LoadFile2("register/content");
    } else if (strlen($page[0]) != 0 && IsCategory($page[0])) {
        $category_title = $zon['config']['category_title'];
        $category_name = $zon['page'][0] ?? $_GET['u'];
        $category = CategoryDataBySlug($category_name);
        $title = str_replace("[name]", $category['name'] ?? '', $category_title);
        $zon['title'] = $title;
        $zon['content'] = LoadFile2("category/content");
    } else if (strlen($page[0]) == 11 && $page[0] === 'liked-games') {
        $zon['title'] = "Liked Games";
        $zon['content'] = LoadFile2("user-profile/content");
    } else if ($page[0] === 'recent-play-games') {
        $zon['title'] = "Recent Play Games";
        $zon['content'] = LoadFile2("recent-play-games/content");
    } else if ($page[0] === 'search') {
        $zon['title'] =  ms_secure($_GET['s'] ?? '');
        $zon['content'] = LoadFile2("search-page/content");
    } else if ($page[0] === 'random-play') {
        $zon['title'] =   "Random Play - Play Random Games";
        $zon['content'] = LoadFile2("autoplay/content");
    }
}

echo LoadFile2("container");