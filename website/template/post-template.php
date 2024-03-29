          <section class="timeline">
            <article class="post">
              <div class="post-head">
                <a href="#"><h3><?php echo $post['title'] ?></h3></a>
                <div class="usr-info">
                  <?php if ($post['usrId'] == $_SESSION['user_id']): ?>
                    <a href="personal-profile.php"><img src="<?php echo $userInfo[0]['profileImg']; ?>" alt="post author profile picture" class="profile-picture"></a>
                    <a href="personal-profile.php"><p class="usertag">@<?php echo $user['username']; ?></p></a>
                  <?php else: ?>
                    <a href="user-profile.php?usrId=<?php echo $post['usrId']; ?>"><img src="<?php echo $userInfo[0]['profileImg']; ?>" alt="post author profile picture" class="profile-picture"></a>
                    <a href="user-profile.php?usrId=<?php echo $post['usrId']; ?>"><p class="usertag">@<?php echo $user['username']; ?></p></a>
                  <?php endif; ?>
                </div>
              </div>
              <div class="post-body">
                <div class="date-location">
                  <div class="date-location-item">
                    <p class="nc-info-tag">Data:</p>
                    <p><?php echo $date; ?></p>
                  </div>
                  <div class="date-location-item">
                    <p class="nc-info-tag">Luogo: </p>
                    <p><?php echo $post['location']; ?></p>
                  </div>
                </div>   
                <img src="img/<?php echo $user['username'] ?>/posts/<?php echo $post['image']; ?>" alt="" />
                <p class="caption"><?php echo $post['caption']; ?></p>
                <div class="post-stats">
                  <span data-post-id="<?php echo $postId; ?>" data-session-id="<?php echo $_SESSION['user_id']; ?>" class="likesLink" >Mi Piace: <?php echo $post['likes']; ?></span>
                  <span data-post-id="<?php echo $postId; ?>" data-session-id="<?php echo $_SESSION['user_id']; ?>" class="participantsLink" >Partecipanti: <?php echo $post['participants']; ?></span>
                </div>
                <div class="interaction-buttons">
                <label for="post-<?php echo $postId ?>-like-btn"><button type="button" name="like button" id="post-<?php echo $postId ?>-like-btn" onclick="likePost(<?php echo $postId ?>, <?php echo $_SESSION['user_id']; ?>, this)">
                        <span class="material-symbols-outlined like-btn">favorite</span></button></label>
                  <label for="join-btn"><input type="button" name="join event button" id="join-btn"
                    class="btn btn-primary" value="Partecipa" onclick="participateToEvent(this, <?php echo $postId; ?>)" /></label>
                </div>
              </div>
            </article>

            <?php foreach ($comments as $comment): ?>
            <?php 
            if (checkUserInfoExists($comment['usrId'], $mysqli)) {
              $profileImg = getUserInfo($comment['usrId'], $mysqli)[0]['profileImg'];
              $profileImg = IMG_DIR . $comment['username'] . "/propic/" . $profileImg;
            } else {
              $profileImg = 'img/no-profile-pic.png';
            }
            ?>
            <article class="comment">
                <div class="comment-head">
                    <img src="<?php echo $profileImg; ?>" alt="profile pic" />
                    <div class="comment-head-info">
                        <?php if ($comment['usrId'] == $_SESSION['user_id']): ?>
                          <a href="personal-profile.php"><h3 class="comment-author"><?php echo $comment['firstName']; ?> <?php echo $comment['lastName']; ?></h3></a>
                        <?php else: ?>
                          <a href="user-profile.php?usrId=<?php echo $comment['usrId']; ?>"><h3 class="comment-author"><?php echo $comment['firstName']; ?> <?php echo $comment['lastName']; ?></h3></a>
                        <?php endif; ?>
                        <p>@<?php echo $comment['username']; ?></p>
                    </div>
                </div>
                <p class="comment-body"><?php echo $comment['content']; ?></p>
            </article>
            <?php endforeach ?>
            
            <div id="modal" class="modal">
              <div class="modal-content">
                <p class="comment-error">Il campo dei commenti è vuoto o è più lungo di 255 caratteri. Inserisci un commento adeguato prima di inviarlo.</p>
              </div>
            </div>
            
            <form class="comment-form">
                    <textarea name="comment-text-area" id="comment-text-area" placeholder="Scrivi un commento" title="Scrivi un commento"></textarea>
                    <button type="button" onclick="postComment(<?php echo $_SESSION['user_id']; ?>, <?php echo $postId; ?>)" class="btn btn-primary">Commenta</button>
            </form>

          </section>
