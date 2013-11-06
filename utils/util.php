<?php

	function check_for_empty_string($string) {
		return (!isset($string) || trim($string)==='');
	}
	
	function email_not_used($email, $user_id = NULL) {
		// Build the query to get all the users
		if (empty($user_id))
		{
			$q = "SELECT *
                    FROM users
    			   WHERE UPPER(email) = UPPER('".$email."')";
		} else {
		$q = "SELECT *
                    FROM users
    			   WHERE UPPER(email) = UPPER('".$email."') AND
    			   		 user_id != ".$user_id;
		}
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
	
	function get_keywords($content, $post_id) {
		preg_match_all('/#([\p{L}\p{Mn}]+)/u',$_POST['content'], $content);
		foreach ($content[1] as $keyword) {
			$data = Array("keyword" => $keyword, "post_id" => $post_id);
			$q="SELECT * FROM posts_keywords WHERE keyword = '".$keyword."' AND post_id = ".$post_id;
			$keyword_row = DB::instance ( DB_NAME )->select_rows ( $q );
			if (empty($keyword_row))
				DB::instance(DB_NAME)->insert('posts_keywords', $data);
		}
	}
?>