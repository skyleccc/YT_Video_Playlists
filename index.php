<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youtube Video Playlists</title>
    <link rel="icon" href="./webPicsDefault/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" >
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <?php
        include 'loginSQL.php';
    ?>
</head>
<body onload="displayElements();">
    <div id="dashboard">
        <div class="topBar">
            <div class="profPic">
                <img src="profilePictures/defaultMale.png" alt="Profile Picture">
            </div>
            <div class="profName">
                Welcome, <br>
                <b>User</b>
            </div>
        </div>
        <div class="middleBar">
            <div class="button" id="addPlaylist" onclick="displayAddPlaylistModal();">
                <i class="fa-regular fa-square-plus"></i> Add Playlist
            </div>
            <div class="featureBox">
                <div class="feature" onclick="redirectTo('index.php');">
                    <i class="fa-solid fa-folder-open"></i> Playlists
                </div>
                <div class="feature" onclick="redirectTo('videoLists.php');">
                    <i class="fa-solid fa-circle-play"></i> Videos
                </div>
            </div>
        </div>
        <div class="playlistBar">
            <div class="playlistBar-header">
                FAVORITE PLAYLISTS
            </div>
            <div id="playlistsBar" class="playlistBar-playlists">
                <!-- INSERT FAVORITE PLAYLISTS -->
                <?php
                    $favorites = $conn->query("SELECT * FROM finals_playlists WHERE favorited='1'");
                    if ($favorites->num_rows > 0) {
                        while ($row = $favorites->fetch_assoc()) {
                            $playlistID = $row["playlist_id"];
                            $vids = mysqli_query($conn, "SELECT * FROM finals_videos WHERE playlist_id=".$playlistID);
                            $numVids = mysqli_num_rows($vids);
                            $fileFolder = "./playlistPictures/";
                            if($row["defaultPic"] == 1){
                                $fileName = $fileFolder."default".$row["fileType"];
                            }else{
                                $fileName = $fileFolder.$row["playlist_id"].$row["fileType"];
                            }
                            $fav_title = htmlspecialchars($row["playlistTitle"]);
                            echo '
                                <a class="playlists" href="playlistVideos.php?playlist_id='.$row["playlist_id"].'">
                                    <div class="playlists-img">
                                        <img src="'.$fileName.'" alt="Playlist Picture">
                                    </div>
                                    <div class="playlists-title">
                                        <b>'.$fav_title.'</b><br>
                                        '.$numVids.' videos added
                                    </div>
                                </a>
                            ';
                        }
                    }
                ?>
            </div>
        </div>
        <div class="bottomBar">
            <div class="feature bottom">
                <i class="fa-sharp fa-solid fa-right-from-bracket"></i> Sign Out
            </div>
        </div>
    </div>
    <div id="playlistSpace">
        <!-- INSERT PLAYLIST HERE -->
        <?php
            $playlistTiles = $conn->query("SELECT * FROM finals_playlists");
            $tile_number = 0;
            if ($playlistTiles->num_rows > 0) {
                while ($play_row = $playlistTiles->fetch_assoc()) {
                    $fileFolder = "./playlistPictures/";
                    if($play_row["defaultPic"] == 1){
                        $play_fileName = $fileFolder."default".$play_row["fileType"];
                    }else{
                        $play_fileName = $fileFolder.$play_row["playlist_id"].$play_row["fileType"];
                    }
                    if($play_row["favorited"] == 1){
                        $favoriteClass = "favorite";
                        $favoriteLink = "removeFavoritePlaylist.php?playlist_id=";
                    }else{
                        $favoriteClass = "";
                        $favoriteLink = "favoritePlaylist.php?playlist_id=";
                    }

                    if($play_row["playlistDesc"] == ""){
                        $play_Desc = "NO DESCRIPTION AVAILABLE.";
                    }else{
                        $play_Desc = $play_row["playlistDesc"];
                    }
                    $playlistVideos = mysqli_query($conn, "SELECT * FROM finals_videos WHERE playlist_id=".$play_row["playlist_id"]);
                    $playlist_numVideos = mysqli_num_rows($playlistVideos);

                    echo '
                        <a class="playlistTile" href="playlistVideos.php?playlist_id='.$play_row["playlist_id"].'">
                            <div class="playlistPic">
                                <img src="'.$play_fileName.'" alt="Playlist Picture">
                                <i class="fas fa-star star-icon '.$favoriteClass.'" onclick="redirectTo('."'".$favoriteLink.$play_row["playlist_id"]."'".');" onmouseenter="disableLink('.$tile_number.')" onmouseleave="enableLink('.$tile_number.','."'"."playlistVideos.php?playlist_id=".$play_row["playlist_id"]."'".')"></i>
                            </div>
                            <div class="playlistTitle">
                                '.$play_row["playlistTitle"].'
                                <div style="font-size: 16px;">
                                    <b>'.$playlist_numVideos.' Videos</b>
                                </div>
                            </div>
                            <div class="playlistDesc">
                                '.$play_Desc.'
                            </div>
                            <div class="icons" onmouseenter="disableLink('.$tile_number.')" onmouseleave="enableLink('.$tile_number.','."'"."playlistVideos.php?playlist_id=".$play_row["playlist_id"]."'".')">
                                <i class="fas fa-edit" onclick="displayEditPlaylistModal('."'".str_replace(chr(39), "𓀴", str_replace(chr(34), "𓀵", $play_row["playlistTitle"]))."'".","."'".str_replace(chr(39), "𓀴", str_replace(chr(34), "𓀵", $play_row["playlistDesc"]))."'".",".$play_row["playlist_id"].');" ></i>
                                <i class="fas fa-trash-alt" onclick="redirectTo('."'"."removePlaylist.php?playlist_id=".$play_row["playlist_id"]."'".');"></i>
                            </div>
                        </a>
                    ';
                    $tile_number++;
                }
            }
        ?>
    </div>
    <!-- ADD PLAYLIST MODAL -->
    <div id="addPlaylistModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>ADD A PLAYLIST</h3>
                <span class="close" id="addPlaylistClose">&times;</span>
            </div>
            <form action="addPlaylist.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div>
                        <label for="input-playlistTitle">Playlist Title: <span class="req">*</span></label><br>
                        <input type="text" id="input-playlistTitle" name="input-playlistTitle" required>
                    </div>
                    <div>
                        <label for="input-playlistDesc">Playlist Description:</label><br>
                        <textarea id="input-playlistDesc" name="input-playlistDesc"></textarea>
                    </div>
                    <div>
                        <label for="input-playlistPic">Upload Image:</label><br>
                        <input type="file" id="input-playlistPic" name="input-playlistPic">
                        <div class="togglePic">
                            <input type="checkbox" id="togglePicInput" name="togglePicInput" onclick="changeType();"> Use Link Instead<br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" id="submitPlaylist">ADD PLAYLIST</button>
                </div>
            </form>
        </div>
    </div>
    <!-- ADD PLAYLIST MODAL -->
    <!-- EDIT PLAYLIST MODAL -->
    <div id="editPlaylistModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>EDIT A PLAYLIST</h3>
                <span class="close" id="editPlaylistClose">&times;</span>
            </div>
            <form id="editPlaylist" action="/" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div>
                        <label for="edit-playlistTitle">Edit Playlist Title: <span class="req">*</span></label><br>
                        <input type="text" id="edit-playlistTitle" name="edit-playlistTitle" required>
                    </div>
                    <div>
                        <label for="edit-playlistDesc">Edit Playlist Description:</label><br>
                        <textarea id="edit-playlistDesc" name="edit-playlistDesc"></textarea>
                    </div>
                    <div>
                        <label for="edit-playlistPic">Edit Upload Image:</label><br>
                        <input type="file" id="edit-playlistPic" name="edit-playlistPic">
                        <div class="togglePic">
                            <input type="checkbox" id="togglePicInput2" name="togglePicInput" onclick="changeType2();"> Use Link Instead<br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" id="editVideo">EDIT PLAYLIST</button>
                </div>
            </form>
        </div>
    </div>
    <!-- EDIT PLAYLIST MODAL -->
</body>

<?php
$conn->close();
?>
</html>