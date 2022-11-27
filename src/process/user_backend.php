<?php
    session_start();
    require 'json_response.php';
    require 'error_handling.php';
    header('Content-Type: application/json; charset=utf-8');

    function fetch_user($db)
    {
        $username = (isset($_GET['username'])) ? $_GET['username'] : '%';
        $email = (isset($_GET['email'])) ? $_GET['email'] : '%';

        $query = <<< Q
                    SELECT * FROM user_account WHERE username LIKE '%$username%' 
                    AND email LIKE '%$email%' 
                Q;

        $result = pg_query($db, $query);
        $data = array();
        while ($row = pg_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo_json_msg(200, "OK", $data);
    }
    if (isset($_SESSION['isadmin']) && $_SESSION['isadmin'] = true && isset($_GET['a'])) {
        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
        if ($db) {
            if ($_GET['a'] == 'fetch') {
                fetch_user($db);
            } else {
                echo_json_msg(400, "Bad Request", null);
            }
        } else {
            echo_json_msg(500, "Internal Server Error");
        }
    } else {
        echo_json_msg(400, "Bad Request");
    }
?>