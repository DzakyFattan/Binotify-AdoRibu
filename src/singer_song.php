<?php require_once 'components/default_session.php'; 
    $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
    $creator_id = $_GET['id'];
    $subs_id = $_SESSION['user_id'];
    $permission_data = <<< Q
            SELECT * FROM subscription WHERE creator_id = $creator_id AND subscriber_id = $subs_id AND status = 'ACCEPTED'
        Q;
    $result = pg_query($db, $permission_data);
    if (pg_num_rows($result) == 0){
        header("Location: /singer_list.php");
        exit;
    }

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
    <head>
        <?php include_once 'components/global-dep.php'; ?>
        <?php include_once 'components/header-dep.php'; ?>
        <?php include_once "components/list-d-dep.php"; ?>
        <link rel="stylesheet" href="/assets/css/album.css">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="bg-wrap">
            <?php include_once 'components/header.php'; ?>
            <input id="creator-id" class="display-none" value="<?php echo $_GET['id'] ?>" />
            <input id="subscriber-id" class="display-none" value="<?php echo $_SESSION['user_id'] ?>" />
            <section class="section-fw">
                <div class="album-list flex-col">
                    <div class="add-song-control flex-row">
                        <h1 class="section-title">Song List</h1>
                    </div>
                    <div class="alb-middle-limit">

                    </div>
                </div>
            </section>
            <?php include_once 'components/footer.php'; ?>
        </div>
    </body>
    <script src="/assets/js/singer_song.js"></script>
</html>