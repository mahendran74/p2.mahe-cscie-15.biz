<div class="wrapper">
   <!-- page-content -->
   <div class="page-content">
      <h2 class="heading">SIGN UP</h2>
      <!-- entry-content -->	
      <div class="entry-content cf">
<form method='POST' action='/users/p_signup'  id="contactForm">

    First Name<br/>
    <input type='text' name='first_name' required placeholder="Enter your first name" pattern='[a-zA-Z0-9]+'>
    <br/>
    Last Name<br/>
    <input type='text' name='last_name' required placeholder="Enter your last name" pattern='[a-zA-Z0-9]+'>
    <br/>
    Email<br/>
    <input type="email" name="email" required placeholder="Enter a valid email address">
    <br/>

    Password<br/>
    <input type='password' name='password' required placeholder="Enter your password">
    <br/>
    
    Confirm Password<br>
    <input type='password' name='confirm_password' required placeholder="Confirm the password" onchange="this.pattern = form.password.value;">
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