<?php
    session_start();
    require 'json_response.php';
    require 'error_handling.php';
    function addNewAlbum(){
        $judul = $_POST["judul_album"];
        $penyanyi = $_POST["penyanyi_album"];
        $tanggal_terbit = $_POST["tanggal_terbit_album"];
        $genre = $_POST["genre_album"];
        $valid = 1;

        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
        $queryCheckJudul = "SELECT * FROM album WHERE judul = '$judul'";
        $resultCheckJudul = pg_query($db, $queryCheckJudul);
        $imagename = $_FILES["album_cover_upload"]["name"];
        $target_dir_image = "../assets/img/";
        $imageFileType = strtolower(pathinfo($imagename,PATHINFO_EXTENSION));
        $tmpimagename = basename($_FILES['album_cover_upload']['tmp_name']).".".$imageFileType;
        $target_file_image = $target_dir_image. basename($_FILES['album_cover_upload']['tmp_name']).".".$imageFileType;
        if (isset($genre)){ 
            $query = "INSERT INTO album (judul, penyanyi, total_duration, image_path, tanggal_terbit, genre) VALUES ('$judul', '$penyanyi', 0, '/assets/img/$tmpimagename', '$tanggal_terbit', '$genre')";
            if (move_uploaded_file($_FILES["album_cover_upload"]["tmp_name"], $target_file_image)){
                $result = pg_query($db, $query);
                echo_json_msg(200,"Upload berhasil");
            } else {
                echo_json_msg(400,"Upload gagal");
            }
        } else {
            $query = "INSERT INTO album (judul, penyanyi, total_duration, image_path, tanggal_terbit) VALUES ('$judul', '$penyanyi', 0, '/assets/img/$tmpimagename', '$tanggal_terbit')";
            if (move_uploaded_file($_FILES["album_cover_upload"]["tmp_name"], $target_file_image)){
                $result = pg_query($db, $query);
                echo_json_msg(200,"Upload berhasil");
            } else {
                echo_json_msg(400,"Upload gagal");
            }
        }
    }

    function addAlbumContent(){
        $judul = $_POST['judul'];
        $penyanyi = $_POST['penyanyi'];
        $tanggal_terbit = $_POST['tanggal_terbit'];
        $genre = $_POST['genre'];
        $album = $_POST['album'];
    
        $dur = shell_exec("ffmpeg -i ".$_FILES['file']['tmp_name']." 2>&1");
        preg_match("/Duration: (.{2}):(.{2}):(.{2})/", $dur, $duration);
        $hours = $duration[1];
        $minutes = $duration[2];
        $seconds = $duration[3];
        $durasi = $seconds + ($minutes*60) + ($hours*60*60);
        $filename = $_FILES['file']['name'];
        $FileType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
        $target_dir = "../assets/audio/";
        $target_file = $target_dir. basename($_FILES['file']['tmp_name']).".".$FileType;
        $tmpname = basename($_FILES['file']['tmp_name']).".".$FileType;
    
        $isvalid = 1;
        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
        $queryCheckJudul = "SELECT * FROM song WHERE judul = '$judul' AND penyanyi = '$penyanyi'";
        $queryCheckAlbum = "SELECT album_id FROM album WHERE album_id = '$album'";
        $resultCheckJudul = pg_query($db, $queryCheckJudul);
        $resultCheckAlbum = pg_query($db, $queryCheckAlbum);

        if (isset($_FILES['image'])){
            $image = $_FILES['image']['name'];
            $imageFileType = strtolower(pathinfo($image,PATHINFO_EXTENSION));
            $target_dir_image = "../assets/img/";
            $tmpimagename = basename($_FILES['image']['tmp_name']).".".$imageFileType;
            $target_file_image = $target_dir_image. basename($_FILES['image']['tmp_name']).".".$imageFileType;
            if(move_uploaded_file($_FILES['file']['tmp_name'], $target_file && move_uploaded_file($_FILES['image']['tmp_name'], $target_file_image))){
                $query = "INSERT INTO song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id) VALUES ('$judul', '$penyanyi', '$tanggal_terbit', '$genre', '$durasi', '/assets/audio/$tmpname', '/assets/img/$tmpimagename', '$album')";
                $result = pg_query($db, $query);
                $countTime = "UPDATE album SET total_duration = (SELECT SUM(duration) FROM song WHERE album_id = '$album') WHERE album_id = '$album'";
                $resultTime = pg_query($db, $countTime);
                echo_json_msg(200,"Upload success");
            }
        
            else {
                echo_json_msg(400,"Upload failed");
            }
        } else {
            if(move_uploaded_file($_FILES['file']['tmp_name'], $target_file)){
                $query = "INSERT INTO song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, album_id) VALUES ('$judul', '$penyanyi', '$tanggal_terbit', '$genre', '$durasi', '/assets/audio/$tmpname', '$album')";
                $result = pg_query($db, $query);
                $countTime = "UPDATE album SET total_duration = (SELECT SUM(duration) FROM song WHERE album_id = '$album') WHERE album_id = '$album'";
                $resultTime = pg_query($db, $countTime);
                echo_json_msg(200,"Upload success");
            }
            else {
                echo_json_msg(400,"Upload failed");
            }
        }
            
        
    }

    function deleteAlbum(){
        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
        $id = $_POST['id_delete'];
        $filequery = "SELECT song_id FROM song WHERE album_id = '$id'";
        $fileResult = pg_query($db, $filequery);
        while ($row = pg_fetch_row($fileResult)){
            $filequery2 = "SELECT audio_path FROM song WHERE song_id = '$row[0]'";
            $fileResult2 = pg_query($db, $filequery2);
            $row2 = pg_fetch_row($fileResult2);
            $filequery3 = "SELECT image_path FROM song WHERE song_id = '$row[0]'";
            $fileResult3 = pg_query($db, $filequery3);
            $row3 = pg_fetch_row($fileResult3);
            unlink("..".$row2[0]);
            unlink("..".$row3[0]);
        }

        $query = "DELETE from album WHERE album_id = '$id'";
        $result = pg_query($db,$query);
        if($result){
            echo_json_msg(200,"Success");
        } else{
            echo_json_msg(200,"Failed");
        }
    }

    function deleteAlbumContent(){
        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
        $id = $_POST['id'];
        $album = $_POST['album_id'];

        $queryPath = "SELECT audio_path, image_path FROM song WHERE song_id = '$id'";
        $resultPath = pg_query($db,$queryPath);
        $path = pg_fetch_assoc($resultPath);
        unlink ("..".$path['audio_path']);
        if ($path['image_path'] != NULL){
            unlink ("..".$path['image_path']);
        }

        $query = "DELETE from song WHERE song_id = '$id'";
        $result = pg_query($db,$query);
        $countTime = "UPDATE album SET total_duration = (SELECT COALESCE(SUM(duration),0) FROM song WHERE album_id = '$album') WHERE album_id = '$album'";
        $resultTime = pg_query($db, $countTime);
        if($result  && $resultTime){
            echo "File deleted";
        } else{
            echo "Failed";
        }
    }

    function getAlbum(){
        $id = $_GET['id'];
        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
        $query = "SELECT * FROM album WHERE album_id = '$id'";
        $result = pg_query($db, $query);
        $data = array();
        while($row = pg_fetch_assoc($result)){
            $data[] = $row;
        }
        echo_json_msg(200, "OK", $data);
    }

    function countData(){
        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
        $query = "SELECT * FROM album";
        $result = pg_query($db, $query);
        $data = pg_num_rows($result);
        echo_json_msg(200, "OK", $data);
    }

    function editAlbum(){
        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
        $id = $_POST['id'];
        $judul = $_POST['judul'];
        $tanggal_terbit = $_POST['tanggal_terbit'];
        $genre = $_POST['genre'];
        
        if (isset($_FILES['image'])){
            $target_dir = "../assets/img/";
            $tmpname = basename($_FILES['image']['tmp_name']).".".$imageFileType;
            $target_file = $target_dir. basename($_FILES['image']['tmp_name']).".".$imageFileType;
            if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)){
                $findPath = "SELECT image_path FROM album WHERE album_id = '$id'";
                $resultPath = pg_query($db, $findPath);
                $path = pg_fetch_assoc($resultPath);
                unlink("..".$path['image_path']);
                $query = "UPDATE album SET judul = '$judul', tanggal_terbit = '$tanggal_terbit', genre = '$genre', image_path = '/assets/img/$tmpname' WHERE album_id = '$id'";
                $result = pg_query($db, $query);
                echo_json_msg(200,"Upload berhasil");
            }
            else {
                echo_json_msg(200,"Upload gagal");
            }
        } else {
            $query = "UPDATE album SET judul = '$judul', tanggal_terbit = '$tanggal_terbit', genre = '$genre' WHERE album_id = '$id'";
            $result = pg_query($db, $query);
            echo_json_msg(200, "Berhasil");
        }
    }

    function getAlbumDuration(){
        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
        $id = $_GET['album_id_duration'];
        $query = "SELECT SUM(duration) FROM song WHERE album_id = '$id'";
        $result = pg_query($db, $query);
        $data = pg_fetch_assoc($result);
        echo_json_msg(200, "OK", $data);
    }

    function getSongByAlbumID(){
        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
        $id = $_GET['album_id_song'];
        $query = "SELECT * FROM song WHERE album_id = '$id'";
        $result = pg_query($db, $query);
        $data = array();
        while($row = pg_fetch_assoc($result)){
            $data[] = $row;
        }
        echo_json_msg(200, "OK", $data);
    }
    function changeSongAlbumto($songid, $albumid) {
        $db = pg_connect("host=tubes-1-db port=5432 dbname=musikwbd user=postgres password=postgres");
        $query = "UPDATE song SET album_id = $albumid WHERE song_id = $songid";
        $result = pg_query($db, $query);
        if($result){
            echo_json_msg(200,"Success");
        } else{
            echo_json_msg(400,"Failed");
        }
    }

    if (isset($_POST["judul_album"]) && isset($_POST["penyanyi_album"]) && isset($_POST["tanggal_terbit_album"]) && isset($_FILES["album_cover_upload"])){
        addNewAlbum();
    } else if (isset($_POST['album_id']) && isset($_POST['song_id'])){
        changeSongAlbumto($_POST['song_id'], $_POST['album_id']);
    } elseif (isset($_POST['id']) && isset($_POST['judul']) && isset($_POST['tanggal_terbit']) && isset($_POST['genre'])){
        editAlbum();
    } elseif (isset($_POST['id_delete'])){
        deleteAlbum();
    } elseif (isset($_POST['id']) && isset($_POST['album_id'])){
        deleteAlbumContent();
    } elseif (isset($_GET['id'])){
        getAlbum();
    } elseif (isset($_GET['count'])){
        countData();
    } elseif (isset($_GET['album_id_duration'])){
        getAlbumDuration();
    } elseif (isset($_GET['album_id_song'])){
        getSongByAlbumID();
    } else {
        echo_json_msg(400, "Bad Request");
    }
?>