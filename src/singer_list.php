<?php require_once 'components/default_session.php'; ?>
<?php 
    if (!isset($_SESSION['login']) || $_SESSION['login'] == false) {
        header("Location: /");
        exit;
    }

    $headers = array(
        'Content-Type: text/xml; charset=utf-8',
        'Authorization: Ima-Suki-Ni-Naru'
    );

    $body2=
    '<?xml version="1.0" encoding="utf-8"?>
    <s11:Envelope xmlns:s11="http://schemas.xmlsoap.org/soap/envelope/">
      <s11:Body>
        <ns1:getSub xmlns:ns1="http://service.tubes2.com/">
          <arg0></arg0><arg1></arg1><arg2></arg2>
        </ns1:getSub>
      </s11:Body>
    </s11:Envelope>';
    
    $ch2 = curl_init("http://tubes2-soap-ws:2434/subscription");
    curl_setopt($ch2, CURLOPT_HTTPGET, true);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, $body2);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);

    $result2 = curl_exec($ch2);
    curl_close($ch2);

    $data = explode(",", $result2);

    $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
    $del_data = <<< Q
        DELETE FROM subscription
    Q;

    $result_db = pg_query($db, $del_data);

    for($x = 1; $x < count($data); $x+=3) {
      $creator_id = $data[$x];
      $subs_id = $data[$x+1];
      $status = "";
      if (preg_match('/PENDING/i', $data[$x+2]) == 1){
        $status = "PENDING";
      } elseif (preg_match('/ACCEPTED/i', $data[$x+2]) == 1){
        $status = "ACCEPTED";
      } elseif (preg_match('/REJECTED/i', $data[$x+2]) == 1){
        $status = "REJECTED";
      }

      $insert_data = <<< Q
        INSERT INTO subscription(creator_id, subscriber_id, status) VALUES ($creator_id, $subs_id, '$status')
      Q;

      $result_db2 = pg_query($db, $insert_data);
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
        <link rel="stylesheet" href="/assets/css/album-list.css">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="bg-wrap">
            <?php include_once 'components/header.php'; ?>
            <section class="section-fw">
                <div class="album-list flex-col">
                    <h1 class="section-title">Singer List</h1>
                    <div class="alb-middle-limit">
                        <?php include 'components/list-display.php'; ?> 
                    </div>
                    <?php include 'components/list-d-control.php'; ?>
                </div>
            </section>
            <?php include_once 'components/footer.php'; ?>
        </div>
    </body>
    <script src="/assets/js/singer_list.js"></script>
</html>