<?php $page = "Add Page"; ?>
<?php require "../app/includes/function_general.php"; ?>
<?php include "includes/header.php"; ?>
<?php // include "includes/config.php"; ?>
<?php
if (!empty($_GET['token_id']) && isset($_GET['action'])) {
    $token_id = $_GET['token_id'];
    $action_type = $_GET['action'];
}

if (isset($token_id) && isset($action_type) && !empty($token_id)) {

    $sql = "select * from zon_pages where id=$token_id";
    $run = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($run);
}
?>

<body class="dark:bg-[#121317]">
    <main class="d-flex">
        <?php include "includes/sidebar.php"; ?>
        <div class="main w-full px-12 py-6">
            <div class="games-list mt-6">

                <form action="functions/functions.php" method="post" id="add-page" class="tab">
                <?php if(!empty($token_id)) { ?> <input hidden type="text" name="id" value="<?php echo $data['id']; ?>"> <?php } ?>
                    <div class="input-group flex flex-column">
                        <label class="text-gray-500 uppercase text-[10px] mb-2">Page title</label>
                        <input value="<?php if(!empty($token_id)) { echo $data['title']; } ?>" required name="page_title"
                            class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs"
                            type="text" placeholder="Page title">
                    </div>

                    <div class="input-group flex flex-column mt-6">
                        <label class="text-gray-500 uppercase text-[10px] mb-2">Page Description</label>
                        <input value="<?php if(!empty($token_id)) { echo $data['desc']; } ?>" required name="page_desc"
                            class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs"
                            type="text" placeholder="Page Description">
                    </div>

                    <div class="input-group flex flex-column mt-6">
                        <label class="text-gray-500 uppercase text-[10px] mb-2">Page Content</label>
                        <textarea name="page_content" id="editor"
                            class="py-[15px] text-gray-500 outline-none focus:outline focus:outline-blue-500 transition-sm  px-3 text-xs"
                            type="text" placeholder="Page Content"><?php if(!empty($token_id)) { echo $data['content']; } ?></textarea>
                    </div>

                    <button name="<?php if(!empty($token_id)) { echo "update_page"; } else {echo "add_page"; } ?>" class="bg-blue-600 text-white uppercase mt-4 text-sm rounded-sm py-2 px-3 "><?php if(!empty($token_id)) { echo "update"; } else {echo "publish"; } ?></button>
                    
                </form>
            </div>
        </div>
    </main>

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
    <?php include "includes/footer.php"; ?>
</body>

</html>