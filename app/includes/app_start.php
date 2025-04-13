<?php
session_start();

try {
    $socket = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($socket) {
        # empty code;
    } else {
        # error if connection is not established 
    }
} catch (Exeception $error) {
    if ($error) {
        echo 'Connection is not establish';
    }
}



// function

function FeaturedGames()
{
    global $socket;
    $data = [];
    foreach (runner("SELECT * FROM zon_featured_games ORDER BY id DESC") as $game_id) {
        if (num_rows(T_ZON_GAMES, "id=" . $game_id['game_id'])) {
            $id = $game_id['game_id'];
            $data[] = runner("SELECT * FROM zon_games WHERE id=$id")[0];
        }
    }
    return $data;
}

function OriginalsGames()
{
    global $socket;
    $data = [];
    $sql = "SELECT * FROM zon_originals ORDER BY id DESC";

    if (count(runner($sql)) > 0) {
        foreach (runner("SELECT * FROM zon_originals ORDER BY id DESC") as $game_id) {
            $id = $game_id['game_id'];
            $data[] = runner("SELECT * FROM zon_games WHERE id=$id")[0];
        }
    }

    return $data;
}


function routerLink($link)
{
    global $site_url;
    echo $site_url . $link;
}

function runner($sql)
{
    global $socket;
    $row = $socket->query($sql);
    $data = [];
    while ($r = $row->fetch_assoc()) {
        $data[] = $r;
    }

    return $data;
}


function LoadStaticThemeFile($path)
{
    global $zon, $site_url;
    $theme = $zon['config']['theme'];
    echo $site_url . "themes/$theme/" . $path;
}
function LoadFile($name)
{
    global $zon;
    $theme = $zon['config']['theme'];
    $path = "themes/$theme/layout/" . $name . "/content.phtml";
    if (file_exists($path)) {
        include ($path);
    } else {
        echo 'file not exists.';
    }
}


function LoadFile2($name)
{
    global $zon;
    $theme = $zon['config']['theme'];
    $path = "themes/$theme/layout/" . $name . ".phtml";
    if (file_exists($path)) {
        ob_start();
        include ($path);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    } else {
        echo 'file not exists.';
    }
}

function ZonConfig()
{
    global $socket;
    $sql = $socket->query('SELECT * FROM ' . T_ZON_CONFIG);
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data = $row;
    }
    return $data;
}

$zon = [];
$zon['url'] = $_GET['url'] ?? '';
$zon['page'] = explode("/", $_GET['url'] ?? '');
$zon['config'] = ZonConfig();
$zon['logo'] = $site_url . "static/img/logo/" . $zon['config']['site_logo_dark'];
$zon['user'] = getLoggedinUser();


// if (isset($_GET) && isset($_GET['theme'])) {
//     if ($_GET['theme'] === 'garud' || $_GET['theme'] === 'zontal') {
//         $_SESSION['theme'] = $_GET['theme'];
//         if (isset($_SESSION) && isset($_SESSION['theme'])) {
//             $zon['config']['theme'] = $_SESSION['theme'];
//             header("Location: ?");
//         }
//     }
// }

// $zon['config']['theme'] = $_SESSION['theme'] ?? 'garud';



if (isset($_SESSION['Loggedin'])) {
    define("IsLoggedin", true);
} else {
    define("IsLoggedin", false);
}


if (isset($_SESSION['is_admin_Loggedin'])) {
    define("IsAdmin", true);
} else {
    define("IsAdmin", false);
}

function getLoggedinUser()
{
    global $socket;

    if (isset($_SESSION['Loggedin']) && isset($_SESSION['Loggedin_user'])) {
        $user_i = $_SESSION['Loggedin_user'];
        $sql = "SELECT * FROM " . T_ZON_USERS . " WHERE username='$user_i' OR email='$user_i' ";
        $runned = mysqli_query($socket, $sql);
        $data = [];
        while ($row = $runned->fetch_assoc()) {
            $data = $row;
        }
        return $data;
    }
}

