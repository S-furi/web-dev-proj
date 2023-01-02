        <section class="timeline">
            <div class="profile-infobox">
              <div class="profile-infobox-head">
                <div class="profile-usr-info">
                  <img src="img/no-profile-pic.png" alt="post author profile picture" class="big-profile-picture">
                  <div class="nametags">
                    <h3><?php echo $user["firstName"]; ?> <?php echo $user["lastName"]; ?></h3>
                    <p class="usertag">@<?php echo $user["username"]; ?></p>
                  </div>
                </div>
                <label for="follow-btn"><button type="button" name="follow button" id="follow-btn"
                    class="btn btn-primary" onclick="followUser(<?php echo $_SESSION['user_id']; ?>, <?php echo $user['usrId'] ?>, this)">Segui</button></label>
                <span class="material-symbols-outlined" id="person_add">person_add</span>
              </div>
              <div class="profile-infobox-body">
                <p class="profile-descr">Descrizione</p>
                <div class="follow-info">
                  <p class="info-tag">Seguaci: </p>
                  <p class="followers-data"><?php echo $followers_n; ?></p>
                  <p class="info-tag">Seguiti: </p>
                  <p><?php echo $following_n; ?></p>
                </div>
              </div>
            </div>
            <?php foreach ($posts as $post): ?>
            <?php $eventDate = date("d-m-Y H:i", strtotime($post['eventDate'])); ?>
            <article class="post">
              <div class="post-head">
                <a href="post.php?usrId=<?php echo $post['usrId']; ?>&postId=<?php echo $post['postId']; ?>"><h3><?php echo $post['title']; ?></h3></a>
              </div>
              <div class="post-body">
                <div class="date-location">
                  <div class="date-location-item">
                    <p class="info-tag">Data:</p>
                    <p><?php echo $eventDate; ?></p>
                  </div>
                  <div class="date-location-item">
                    <p class="info-tag">Luogo:</p>
                    <p><?php echo $post['location']; ?></p>
                  </div>
                </div>
                <p><?php echo $post['caption']; ?></p>
                <p class="likes-n">Mi Piace: <?php echo $post['likes']; ?></p>
                <div class="profile-interaction-buttons">
                <label for="like-btn"><button type="button" name="like button" id="like-btn" onclick="likePost(<?php echo $post["postId"] ?>, <?php echo $_SESSION["user_id"] ?>, this)"><span
                        class="material-symbols-outlined like-btn">favorite</span></button></label>
                    <a href="post.php?usrId=<?php echo $post['usrId']; ?>&postId=<?php echo $post['postId']; ?>#comment-text-area" target="_self">
                      <input type="button" name="comment button" id="comment-btn" />
                      <span class="material-symbols-outlined">comment</span>
                    </a>
                </div>
              </div>
            </article>
            <?php endforeach; ?>
        </section>
