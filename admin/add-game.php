<?php require "../app/includes/function_general.php"; ?>
<?php include "includes/header.php"; ?>
<?php // include "includes/config.php"; ?>

<?php
if (!empty($_GET['token_id']) && isset($_GET['action'])) {
    $token_id = $_GET['token_id'];
    $action_type = $_GET['action'];
}

if (isset($token_id) && isset($action_type) && !empty($token_id)) {

    $sql = "select * from zon_games where id=$token_id";
    $run = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($run);
}
?>

<body class="dark:bg-[#121317]">
    <main class="d-flex">
        <?php include "includes/sidebar.php"; ?>
        <div class="main w-full px-12 py-6">
            <div class="games-list mt-6">

                <form action="functions/functions.php" method="POST" enctype="multipart/form-data" id="add-page"
                    class="tab">
                    <?php if (!empty($token_id)) { ?> <input hidden type="text" name="game_id"
                            value="<?php echo $data['id']; ?>"> <?php } ?>
                    <div class="flex gap-6">



                        <div class="input-form w-full">
                            <div class="input-group flex flex-column">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">Name</label>
                                <input value="<?php if (!empty($token_id)) {
                                    echo $data['game_name'];
                                } ?>" required
                                    name="game_name"
                                    class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs"
                                    type="text" placeholder="Title">
                            </div>

                            <div class="input-group flex flex-column mt-6">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">slug</label>
                                <input value="<?php if (!empty($token_id)) {
                                    echo $data['game_slug'];
                                } ?>" required
                                    name="game_slug"
                                    class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs"
                                    type="text" placeholder="Title">
                            </div>

                            <div class="input-group flex flex-column mt-6">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">Description</label>
                                <input required
                                    value="<?php if (!empty($token_id)) {
                                        echo $data['game_description'];
                                    } ?>"
                                    name="game_description"
                                    class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs"
                                    type="text" placeholder="Description">
                            </div>

                            <div class="input-group flex flex-column mt-6">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">Image Source Type</label>
                                <select id="File_Type"
                                    class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs"
                                    type="text" placeholder="Username">
                                    <option value="URL" selected>URL</option>
                                    <option value="File">File</option>
                                </select>
                            </div>

                            <div id="FILE_IMAGE" class="input-group flex flex-column mt-6 hidden">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">FOR game image</label>
                                <input name="game_image"
                                    class="py-0 border-2 border-gray-100 text-gray-500 outline-none rounded-sm focus:outline bg-white h-12 focus:outline-blue-500 transition-sm  px-0 text-xs"
                                    type="file" placeholder="Game Image">
                            </div>

                            <div id="URL_IMAGE" class="input-group flex flex-column mt-6 hidden">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">FOR game image</label>
                                <input value="<?php if (!empty($token_id)) {
                                    echo $data['game_image_url'];
                                } ?>"
                                    name="game_image_url"
                                    class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs"
                                    type="url" placeholder="Game Image URL">
                            </div>


                            <div class="input-group flex flex-column mt-6">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">Game URL</label>
                                <input value="<?php if (!empty($token_id)) {
                                    echo $data['game_url'];
                                } ?>" required
                                    name="game_frame_url"
                                    class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs"
                                    type="text" placeholder="Game URL">
                            </div>

                            <div class="input-group flex flex-column mt-6">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">Game Banner URL</label>
                                <input value="<?php if (!empty($token_id)) {
                                    echo $data['game_banner_url'];
                                } ?>"
                                     name="game_banner_url"
                                    class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs"
                                    type="text" placeholder="Game Banner URL">
                            </div>

                            <div class="input-group flex flex-column mt-6">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">game category</label>
                                <select name="game_category"
                                    class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm w-full  px-3 text-xs"
                                    id="">
                                    <?php
                                    $query = mysqli_query($con, "select * from zon_category order by id desc");
                                    while ($row = mysqli_fetch_assoc($query)) { ?>
                                        <option <?php if (!empty($token_id)) {
                                            if ($row['name'] == $data['game_category']) {
                                                echo "selected";
                                            }
                                        } ?> value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <input type="hidden" value=" " name="game_tags" />

                            <!-- <div class="input-group flex flex-column mt-6">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">game tags</label>
                                <textarea name="game_tags"
                                    class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm w-full px-3 text-xs"
                                    id=""><?php if (!empty($token_id)) { echo $data['game_tags'];} ?></textarea>
                                <label class="text-gray-500 capitalize text-[10px] mb-2">You can saperate tags by commas (,)</label>
                            </div> -->

                            <div class="input-group flex flex-column mt-6">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">game action</label>
                                <select name="game_status"
                                    class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm w-full  px-3 text-xs"
                                    id="">
                                    <option <?php if (!empty($token_id)) {
                                        if ($data['game_published'] == 'Yes') {
                                            echo "selected";
                                        }
                                    } ?> value="Yes">Publish</option>
                                    <option <?php if (!empty($token_id)) {
                                        if ($data['game_published'] == 'No') {
                                            echo "selected";
                                        }
                                    } ?> value="No">unpublish</option>
                                </select>
                            </div>
                        </div>
                        <div class="other-inputs w-80"></div>

                    </div>
                    <button name="<?php if (!empty($token_id)) {
                        echo 'update_game';
                    } else {
                        echo 'upload_game';
                    } ?>"
                        class="bg-blue-600 text-white uppercase mt-4 text-sm rounded-sm py-2 px-3 "><?php if (!empty($token_id)) {
                            echo 'update';
                        } else {
                            echo 'Publish';
                        } ?></button>
                </form>
            </div>
    </main>
    <?php include "includes/footer.php"; ?>
</body>

</html>