function DynamicSection()
{
    global $socket;
    $sql = $socket->query('SELECT * FROM ' . T_ZON_SEC);
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getCategories()
{
    global $socket;
    $sql = $socket->query('SELECT * FROM ' . T_ZON_CATEGORY);
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function GameByCategoryName($id, $limit)
{
    global $socket;
    $name = getCategoryNameById($id);
    if ($limit !== '') {
        $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " WHERE game_category='$name' LIMIT $limit ");
        $data = [];
        while ($row = $sql->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}

function GameByCategoryWise($name, $limit = 0)
{
    global $socket;
    if ($limit !== 0) {
        $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " WHERE game_category='$name' LIMIT $limit ");
    } else {
        $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " WHERE game_category='$name'");
    }
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getGame($limit)
{
    global $socket;
    if ($limit !== '') {
        $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . "  ORDER BY id DESC LIMIT $limit ");
        $data = [];
        while ($row = $sql->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}

function getNewGamesByLimit($limit)
{
    global $socket;
    if ($limit !== '') {
        $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " ORDER BY id DESC LIMIT $limit ");
        $data = [];
        while ($row = $sql->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}


function getCategoryNameById($id)
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_CATEGORY . " WHERE id=$id ");
    $data = $row = $sql->fetch_assoc();

    return $data['name'];
}

function getFeaturedGames()
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_F_GAMES);
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getNewGames()
{
    global $socket, $zon;
    $limit = $zon['config']['section_games_limit'];
    $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " ORDER BY id DESC LIMIT " . $limit);
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getNewGamesByCategory($category_name)
{
    global $socket, $zon;
    $limit = $zon['config']['section_games_limit'];
    $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " WHERE game_category='$category_name' ORDER BY id DESC LIMIT " . $limit);
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        if ($row['game_played'] == 0) {
            $data[] = $row;
        }
    }
    return $data;
}

function getPopularGames()
{
    global $socket, $zon;
    $limit = $zon['config']['section_games_limit'];
    $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " ORDER BY id DESC ");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        if ($row['game_played'] >= $zon['config']['popular_views']) {
            if (count($data) != $limit) {
                $data[] = $row;
            }
        }
    }
    return $data;
}

function getPopularGamesByCategory($category_name)
{
    global $socket, $zon;
    $limit = $zon['config']['section_games_limit'];
    $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " WHERE game_category='$category_name' ORDER BY id DESC ");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        if ($row['game_played'] >= $zon['config']['popular_views']) {
            if (count($data) != $limit) {
                $data[] = $row;
            }
        }
    }
    return $data;
}

function getGamesById($game_id)
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " WHERE id=$game_id ");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data = $row;
    }
    return $data;
}

function getCategory($limit = 0)
{
    global $socket;
    if ($limit !== 0) {
        $sql = $socket->query("SELECT * FROM " . T_ZON_CATEGORY . " LIMIT $limit ");
    } else {
        $sql = $socket->query("SELECT * FROM " . T_ZON_CATEGORY);
    }
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function num_rows($table, $con)
{
    global $socket;
    $sql = $socket->query("SELECT * FROM $table WHERE $con ORDER BY id DESC ");
    $count = 0;
    while ($row = $sql->fetch_assoc()) {
        $count++;
    }

    return $count;
}

function getGamesByPopular($limit)
{
    global $socket;
    $sql = $socket->query("SELECT MAX( game_played ) FROM " . T_ZON_GAMES . "  $limit ");
    $s = $socket->query("SELECT * FROM " . T_ZON_GAMES . " $limit ");
    $data = [];
    $count = 0;
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
        $count++;
    }
    $data2 = [];
    if ($count <= 4) {
        while ($r = $s->fetch_assoc()) {
            $data2[] = $r;
        }
    }

    return $data2;
}

function getBlogs()
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_BLOGS . " ORDER BY id DESC ");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

function tabActivation($page, $class)
{
    global $zon;

    if (isset($zon['page'][0]) && $zon['page'][0] == $page) {
        echo $class;
    }

}

function AutoPlay()
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " ORDER BY id DESC ");
    $game_len = count(mysqli_fetch_all($sql));
    $game_id = rand(1, $game_len);
    if (num_rows(T_ZON_GAMES, "id=$game_id") > 0) {
        $game_id = rand(1, $game_len);
    }
    $game_sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " WHERE id=$game_id ORDER BY id DESC ");
    $data = [];
    while ($row = $game_sql->fetch_assoc()) {
        $data = $row;
    }

    return $data;
}


function getAd($offset, $d)
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_ADS . " ORDER BY id DESC LIMIT $offset ");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data = $row;
    }
    return $data[$d];
}

function getAdById($id, $d)
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_ADS . " WHERE id=$id ORDER BY id DESC");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data = $row;
    }

    if ($data['status'] == 0) {
        return $data[$d];
    } else {
        return '';
    }
}

function makeSlug($v)
{
    $e = strtolower($v);
    $e = preg_replace("/[^a-zA-Z ]+/", "", $e);
    $e = str_replace(" ", "-", $e);
    // $e = urlencode($e);
    return $e;
}


function uSlug($v)
{
    $e = str_replace("-", " ", $v);
    $e = urldecode($e);
    return $e;
}


function blogById($id)
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_BLOGS . " WHERE id=$id ORDER BY id DESC ");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data = $row;
    }

    return $data;
}

