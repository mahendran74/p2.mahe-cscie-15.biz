<div class="wrapper">
   <!-- page-content -->
   <div class="page-content">
     <h2 class="heading">EDIT THIS POST</h2>
      <!-- entry-content 	-->
      <div class="entry-content cf">

            <form method='POST' action='/posts/p_update' id="contactForm">

    <label for='content'>New Post:</label><br>
    <textarea name='content' id='content'><?=$post['content']?></textarea>
<input type="hidden" name="post_id" value="<?=$post['post_id']?>"/> 
    <br><br>
    <input type='submit' value='Update'>
</form> 

      </div>
      <div class="c-1"></div>
      <div class="c-2"></div>
      <div class="c-3"></div>
      <div class="c-4"></div>
   </div>
</div>