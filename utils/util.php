<?php
/**
 *
 * @author Mahendran Sreedevi
 *         Utility class that does the following
 *         - Checks if a string is empty
 *         - Checks if the email is already registered
 *         - Checks if the email and password are valid
 *         - Gets the keywords in a post and updates the database
 */
/**
 * Checks whether the given string is empty
 * @param unknown $string String to check
 * @return boolean True if it's blank and false if not
 */
function check_for_empty_string($string) {
	return (! isset ( $string ) || trim ( $string ) === '');
}
/**
 * Checks whether the emal is already used
 * @param unknown $email Email to check
 * @param string $user_id Optional user id
 * @return boolean True if is used already and false if it's not.
 */
function email_not_used($email, $user_id = NULL) {
	// Check if the user id is null or not
	if (empty ( $user_id )) {
		// User id is null, so request from the sign up view
		// Check whether the email is used by any signed up user
		$q = 	"SELECT *
				FROM users
    			WHERE UPPER(email) = UPPER('" . $email . "')";
	} else {
		// User id is not null, so request from the update profile view
		// Check whether the email is used by any other signed up user 
		// other than the current one 
		$q = 	"SELECT *
                FROM users
    		    WHERE UPPER(email) = UPPER('" . $email . "') AND
    			   		 user_id != " . $user_id;
	}
	// Execute the query to get all the email.
	// Store the result array in the variable $users
	$users = DB::instance ( DB_NAME )->select_rows ( $q );
	return (empty ( $users ));
}
/**
 * Function takes the user's email and password and checks the database
 * for a token
 * @param String $email
 * @param String $password
 * @return String token of the user
 */
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
/**
 * Gets all the keywords and inserts them if they are not already present
 * @param unknown $content Post to be scanned for keywords
 * @param unknown $post_id Post id
 */
function get_keywords($content, $post_id) {
	// Get all words starting with a #
	preg_match_all ( '/#([\p{L}\p{Mn}]+)/u', $_POST ['content'], $content );
	foreach ( $content [1] as $keyword ) {
		// Check if the keyword is already registered for this post,
		// if so, don't insert it again
		$q = "SELECT * FROM posts_keywords WHERE keyword = '" . $keyword . "' AND post_id = " . $post_id;
		$keyword_row = DB::instance ( DB_NAME )->select_rows ( $q );
		if (empty ( $keyword_row )) {
			// The keyword is not registered, so insert it.
			$data = Array (
					"keyword" => $keyword,
					"post_id" => $post_id 
			);
	
			DB::instance ( DB_NAME )->insert ( 'posts_keywords', $data );
		}
	}
}
function strip_new_line($content) {
	return nl2br($content);
}
?>