function getTitle()
{
    global $zon;

    if ($zon['page'][0] == 'autoplay') {
        echo "AutoPlay - Play Random Games";
    } else if ($zon['page'][0] == 'games') {
        echo $zon['config']['games_title'];
    } else if ($zon['page'][0] == 'all-games') {
        echo $zon['config']['games_title'];
    } else if ($zon['page'][0] == 'popular-games' || ($zon['page'][0] == 'archive' && $zon['page'][1] == 'popular')) {
        echo $zon['config']['games_title'];
    } else if ($zon['page'][0] == 'category' || ($zon['page'][0] == 'game' && count($zon['page']) == 2)) {
        $category_title = $zon['config']['category_title'];
        $title = str_replace("[name]", $_GET['n'] ?? '', $category_title);
        echo $title;
    } else if ($zon['page'][0] == 'blogs') {
        echo "Blogs";
    } else if ($zon['page'][0] == 'game' && (count($zon['page']) == 3 || count($zon['page']) == 4)) {
        $play_title = $zon['config']['play_title'];
        if ($zon['page'][0] == 'single') {
            $game = getGamesById($zon['page'][1]);
        } else {
            $game = getGamesById($zon['page'][2]);
        }
        $title = str_replace("[name]", $game['game_name'], $play_title);
        echo $title;
    } else if ($zon['page'][0] == 'blog') {
        $blog = blogById($zon['page'][2]);
        echo $blog['blog_title'];
    } else if ($zon['page'][0] == 'login') {
        echo "Login";
    } else if ($zon['page'][0] == 'register') {
        echo "Register";
    } else if ($zon['page'][0] == 'page') {
        $page = getPageById($zon['page'][1]);
        echo $page['title'];
    } else if ($zon['page'][0] == '') {
        echo $zon['config']['site_title'];
    } else if (isset($zon['page'][0]) && num_rows(T_ZON_USERS, "username='" . $zon['page'][0] . "'")) {
        $username = $zon['user']['username'];
        $pro_title = $zon['config']['profile_title'];
        $title = str_replace("[name]", $username, $pro_title);
        echo $title;
    } else if ($zon['page'][0] === 'search') {
        $title = ms_secure($_GET['s'] ?? '');
        echo $title;
    } else {
        echo "404 Page Not Found";
    }


}


function getPages()
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_PAGES . " ORDER BY id DESC ");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

function getPageById($id)
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_PAGES . " WHERE id=$id ORDER BY id DESC ");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data = $row;
    }

    return $data;
}

function getGamesByQuery($query, $limit)
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " WHERE game_name LIKE '%$query%' LIMIT $limit ");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getCommentsByGameId($id)
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_COMMENTS . " WHERE game_id=$id ORDER BY id DESC ");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}


function getUserDataById($id)
{
    global $socket;
    $sql = $socket->query("SELECT * FROM " . T_ZON_USERS . " WHERE id=$id");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data = $row;
    }
    return $data;
}

function redirect($path, $full = 0)
{
    global $site_url;
    $p = $path;
    if ($full == 1) {
        $p = $site_url . $path;
    } else {
        $p = $path;
        return $p;
    }

    echo "<script>window.location.href = '$p'</script>";

}

// getting user game by their ip 
function getUserGame()
{
    global $socket;
    $ip = $_SERVER['REMOTE_ADDR'];
    $sql = $socket->query("SELECT * FROM zon_likes WHERE user_ip='$ip' ORDER BY id DESC");
    $game_id = [];
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $game_id[] = $row['game_id'];
    }

    foreach($game_id as $id) {
        $data[] = runner("SELECT * FROM zon_games WHERE id=$id")[0];
    }

    return $data;
}

function getRecentGame() {

    $data = [];
    foreach($_SESSION['games'] as $id) {
        $data[] = runner("SELECT * FROM zon_games WHERE id=$id ORDER BY id DESC")[0];
    }

    return $data;

}

