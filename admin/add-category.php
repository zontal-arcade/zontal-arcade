<?php require "../app/includes/function_general.php"; ?>
<?php include "includes/header.php"; ?>
<?php // include "includes/config.php"; ?>

<?php
if (!empty($_GET['token_id']) && isset($_GET['action'])) {
    $token_id = $_GET['token_id'];
    $action_type = $_GET['action'];
}

if (isset($token_id) && isset($action_type) && !empty($token_id)) {

    $sql = "select * from zon_category where id=$token_id";
    $run = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($run);
}
?>

<body class="dark:bg-[#121317]">
    <main class="d-flex">
        <?php include "includes/sidebar.php"; ?>
        <div class="main w-full px-12 py-6">
            <div class="games-list mt-6">

                <form action="functions/functions.php" method="POST" enctype="multipart/form-data" id="add-page" class="tab">
                <?php if(!empty($token_id)) { ?> <input hidden type="text" name="category_id" value="<?php echo $data['id']; ?>"> <?php } ?>
                    <div class="flex gap-6">
                        <div class="input-form w-full">
                            <div class="input-group flex flex-column">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">Name</label>
                                <input id="category_name" value="<?php if(!empty($token_id)) { echo $data['name']; } ?>" required name="game_category" class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs" type="text" placeholder="Category Name">
                            </div>
                            <div class="input-group flex flex-column mt-6">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">Slug</label>
                                <input required id="category_slug" value="<?php if(!empty($token_id)) { echo $data['slug']; } ?>" required name="game_category_slug" class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs" type="text" placeholder="Category Slug">
                            </div>
                            <!-- <div class="input-group flex flex-column mt-6">
                                <label class="text-gray-500 uppercase text-[10px] mb-2">Icon Code</label>
                                <input required id="category_slug" value="<?php if(!empty($token_id)) { echo $data['icon']; } ?>" required name="game_category_icon" class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs" type="text" placeholder="Category icon">
                                <label class="text-gray-500 text-[10px] mb-2">This icon is only show on dorado theme. you get icons from here <a href="https://icons8.com/" class="text-blue-600" >Icons8</a> </label>
                            </div> -->
                        </div>
                        <div class="other-inputs w-80"></div>
                    </div>
                    <button name="<?php if(!empty($token_id)) { echo 'update_category'; } else{ echo 'add_category'; } ?>" class="bg-blue-600 text-white uppercase mt-4 text-sm rounded-sm py-2 px-3 "><?php if(!empty($token_id)) { echo 'update'; } else{ echo 'Add'; } ?></button>
                </form>
            </div>
    </main>
    <?php include "includes/footer.php"; ?>
</body>

</html>