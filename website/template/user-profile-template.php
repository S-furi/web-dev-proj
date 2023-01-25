<?php $is_following = checkFollow($_SESSION['user_id'], $user['usrId'], $mysqli); ?>

        <div class="timeline">
            <div class="profile-infobox">
              <div class="profile-infobox-head">
                <div class="profile-usr-info">
                  <img src="<?php echo $userInfo[0]['profileImg']; ?>" alt="post author profile picture" class="big-profile-picture">
                  <div class="nametags">
                    <h3><?php echo $user["firstName"]; ?> <?php echo $user["lastName"]; ?></h3>
                    <p class="usertag">@<?php echo $user["username"]; ?></p>
                  </div>
                </div>
                <?php if (!$is_following): ?>
                  <label for="follow-btn"><button type="button" name="follow button" id="follow-btn"
                      class="btn btn-primary" onclick="followUser(<?php echo $_SESSION['user_id']; ?>, <?php echo $user['usrId'] ?>, this)">Segui</button></label>
                  <span class="material-symbols-outlined" id="person_add" onclick="followUser(<?php echo $_SESSION['user_id']; ?>, <?php echo $user['usrId'] ?>, this)">person_add</span>
                <?php else: ?>
                  <label for="unfollow-btn"><button style="background-color: var(--color-secondary);" type="button" name="unfollow button" id="unfollow-btn"
                      class="btn btn-primary" onclick="unfollowUser(<?php echo $_SESSION['user_id']; ?>, <?php echo $user['usrId'] ?>, this)">Unfollow</button></label>
                  <span class="material-symbols-outlined" style="background-color: var(--color-secondary);" id="person_off" onclick="unfollowUser(<?php echo $_SESSION['user_id']; ?>, <?php echo $user['usrId'] ?>, this)">person_off</span>
                <?php endif; ?>
              </div>
              <div class="profile-infobox-body">
                <p class="profile-descr"><?php echo $userInfo[0]['bio']; ?></p>
                <div class="follow-info">
                  <span data-usr-id="<?php echo $_GET['usrId']; ?>" data-session-id="<?php echo $_SESSION['user_id']; ?>" class="info-tag followers-link" >Seguaci: </span>
                  <p class="followers-data"><?php echo $followers_n; ?></p>
                  <span data-usr-id="<?php echo $_GET['usrId']; ?>" data-session-id="<?php echo $_SESSION['user_id']; ?>" class="info-tag following-link" >Seguiti: </span>
                  <p><?php echo $following_n; ?></p>
                </div>
              </div>
            </div>
            <?php if (!empty($posts)): ?>
              <?php foreach ($posts as $post): ?>
              <?php $eventDate = date("d-m-Y H:i", strtotime($post['eventDate'])); ?>
              <article class="post">
                <div class="post-head">
                  <a href="post.php?usrId=<?php echo $post['usrId']; ?>&postId=<?php echo $post['postId']; ?>"><h3><?php echo $post['title']; ?></h3></a>
                </div>
                <div class="post-body">
                  <div class="date-location">
                    <div class="date-location-item">
                      <p class="nc-info-tag">Data:</p>
                      <p><?php echo $eventDate; ?></p>
                    </div>
                    <div class="date-location-item">
                      <p class="nc-info-tag">Luogo:</p>
                      <p><?php echo $post['location']; ?></p>
                    </div>
                  </div>
                  <p class="caption"><?php echo $post['caption']; ?></p>
                  <div class="post-stats">
                    <span data-post-id="<?php echo $post['postId']; ?>" data-session-id="<?php echo $_SESSION['user_id']; ?>" class="likesLink" >Mi Piace: <?php echo $post['likes']; ?></span>
                    <span data-post-id="<?php echo $post['postId']; ?>" data-session-id="<?php echo $_SESSION['user_id']; ?>" class="participantsLink" >Partecipanti: <?php echo $post['participants']; ?></span>
                  </div>
                  <div class="profile-interaction-buttons">
                      <a href="post.php?usrId=<?php echo $post['usrId']; ?>&postId=<?php echo $post['postId']; ?>#comment-text-area" target="_self">
                        <span class="material-symbols-outlined">comment</span>
                      </a>
                  </div>
                </div>
              </article>
              <?php endforeach; ?>
            <?php else: ?>
              <article class="post">
                <div class="post-not-present">
                  <p>Nessun post caricato!</p>
                </div>
              </article>
            <?php endif; ?>
            
            <div id="modal" class="modal">
              <div class="modal-content">
              </div>
            </div>

</div>
