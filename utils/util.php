<?php

	function check_for_empty_string($string) {
		return (!isset($string) || trim($string)==='');
	}
	
	function email_not_used($email, $user_id) {
		// Build the query to get all the users
		$q = "SELECT *
                    FROM users
    			   WHERE UPPER(email) = UPPER('".$email."') AND
    			   		 user_id != ".$user_id;
			
		// Execute the query to get all the email.
		// Store the result array in the variable $users
		$users = DB::instance ( DB_NAME )->select_rows ( $q );
		return (empty($users));
	}
	
	function check_login($email, $password) {
		// Hash submitted password so we can compare it against one in the db
		$password = sha1 ( PASSWORD_SALT . $password );
		
		// Search the db for this email and password
		// Retrieve the token if it's available
		$q = "SELECT token
                FROM users
               WHERE email = '" . $email . "'
                 AND password = '" . $password . "'";
		$token = DB::instance ( DB_NAME )->select_field ( $q );
		return $token;
	}
?>