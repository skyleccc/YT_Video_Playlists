function displayAddPlaylistModal(){
    // Get the modal
    var modal = document.getElementById("addPlaylistModal");
    // Get the <span> element that closes the modal
    var span = document.getElementById("addPlaylistClose");
    // When the user clicks on the button, open the modal
    modal.style.display = "block";
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
        removeAddPlaylistModalValues();
    }
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
        modal.style.display = "none";
        removeAddPlaylistModalValues();
        }
    }
}

function removeAddPlaylistModalValues(){
    document.getElementById('input-playlistTitle').value = "";
    document.getElementById('input-playlistDesc').value = "";
    document.getElementById('input-playlistPic').value = "";
}

function displayEditPlaylistModal(playlistTitle, playlistDescription, playlistID){
    var modalEdit = document.getElementById("editPlaylistModal");
    var span = document.getElementById("editPlaylistClose");
    modalEdit.style.display = "block";

    span.onclick = function() {
        modalEdit.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modalEdit) {
        modalEdit.style.display = "none";
        }
    }

    var conv_playlistTitle = convertChars(convertChars(playlistTitle, "𓀴", "\'"), "𓀵", "\"");
    var conv_playlistDescription = convertChars(convertChars(playlistDescription, "𓀴", "\'"), "𓀵", "\"");

    document.getElementById('edit-playlistTitle').value = conv_playlistTitle;
    document.getElementById('edit-playlistDesc').value = conv_playlistDescription;
    document.getElementById('editPlaylist').action = "./editPlaylist.php?playlist_id="+playlistID;
}

function displayEditPlaylistModal2(playlistTitle, playlistDescription, playlistID){
    var modalEdit = document.getElementById("editPlaylistModal");
    var span = document.getElementById("editPlaylistClose");
    modalEdit.style.display = "block";

    span.onclick = function() {
        modalEdit.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modalEdit) {
        modalEdit.style.display = "none";
        }
    }

    var conv_playlistTitle = convertChars(convertChars(playlistTitle, "𓀴", "\'"), "𓀵", "\"");
    var conv_playlistDescription = convertChars(convertChars(playlistDescription, "𓀴", "\'"), "𓀵", "\"");

    document.getElementById('edit-playlistTitle').value = conv_playlistTitle;
    document.getElementById('edit-playlistDesc').value = conv_playlistDescription;
    document.getElementById('editPlaylist').action = "./editPlaylist2.php?playlist_id="+playlistID;
}

function displayAddVideoModal(){
    var modal = document.getElementById("addVideoModal");
    var span = document.getElementById("addVideoClose");
    modal.style.display = "block";
    
    span.onclick = function() {
        modal.style.display = "none";
        removeAddVideoModalValues();
    }
    
    window.onclick = function(event) {
        if (event.target == modal) {
        modal.style.display = "none";
        removeAddVideoModalValues();
        }
    }
}

function removeAddVideoModalValues(){
    document.getElementById('input-videoTitle').value = "";
    document.getElementById('input-videoChannel').value = "";
    document.getElementById('input-videoLink').value = "";
}

function displayEditVideoModal(videoTitle, videoChannel, videoURL, videoID, playlistID){
    var modal = document.getElementById("editVideoModal");
    var span = document.getElementById("editVideoClose");
    modal.style.display = "block";
    
    span.onclick = function() {
        modal.style.display = "none";
    }
    
    window.onclick = function(event) {
        if (event.target == modal) {
        modal.style.display = "none";
        }
    }

    conv_videoTitle = convertChars(convertChars(videoTitle, "𓀴", "\'"), "𓀵", "\"");
    conv_videoChannel = convertChars(convertChars(videoChannel, "𓀴", "\'"), "𓀵", "\"");

    document.getElementById('edit-videoTitle').value = conv_videoTitle;
    document.getElementById('edit-videoChannel').value = videoChannel;
    document.getElementById('edit-videoLink').value = videoURL;
    document.getElementById('editVideo').action = "./editVideo.php?video_id="+videoID+"&playlist_id="+playlistID;
}

function displayEditVideoModal2(videoTitle, videoChannel, videoURL, videoID){
    var modal = document.getElementById("editVideoModal");
    var span = document.getElementById("editVideoClose");
    modal.style.display = "block";
    
    span.onclick = function() {
        modal.style.display = "none";
    }
    
    window.onclick = function(event) {
        if (event.target == modal) {
        modal.style.display = "none";
        }
    }

    conv_videoTitle = convertChars(convertChars(videoTitle, "𓀴", "\'"), "𓀵", "\"");
    conv_videoChannel = convertChars(convertChars(videoChannel, "𓀴", "\'"), "𓀵", "\"");

    document.getElementById('edit-videoTitle').value = conv_videoTitle;
    document.getElementById('edit-videoChannel').value = videoChannel;
    document.getElementById('edit-videoLink').value = videoURL;
    document.getElementById('editVideo').action = "./editVideo2.php?video_id="+videoID;
}

function displayVideoModal(link){
    var modal = document.getElementById('displayVideoModal');
    var span = document.getElementById('displayVideoClose');
    document.getElementById('videoFrame').src = link;

    modal.style.display = "block";

    span.onclick = function() {
        modal.style.display = "none";
        document.getElementById('videoFrame').src = "";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
        modal.style.display = "none";
        document.getElementById('videoFrame').src = "";
        }
    }

}


function changeType(){
    document.getElementById('togglePicInput').addEventListener('change', function() {
        const playlistPicInput = document.getElementById('input-playlistPic');
        if (this.checked) {
            playlistPicInput.setAttribute('type', 'text');
        } else {
            playlistPicInput.setAttribute('type', 'file');
        }
    });
}

function changeType2(){
    document.getElementById('togglePicInput2').addEventListener('change', function() {
        const playlistPicInput = document.getElementById('edit-playlistPic');
        if (this.checked) {
            playlistPicInput.setAttribute('type', 'text');
        } else {
            playlistPicInput.setAttribute('type', 'file');
        }
    });
}

function changeType3(){
    document.getElementById('togglePicInput3').addEventListener('change', function() {
        const playlistPicInput = document.getElementById('edit-playlistPic2');
        if (this.checked) {
            playlistPicInput.setAttribute('type', 'text');
        } else {
            playlistPicInput.setAttribute('type', 'file');
        }
    });
}

function redirectTo(url) {
    window.location.href = url;
}

function disableLink(x){
    const tilesURL = document.querySelectorAll('.playlistTile');
    tilesURL[x].setAttribute('href', "#");
}

function enableLink(x, link){
    const tilesURL = document.querySelectorAll('.playlistTile');
    link = link
    tilesURL[x].setAttribute('href', link);
}

function displayElements(){
    document.getElementById('playlistsBar').style.visibility = "visible";
    document.getElementById('playlistSpace').style.visibility = "visible";
}

function displayElements2(){
    document.getElementById('playlistsBar').style.visibility = "visible";
    document.getElementById('videoSpace').style.visibility = "visible";
}

function displayElements3(){
    document.getElementById('playlistsBar').style.visibility = "visible";
    document.getElementById('videoLists').style.visibility = "visible";
}

function convertChars(string, charRemove, charAdd){
    const regex = new RegExp(charRemove, 'gu');
    return string.replace(regex, charAdd);
}