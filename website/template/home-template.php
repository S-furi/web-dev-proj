    <div class="center-col">
        <?php $user = $templateParams["user"]; ?>
        <?php foreach($templateParams["posts"] as $post): ?>

        <div class="post">
            <div class="info">
                <div class="user">
                    <div class="profile-pic"><img src="img/cover 1.png" alt=""></div>
                    <p class="username"><?php echo $user[0]["username"]; ?></p>
                </div>
                <div>
                    <p class="postName"><?php echo $post["title"]; ?></p>
                </div>
                <img src="img/option.PNG" class="options" alt="">
            </div>
            <img src="<?php echo UPLOAD_DIR.$post["image"]; ?>" class="post-image" alt="">
            <div class="post-content">
                <div class="reaction-wrapper">
                    <img src="img/like.PNG" class="icon" alt="">
                    <a href="comment.php"><img src="img/comment.PNG" class="icon" alt=""></a>
                    <img src="img/send.PNG" class="icon" alt="">
                    <img src="img/save.PNG" class="save icon" alt="">
                </div>
                <p class="likes">1,012 likes</p>
                <p class="description"><span><?php echo $user[0]["username"]; ?> </span> <?php echo $post["caption"]; ?></p>
                <p class="post-time">2 minutes ago</p>
            </div>
        </div>

        <?php endforeach; ?>
    </div>
