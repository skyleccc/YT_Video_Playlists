<?php

$servername = "localhost";
$server_username = "root";
$dbpassword = "";
$dbname = "youtube_video_playlist";

$conn = mysqli_connect($servername, $server_username, $dbpassword, $dbname);

if(!$conn){
    die("Connection Failed: ". mysqli_connect_error());
}
?>