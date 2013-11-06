<div class="wrapper cf">
   <!-- page-content -->
   <div class="page-content">
      <?php if($user):?>
      <h2 class="heading">Hello<?php if($user) echo ', '.$user->first_name; ?></h2>
       <p class="infobox-warning">Here you will see all your posts and the posts of people whom you are following.</p>
      <div class="toggle-trigger"><i></i>Click here to post something...</div>
      <div class="toggle-container">
         <form id="contactForm" action="/posts/p_add" method="POST">
            <fieldset>
               <p>
                  <textarea  name="content" required  id="content" rows="1" cols="25" class="form-poshytip" placeholder="Type in your post. User # to denote keywords, like #great." title="Type in your post. User # to denote keywords, like #great." maxlength="255"></textarea>
               </p>
               <p><input type="submit" value="Post" id="submit" /> </p>
            </fieldset>
         </form>
      </div>
      <br/>
      <br/>
      <div id="posts-list" class="cf">
         <?php foreach($posts as $post): ?>
         <!-- posts list -->
         <article>
            <div class="feature-image">
               <img src="<?=$post['avatar']?>" alt="Avatar" />
            </div>
            <div class="excerpt">
               <h3 ><?=$post['first_name']?> <?=$post['last_name']?></h3>
               <p>
                  <?=$post['content']?>	
               </p>
            </div>
            <div class="meta">
               <span class="tags">    
               <time datetime="<?=Time::display($post['modified'],'Y-m-d H:i')?>">
               <?=Time::display($post['modified'])?>
               </time></span>
               <div class="edit"><a href="/posts/edit/<?=$post['post_id']?>" title="Edit post"><img src="/img/edit.png" alt="Edit"/></a></div>
               <div class="del"><a class="confirm" href="/posts/delete/<?=$post['post_id']?>"  title="Delete post"><img src="/img/delete.png" alt="Delete"/></a></div>
            </div>
            <i class="tape"></i>
         </article>
         <?php endforeach; ?>
      </div>
      <?php else:?>
      <!-- entry-content -->	
      <div class="entry-content cf">
         <div class="headline">
            Welcome to Bloggette
         </div>
         <!-- 2 cols -->
         <div class="one-half">
            <h4 class="heading">Its short and sweet</h4>
            Features include
            <ul>
               <li>Add, edit and delete short blog entries (255 chars)</li>
               <li>Ability to modify your profile information and password(+1)</li>
               <li>Upload an avatar(+1)</li>
               <li>Use hashtags to denote keywrods(+1)</li>
               <li>Find and follow users based on keywords(+1)</li>
               <li>See trending keywords in a word cloud.</li>
            </ul>
         </div>
         <div class="one-half last">
            <h4 class="heading">Login here</h4>
            <?php if(isset($login_error_message)): ?>
            <p class="infobox-error"><?=$login_error_message?></p>
            <?php endif; ?>
            <form method='POST' action='/users/p_login' id="contactForm">
               Email<br/>
               <input type='text' name='email'><br/>
               Password<br/>
               <input type='password' name='password'><br/>
               <input type='submit' value='Log in'>
            </form>
         </div>
      </div>
      <?php endif; ?>
      <div class="c-1"></div>
      <div class="c-2"></div>
      <div class="c-3"></div>
      <div class="c-4"></div>
   </div>
</div>