<?php
    include 'loginSQL.php';
    $playlistID = $_GET["playlist_id"];

    $update = "UPDATE finals_playlists
    SET favorited='1' 
    WHERE playlist_id='$playlistID'";

    mysqli_query($conn, $update);

    $conn->close();
    header("Location: index.php");
?>