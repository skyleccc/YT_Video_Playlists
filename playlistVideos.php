<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist Videos</title>
    <link rel="icon" href="./webPicsDefault/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <?php
        include 'loginSQL.php';
        $playlistID_main = $_GET["playlist_id"];
        $playlist = $conn->query("SELECT * FROM `finals_playlists` WHERE playlist_id=".$playlistID_main);
        $playlist_row = $playlist->fetch_assoc();

        $fileFolder = "./playlistPictures/";
        if($playlist_row["defaultPic"] == 1){
            $play_fileName = $fileFolder."default".$playlist_row["fileType"];
        }else{
            $play_fileName = $fileFolder.$playlist_row["playlist_id"].$playlist_row["fileType"];
        }

        function getYoutubeEmbedUrl($url)
        {
            $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
            $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

            if (preg_match($longUrlRegex, $url, $matches)) {
                $youtube_id = $matches[count($matches) - 1];
            }

            if (preg_match($shortUrlRegex, $url, $matches)) {
                $youtube_id = $matches[count($matches) - 1];
            }
            return 'https://www.youtube.com/embed/' . $youtube_id ;
        }

    ?>
</head>
<body onload="displayElements2();">
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
            <div class="button" id="addPlaylist" onclick="displayAddVideoModal();">
                <i class="fa-regular fa-square-plus"></i> Add Video
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
    <div id="videoSpace">
        <div class="playlistInfo">
            <!-- PLAYLIST INFO HERE -->
            <?php
                $vidsDesc = mysqli_query($conn, "SELECT * FROM finals_videos WHERE playlist_id=".$playlistID_main);
                $numVidsDesc = mysqli_num_rows($vidsDesc);
                if($playlist_row["playlistDesc"] == ""){
                    $playlist_Desc = "NO DESCRIPTION AVAILABLE.";
                }else{
                    $playlist_Desc = $playlist_row["playlistDesc"];
                }
                echo'
                <div class="playlistInfo-pic">
                    <img src="'.$play_fileName.'" alt="Playlist Picture">
                </div>
                <div class="playlistInfo-descriptions">
                    <div class="playlistInfo-titlebar">
                        <div class="playlistInfo-title">
                        '.$playlist_row["playlistTitle"].'
                        </div>
                        <div class="icons">
                            <i class="fas fa-edit" onclick="displayEditPlaylistModal2('."'".str_replace(chr(39), "𓀴", str_replace(chr(34), "𓀵", $playlist_row["playlistTitle"]))."'".","."'".str_replace(chr(39), chr(34), $playlist_row["playlistDesc"])."'".",".$playlistID_main.');"></i>
                            <i class="fas fa-trash-alt" onclick="redirectTo('."'"."removePlaylist.php?playlist_id=".$playlistID_main."'".');"></i>
                        </div>
                    </div>
                    <div class="playlistInfo-mini">
                        <i class="fa-solid fa-clock"></i>'.$playlist_row["dateCreated"].'
                        <span class="space"></span>
                        <i class="fa-solid fa-film"></i>'.$numVidsDesc.' videos
                    </div>
                    <div class="playlistInfo-description">
                    '.$playlist_Desc.'
                    </div>
                </div>
                ';
            ?>
        </div>
        <div class="playlistVideos">
            <!-- VIDEO TABLES HERE -->
            <?php
                $table_query = $conn->query("SELECT * FROM finals_videos WHERE playlist_id=".$playlistID_main);
                if($table_query->num_rows > 0){
                    echo'
                    <table>
                        <tr>
                            <th class="videoName">Video Name</th>
                            <th class="channelName">Channel</th>
                            <th class="videoAdded">Video Added</th>
                            <th class="actionTab">Actions</th>
                        </tr>
                    ';
                    
                    while($table_row = $table_query->fetch_assoc()){
                        $embed_videoLink = getYoutubeEmbedUrl($table_row["videoLink"]);
                        echo'
                        <tr>
                            <td>'.$table_row["videoTitle"].'</td>
                            <td>'.$table_row["videoChannel"].'</td>
                            <td>'.$table_row["videoAdded"].'</td>
                            <td> 
                                <div class="actionTabButtons">
                                    <button class="watchButton" onclick="displayVideoModal('."'".$embed_videoLink."'".');">
                                        <i class="fas fa-play" ></i> Watch
                                    </button>
                                    <button class="editButton" onclick="displayEditVideoModal('."'".str_replace(chr(39), "𓀴", str_replace(chr(34), "𓀵", $table_row["videoTitle"]))."'".', '."'".str_replace(chr(39), "𓀴", str_replace(chr(34), "𓀵", $table_row["videoChannel"]))."'".', '."'".$table_row["videoLink"]."'".', '.$table_row["video_id"].', '."'".$playlistID_main."'".');">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="deleteButton" onclick="redirectTo('."'./removeVideo.php?video_id=".$table_row["video_id"]."&playlist_id=".$playlistID_main."'".')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        ';
                    }
                    
                    echo'
                    </table>
                    ';
                }else{
                    echo '
                    <div style="width: 100%; padding: 100px;  ;text-align: center">
                        <h1>PLAYLIST IS EMPTY.</h1>
                    </div>
                    ';
                }
            ?>
        </div>
    </div>
    <!-- ADD VIDEO MODAL -->
    <?php
        echo'
        <div id="addVideoModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>ADD A YOUTUBE VIDEO</h3>
                    <span class="close" id="addVideoClose">&times;</span>
                </div>
                <form action="addVideo.php?playlist_id='.$playlistID_main.'" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div>
                            <label for="input-videoTitle">Youtube Video Title: <span class="req">*</span></label><br>
                            <input type="text" id="input-videoTitle" name="input-videoTitle" required>
                        </div>
                        <div>
                            <label for="input-videoChannel">Youtube Channel: <span class="req">*</span></label><br>
                            <input type="text" id="input-videoChannel" name="input-videoChannel" required>
                        </div>
                        <div>
                            <label for="input-videoLink">Youtube Link: <span class="req">*</span></label><br>
                            <input type="text" id="input-videoLink" name="input-videoLink" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submit" id="submitVideo">ADD YT VIDEO</button>
                    </div>
                </form>
            </div>
        </div>
        ';
    ?>
    <!-- ADD VIDEO MODAL -->
    <!-- EDIT VIDEO MODAL -->
    <div id="editVideoModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>EDIT A YOUTUBE VIDEO</h3>
                <span class="close" id="editVideoClose">&times;</span>
            </div>
            <form id="editVideo" action="/" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div>
                        <label for="edit-videoTitle">Edit Youtube Video Title: <span class="req">*</span></label><br>
                        <input type="text" id="edit-videoTitle" name="edit-videoTitle" required>
                    </div>
                    <div>
                        <label for="edit-videoChannel">Edit Youtube Channel: <span class="req">*</span></label><br>
                        <input type="text" id="edit-videoChannel" name="edit-videoChannel" required>
                    </div>
                    <div>
                        <label for="edit-videoLink">Edit Youtube Link: <span class="req">*</span></label><br>
                        <input type="text" id="edit-videoLink" name="edit-videoLink" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit">EDIT YOUTUBE VIDEO</button>
                </div>
            </form>
        </div>
    </div>
    <!-- EDIT VIDEO MODAL -->
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
                        <label for="edit-playlistPic2">Edit Upload Image:</label><br>
                        <input type="file" id="edit-playlistPic2" name="edit-playlistPic">
                        <div class="togglePic">
                            <input type="checkbox" id="togglePicInput3" name="togglePicInput" onclick="changeType3();"> Use Link Instead<br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit">EDIT PLAYLIST</button>
                </div>
            </form>
        </div>
    </div>
    <!-- EDIT PLAYLIST MODAL -->
    <!-- DISPLAY VIDEO -->
    <div id="displayVideoModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>YOUTUBE VIDEO</h3>
                <span class="close" id="displayVideoClose">&times;</span>
            </div>
            <div class="modal-body">
                <iframe id="videoFrame" src="/" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    <!-- DISPLAY VIDEO -->
</body>
<?php
$conn->close();
?>
</html>