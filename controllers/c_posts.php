<?php
include '\utils\util.php';
class posts_controller extends base_controller {
	public function __construct() {
		parent::__construct ();
		
		// Make sure user is logged in if they want to use anything in this controller
		if (! $this->user) {
			Router::redirect ( "/" );
		}
	}
	public function add() {
		Router::redirect ( "/" );
	}
	public function edit($post_id) {
		if (! isset ( $this->user->user_id ) || ! isset ( $post_id )) {
			Router::redirect ( "/" );
		}
		$q = "SELECT * FROM posts WHERE post_id = " . $post_id;
		$post = DB::instance ( DB_NAME )->select_row ( $q );
		$this->template->content = View::instance ( "v_posts_edit" );
		$this->template->title = "Edit";
		$this->template->content->post = $post;
		echo $this->template;
	}
	public function p_add() {
		if (! isset ( $this->user->user_id )) {
			Router::redirect ( "/" );
		}
		if (! check_for_empty_string ( $_POST ['content'] )) {
			// Associate this post with this user
			$_POST ['user_id'] = $this->user->user_id;
			
			// Unix timestamp of when this post was created / modified
			$_POST ['created'] = Time::now ();
			$_POST ['modified'] = Time::now ();
			$_POST ['content'] = str_replace ( "<", "&lt;", $_POST ['content'] );
			$_POST ['content'] = str_replace ( ">", "&gt;", $_POST ['content'] );
			// Insert
			// Note we didn't have to sanitize any of the $_POST data because we're using the insert method which does it for us
			$post_id = DB::instance ( DB_NAME )->insert ( 'posts', $_POST );
			get_keywords ( $_POST ['content'], $post_id );
		}
		// Send them back to the main index.
		Router::redirect ( "/" );
	}
	public function p_update() {
		if (! isset ( $this->user->user_id )) {
			Router::redirect ( "/" );
		}
		$_POST = DB::instance ( DB_NAME )->sanitize ( $_POST );
		if (! check_for_empty_string ( $_POST ['content'] )) {
			
			// Unix timestamp of when this post was created / modified
			$_POST ['modified'] = Time::now ();
			$_POST ['content'] = str_replace ( "<", "&lt;", $_POST ['content'] );
			$_POST ['content'] = str_replace ( ">", "&gt;", $_POST ['content'] );
			
			$where_condition = 'WHERE post_id = ' . $_POST ['post_id'];
			DB::instance ( DB_NAME )->update ( 'posts', $_POST, $where_condition );
			
			get_keywords ( $_POST ['content'], $_POST ['post_id'] );
		}
		// Send them back to the main index.
		Router::redirect ( "/" );
	}
	public function delete($post_id) {
		if (! isset ( $this->user->user_id ) || ! isset ( $post_id )) {
			Router::redirect ( "/" );
		}
		DB::instance ( DB_NAME )->delete ( 'posts', "WHERE post_id = " . $post_id );
		// Send them back to the main index.
		Router::redirect ( "/" );
	}
	public function keyword($query_keyword) {
		if (! isset ( $this->user->user_id )) {
			Router::redirect ( "/" );
		}
		$this->template->content = View::instance ( "v_keyword_index" );
		$this->template->title = "Keyword posts";
		
		$q = "SELECT 	posts.post_id, 
  						posts.content, 
    					posts.created, 
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
		
		foreach ( $dbposts as $dbpost ) {
			$post ['post_id'] = $dbpost ['post_id'];
			$post ['created'] = $dbpost ['created'];
			$post ['post_user_id'] = $dbpost ['post_user_id'];
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
			// echo $post["content"];
			// echo "\r\n";
		}
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
		
		// Pass data to the View
		$this->template->content->keyword = $query_keyword;
		$this->template->content->connections = $connections;
		$this->template->content->posts = $posts;
		echo $this->template;
	}
	public function trend() {
		if (! isset ( $this->user->user_id )) {
			Router::redirect ( "/" );
		}
		$client_files_body = Array (
				"/js/jquery.awesomeCloud-0.2.min.js",
				"/js/sample-app.js" 
		);
		$this->template->client_files_body = Utils::load_client_files ( $client_files_body );
		$q = "SELECT keyword, 
                     count(*) AS weight
                FROM posts_keywords
            GROUP BY keyword";
		$trends = DB::instance ( DB_NAME )->select_rows ( $q );
		$this->template->content = View::instance ( 'v_posts_trend' );
		$this->template->title = "Trends";
		$this->template->content->trends = $trends;
		echo $this->template;
	}
}
?>