<?php
require_once('api-bootstrap.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Super simple function that determine if the 
// current user can delete the selected post
function userCanDelete($postId, $usrId, mysqli $mysqli) {
  $author = getPostFromPostId($postId, $mysqli)['usrId'];
  return $author == $usrId;
}

if (!isset($_GET["action"])) {
  header("HTTP/1.1 204 No Content");
}

if ($_GET["action"] == "fetch") {
  if (isset($_POST['postId'])) {
    $result = getPostFromPostId($_POST['postId'], $mysqli);

    if (count($result) == 0) {
      header("HTTP/1.1 204 No Content");
      return;
    }

    header("Content-Type: application/json");
    echo json_encode($result);
  }
// action = 0 means that a new post has to be inserted
} elseif ($_GET["action"] == 0){
    if(isset($_POST["title"], $_POST["description"], $_POST['location-id'], $_POST['event-datetime']) && $_FILES["photo"]["error"] == 0) {
        $user_id = $_SESSION["user_id"];
        $username = getUser($user_id, $mysqli)['username'];
        $title = $_POST["title"];
        $photo = $_FILES["photo"];
        
        $upload_post_dir = '../img/' . $username . "/posts/";
        if (!is_dir($upload_post_dir)) {
          mkdir($upload_post_dir, 0777, true);
        } 

        list($err, $imgPath) = uploadImage($upload_post_dir, $photo);

        if ($err != 0) {
            $caption= $_POST["description"];
            $location = $_POST["location-id"];
            $event_datetime = $_POST["event-datetime"];

            list($resut, $message) = createPost($user_id, $title, $caption, $imgPath, $location, $event_datetime, $mysqli);
            $msg = $message;
        } else {
            $msg = $imgPath;
        }
    } else {
        $msg = "Riempi tutti i campi";
    }

    $msg = htmlentities($msg);

    header("Location: ../post-creation.php?err=".$msg);


// action = 1 means that clients need all followed users events information
} else if ($_GET["action"] == 1) {
    $usrId = $_SESSION["user_id"];
    $events = getAllEventsDetails($usrId, $mysqli);

    header("Content-Type: application/json");
    echo json_encode($events);
    return;

// action = 2: like has been toggled, checks whether to delete the like or add
} else if ($_GET["action"] == 2) {
    if (isset($_POST["userId"], $_POST["postId"])){
        $usrId = $_POST["userId"];
        $postId = $_POST["postId"];

        $response["ok"] = false;

        if (registerLikeAction($usrId, $postId, $mysqli)) {
            $response["ok"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($response);
        return;

    } else {
        header("HTTP/1.1 204 No Content");
    }

// action = 3: return true if user has liked the provided post
} else if ($_GET["action"] == 3) {
    if (isset($_POST["postId"])){
        $usrId = $_SESSION["user_id"];
        $postId = $_POST["postId"];

        $response["hasAlreadyLikedPost"] = false;

        if (hasAlreadyLikedPost($usrId, $postId, $mysqli)) {
            $response["hasAlreadyLikedPost"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($response);
        return;
    } else {
        header("HTTP/1.1 204 No Content");
    }

// action = 4: delete the post with the provided postId
} else if ($_GET["action"] == 4) {
  if (isset($_POST["postId"])) {
    $response["postDeleted"] = false;
    $postId = $_POST["postId"];

    if (userCanDelete($postId, $_SESSION['user_id'], $mysqli)) {
      $post = getPostFromPostId($postId, $mysqli);
      $username = getUser($_SESSION["user_id"], $mysqli)['username'];

      list($res, $msg) = deleteImg(POST_IMG_DIR. $username . "/posts/". $post["image"]);

      $response["msg"] = $msg;
      
      if ($res && deletePost($postId, $mysqli)) {
        $response["postDeleted"] = true;
      }
    }

    header("Content-Type: application/json");
    echo json_encode($response);
    return;
  } else {
    header("HTTP/1.1 204 No Content");
  }
}
