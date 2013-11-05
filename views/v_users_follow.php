<div class="wrapper cf">
   <!-- page-content -->
   <div class="page-content">
      <h2 class="heading">FOLLOW OTHERS (OR NOT)</h2>
         <?php if(isset($follow_users_message)): ?>
            <p class="infobox-info"><?=$follow_users_message?></p>
         <?php else:?>
      <div id="items-list" class="cf">
         <?php foreach($users as $user): ?>
         <article>
            <div class="feature-image">
               <img src="<?=$user['avatar']?>" alt="Avatar"/>
            </div>
            <div class="excerpt">
               <h3><?=$user['first_name']?> <?=$user['last_name']?></h3>
               <!-- If there exists a connection with this user, show a unfollow link -->
               <?php if(isset($connections[$user['user_id']])): ?>
               <a href='/users/unfollow/<?=$user['user_id']?>' class="link-button red">Unfollow</a>
               <!-- Otherwise, show the follow link -->
               <?php else: ?>
               <a href='/users/follow/<?=$user['user_id']?>' class="link-button green">Follow</a>
               <?php endif; ?>
            </div>
            <i class="tape"></i>
         </article>
         <br><br>
         <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <div class="c-1"></div>
      <div class="c-2"></div>
      <div class="c-3"></div>
      <div class="c-4"></div>
   </div>
</div>