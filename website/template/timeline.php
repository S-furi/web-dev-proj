          <section class="timeline">
            <?php if (isset($templateParams["posts"])) : ?>
              <?php foreach ($templateParams["posts"] as $post) : ?>
                <article class="post">
                  <div class="post-head">
                <a href="comment.php?usrId=<?php echo $post["usrId"] ?>&postId=<?php echo $post["postId"] ?>">
                        <h3><?php echo $post["title"]; ?></h3>
                    </a>
                    <div class="usr-info">
                      <img src="img/no-profile-pic.png" alt="post author profile picture" class="profile-picture">
                      <p class="usertag">@<?php echo $post["author"] ?></p>
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
                    <img src="<?php echo $post["image"] ?>" alt="" />
                    <p><?php echo $post["caption"] ?></p>
                    <p class="likes-n">Mi Piace: <?php echo $post["likes"] ?></p>
                    <div class="interaction-buttons">
                      <label for="like-btn"><input type="button" name="like button" id="like-btn" /><span class="material-symbols-outlined">favorite</span></label>
                      <label for="comment-btn"><input type="button" name="comment button" id="comment-btn" /><span class="material-symbols-outlined">comment</span></label>
                      <label for="join-btn"><input type="button" name="join event button" id="join-btn" class="btn btn-primary" value="Partecipa" /></label>
                    </div>
                  </div>
                </article>
              <?php endforeach; ?>
            <?php endif; ?>
          </section>
