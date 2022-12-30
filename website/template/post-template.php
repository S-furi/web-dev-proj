          <section class="timeline">
            <article class="post">
              <div class="post-head">
                <h3><?php echo $post['title'] ?></h3>
                <div class="usr-info">
                  <a href="user-profile.php?usrId=<?php echo $usrId; ?>">
                  <img src="img/no-profile-pic.png" alt="post author profile picture" class="profile-picture">
                  </a>
                  <a href="user-profile.php?usrId=<?php echo $usrId; ?>">
                  <p class="usertag">@<?php echo $user['username']; ?></p>
                  </a>
                </div>
              </div>
              <div class="post-body">
                <div class="date-location">
                  <div class="date-location-item">
                    <p class="info-tag">Data:</p>
                    <p><?php echo $date; ?></p>
                  </div>
                  <div class="date-location-item">
                    <p class="info-tag">Luogo: </p>
                    <p><?php echo $post['location']; ?></p>
                  </div>
                </div>   
                <img src="img/posts/<?php echo $post['image']; ?>" alt="" />
                <p><?php echo $post['caption']; ?></p>
                <p class="likes-n">Mi Piace: <?php echo $post['likes'] ?></p>
                <div class="interaction-buttons">
                  <label for="like-btn"><input type="button" name="like button" id="like-btn" /><span
                      class="material-symbols-outlined">favorite</span></label>
                  <label for="join-btn"><input type="button" name="join event button" id="join-btn"
                    class="btn btn-primary" value="Partecipa" /></label>
                </div>
              </div>
            </article>

            <?php foreach ($comments as $comment): ?>
            <article class="comment">
                <div class="comment-head">
                    <img src="img/no-profile-pic.png" alt="to do dynamic in php" />
                    <div class="comment-head-info">
                        <h3 class="comment-author"><?php echo $comment['firstName']; ?> <?php echo $comment['lastName']; ?></h3>
                        <p>@<?php echo $comment['username']; ?></p>
                    </div>
                </div>
                <p class="comment-body"><?php echo $comment['content']; ?></p>
            </article>
            <?php endforeach ?>
            
            <form class="comment-form">
                    <textarea name="comment-text-area" id="comment-text-area" placeholder="Scrivi un commento"></textarea>
                    <button type="submit" class="btn btn-primary">Commenta</button>
            </form>

          </section>
