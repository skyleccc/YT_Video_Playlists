<?php
include 'loginSQL.php';
$playlistID = $_GET["playlist_id"];

$delete = "DELETE FROM finals_playlists WHERE finals_playlists.playlist_id = $playlistID";

$file = "./playlistPictures/".$playlistID.".*";

foreach(glob($file) as $filename){
    unlink(realpath($filename));
}

mysqli_query($conn, $delete);

$conn->close();

header("Location: index.php");
?>