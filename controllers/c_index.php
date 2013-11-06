<?php
class index_controller extends base_controller {
	
	/*
	 * ------------------------------------------------------------------------------------------------- -------------------------------------------------------------------------------------------------
	 */
	public function __construct() {
		parent::__construct ();
	}
	
	/*
	 * ------------------------------------------------------------------------------------------------- Accessed via http://localhost/index/index/ -------------------------------------------------------------------------------------------------
	 */
	public function index() {
		
		// Any method that loads a view will commonly start with this
		// First, set the content of the template with a view file
		$this->template->content = View::instance ( 'v_index_index' );
		
		// Now set the <title> tag
		$this->template->title = APP_NAME;
		
		// CSS/JS includes
		/*
		 * $client_files_head = Array(""); 
		 * $this->template->client_files_head = Utils::load_client_files($client_files); 
		 * $client_files_body = Array(""); 
		 * $this->template->client_files_body = Utils::load_client_files($client_files_body);
		 */
		
		// Build the query
		if ($this->user) {
			
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
				$post ['post_id'] = $dbpost ['post_id'];
				$post ['modified'] = $dbpost ['modified'];
				$post ['post_user_id'] = $dbpost ['post_user_id'];
				$post ['follower_id'] = $dbpost ['follower_id'];
				$post ['first_name'] = $dbpost ['first_name'];
				$post ['last_name'] = $dbpost ['last_name'];
				$post ['avatar'] = $dbpost ['avatar'];
				
				$q = "SELECT * FROM posts_keywords WHERE post_id = " . $dbpost ["post_id"];
				$keywords = DB::instance ( DB_NAME )->select_rows ( $q );
				$mod_content = $dbpost ["content"];
				foreach ( $keywords as $keyword ) {
					$mod_content = str_replace ( "#" . $keyword ["keyword"], "<a href='/posts/keyword/" . $keyword ["keyword"] . "'>#" . $keyword ["keyword"] . "</a>", $mod_content );
				}
				// array_push($post, $mod_content);
				$post ["content"] = $mod_content;
				$posts [] = $post;
			}
			$client_files_body = Array (
					"/js/confirm.js"
			);
			$this->template->client_files_body = Utils::load_client_files ( $client_files_body );
			// Pass data to the View
			$this->template->content->posts = $posts;
		}
		
		echo $this->template;
	} // End of method
} # End of class
