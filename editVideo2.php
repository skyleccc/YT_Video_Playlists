<?php
include 'loginSQL.php';
$videoID = $_GET["video_id"];

if(isset($_POST['submit'])){
    $videoTitle = mysqli_real_escape_string($conn, $_POST["edit-videoTitle"]);
    $channel = mysqli_real_escape_string($conn, $_POST["edit-videoChannel"]);
    $videoLink = mysqli_real_escape_string($conn, $_POST["edit-videoLink"]);

    if(preg_match("@^https?://@", $videoLink)){
        $https_url = $videoLink;
    }else{
        $https_url = "https://".$videoLink;
    }

    $update = "UPDATE finals_videos 
    SET videoTitle='$videoTitle', videoChannel='$channel', videoLink='$https_url'
    WHERE video_id=$videoID";
    mysqli_query($conn, $update);

    header("Location: videoLists.php");
}

$conn->close();
?>