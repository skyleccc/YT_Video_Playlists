<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Added Video List</title>
    <link rel="icon" href="./webPicsDefault/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <?php
        include 'loginSQL.php';

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
<body onload="displayElements3();">
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
            <div class="button" id="hide" onclick="displayAddPlaylistModal();">
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
                <!-- INSERT FAVORITE PLAYLISTS-->
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
    <div id="videoLists">
        <h1>LIST OF ALL VIDEOS ADDED</h1>
        <?php
        $vids_query = $conn->query("SELECT DISTINCT video_id, videoTitle, videoChannel, videoAdded, videoLink, finals_videos.playlist_id, finals_playlists.playlistTitle, finals_videos.lastModified FROM finals_videos INNER JOIN finals_playlists ON finals_playlists.playlist_id=finals_videos.playlist_id ORDER BY `lastModified` ASC;");
        $numVids = mysqli_num_rows($vids_query);
        
        if($numVids > 0){
            echo'
                <table>
                    <tr>
                        <th class="col_vid">Video Name</th>
                        <th class="col_chan">Channel</th>
                        <th class="col_date">Video Added</th>
                        <th class="col_play">Playlist</th>
                        <th class="col_act">Actions</th>
                    </tr>
            ';
            while($table_row = $vids_query->fetch_assoc()){
                $embed_videoLink = getYoutubeEmbedUrl($table_row["videoLink"]);
                echo'
                <tr>
                    <td>'.$table_row["videoTitle"].'</td>
                    <td>'.$table_row["videoChannel"].'</td>
                    <td>'.$table_row["videoAdded"].'</td>
                    <td>'.$table_row["playlistTitle"].'</td>
                    <td>
                        <div class="actionTabButtons">
                            <button class="watchButton" onclick="displayVideoModal('."'".$embed_videoLink."'".');">
                                <i class="fas fa-play" ></i> Watch
                            </button>
                            <button class="editButton" onclick="displayEditVideoModal2('."'".str_replace(chr(39), "𓀴", str_replace(chr(34), "𓀵", $table_row["videoTitle"]))."'".', '."'".str_replace(chr(39), "𓀴", str_replace(chr(34), "𓀵", $table_row["videoChannel"]))."'".', '."'".$table_row["videoLink"]."'".', '.$table_row["video_id"].');">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="deleteButton" onclick="redirectTo('."'./removeVideo2.php?video_id=".$table_row["video_id"]."'".')">
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
            echo'
                <div style="margin: auto;">
                <h1>NO VIDEOS ARE FOUND.</h1>
                </div>
            ';
        }
        ?>
    </div>
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
</html>