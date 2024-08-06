// update likes function (when like icon pressed)
function updateLike(postId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php_processes/update_likes.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // if it's updated successfully in database, update likes in the post
            document.getElementById('like_count_' + postId).innerHTML = xhr.responseText;
            if (xhr.responseText == '1'){
                // if user liked post, delete dislike
                document.getElementById('dislike_count_' + postId).innerHTML = '0';
            }
        }
    };
    xhr.send("id=" + postId + "&action=like");
}

// update dislikes function (when dislike icon pressed)
function updateDislike(postId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php_processes/update_likes.php", true); // Adjust the path if necessary
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // if it's updated successfully in database, update dislikes in the post
            document.getElementById('dislike_count_' + postId).innerHTML = xhr.responseText;
            if (xhr.responseText == '1'){
                // if user disliked post, delete like
                document.getElementById('like_count_' + postId).innerHTML = '0';
            }
        }
    };
    xhr.send("id=" + postId + "&action=dislike");
}
