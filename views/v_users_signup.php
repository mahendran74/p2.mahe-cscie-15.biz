<div class="wrapper">
   <!-- page-content -->
   <div class="page-content">
      <h2 class="heading">SIGN UP</h2>
      <?php if(isset($profile_error_message)): ?>
      <p class="infobox-error"><?=$profile_error_message?></p>
      <?php endif; ?>
      <!-- entry-content -->	
      <div class="entry-content cf">
         <form method='POST' action='/users/p_signup'  id="contactForm">
            First Name<br/>
            <?php if(isset($user['first_name'])): ?>
            <input type='text' name='first_name' required placeholder="Enter your first name" pattern='[a-zA-Z0-9]+' value="<?=$user['first_name'] ?>">
            <?php else: ?>
            <input type='text' name='first_name' required placeholder="Enter your first name" pattern='[a-zA-Z0-9]+'>
            <?php endif;?>
            <br/>
            Last Name<br/>
            <?php if(isset($user['first_name'])): ?>
            <input type='text' name='last_name' required placeholder="Enter your last name" pattern='[a-zA-Z0-9]+' value="<?=$user['last_name'] ?>">
            <?php else: ?>
            <input type='text' name='last_name' required placeholder="Enter your last name" pattern='[a-zA-Z0-9]+'>
            <?php endif;?>
            <br/>
            Email<br/>
            <?php if(isset($user['email'])): ?>
            <input type='text' name='email' required placeholder="Enter a valid email addresse" value="<?=$user['email'] ?>">
            <?php else: ?>
            <input type='text' name='email' required placeholder="Enter a valid email address">
            <?php endif;?>
            <br/>
            Password<br/>
            <input type='password' name='password' required placeholder="Enter your password">
            <br/>
            Confirm Password<br>
            <input type='password' name='confirm_password' required placeholder="Confirm the password">
            <br><br>
            <input type='submit' value='Sign up'>
         </form>
      </div>
      <div class="c-1"></div>
      <div class="c-2"></div>
      <div class="c-3"></div>
      <div class="c-4"></div>
   </div>
</div>