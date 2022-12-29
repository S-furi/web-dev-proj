          <section class="timeline">
            <?php if (isset($templateParams["posts"])) : ?>
              <?php foreach ($templateParams["posts"] as $post) : ?>
                <article class="post">
                  <div class="post-head">
                <a href="comment.php?usrId=<?php echo $post["usrId"] ?>&postId=<?php echo $post["postId"] ?>">
                        <h3><?php echo $post["title"]; ?></h3>
                    </a>
                    <div class="usr-info">
                      <a href="user-profile.php?usrId=<?php echo $post['usrId']; ?>">
                      <img src="img/no-profile-pic.png" alt="post author profile picture" class="profile-picture">
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
                    <p class="likes-n">Mi Piace: <?php echo $post["likes"] ?></p>
                    <div class="interaction-buttons">
                      <label for="post-<?php echo $post["postId"]?>-like-btn"><button type="button" value="piscio" name="like button" id="post-<?php echo $post["postId"]?>-like-btn"></button><span class="material-symbols-outlined">favorite</span></label>
                      <a href="comment.php?usrId=<?php echo $post['usrId']; ?>&postId=<?php echo $post['postId']; ?>#comment-text-area" target="_self">
                        <input type="button" name="comment button" id="comment-btn" />
                        <span class="material-symbols-outlined">comment</span>
                      </a>
                      <label for="post-<?php echo $post["postId"]?>-join-btn"><button type="button" name="join event button" id="post-<?php echo $post["postId"]?>-join-btn" class="btn btn-primary">Partecipa</button></label>
                    </div>
                  </div>
                </article>
              <?php endforeach; ?>
            <?php endif; ?>
          </section>
