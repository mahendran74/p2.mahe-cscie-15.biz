<div class="wrapper cf">
   <!-- page-content -->
   <div class="page-content">
      <h2 class="heading">POSTS WITH '<?=$keyword?>'</h2>
      <br/>
      <br/>
      <div id="posts-list" class="cf">
         <?php foreach($posts as $post): ?>
         <!-- posts list -->
         <article>
            <div class="feature-image">
               <img src="<?=$post['avatar']?>" alt="Avatar" /><br/>
               <?php if(isset($connections[$post['post_user_id']])): ?>
               <a href='/users/unfollow/<?=$post['post_user_id']?>' class="link-button red" style="
                  margin-top: 3px;width: 80px;">Unfollow</a>
               <!-- Otherwise, show the follow link -->
               <?php elseif($post['post_user_id'] != $user->user_id): ?>
               <a href='/users/follow/<?=$post['post_user_id']?>' class="link-button green" style="
                  margin-top: 3px;width: 80px;">Follow</a>
               <?php endif; ?>
            </div>
            <div class="excerpt">
               <h3><?=$post['first_name']?> <?=$post['last_name']?></h3>
               <p>
                  <?=$post['content']?>	
               </p>
            </div>
            <div class="meta">
               <span class="tags">    <time datetime="<?=Time::display($post['created'],'Y-m-d G:i')?>">
               <?=Time::display($post['created'])?>
               </time></span>
            </div>
            <i class="tape"></i>
         </article>
         <?php endforeach; ?>
      </div>
      <div class="c-1"></div>
      <div class="c-2"></div>
      <div class="c-3"></div>
      <div class="c-4"></div>
   </div>
</div>