function getUserComments($user_id)
{
    global $socket;
    $sql = $socket->query("SELECT *, zon_comments.id FROM zon_comments LEFT JOIN zon_users ON zon_users.id=zon_comments.user_id WHERE zon_comments.user_id=$user_id");
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function add_views($game_id)
{
    global $socket;
    $data = runner("SELECT * FROM " . T_ZON_GAMES . " WHERE id=$game_id")[0];
    $game_played = intval($data['game_played']) + intval(1);
    mysqli_query($socket, "UPDATE " . T_ZON_GAMES . " SET game_played=$game_played WHERE id=$game_id");
}


function add_recent_play_games($game_id)
{
    global $socket;
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $sql = "SELECT * FROM zon_recent_play WHERE game_id=$game_id && user_ip='$user_ip'";
    $date =  date("F j, Y, g:i a");
    if (count(runner($sql)) == 0) {
        mysqli_query($socket, "INSERT INTO zon_recent_play (game_id, user_ip, date) VALUES ($game_id, '$user_ip', '$date')");
    }
    // $game_id = $game_id;
    // $data = runner("SELECT * FROM " . T_ZON_GAMES . " WHERE id=$game_id")[0];
    // $game_played = intval($data['game_played']) + intval(1);
    // mysqli_query($socket, "UPDATE " . T_ZON_GAMES . " SET game_played=$game_played WHERE id=$game_id");
}


function IsGame($slug)
{
    // $game_name = uSlug($slug);
    $sql = "SELECT * FROM " . T_ZON_GAMES . " WHERE game_slug='$slug' ";
    if (count(runner($sql)) > 0) {
        return true;
    } else {
        return false;
    }
}

function IsCategory($slug)
{
    $name = strtolower($slug);
    $name = uSlug($name);
    $sql = "SELECT * FROM " . T_ZON_CATEGORY . " WHERE slug='$slug' ";

    if (count(runner($sql)) > 0) {
        // if (in_array($name, runner($sql))) {
        return true;
        // }
    } else {
        return false;
    }
}

function CategoryDataBySlug($slug)
{
    $name = uSlug($slug);
    // $name = 'Arcade';
    $sql = "SELECT * FROM " . T_ZON_CATEGORY . " WHERE slug='$slug' ";
    $data = [];
    if (count(runner($sql)) > 0) {
        // foreach (runner($sql) as $category) {
        // if (strtolower($category['name']) === strtolower($name)) {
        $data = runner($sql)[0];
        // }

        // }
        return $data;
    } else {
        return false;
    }
}

function GameByCategoryNameWithPagination($id, $pag = '')
{
    global $socket;
    $name = getCategoryNameById($id);
    // if ($limit !== '') {
    $sql = $socket->query("SELECT * FROM " . T_ZON_GAMES . " WHERE game_category='$name' ORDER BY id DESC " . $pag);
    $data = [];
    while ($row = $sql->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
    // }
}

function predictColor($colorName)
{
    // Mapping of first letters to colors
    $colorMap = [
        'a' => 'antiqueWhite',
        'b' => 'rgb(0, 102, 255)',
        'c' => 'crimson',
        'd' => 'rgb(122, 122, 122)',
        'e' => '#13e9fc',
        'f' => 'fuchsia',
        'g' => 'gold',
        'h' => 'rgb(249, 49, 149)',
        'i' => 'rgb(111, 4, 188)',
        'j' => 'rgb(120, 0, 194)',
        'k' => 'rgb(248, 231, 78)',
        'l' => 'lavender',
        'm' => 'magenta',
        'n' => 'rgb(26, 49, 252)',
        'o' => 'rgb(181, 181, 1)',
        'p' => 'rgb(168, 4, 168)',
        'q' => 'rgb(31, 228, 246)',
        'r' => 'rgb(222, 17, 17)',
        's' => 'silver',
        't' => 'turquoise',
        'u' => 'rgb(172, 255, 30)',
        'v' => 'rgb(253, 53, 253)',
        'w' => 'white',
        'x' => 'rgb(139, 55, 255)',
        'y' => 'rgb(255, 255, 15)',
        'z' => 'rgb(0, 140, 255)'
    ];

    // Get the first letter of the color name
    $firstLetter = strtolower($colorName[0]);

    // Return the corresponding color or a default color if not found
    return $colorMap[$firstLetter] ?? 'Black';
}


function getUserRecentPlayGame() {
    global $socket;

    $user_ip = $_SERVER['REMOTE_ADDR'];
    $data = [];
    $games = runner("SELECT * FROM zon_recent_play WHERE user_ip='$user_ip'  ORDER BY id DESC  ");
    foreach($games as $game) {
        $data[] = runner("SELECT * FROM zon_games WHERE id=" . $game['game_id'])[0];
    }
    return $data;
}

function getUserLikedGame() {
    global $socket;

    $user_ip = $_SERVER['REMOTE_ADDR'];
    $data = [];
    $games = runner("SELECT * FROM zon_likes WHERE user_ip='$user_ip' ORDER BY id DESC ");
    foreach($games as $game) {
        $data[] = runner("SELECT * FROM zon_games WHERE id=" . $game['game_id'])[0];
    }
    return $data;
}

function ms_secure($v) {
    global $socket;
    $v = $socket->real_escape_string($v);
    $v = strip_tags($v);
    return $v;
}

function path() {
    if (isset($_GET) && !empty($_GET)) {
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            $url = ms_secure($_GET['url']);
            return $url;
        }
    }

    return '/';
}


function IsGameLiked($ip, $game_id, $table) {
    return count(runner("SELECT * FROM $table WHERE game_id=$game_id && user_ip='$ip'"));
}

function formatNumber($number) {
    if ($number >= 1000000000) {
        return number_format($number / 1000000000, 1) . 'B';
    } elseif ($number >= 1000000) {
        return number_format($number / 1000000, 1) . 'M';
    } elseif ($number >= 1000) {
        return number_format($number / 1000, 1) . 'K';
    }
    return number_format($number);
}