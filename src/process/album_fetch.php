<?php
    session_start();
    require 'json_response.php';
    require 'error_handling.php';
    header('Content-Type: application/json; charset=utf-8');
    if (isset($_SESSION['login'])) {
        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres"); 
        if($db && isset($_GET['page']) && isset($_GET['limit'])) {
            // $judul = (isset($_GET['judul']) ? $_GET['judul'] : '%');
            // $penyanyi = (isset($_GET['penyanyi']) ? $_GET['penyanyi'] : '%');
            // $tahun = (isset($_GET['tahun']) ? $_GET['tahun'] : '%');
            // $genre = (isset($_GET['genre']) ? $_GET['genre'] : '%');
            // $sort = (isset($_GET['sort']) ? $_GET['sort'] . ', judul' : 'judul');
            // $order = (isset($_GET['order']) ? $_GET['order'] : 'ASC');
            $p = $_GET['page'];
            $l = $_GET['limit'];
            $query = 
                'SELECT * FROM album ORDER BY judul ASC LIMIT '. $l .' OFFSET ' . ($p - 1) * $l;
            

            $result = pg_query($db, $query);
            $data = array();
            while($row = pg_fetch_assoc($result)){
                $data[] = $row;
            }
            echo_json_msg(200, "OK", $data);
        } else {
            echo_json_msg(500, "Internal Server Error");
        }
    } else {
        echo_json_msg(400, "Bad Request");
    }
?>