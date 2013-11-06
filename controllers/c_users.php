<?php
include ($_SERVER ['DOCUMENT_ROOT'] . '/utils/util.php'); // Include utility class
/**
 *
 * @author Mahendran Sreedevi
 *         This controller does the following functions
 *         - Login in a user
 *         - Log out the user
 *         - Update the user's profile
 *         - Change the user's password
 *         - Upload user's avatar
 *         - Sign up the user
 *         - Follow another user
 *         - Unfollow another user
 */
class users_controller extends base_controller {
	
	/**
	 * Constuctor 
	 */
	public function __construct() {
		parent::__construct ();
	}
	/**
	 * Show the sign up page
	 */
	public function signup() {
		$this->template->content = View::instance ( 'v_users_signup' ); // Set view
		$this->template->title = "Sign Up"; // Set title
		echo $this->template; // Render view
	}
	/**
	 * Process the sign up request
	 */
	public function p_signup() {
		// Sanitize data for SQL injection attacks
		$_POST = DB::instance ( DB_NAME )->sanitize ( $_POST );
		$this->template->content = View::instance ( 'v_users_signup' ); // Set view
		$this->template->title = "Sign Up"; // Set title
		$this->template->content->user = $_POST; // Set data
		// Check for blank first name
		if (empty ( $_POST ['first_name'] )) {
			// Show error message
			$this->template->content->profile_error_message = "The first name is a required field.";
			echo $this->template; // Render view
			return;
		}
		// Check for blank last name
		if (empty ( $_POST ['last_name'] )) {
			// Show error message
			$this->template->content->profile_error_message = "The last name is a required field.";
			echo $this->template; // Render template
			return;
		}
		// Check for blank email
		if (empty ( $_POST ['email'] )) {
			// Show error message
			$this->template->content->profile_error_message = "The email address is a required field.";
			echo $this->template; // Render template
			return;
		}
		// Check if the email is already registered by someone else. The email should be unique
		if (! email_not_used ( $_POST ['email'] )) {
			// Show error message
			$this->template->content->profile_error_message = "This email address (" . $_POST ['email'] . ") is already registered. Please try another one.";
			;
			echo $this->template; // Render template
			return;
		}
		// Check for blank password
		if (empty ( $_POST ['password'] )) {
			// Show error message
			$this->template->content->profile_error_message = "Please enter the password.";
			echo $this->template; // Render template
			return;
		}
		// Check for blank password confirmation
		if (empty ( $_POST ['confirm_password'] )) {
			// Show error message
			$this->template->content->profile_error_message = "Please confirm the password by entering it again.";
			echo $this->template; // Render template
			return;
		}
		// Check if password and it's confirmation matches
		if ($_POST ['password'] != $_POST ['confirm_password']) {
			// Show error message
			$this->template->content->profile_error_message = "The new password and it's confirmation does not match. Please make sure enter the same password while confirming it.";
			echo $this->template; // Render template
			return;
		}
		// All validations passed
		$_POST ['created'] = Time::now (); // Set created datetime
		$_POST ['modified'] = Time::now (); // Set modified datetime
		
		// Encrypt the password
		$_POST ['password'] = sha1 ( PASSWORD_SALT . $_POST ['password'] );
		
		// Create an encrypted token via their email address and a random string
		$_POST ['token'] = sha1 ( TOKEN_SALT . $_POST ['email'] . Utils::generate_random_string () );
		// Set the default avatar. The user can change it later.
		$_POST ['avatar'] = "\uploads\avatars\default.gif";
		// Remove the password confirmation from the array
		unset ( $_POST ['confirm_password'] );
		// Insert this user into the database
		$user_id = DB::instance ( DB_NAME )->insert ( "users", $_POST );
		// Set the cookie
		setcookie ( "token", $_POST ['token'], strtotime ( '+1 year' ), '/' );
		// Redirect to home page
		Router::redirect ( "/" );
	}
	/**
	 * Process the update profile request
	 */
	public function p_update() {
		// Check to see if the user id logged in.
		if (! $this->user) {
			// If not redirect it back to the home page for login.
			Router::redirect ( "/" );
		}
		// Sanitize the data for SQL injection attacks
		$_POST = DB::instance ( DB_NAME )->sanitize ( $_POST );
		$messages = array ();
		// Check for blank first name
		if (empty ( $_POST ['first_name'] )) {
			$messages ['profile_error_string'] = "The first name is a required field.";
			$this->profile ( $messages );
			return;
		}
		// Check for blank last name
		if (empty ( $_POST ['last_name'] )) {
			$messages ['profile_error_string'] = "The last name is a required field.";
			$this->profile ( $messages );
			return;
		}
		// Check for blank email
		if (empty ( $_POST ['email'] )) {
			$messages ['profile_error_string'] = "The email address is a required field.";
			$this->profile ( $messages );
			return;
		}
		// Check if the email is already registered by someone else. The email should be unique
		if (email_not_used ( $_POST ['email'], $this->user->user_id )) {
			// All validations passed
			$_POST ['modified'] = Time::now (); // Set modified date time
			// Insert this user into the database
			DB::instance ( DB_NAME )->update ( "users", $_POST, "WHERE user_id = " . $this->user->user_id );
			$messages ['profile_message'] = "Your profile was updated successfully"; // Set success message
			$this->profile ( $messages ); // Call the profile function with the message
			return;
		} else {
			// Set the error message
			$messages ['profile_error_string'] = "This email address (" . $_POST ['email'] . ") is already registered. Please try another one.";
			$this->profile ( $messages ); // Call the profile function with the message
			return;
		}
	}
	/**
	 * Logs out the user
	 */
	public function logout() {
		// Generate new token
		$new_token = sha1 ( TOKEN_SALT . $this->user->email . Utils::generate_random_string () );
		// Add it to an array
		$data = Array (
				"token" => $new_token 
		);
		// Update the user info with the array
		DB::instance ( DB_NAME )->update ( "users", $data, "WHERE token = '" . $this->user->token . "'" );
		
		// Delete their token cookie by setting it to a date in the past - effectively logging them out
		setcookie ( "token", "", strtotime ( '-1 year' ), '/' );
		
		// Send them back to the home page
		Router::redirect ( "/" );
	}
	/**
	 * Shows the login view
	 * @param string $message Error message to be displayed
	 */
	public function login($message = "") {
		$this->template->content = View::instance ( 'v_index_index' ); // Set view
		$this->template->content->login_error_message = $message; // Set login error message
		echo $this->template; // Render view
	}
	/**
	 * Processes the login request
	 */
	public function p_login() {
		// Sanitize the data for SQL injection attacks
		$_POST = DB::instance ( DB_NAME )->sanitize ( $_POST );
		
		$messages = array ();
		// Checks for empty email
		if (empty ( $_POST ['email'] )) {
			$this->login ( "Please enter your email address." ); // Render the login view with error message
			return; 
		}
		// Checks for empty password
		if (empty ( $_POST ['password'] )) {
			$this->login ( "Please enter your password." ); // Render the login view with error message
			return;
		}
		
		// Check the database for a token with the user's email and password
		$token = check_login ( $_POST ['email'], $_POST ['password'] );
		// Check if the token is present or not
		if (! $token) {
			// Token not found.
			$this->login ( "Invalid username or password" ); // Render the login view with error message
		} else {
			// Token is validated. Store the cookie
			setcookie ( "token", $token, strtotime ( '+1 year' ), '/' );
			Router::redirect ( "/" ); // Redirect to home page
		}
	}
	/**
	 * Function processes a follow request and sends it back to the view where it came from
	 * If no user id is found in the request, render the view to list all users
	 * @param string $user_id_followed Either the user id to be followed 
	 * or the user_id with the view from which the request came from
	 */
	public function follow($user_id_followed = "none") {
		// Check to see if the user id logged in.
		if (! $this->user) {
			// If not redirect it back to the home page for login.
			Router::redirect ( "/" );
		}
		if ($user_id_followed != "none") {
			// If the follow request is comming from the v_keyword_index view,
			// it will have the user id and the view seperated by a '_'
			// Check to see if there is an _ in the user id
			if (strpos ( $user_id_followed, '_' ) !== false) {
				// If so, split the string to the user id and the view
				$pieces = explode ( "_", $user_id_followed );
				$followed_id = $pieces [0];
				$redirect = "/posts/keyword/" . $pieces [1]; // Set the view to be redirected
			} else {
				// The request is coming from v_users_follow
				$followed_id = $user_id_followed;
				$redirect = "/users/follow"; // Set that view to be redirected
			}
			
			// Prepare the data array to be inserted
			$data = Array (
					"created" => Time::now (),
					"user_id" => $this->user->user_id,
					"user_id_followed" => $followed_id 
			);
			// Insert the followed user info
			DB::instance ( DB_NAME )->insert ( 'users_users', $data );
			// Send them back to where they came from
			Router::redirect ( $redirect );
		} else {
			// No user id, so render the view
			$this->template->content = View::instance ( 'v_users_follow' ); // Set view
			$this->template->title = "Follow"; // Set title
			
			// Query to get all the OTHER users
			$q = "SELECT *
                    FROM users
    			   WHERE  user_id != " . $this->user->user_id;

			$users = DB::instance ( DB_NAME )->select_rows ( $q );
			
			// Get list of already connected user incase the user wants to unfollow them.
			$q = "SELECT *
                    FROM users_users
                   WHERE user_id = " . $this->user->user_id;
			$connections = DB::instance ( DB_NAME )->select_array ( $q, 'user_id_followed' );
			// Check if there are other users other than the current user
			if ($users) {
				// Pass data (users and connections) to the view
				$this->template->content->users = $users;
				$this->template->content->connections = $connections;
			} else {
				// No other user, so show the message
				$this->template->content->follow_users_message = "There are no other signed up users at this time. Please wait till someone signs up and try again.";
			}
			// Render the view
			echo $this->template;
		}
	}
	/**
	 * Process an unfollow request
	 * @param unknown $user_id_followed user id to be unfollowed
	 */
	public function unfollow($user_id_followed) {
		// Check to see if the user id logged in.
		if (! $this->user) {
			// If not redirect it back to the home page for login.
			Router::redirect ( "/" );
		}
		// If the follow request is comming from the v_keyword_index view,
		// it will have the user id and the view seperated by a '_'
		// Check to see if there is an _ in the user id
		if (strpos ( $user_id_followed, '_' ) !== false) {
			// If so, split the string to the user id and the view
			$pieces = explode ( "_", $user_id_followed );
			$followed_id = $pieces [0];
			$redirect = "/posts/keyword/" . $pieces [1]; // Set the view to be redirected
		} else {
			// The request is coming from v_users_follow
			$followed_id = $user_id_followed;
			$redirect = "/users/follow"; // Set that view to be redirected
		}
		
		// Delete this connection
		$where_condition = 'WHERE user_id = ' . $this->user->user_id . ' AND user_id_followed = ' . $followed_id;
		DB::instance ( DB_NAME )->delete ( 'users_users', $where_condition );
		
		// Send them back to where they came from
		Router::redirect ( $redirect );
	}
	/**
	 * Show the profile view with any messages
	 * @param unknown $messages message to display
	 */
	public function profile($messages = array()) {
		// Check to see if the user id logged in.
		if (! $this->user) {
			// If not redirect it back to the home page for login.
			Router::redirect ( "/" );
		}
		// Set the script to open the first blind
		$client_files_body = Array (
				"/js/option1.js" 
		);
		$this->template->content = View::instance ( 'v_users_profile' ); // Set view
		$this->template->title = "Profile"; // Set title

		// Query to get the user's profile
		$q = "SELECT * FROM users WHERE user_id = " . $this->user->user_id;
		$profile = DB::instance ( DB_NAME )->select_row ( $q );
		
		$this->template->content->profile = $profile; // Set template data
		// Set the error or success message to display and the script to display the right blind
		if (isset ( $messages ['profile_message'] )) {
			$this->template->content->profile_message = $messages ['profile_message'];
		} else if (isset ( $messages ['profile_error_string'] )) {
			$this->template->content->profile_error_string = $messages ['profile_error_string'];
		} else if (isset ( $messages ['avatar_message'] )) {
			$this->template->content->avatar_message = $messages ['avatar_message'];
			$client_files_body = Array (
					"/js/option2.js" // Script to open second blind
			);
		} else if (isset ( $messages ['avatar_error_message'] )) {
			$this->template->content->avatar_error_message = $messages ['avatar_error_message'];
			$client_files_body = Array (
					"/js/option2.js" // Script to open second blind 
			);
		} else if (isset ( $messages ['password_message'] )) {
			$this->template->content->password_message = $messages ['password_message'];
			$client_files_body = Array (
					"/js/option3.js"  // Script to open third blind
			);
		} else if (isset ( $messages ['password_error_message'] )) {
			$this->template->content->password_error_message = $messages ['password_error_message'];
			$client_files_body = Array (
					"/js/option3.js"  // Script to open second blind
			);
		}
		// Load the script
		$this->template->client_files_body = Utils::load_client_files ( $client_files_body );
		echo $this->template; // Render view
	}
	/**
	 * Process the upload request.
	 * Saves the image to the /upload/avatar folder
	 * Update the user's profile with the avatar
	 */
	public function p_upload() {
		// Check to see if the user id logged in.
		if (! $this->user) {
			// If not redirect it back to the home page for login.
			Router::redirect ( "/" );
		}
		// Check if the user selected any image
		if ($_FILES ['avatar'] ['error'] != 0) {
			// If not, show an error message
			$messages ['avatar_error_message'] = "Please select a image file and try again.";
			$this->profile ( $messages );
			return;
		}
		// Upload the file in the /upload/avatar and rename it to <user_id>_avatar.<extension>
		$file_name = Upload::upload ( $_FILES, "/uploads/avatars/", array (
				"jpg",
				"jpeg",
				"gif",
				"png" 
		), $this->user->user_id . "_avatar" );
		// Create where clause
		$where_condition = 'WHERE user_id = ' . $this->user->user_id;
		// Set array to inser
		$data = Array (
				"avatar" => "/uploads/avatars/" . $file_name,
				"modified" => Time::now () 
		);
		// Updates the user info
		DB::instance ( DB_NAME )->update ( 'users', $data, $where_condition );
		$messages = array ();
		// Show success message
		$messages ['avatar_message'] = "Your avatar has been sucessfully changed.";
		$this->profile ( $messages ); // Render view
	}
	/**
	 * Process update password request
	 */
	public function p_updatepassword() {
		// Check to see if the user id logged in.
		if (! $this->user) {
			// If not redirect it back to the home page for login.
			Router::redirect ( "/" );
		}
		// Sanitize the data for SQL injection attacks
		$_POST = DB::instance ( DB_NAME )->sanitize ( $_POST );
		$messages = array ();
		// Check if the old password is empty
		if (empty ( $_POST ['old_password'] )) {
			$messages ['password_error_message'] = "Please enter the old password.";
			$this->profile ( $messages );
			return;
		}
		// Check if the new password is empty		
		if (empty ( $_POST ['password'] )) {
			$messages ['password_error_message'] = "Please enter the new password.";
			$this->profile ( $messages );
			return;
		}
		// Check if the password confirmation is empty		
		if (empty ( $_POST ['new_password'] )) {
			$messages ['password_error_message'] = "Please confirm the password by entering it again.";
			$this->profile ( $messages );
			return;
		}
		// Check if the password matches it's confirmation
		if ($_POST ['password'] != $_POST ['new_password']) {
			$messages ['password_error_message'] = "The new password and it's confirmation does not match. Please make sure enter the same password while confirming it.";
			$this->profile ( $messages );
			return;
		}
		// Check to see if the old password is a valid one
		$token = check_login ( $this->user->email, $_POST ['old_password'] );
		if (! $token) {
			// If not, show the message
			$messages ['password_error_message'] = "The old password failed to authenticatio. Please re-enter the old password.";
			$this->profile ( $messages );
			return;
		}
		// All validations passed
		// Encrypt the password
		$password = sha1 ( PASSWORD_SALT . $_POST ['new_password'] );
		// Create an encrypted token via their email address and a random string
		$token = sha1 ( TOKEN_SALT . $this->user->email . Utils::generate_random_string () );
		// Set where condition
		$where_condition = 'WHERE user_id = ' . $this->user->user_id;
		// Set array to update
		$data = Array (
				"password" => $password,
				"token" => $token,
				"modified" => Time::now () 
		);
		DB::instance ( DB_NAME )->update ( 'users', $data, $where_condition );
		$messages = array ();
		$messages ['password_message'] = "Your password has been sucessfully changed."; // Show message
		setcookie ( "token", $token, strtotime ( '+1 year' ), '/' ); // Set cookie
		$this->profile ( $messages ); // Call the profile function to render the v_user_profile view
	}
}
?>