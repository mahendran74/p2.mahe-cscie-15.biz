<?php
include ($_SERVER ['DOCUMENT_ROOT'] . '/utils/util.php'); // Include utility class
/**
 * @author Mahendran Sreedevi 
 * This is the controller for the home page.
 */
class index_controller extends base_controller {
	

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct ();
	}
	

	/**
	 * This function gets the list of all user's posts and all the posts from people she follows
	 */
	public function index() {
		// Set the view
		$this->template->content = View::instance ( 'v_index_index' );
		// Set the title
		$this->template->title = APP_NAME;
	
		// Check to see if the user is logged in
		if ($this->user) {
			// This query gets the list of all user's posts and all the posts from people she follows
			$q = "SELECT * 
                    FROM (SELECT posts.post_id, 
                                 posts.content, 
                                 posts.modified, 
                                 posts.user_id       AS post_user_id, 
                                 users_users.user_id AS follower_id, 
                                 users.first_name, 
                                 users.last_name, 
                                 users.avatar 
                            FROM posts 
                                 INNER JOIN users_users 
                                         ON posts.user_id = users_users.user_id_followed 
                                 INNER JOIN users 
                                         ON posts.user_id = users.user_id 
                           WHERE users_users.user_id = " . $this->user->user_id . " 
                           UNION 
                          SELECT posts.post_id, 
                                 posts.content, 
                                 posts.modified, 
                                 posts.user_id                AS post_user_id, 
                                 " . $this->user->user_id . " AS follower_id, 
                                 users.first_name, 
                                 users.last_name, 
                                 users.avatar 
                            FROM posts 
                                 INNER JOIN users 
                                         ON posts.user_id = users.user_id 
                           WHERE posts.user_id = " . $this->user->user_id . ") AS all_posts 
                ORDER BY all_posts.modified DESC ";
			$posts = array ();
			// Run the query
			$dbposts = DB::instance ( DB_NAME )->select_rows ( $q );
			foreach ( $dbposts as $dbpost ) {
				// Copy the result array into another array for editing.
				$post ['post_id'] = $dbpost ['post_id'];
				$post ['modified'] = $dbpost ['modified'];
				$post ['post_user_id'] = $dbpost ['post_user_id'];
				$post ['follower_id'] = $dbpost ['follower_id'];
				$post ['first_name'] = $dbpost ['first_name'];
				$post ['last_name'] = $dbpost ['last_name'];
				$post ['avatar'] = $dbpost ['avatar'];
				// For each post get the list of keywords. 
				$q = "SELECT * FROM posts_keywords WHERE post_id = " . $dbpost ["post_id"];
				$keywords = DB::instance ( DB_NAME )->select_rows ( $q );
				$mod_content = $dbpost ["content"];
				foreach ( $keywords as $keyword ) {
					// Go through the post and add links to keywords
					$mod_content = str_replace ( "#" . $keyword ["keyword"], "<a href='/posts/keyword/" . $keyword ["keyword"] . "'>#" . $keyword ["keyword"] . "</a>", $mod_content );
				}
				// Add content back to array
				$post ["content"] = strip_new_line($mod_content);
				$posts [] = $post;
			}
			// Include the delete confirmation js
			$client_files_body = Array ("/js/confirm.js");
			$this->template->client_files_body = Utils::load_client_files ( $client_files_body );
			// Pass data to the View
			$this->template->content->posts = $posts;
		}
		// Render the template
		echo $this->template;
	} 
} 
