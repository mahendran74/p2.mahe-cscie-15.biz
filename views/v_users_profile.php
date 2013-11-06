<div class="wrapper">
   <!-- page-content -->
   <div class="page-content">
      <h2 class="heading">YOUR PROFILE</h2>
      <!-- entry-content -->	
      <div class="entry-content cf">
         <div class="accordion-trigger">Modify profile</div>
         <div class="accordion-container">
            <?php if(isset($profile_error_string)): ?>
            <p class="infobox-error"><?=$profile_error_string?></p>
            <?php endif; ?>
            <?php if(isset($profile_message)): ?>
            <p class="infobox-success"><?=$profile_message?></p>
            <?php endif; ?>
            <form method='POST' action='/users/p_update' id="profileform">
               <fieldset>
                  <p>
                     <label for="first_name" >First Name</label>
                     <input type='text' name='first_name' id='first_name' required value="<?=$profile['first_name']?>" class="form-poshytip" title="Enter your first name">
                  </p>
                  <p>
                     <label for="last_name" >Last Name</label>
                     <input type='text' name='last_name' id='last_name' required value="<?=$profile['last_name']?>" class="form-poshytip" title="Enter your last name">
                  </p>
                  <p>
                     <label for="email" >Email</label>
                     <input name="email"  type="text" id='email' class="form-poshytip" value="<?=$profile['email']?>" title="Enter your email address" required/>
                  </p>
                  <input type='submit' value='Update'>
               </fieldset>
            </form>
         </div>
         <div class="accordion-trigger">Change avatar</div>
         <div class="accordion-container">
            <?php if(isset($avatar_error_message)): ?>
            <p class="infobox-error"><?=$avatar_error_message?></p>
            <?php endif; ?>
            <?php if(isset($avatar_message)): ?>
            <p class="infobox-success"><?=$avatar_message?></p>
            <?php endif; ?>
            <br/>
            <div class="avatar-image">
               <img src="<?=$profile['avatar']?>" alt="Avatar" />
            </div>
            <br/>
            <form method='POST' enctype="multipart/form-data" action='/users/p_upload' id="avatarform">
               <fieldset>
                  <input type='file' name='avatar'><br/>
                  <input type='submit'>
               </fieldset>
            </form>
         </div>
         <div class="accordion-trigger">Change password</div>
         <div class="accordion-container">
            <?php if(isset($password_error_message)): ?>
            <p class="infobox-error"><?=$password_error_message?></p>
            <?php endif; ?>
            <?php if(isset($password_message)): ?>
            <p class="infobox-success"><?=$password_message?></p>
            <?php endif; ?>
            <form method='POST' action='/users/p_updatepassword' id="passwordform">
               <fieldset>
                  <p>
                     <label for="old_password" >Old password</label>
                     <input type='password' name='old_password' id='old_password'  placeholder="Enter your old password" title="Enter your old password" class="form-poshytip">
                  </p>
                  <p>
                     <label for="password" >New password</label>
                     <input type='password' name='password' id='password'  placeholder="Enter your new password"  title="Enter your new password" class="form-poshytip">
                  </p>
                  <p>
                     <label for="new_password" >Confirm password</label>
                     <input type='password' name='new_password' id='new_password'  placeholder="Enter your new password again" title="Enter your new password again" class="form-poshytip">
                  </p>
                  <input type='submit' value='Update'>
               </fieldset>
            </form>
         </div>
      </div>
      <div class="c-1"></div>
      <div class="c-2"></div>
      <div class="c-3"></div>
      <div class="c-4"></div>
   </div>
</div>