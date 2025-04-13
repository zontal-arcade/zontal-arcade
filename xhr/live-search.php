<?php
require_once '../config.php';
require_once '../app/includes/constant.php';
require_once '../app/includes/app_start.php';

$page = $_POST['page'] ?? 1;
$s = $_POST['query'] ?? '';
$limit = 10;
// $row = ($page - 1) * $limit;

// $query = "SELECT * from zon_games WHERE game_name LIKE '%$s%' limit $row,$limit";
// $run = mysqli_query($con, $query);

if (num_rows(T_ZON_GAMES, " game_name LIKE '%$s%' ") > 0) {
    foreach (getGamesByQuery($s, $limit) as $game) {
        $cn = $game['game_category'];
        $c = runner("SELECT * FROM zon_category WHERE name='$cn'")[0];
        $game['slug'] = $c['slug']; ?>
        <div class="card wow <?php echo $zon['config']['animate_class'] ?>">
            <img src="<?= $game['game_image_url'] ?>" />
            <div class="text">
                <a data-ajax="true" data-href="<?= $game['game_slug'] ?>" class="game-name"
                    href="<?= $site_url . $game['game_slug'] ?>"><?= $game['game_name'] ?></a>
                <a data-ajax="true" data-href="<?= $game['slug'] ?>" class="game-category"
                    href="<?= $site_url . $game['slug'] ?>"><?= $game['game_category'] ?></a>
            </div>
        </div>
    <?php }
} else {
    echo "<span>We don't have any results for this query '{$s}' 😓</span>";
} ?>