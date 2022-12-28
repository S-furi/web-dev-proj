          <section class="timeline">
            <article class="post">
              <div class="post-head">
                <h3><?php echo $post['title'] ?></h3>
                <div class="usr-info">
                  <img src="img/no-profile-pic.png" alt="post author profile picture" class="profile-picture">
                  <p class="usertag">@<?php echo $user['username']; ?></p>
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
                <img src="img/posts/<?php echo $post['image']; ?>" alt="to do dynamic in php" />
                <p><?php echo $post['caption']; ?></p>
                <p class="likes-n">Mi Piace: 69</p>
                <div class="interaction-buttons">
                  <label for="like-btn"><input type="button" name="like button" id="like-btn" /><span
                      class="material-symbols-outlined">favorite</span></label>
                  <label for="comment-btn"><input type="button" name="comment button" id="comment-btn" /><span
                      class="material-symbols-outlined">comment</span></label>
                  <label for="join-btn"><input type="button" name="join event button" id="join-btn"
                    class="btn btn-primary" value="Partecipa" /></label>
                </div>
              </div>
            </article>

            <!-- <?php foreach ($comments as $comment): ?> -->
            <article class="comment">
                <div class="comment-head">
                    <img src="../img/no-profile-pic.png" alt="to do dynamic in php" />
                    <div class="comment-head-info">
                        <h3 class="comment-author">Nome Autore</h3>
                        <p>@username</p>
                    </div>
                </div>
                <p class="comment-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer molestie congue justo nec ultricies. Maecenas ligula tellus, gravida mollis venenatis ut, porta vitae justo. Suspendisse sodales efficitur quam, ac elementum mauris commodo id. Fusce ultricies libero ante, at convallis leo laoreet a. Etiam dignissim scelerisque bibendum. Aenean nec ornare nisl. Ut commodo risus velit, eu sollicitudin nibh facilisis ut. Maecenas ac ultricies felis. </p>
            </article>
            <!-- <?php endforeach ?> -->
            
            <form class="comment-form">
                    <textarea name="comment-text-area" id="comment-text-area" placeholder="Scrivi un commento"></textarea>
                    <button type="submit" class="btn btn-primary">Commenta</button>
            </form>

          </section>