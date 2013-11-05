<?php
include '\utils\util.php';
class users_controller extends base_controller {
	public function __construct() {
		parent::__construct ();
	}
	public function signup() {
		
		// Setup view
		$this->template->content = View::instance ( 'v_users_signup' );
		$this->template->title = "Sign Up";
		
		// Render template
		echo $this->template;
	}
	public function p_signup() {
		// Sanitize the user entered data to prevent any funny-business (re: SQL Injection Attacks)
		$_POST = DB::instance ( DB_NAME )->sanitize ( $_POST );
		if (email_not_used ( $_POST ['email'], $this->user->user_id )) {
			// More data we want stored with the user
			$_POST ['created'] = Time::now ();
			$_POST ['modified'] = Time::now ();
			
			// Encrypt the password
			$_POST ['password'] = sha1 ( PASSWORD_SALT . $_POST ['password'] );
			
			// Create an encrypted token via their email address and a random string
			$_POST ['token'] = sha1 ( TOKEN_SALT . $_POST ['email'] . Utils::generate_random_string () );
			
			unset ( $_POST ['confirm_password'] );
			// Insert this user into the database
			$user_id = DB::instance ( DB_NAME )->insert ( "users", $_POST );
			setcookie ( "token", $_POST ['token'], strtotime ( '+1 year' ), '/' );
			// Redirect to home page
			Router::redirect ( "/" );
		} else {
			echo "Email exists";
		}
	}
	public function p_update() {
		if (! isset ( $this->user->user_id )) {
			Router::redirect ( "/" );
		}
		// Sanitize the user entered data to prevent any funny-business (re: SQL Injection Attacks)
		$_POST = DB::instance ( DB_NAME )->sanitize ( $_POST );
		$messages = array ();
		if (empty ( $_POST ['first_name'] )) {
			$messages ['profile_error_string'] = "The first name is a required field.";
			$this->profile ( $messages );
			return;
		}
		if (empty ( $_POST ['last_name'] )) {
			$messages ['profile_error_string'] = "The last name is a required field.";
			$this->profile ( $messages );
			return;
		}
		if (empty ( $_POST ['email'] )) {
			$messages ['profile_error_string'] = "The email address is a required field.";
			$this->profile ( $messages );
			return;
		}
		if (email_not_used ( $_POST ['email'], $this->user->user_id )) {
			// More data we want stored with the user
			$_POST ['modified'] = Time::now ();
			// Insert this user into the database
			DB::instance ( DB_NAME )->update ( "users", $_POST, "WHERE user_id = " . $this->user->user_id );
			$messages ['profile_message'] = "Your profile was updated successfully";
			$this->profile ( $messages );
			return;
		} else {
			$messages ['profile_error_string'] = "This email address (" . $_POST ['email'] . ") is already registered. Please try another one.";
			$this->profile ( $messages );
			return;
		}
	}
	public function logout() {
		
		// Generate and save a new token for next login
		$new_token = sha1 ( TOKEN_SALT . $this->user->email . Utils::generate_random_string () );
		
		// Create the data array we'll use with the update method
		// In this case, we're only updating one field, so our array only has one entry
		$data = Array (
				"token" => $new_token 
		);
		
		// Do the update
		DB::instance ( DB_NAME )->update ( "users", $data, "WHERE token = '" . $this->user->token . "'" );
		
		// Delete their token cookie by setting it to a date in the past - effectively logging them out
		setcookie ( "token", "", strtotime ( '-1 year' ), '/' );
		
		// Send them back to the main index.
		Router::redirect ( "/" );
	}
	public function login($message = "") {
		$this->template->content = View::instance ( 'v_index_index' );
		$this->template->content->login_error_message = $message;
		echo $this->template;
	}
	public function p_login() {
		// Sanitize the user entered data to prevent any funny-business (re: SQL Injection Attacks)
		$_POST = DB::instance ( DB_NAME )->sanitize ( $_POST );
		
		$messages = array ();
		if (empty ( $_POST ['email'] )) {
			$this->login("Please enter your email address.");
			return;
		}
		if (empty ( $_POST ['password'] )) {
			$this->login("Please enter your password.");
			return;
		}
		
		// If we didn't find a matching token in the database, it means login failed
		$token = check_login ( $_POST ['email'], $_POST ['password'] );
		if (! $token) {
			
			$this->login("Invalid username or password");
			
			// But if we did, login succeeded!
		} else {
			
			/*
			 * Store this token in a cookie using setcookie() Important Note: *Nothing* else can echo to the page before setcookie is called Not even one single white space. param 1 = name of the cookie param 2 = the value of the cookie param 3 = when to expire param 4 = the path of the cooke (a single forward slash sets it for the entire domain)
			 */
			setcookie ( "token", $token, strtotime ( '+1 year' ), '/' );
			
			// Send them to the main page - or whever you want them to go
			Router::redirect ( "/" );
		}
	}
	public function follow($user_id_followed = "none") {
		if (! isset ( $this->user->user_id )) {
			Router::redirect ( "/" );
		}
		if ($user_id_followed != "none") {
			// Prepare the data array to be inserted
			$data = Array (
					"created" => Time::now (),
					"user_id" => $this->user->user_id,
					"user_id_followed" => $user_id_followed 
			);
			
			// Do the insert
			DB::instance ( DB_NAME )->insert ( 'users_users', $data );
			
			// Send them back
			Router::redirect ( "/users/follow" );
		} else {
			// Setup view
			$this->template->content = View::instance ( 'v_users_follow' );
			$this->template->title = "Follow";
			
			// Build the query to get all the users
			$q = "SELECT *
                    FROM users
    			   WHERE user_id != " . $this->user->user_id;
			
			// Execute the query to get all the users.
			// Store the result array in the variable $users
			$users = DB::instance ( DB_NAME )->select_rows ( $q );
			
			// Build the query to figure out what connections does this user already have?
			// I.e. who are they following
			$q = "SELECT *
                    FROM users_users
                   WHERE user_id = " . $this->user->user_id;
			
			// Execute this query with the select_array method
			// select_array will return our results in an array and use the "users_id_followed" field as the index.
			// This will come in handy when we get to the view
			// Store our results (an array) in the variable $connections
			
			$connections = DB::instance ( DB_NAME )->select_array ( $q, 'user_id_followed' );
			
			if (sizeof($connections) > 0) {
			// Pass data (users and connections) to the view
			$this->template->content->users = $users;
			$this->template->content->connections = $connections;
			
			 } else {
			 $this->template->content->follow_users_message = "There are no other signed up users at this time. Please wait till someone signs up and try again.";
			 }
			 // Render the view
			 echo $this->template;
		}
	}
	public function unfollow($user_id_followed) {
		if (! isset ( $this->user->user_id )) {
			Router::redirect ( "/" );
		}
		// Delete this connection
		$where_condition = 'WHERE user_id = ' . $this->user->user_id . ' AND user_id_followed = ' . $user_id_followed;
		DB::instance ( DB_NAME )->delete ( 'users_users', $where_condition );
		
		// Send them back
		Router::redirect ( "/posts/users" );
	}
	public function profile($messages = array()) {
		if (! isset ( $this->user->user_id )) {
			Router::redirect ( "/" );
		}
		$client_files_body = Array (
				"/js/option1.js" 
		);
		$this->template->content = View::instance ( 'v_users_profile' );
		$this->template->title = "Profile";
		$q = "SELECT * FROM users WHERE user_id = " . $this->user->user_id;
		$profile = DB::instance ( DB_NAME )->select_row ( $q );
		$this->template->content->profile = $profile;
		if (isset ( $messages ['profile_message'] )) {
			$this->template->content->profile_message = $messages ['profile_message'];
		} else if (isset ( $messages ['profile_error_string'] )) {
			$this->template->content->profile_error_string = $messages ['profile_error_string'];
		} else if (isset ( $messages ['avatar_message'] )) {
			$this->template->content->avatar_message = $messages ['avatar_message'];
			$client_files_body = Array (
					"/js/option2.js" 
			);
		} else if (isset ( $messages ['avatar_error_message'] )) {
			$this->template->content->avatar_error_message = $messages ['avatar_error_message'];
			$client_files_body = Array (
					"/js/option2.js" 
			);
		} else if (isset ( $messages ['password_message'] )) {
			$this->template->content->password_message = $messages ['password_message'];
			$client_files_body = Array (
					"/js/option3.js" 
			);
		} else if (isset ( $messages ['password_error_message'] )) {
			$this->template->content->password_error_message = $messages ['password_error_message'];
			$client_files_body = Array (
					"/js/option3.js" 
			);
		}
		$this->template->client_files_body = Utils::load_client_files ( $client_files_body );
		// Render template
		echo $this->template;
	}
	public function p_upload() {
		if (! isset ( $this->user->user_id )) {
			Router::redirect ( "/" );
		}
		if ($_FILES ['avatar'] ['error'] != 0) {
			$messages ['avatar_error_message'] = "Please select a image file and try again.";
			$this->profile ( $messages );
			return;
		}
		$file_name = Upload::upload ( $_FILES, "/uploads/avatars/", array (
				"jpg",
				"jpeg",
				"gif",
				"png" 
		), $this->user->user_id . "_avatar" );
		
		$where_condition = 'WHERE user_id = ' . $this->user->user_id;
		$data = Array (
				"avatar" => "/uploads/avatars/" . $file_name,
				"modified" => Time::now () 
		);
		DB::instance ( DB_NAME )->update ( 'users', $data, $where_condition );
		$messages = array ();
		$messages ['avatar_message'] = "Your avatar has been sucessfully changed.";
		$this->profile ( $messages );
	}
	public function p_updatepassword() {
		if (! isset ( $this->user->user_id )) {
			Router::redirect ( "/" );
		}
		// Sanitize the user entered data to prevent any funny-business (re: SQL Injection Attacks)
		$_POST = DB::instance ( DB_NAME )->sanitize ( $_POST );
		$messages = array ();
		if (empty ( $_POST ['old_password'] )) {
			$messages ['password_error_message'] = "Please enter the old password.";
			$this->profile ( $messages );
			return;
		}
		if (empty ( $_POST ['password'] )) {
			$messages ['password_error_message'] = "Please enter the new password.";
			$this->profile ( $messages );
			return;
		}
		if (empty ( $_POST ['new_password'] )) {
			$messages ['password_error_message'] = "Please confirm the password by entering it again.";
			$this->profile ( $messages );
			return;
		}
		if ($_POST ['password'] != $_POST ['new_password']) {
			$messages ['password_error_message'] = "The new password and it's confirmation does not match. Please make sure enter the same password while confirming it.";
			$this->profile ( $messages );
			return;
		}
		$token = check_login ( $this->user->email, $_POST ['old_password'] );
		if (! $token) {
			$messages ['password_error_message'] = "The old password failed to authenticatio. Please re-enter the old password.";
			$this->profile ( $messages );
			return;
		}
		// Encrypt the password
		$password = sha1 ( PASSWORD_SALT . $_POST ['new_password'] );
		
		// Create an encrypted token via their email address and a random string
		$token = sha1 ( TOKEN_SALT . $this->user->email . Utils::generate_random_string () );
		
		$where_condition = 'WHERE user_id = ' . $this->user->user_id;
		$data = Array (
				"password" => $password,
				"token" => $token,
				"modified" => Time::now () 
		);
		DB::instance ( DB_NAME )->update ( 'users', $data, $where_condition );
		$messages = array ();
		$messages ['password_message'] = "Your password has been sucessfully changed.";
		setcookie ( "token", $token, strtotime ( '+1 year' ), '/' );
		$this->profile ( $messages );
	}
}
?>