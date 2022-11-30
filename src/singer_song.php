<?php require_once 'components/default_session.php'; ?>
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
            <input id="album-id" class="display-none" value="<?php echo $_GET['id'] ?>" />
            <section class="album-cover section-fw flex-row">
                <div class="album-cover-img"><img src="" alt=""></div>
                    <div class="album-details">
                        <div id="modifiable-wrapper"></div>
                    </div>
            </section>
            <section class="section-fw">
                <div class="album-list flex-col">
                    <div class="add-song-control flex-row">
                        <h1 class="section-title">Song List</h1>
                    </div>
                    <form action="" class="display-none flex-col add-song-panel" autocomplete="off">
                        <input type="list" class="input-text" list="song-list-opt" id="song-add-choice" placeholder="Search song">
                        <datalist id="song-list-opt">
                        </datalist>
                        <input type="hidden" id="song-add-choice-hidden" value="">
                        <input type="button" class="button-filter add-song-btn" value="Add Song">
                    </form>
                    <div class="song-out">

                    </div>
                </div>
            </section>
            <?php include_once 'components/footer.php'; ?>
        </div>
    </body>
    <script src="/assets/js/singer_song.js"></script>
</html>