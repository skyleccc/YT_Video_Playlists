<?php
include 'loginSQL.php';

if(isset($_POST['submit'])){
    $playlistTitle = mysqli_real_escape_string($conn, $_POST["input-playlistTitle"]);
    $playlistDesc =  mysqli_real_escape_string($conn, $_POST["input-playlistDesc"]);
    $playlistPic;
    $isDefaultPic = 0;
    $favorited = 0;

    // file variables
    $file_name;
    $file_temp_name;
    $file_size;
    $file_error;
    $file_type = ".png";
    $file_ext;
    $file_actual_ext;
    
    $file_folder = "./playlistPictures/";

    $add = "INSERT INTO finals_playlists (playlistTitle, playlistDesc, fileType, defaultPic, favorited) VALUES ('$playlistTitle', '$playlistDesc', '$file_type', '$isDefaultPic' ,'$favorited')";
    mysqli_query($conn, $add);

    $image_id = $conn->insert_id;

    // check if picture inputted is link or file
    if(!empty($_POST["input-playlistPic"])){
        $web_URL =  $_POST["input-playlistPic"]; 

        if(preg_match("@^https?://@", $web_URL)){
            $url = $web_URL;
        }else{
            $url = "https://".$web_URL;
        }

        $file_name = basename($url);
        $full_path = $file_folder.$file_name;

        if (file_put_contents($full_path, file_get_contents($url))) { 
            echo "File downloaded successfully to $full_path<br>"; 
        
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimetype = finfo_file($finfo, $full_path);
            finfo_close($finfo);
        
            $allowed_types = array(
                'image/jpeg' => '.jpeg',
                'image/jpg' => '.jpg',
                'image/png' => '.png',
                'image/svg' => '.svg',
                'image/gif' => '.gif',
                'image/webp' => '.webp',
                'image/apng' => '.apng',
                'image/avif' => '.avif',
                'image/ico' => '.ico',
                'image/cur' => '.cur',
                'image/bmp' => '.bmp',
                'image/jfif' => '.jfif'
            );
        
            if (isset($allowed_types[$mimetype])) {
                $new_extension = $allowed_types[$mimetype];
                
                $new_file_name = $image_id . $new_extension;
                $new_full_path = $file_folder . $new_file_name;
                
                if (rename($full_path, $new_full_path)) {
                    echo "File renamed to $new_full_path<br>";
                    $file_type = $new_extension;
                } else {
                    echo "Failed to rename file.<br>";
                    $playlistPic = "default.png";
                    $isDefaultPic = 1;
                    $file_type = ".png";
                }
            } else {
                // If the file type is not allowed, delete the file
                unlink($full_path);
                echo "Invalid file type. File deleted.<br>";
                $playlistPic = "default.png";
                $isDefaultPic = 1;
                $file_type = ".png";
            }
        }else{
            $playlistPic = "default.png";
            $isDefaultPic = 1;
            $file_type = ".png";
        }
    }else if (!empty($_FILES["input-playlistPic"])){
        $playlistFile = $_FILES["input-playlistPic"];
        $file_name = $_FILES['input-playlistPic']['name'];
        $file_temp_name = $_FILES['input-playlistPic']['tmp_name'];
        $file_size = $_FILES['input-playlistPic']['size'];
        $file_error = $_FILES['input-playlistPic']['error'];
        $file_type = $_FILES['input-playlistPic']['type'];
        $file_ext = explode('.', $file_name);
        $file_actual_ext = strtolower(end($file_ext));
        $allowed_ext = array('jpg', 'jpeg', 'png', 'svg', 'gif', 'webp', 'apng', 'avif', 'ico', 'cur', 'bmp', 'jfif');

        if(in_array($file_actual_ext, $allowed_ext)){
            if($file_error === 0){
                $stored_path = $file_folder.$image_id.".".$file_actual_ext;
                move_uploaded_file($file_temp_name, $stored_path);
                $file_type = ".".$file_actual_ext;
            }else{
                $playlistPic = "default.png";
                $isDefaultPic = 1;
                $file_type = ".png";
            }
        }else{
            $playlistPic = "default.png";
            $isDefaultPic = 1;
            $file_type = ".png";
        }

    }else{
        $playlistPic = "default.png";
        $isDefaultPic = 1;
        $file_type = ".png";
    }
    
    $update = "UPDATE finals_playlists
    SET playlistTitle='$playlistTitle', playlistDesc='$playlistDesc', fileType='$file_type', defaultPic='$isDefaultPic', favorited='$favorited' 
    WHERE playlist_id='$image_id'";

    mysqli_query($conn, $update);

    header("Location: index.php");
  }

  $conn->close();
?>