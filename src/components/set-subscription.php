<?php 
    require_once 'default_session.php';
    header("Location: ../singer_list.php");
    $headers = array(
        'Content-Type: text/xml; charset=utf-8',
        'Authorization: Ima-Suki-Ni-Naru'
    );

    $body = 
        '<?xml version="1.0" encoding="utf-8"?>
        <s11:Envelope xmlns:s11="http://schemas.xmlsoap.org/soap/envelope/">
          <s11:Body>
            <ns1:subscribe xmlns:ns1="http://service.tubes2.com/">
              <arg0>'. $_GET['id'] .'</arg0><arg1>'. $_SESSION['user_id']. '</arg1>
            </ns1:subscribe>
          </s11:Body>
        </s11:Envelope>';


    $ch = curl_init("http://tubes2-soap-ws:2434/subscription");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($ch);
    curl_close($ch);
    echo($result);
    exit;
?>