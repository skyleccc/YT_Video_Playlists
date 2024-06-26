<?php
include 'loginSQL.php';
$videoID = $_GET["video_id"];
$playlistID = $_GET["playlist_id"];

$delete = "DELETE FROM finals_videos WHERE finals_videos.video_id=$videoID";
mysqli_query($conn, $delete);

$conn->close();

$website = "playlistVideos.php?playlist_id=".$playlistID;
header("Location: ".$website);
?>