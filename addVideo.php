<?php
include 'loginSQL.php';
$playlistID = $_GET["playlist_id"];

if(isset($_POST['submit'])){
    $videoTitle = mysqli_real_escape_string($conn, $_POST["input-videoTitle"]);
    $channel = mysqli_real_escape_string($conn, $_POST["input-videoChannel"]);
    $videoLink = mysqli_real_escape_string($conn, $_POST["input-videoLink"]);

    $add = "INSERT INTO finals_videos (playlist_id, videoTitle, videoChannel, videoLink) VALUES ('$playlistID', '$videoTitle', '$channel', '$videoLink')";
    mysqli_query($conn, $add);

    $video_id = $conn->insert_id;

    if(preg_match("@^https?://@", $videoLink)){
        $https_url = $videoLink;
    }else{
        $https_url = "https://".$videoLink;
    }

    $update = "UPDATE finals_videos 
    SET videoTitle='$videoTitle', videoChannel='$channel', videoLink='$https_url'
    WHERE video_id=$video_id";
    mysqli_query($conn, $update);

    $website = "playlistVideos.php?playlist_id=".$playlistID;
    header("Location: ".$website);
}

$conn->close();
?>