<?php 
    require_once 'default_session.php';
    //header("Location: ../singer_list.php");
    //exit;
?>
<html>
    <head>
        <!--TODO : tambahin display-none aku noob banget ini-->
    </head>
    <body>
        <input id="creator-id" class="display-none" value="<?php echo $_GET['id'] ?>" />
    </body>
    <script src="/assets/js/subscription.js"></script>
</html>