          <section class="timeline">
              <?php if (isset($templateParams["posts"])) : ?>
                  <?php foreach ($templateParams["posts"] as $post) : ?>
                  <?php
                  $user = getUser($post['usrId'], $mysqli);
                  if (checkUserInfoExists($post['usrId'], $mysqli)) {
                    $userInfo = getUserInfo($post['usrId'], $mysqli);
                    $userInfo[0]['profileImg'] = IMG_DIR . $user['username'] . "/propic/" . $userInfo[0]['profileImg'];
                  } else {
                    $userInfo[0] = array('bio' => '', 'profileImg' => 'img/no-profile-pic.png');
                  }                  
                  ?>
                      <article class="post">
                          <div class="post-head">
                              <a href="post.php?usrId=<?php echo $post["usrId"] ?>&postId=<?php echo $post["postId"] ?>">
                                  <h3><?php echo $post["title"]; ?></h3>
                              </a>
                              <div class="usr-info">
                                  <a href="user-profile.php?usrId=<?php echo $post['usrId']; ?>">
                                      <img src="<?php echo $userInfo[0]['profileImg']; ?>" alt="post author profile picture" class="profile-picture">
                                  </a>
                                  <a href="user-profile.php?usrId=<?php echo $post['usrId']; ?>">
                                      <p class="usertag">@<?php echo $post["author"] ?></p>
                                  </a>
                              </div>
                          </div>
                          <div class="post-body">
                              <div class="date-location">
                                  <div class="date-location-item">
                                      <p class="info-tag">Data:</p>
                                      <p><?php echo $post["eventDate"] ?></p>
                                  </div>
                                  <div class="date-location-item">
                                      <p class="info-tag">Luogo:</p>
                                      <p><?php echo $post["location"] ?></p>
                                  </div>
                              </div>
                              <img src="<?php echo str_replace(' ', '%20', $post["image"]) ?>" alt="" />
                              <p><?php echo $post["caption"] ?></p>
                              <div class="post-stats">
                                <a href="#"><p class="likes-n">Mi Piace: <?php echo $post['likes']; ?></p></a>
                                <a href="#" onclick="showLikeUsers(<?php echo $post['postId']; ?>, <?php echo $_SESSION['user_id']; ?>)"><p class="likes-n">Partecipanti: <?php echo $post['participants']; ?></p></a>
                              </div>
                              <div class="interaction-buttons">
                                  <div class="like-comment-div">
                                      <label for="post-<?php echo $post["postId"] ?>-like-btn"><button type="button" value="like" name="like button" id="post-<?php echo $post["postId"] ?>-like-btn" onclick="likePost(<?php echo $post["postId"] ?>, <?php echo $_SESSION["user_id"] ?>, this)"><span class="material-symbols-outlined like-btn">favorite</span></button></label>
                                      <a href="post.php?usrId=<?php echo $post['usrId']; ?>&postId=<?php echo $post['postId']; ?>#comment-text-area" target="_self">
                                          <input type="button" name="comment button" id="comment-btn" />
                                          <span class="material-symbols-outlined">comment</span>
                                      </a>
                                  </div>
                                  <label for="post-<?php echo $post["postId"] ?>-join-btn"><button type="button" name="join event button" id="post-<?php echo $post["postId"] ?>-join-btn" class="btn btn-primary" onclick="participateToEvent(this)">Partecipa</button></label>
                              </div>
                          </div>
                      </article>
                  <?php endforeach; ?>
              <?php endif; ?>
            
            <div id="modal" class="modal">
              <div class="modal-content">
              </div>
            </div>

          </section>
