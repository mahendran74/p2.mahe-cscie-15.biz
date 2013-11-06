<?php
include ($_SERVER ['DOCUMENT_ROOT'] . '/utils/util.php'); // Include utility class
/**
 *
 * @author Mahendran Sreedevi
 *         This controller does the following
 *         - Add a post
 *         - Delete a post
 *         - Edit a post
 *         - Get list of keywords
 *         - Create the keyword cloud
 */
class posts_controller extends base_controller {
	public function __construct() {
		parent::__construct ();
		
		// Check to see if the user id logged in.
		if (! $this->user) {
			// If not redirect it back to the home page for login.
			Router::redirect ( "/" );
		}
	}
	public function add() {
		// Redirect to home page where the add post form can be found.
		Router::redirect ( "/" );
	}
	public function edit($post_id) {
		// Check whether the post id of the 
		// post to be edited is set.
		if (! isset ( $post_id )) {
			// If not redirect it back to the home page
			Router::redirect ( "/" );
		}
		// Query to get post to be edited.
		$q =	"SELECT * 
				FROM posts 
				WHERE post_id = " . $post_id;
		$post = DB::instance ( DB_NAME )->select_row ( $q ); // Get the post data
		$this->template->content = View::instance ( "v_posts_edit" ); // Set view
		$this->template->title = "Edit"; // Set title
		$this->template->content->post = $post; // Set data
		echo $this->template; // Render view
	}
	/**
	 * Function to process an add post request 
	 */
	public function p_add() {
		// Check to see if the content is not empty
		if (! check_for_empty_string ( $_POST ['content'] )) {
			// Associate this post with this user
			$_POST ['user_id'] = $this->user->user_id; // Set user id
			$_POST ['created'] = Time::now (); // Set created time
			$_POST ['modified'] = Time::now (); // Set modified time
			// Remove '<' and '>' chars for HTML sanitization
			$_POST ['content'] = str_replace ( "<", "&lt;", $_POST ['content'] );
			$_POST ['content'] = str_replace ( ">", "&gt;", $_POST ['content'] );
			$post_id = DB::instance ( DB_NAME )->insert ( 'posts', $_POST ); // Insert the post
			// Get each keyword in the post and add them to the keyword list
			get_keywords ( $_POST ['content'], $post_id );
		}
		// Send back to home page with the message
		$index = new index_controller();
		$index->index("Your post was added successfully");
	}
	/**
	 * Function to process an update post request
	 */	
	public function p_update() {
		// Sanitize the request for SQL injection
		$_POST = DB::instance ( DB_NAME )->sanitize ( $_POST );
		// Check to see if the content is not empty
		if (! check_for_empty_string ( $_POST ['content'] )) {

			$_POST ['modified'] = Time::now (); // Set modified time
			// Remove '<' and '>' chars for HTML sanitization
			$_POST ['content'] = str_replace ( "<", "&lt;", $_POST ['content'] );
			$_POST ['content'] = str_replace ( ">", "&gt;", $_POST ['content'] );
			
			$where_condition = 'WHERE post_id = ' . $_POST ['post_id']; // Set where clause
			DB::instance ( DB_NAME )->update ( 'posts', $_POST, $where_condition ); // Update
			// Get each keyword in the post and add them to the keyword list
			get_keywords ( $_POST ['content'], $_POST ['post_id'] );
		}
		// Send back to home page.
		$index = new index_controller();
		$index->index("Your post was updated successfully");
	}
	/**
	 * Function to process an delete post request
	 */	
	public function delete($post_id) {
		// Check whether the post id of the
		// post to be deleted is set.
		if ( ! isset ( $post_id )) {
			Router::redirect ( "/" );
		}
		DB::instance ( DB_NAME )->delete ( 'posts', "WHERE post_id = " . $post_id ); // Delete post
		// Send back to home page..
		$index = new index_controller();
		$index->index("Your post was deleted successfully");
	}
	/**
	 * Function to get the list of all posts with a given keyword
	 */	
	public function keyword($query_keyword) {
		$this->template->content = View::instance ( "v_keyword_index" ); // Set view
		$this->template->title = "Keyword posts"; // Set title
		// Query to get all the posts with the given keyword
		$q = "SELECT 	posts.post_id, 
  						posts.content, 
    					posts.modified, 
						posts.user_id AS post_user_id, 
						users.first_name, 
						users.last_name,
 						users.avatar
			FROM 		posts
			INNER JOIN 	posts_keywords ON posts.post_id = posts_keywords.post_id
			INNER JOIN 	users ON posts.user_id = users.user_id
			WHERE UPPER(posts_keywords.keyword) = UPPER('" . $query_keyword . "')";
		// Run the query
		$dbposts = DB::instance ( DB_NAME )->select_rows ( $q );
		// Copy the result to new array for editing
		foreach ( $dbposts as $dbpost ) {
			$post ['post_id'] = $dbpost ['post_id'];
			$post ['modified'] = $dbpost ['modified'];
			$post ['post_user_id'] = $dbpost ['post_user_id'];
			$post ['first_name'] = $dbpost ['first_name'];
			$post ['last_name'] = $dbpost ['last_name'];
			$post ['avatar'] = $dbpost ['avatar'];
			// Get list of all keywords for the post
			$q = "SELECT * FROM posts_keywords WHERE post_id = " . $dbpost ["post_id"];
			$keywords = DB::instance ( DB_NAME )->select_rows ( $q );
			$mod_content = $dbpost ["content"];
			foreach ( $keywords as $keyword ) {
				// Search content for keyword and replace it with the link for the keyword
				$mod_content = str_replace ( "#" . $keyword ["keyword"], "<a href='/posts/keyword/" . $keyword ["keyword"] . "'>#" . $keyword ["keyword"] . "</a>", $mod_content );
			}
			// Set modified content
			$post ["content"] = nl2br($mod_content);
			$posts [] = $post;
		}
		// Query to get all the users that this user is following
		$q = "SELECT *
        FROM users_users
        WHERE user_id = " . $this->user->user_id;
		
		$connections = DB::instance ( DB_NAME )->select_array ( $q, 'user_id_followed' );
		
		// Pass data to the View
		$this->template->content->keyword = $query_keyword;
		$this->template->content->connections = $connections;
		$this->template->content->posts = $posts;
		echo $this->template; // Render template
	}
	/**
	 * Function shows the keyword cloud
	 */
	public function trend() {
		// Add the awesomeCloud and sample-app js
		$client_files_body = Array (
				"/js/jquery.awesomeCloud-0.2.min.js",
				"/js/cloud.js" 
		);
		$this->template->client_files_body = Utils::load_client_files ( $client_files_body );
		// Query to get the list of all keywords and the total number of times it appears in all the posts
		$q = "SELECT keyword, 
                     count(*) AS weight
                FROM posts_keywords
            GROUP BY keyword";
		$trends = DB::instance ( DB_NAME )->select_rows ( $q );
		$this->template->content = View::instance ( 'v_posts_trend' ); // Set view
		$this->template->title = "Trends"; // Set title
		$this->template->content->trends = $trends; // Set data
		echo $this->template; // Render view
	}
}
?>