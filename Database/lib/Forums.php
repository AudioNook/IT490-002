<?php

namespace Database;

require_once(__DIR__ . "/db.php");

use Database\db;
/**
 * Class Forums: Database class for forums
 * @package Database
 */
class Forums extends db
{
    /**
     * Forums constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Get all topics
     * @return array
     */
    public function get_topics()
    {
        $table = 'Discussion_Topics';
        $topics = $this->exec_query("SELECT id, name. description FROM $table");
        if ($topics) {
            return $topics;
        }
    }

    /**
     * Get all posts for a topic
     * @param $topic_id
     * @return array
     */
    public function get_posts($topic_id)
    {
        $posts = $this->exec_query("SELECT Discussion_Posts.id, Discussion_Posts.user_id, Discussion_Posts.post_title, Discussion_Posts.content, Discussion_Posts.created, Users.username FROM Discussion_Posts INNER JOIN Users ON Discussion_Posts.user_id = Users.id WHERE Discussion_Posts.topic_id = :t_id", [":t_id" => $topic_id]);
        if ($posts) {
            return $posts;
        }
    }

    /**
     * Get a single post
     * @param $post_id
     * @return array
     */
    public function create_reply($post_id, $user_id, $reply_msg)
    {
        $query = "INSERT INTO Post_Replies (post_id, user_id, content) VALUES(:post_id, :user_id, :reply_msg)";
        $params = [
            ':post_id' => $post_id,
            ':user_id' => $user_id,
            ':reply_msg' => $reply_msg
        ];
        if ($this->exec_query($query, $params) !== false) {
            return true;
        }
    }

    /**
     * Create a new post
     * @param $topic_id
     * @param $user_id
     * @param $title
     * @param $reply_msg
     * @return bool
     */
    public function create_post($topic_id, $user_id, $title, $reply_msg)
    {
        $query = "INSERT INTO Discussion_Posts (topic_id, user_id, post_title, content) VALUES(:topic_id, :user_id, :post_title, :reply_msg)";
        $params = [
            ':topic_id' => $topic_id,
            ':user_id' => $user_id,
            ':post_title' => $title,
            ':reply_msg' => $reply_msg
        ];
        if ($this->exec_query($query, $params) !== false) {
            return true;
        }
    }

    /**
     * Get a single post
     * @param $post_id
     * @return array
     */
    public function get_discussion($post_id){
        $initial_post = $this->exec_query("SELECT id, user_id, post_title, content, created FROM Discussion_Posts WHERE id = :p_id", [":p_id" => $post_id]);
        $replies = $this->exec_query("SELECT Post_Replies.user_id, Post_Replies.content, Post_Replies.created, Users.username FROM Post_Replies INNER JOIN Users ON Post_Replies.user_id = Users.id WHERE Post_Replies.post_id = :p_id", [":p_id" => $post_id]);
        if ($initial_post && $replies) {
            return [$initial_post, $replies];
        }
    }
}
