<?php
    session_start();
    require 'json_response.php';
    require 'error_handling.php';
    header('Content-Type: application/json; charset=utf-8');

    function fetch_user($db)
    {
        $username = $_SESSION['username'];

        $query = <<< Q
                    SELECT * FROM user_account WHERE username LIKE '%$username%' 
                Q;

        $result = pg_query($db, $query);
        $data = array();
        while ($row = pg_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo_json_msg(200, "OK", $data);
    }
    if (isset($_SESSION['login']) && $_SESSION['login'] == true ) {
        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
        if ($db) {
            fetch_user($db);
        } else {
            echo_json_msg(500, "Internal Server Error");
        }
    } else {
        echo_json_msg(400, "Bad Request");
    }
?>