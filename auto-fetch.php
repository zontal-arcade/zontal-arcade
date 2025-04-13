<?php
// session_start();
// require 'app/init.php';
$page = $zon['config']['auto_fetch_page'] == 0 ? 1 : $zon['config']['auto_fetch_page'];
// $page += $zon['config']['auto_fetch_page'];
$found = 0;

// Function to fetch game data from the API
function fetchGameData()
{
    global $zon, $page, $socket, $found;

    if ($zon['config']['auto_fetch'] == 1) {
        if ($zon['config']['auto_fetch_pub'] === 'gamepix') {
            // Gamepix API URL 
            // $url = "https://feeds.gamepix.com/v1/json?sid=" . $zon['config']['gamepix_sid'] . "&page=$page&pagination=90";
            $url = "https://feeds.gamepix.com/v1/json?sid=" . $zon['config']['gamepix_sid'] . "&pagination=12&page=" . $page;
            $data = file_get_contents($url);
            $data = json_decode($data);
            $data = $data->items;
            $d = [];

            foreach ($data as $game) {

                $game_name = mysqli_real_escape_string($socket, $game->title);
                $game_desc = mysqli_real_escape_string($socket, $game->description);
                $game_image_url = mysqli_real_escape_string($socket, $game->image);
                $game_frame_url = mysqli_real_escape_string($socket, $game->url);
                $game_category = mysqli_real_escape_string($socket, $game->category);
                $game_banner_url = mysqli_real_escape_string($socket, $game->banner_image);
                $game_status = 0;
                $cate_slug = strtolower($game_category);

                $sql = "INSERT INTO `zon_games`(`game_name`, `game_description`, `game_image_url`, `game_url`, `game_published`, `game_category`, `game_status`, `game_played`, `game_banner_url`) VALUES ('$game_name','$game_desc','$game_image_url','$game_frame_url', $game_status, '$game_category', 0, 0, '$game_banner_url')";
                $cate_sql = "INSERT INTO `zon_category`(`name`, `slug`) VALUES ('$game_category', '$cate_slug')";
                $check_game_name = "select * from zon_games where game_name='$game_name'";


                if (!empty($game_image_url)) {
                    if (mysqli_num_rows(mysqli_query($socket, $check_game_name)) == 0) {
                        if (mysqli_query($socket, $sql)) {
                            $d[] = "i";
                        }
                    }


                    $check_category_name = "select * from zon_category where name='$game_category'";
                    if (mysqli_num_rows(mysqli_query($socket, $check_category_name)) == 0) {
                        if ($game_category !== '') {
                            if (mysqli_query($socket, $cate_sql)) {
                                $query_run = true;
                            }
                        }
                    }
                }
            }

            // if ($found == 12) {
            $socket->query("UPDATE zon_config SET auto_fetch_page=" . $page + 1);
            if (empty($d)) {
                $publisher = $zon['config']['auto_fetch_pub'];
                $socket->query("UPDATE zon_config SET auto_fetch_all_games_fetched=1, auto_fetch_game_publisher='$publisher'");
            } else {
                $socket->query("UPDATE zon_config SET auto_fetch_all_games_fetched=0");
            }
            // }
            // return "Game Found " . $found . " Page = " . $page;
            return $d;


        }

        if ($zon['config']['auto_fetch_pub'] === 'gamedistribution') {
            $category = 'all';
            $collection = 'all';
            $tags = 'all';
            $type = 'all';
            $subType = 'all';
            $mobile = 'all';
            $rewarded = 'all';
            $amount = $zon['config']['auto_fetch_amount'];
            $d = [];

            $url = "https://catalog.api.gamedistribution.com/api/v2.0/rss/All/?collection=$collection&categories=$category&tags=$tags&subType=$subType&type=$type&mobile=$mobile&rewarded=$rewarded&amount=$amount&page=$page&format=json";

            $json_data = file_get_contents($url);

            // Decode JSON into PHP array
            $response_data = json_decode($json_data, true);

            // All user data exists in 'data' object
            $data = $response_data;


            foreach ($data as $game) {

                $game_name = mysqli_real_escape_string($socket, $game['Title']);
                $game_desc = mysqli_real_escape_string($socket, $game['Description']);
                $game_image_url = mysqli_real_escape_string($socket, $game['Asset'][0]);
                $game_frame_url = mysqli_real_escape_string($socket, $game['Url']);
                $game_category = mysqli_real_escape_string($socket, $game['Category'][0]);
                $game_banner_url = '';

                if (isset($game['Asset'][3])) {
                    $game_banner_url = $game['Asset'][3];
                }

                if (isset($game['Asset'][4])) {
                    $game_banner_url = $game['Asset'][4];
                }

                $game_status = 0;
                $cate_slug = strtolower($game_category);

                $sql = "INSERT INTO `zon_games`(`game_name`, `game_description`, `game_image_url`, `game_url`, `game_published`, `game_category`, `game_status`, `game_played`, `game_banner_url`) VALUES ('$game_name','$game_desc','$game_image_url','$game_frame_url', $game_status, '$game_category', 0, 0, '$game_banner_url')";
                $cate_sql = "INSERT INTO `zon_category`(`name`, `slug`) VALUES ('$game_category', '$cate_slug')";

                $check_game_name = "select * from zon_games where game_name='$game_name'";

                if (!empty($game_image_url)) {
                    if (mysqli_num_rows(mysqli_query($socket, $check_game_name)) == 0) {
                        if (mysqli_query($socket, $sql)) {
                            $d[] = "i";
                        }
                    }

                    $check_category_name = "select * from zon_category where name='$game_category'";
                    if (mysqli_num_rows(mysqli_query($socket, $check_category_name)) == 0) {
                        if ($game_category !== '') {
                            if (mysqli_query($socket, $cate_sql)) {
                                $query_run = true;
                            }
                        }
                    }
                }
            }

            $socket->query("UPDATE zon_config SET auto_fetch_page=" . $page + 1);
            if (empty($d)) {
                $publisher = $zon['config']['auto_fetch_pub'];
                $socket->query("UPDATE zon_config SET auto_fetch_all_games_fetched=1, auto_fetch_game_publisher='$publisher'");
            } else {
                $socket->query("UPDATE zon_config SET auto_fetch_all_games_fetched=0");
            }
            return $d;
        }

        if ($zon['config']['auto_fetch_pub'] === 'gamemonetize') {
            $category = 'All';
            $type = 'html5';
            $popularity = 'newest';
            $company = 'All';
            $amount = $zon['config']['auto_fetch_amount'] + 10;
            $d = [];

            $url = "https://gamemonetize.com/rssfeed.php?format=json&category=$category&type=$type&popularity=$popularity&company=$company&amount=$amount";


            $json_data = file_get_contents($url);

            // Decode JSON into PHP array
            $response_data = json_decode($json_data, true);

            // All user data exists in 'data' object
            $data = $response_data;


            foreach ($data as $game) {

                $game_name = mysqli_real_escape_string($socket, $game['title']);
                $game_desc = mysqli_real_escape_string($socket, $game['description']);
                $game_image_url = mysqli_real_escape_string($socket, $game['thumb']);
                $game_frame_url = mysqli_real_escape_string($socket, $game['url']);
                $game_category = mysqli_real_escape_string($socket, $game['category']);
                $game_status = 0;
                $cate_slug = strtolower($game_category);

                $sql = "INSERT INTO `zon_games`(`game_name`, `game_description`, `game_image_url`, `game_url`, `game_published`, `game_category`, `game_status`, `game_played`) VALUES ('$game_name','$game_desc','$game_image_url','$game_frame_url', $game_status, '$game_category', 0, 0)";
                $cate_sql = "INSERT INTO `zon_category`(`name`, `slug`) VALUES ('$game_category', '$cate_slug')";

                $check_game_name = "select * from zon_games where game_name='$game_name'";

                if (!empty($game_image_url)) {
                    if (mysqli_num_rows(mysqli_query($socket, $check_game_name)) == 0) {
                        if (mysqli_query($socket, $sql)) {
                            $d[] = "i";
                        }
                    }

                    $check_category_name = "select * from zon_category where name='$game_category'";
                    if (mysqli_num_rows(mysqli_query($socket, $check_category_name)) == 0) {
                        if (mysqli_query($socket, $cate_sql)) {
                            $query_run = true;
                        }
                    }
                }
            }

            // $socket->query("UPDATE zon_config SET auto_fetch_page=" . $page + 1);
            if (empty($d)) {
                $publisher = $zon['config']['auto_fetch_pub'];
                $socket->query("UPDATE zon_config SET auto_fetch_all_games_fetched=1, auto_fetch_game_publisher='$publisher'");
            } else {
                $socket->query("UPDATE zon_config SET auto_fetch_all_games_fetched=0");
            }

            return $d;
        }
    }
    // API URL

    // Fetch data from the API
    // $data = file_get_contents($url);

    // Process the fetched data (you can customize this according to your needs)
    // For now, let's just return the data
    // return $data;
}

// Function to get the last fetch timestamp from a file
function getLastFetchTimestamp()
{
    $filename = 'last_fetch_timestamp.txt';
    if (file_exists($filename)) {
        return intval(file_get_contents($filename));
    } else {
        return 0; // Return 0 if the file doesn't exist
    }
}

// Function to update the last fetch timestamp in a file
function updateLastFetchTimestamp()
{
    $filename = 'last_fetch_timestamp.txt';
    file_put_contents($filename, time());
}

// Check if it's time to fetch the data
$lastFetchTimestamp = getLastFetchTimestamp();
$currentTimestamp = time();
$fetchInterval = 1 * 60 * $zon['config']['auto_fetch_timing']; // 1 minutes in seconds
// $fetchInterval = 1; // 1 seconds
if ($currentTimestamp - $lastFetchTimestamp >= $fetchInterval) {
    // Fetch the data
    fetchGameData();

    // echo "<pre>";
    // Process the data or do whatever you need to do with it
    // print_r($gameData);

    // Update the last fetch timestamp
    updateLastFetchTimestamp();
} else {
    // Data was fetched less than 5 minutes ago, so do nothing
    // echo "Data was fetched less than 5 seconds ago.";
}


// echo json_encode(["message" => fetchGameData()]);