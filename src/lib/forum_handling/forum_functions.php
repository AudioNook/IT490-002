<?php
require_once(__DIR__ . "/../functions.php");
function handle_forum($request)
{
    $table = '';
    switch ($request['type']) {
        case "topics":
            $table = 'Discussion_Topics';
            $topics = executeQuery("SELECT id, name, description FROM $table");
            if ($topics !== false) {
                return [
                    'type' => 'topics',
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Returning Topics',
                    'topics' => $topics
                ];
            }
            break;
        case "posts":
            $topic_id = $request['topic_id'];
            
            $posts = executeQuery("SELECT Discussion_Posts.id, Discussion_Posts.user_id, Discussion_Posts.post_title, Discussion_Posts.content, Discussion_Posts.created, Users.username FROM Discussion_Posts INNER JOIN Users ON Discussion_Posts.user_id = Users.id WHERE Discussion_Posts.topic_id = :t_id", [":t_id" => $topic_id]);
            //$posts = executeQuery("SELECT id, user_id, post_title, content, created FROM Discussion_Posts WHERE topic_id = :t_id", [":t_id" => $topic_id]);
            if ($posts !== false) {
                return [
                    'type' => 'posts',
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Returning Posts',
                    'posts' => $posts
                ];
            }
            break;

        case 'reply':
            $post_id = (int) $request['post_id'];
            $user_id = (int) $request['user_id'];
            $reply_msg = htmlspecialchars($request['reply_msg']);
            $query = "INSERT INTO Post_Replies (post_id, user_id, content) VALUES(:post_id, :user_id, :reply_msg)";
            $params = [
                ':post_id' => $post_id,
                ':user_id' => $user_id,
                ':reply_msg' => $reply_msg
            ];
            if (executeQuery($query, $params) !== false) {
                return [
                    'type' => 'reply',
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Replied to post'
                ];
            }
            break;
            case 'create_post':
                $topic_id = (int) $request['topic_id'];
                $user_id = (int) $request['user_id'];
                $title = htmlspecialchars($request['title']);
                $reply_msg = htmlspecialchars($request['reply_msg']);
                $query = "INSERT INTO Discussion_Posts (topic_id, user_id, post_title, content) VALUES(:topic_id, :user_id, :post_title, :reply_msg)";
                $params = [
                    ':topic_id' => $topic_id,
                    ':user_id' => $user_id,
                    ':post_title' => $title,
                    ':reply_msg' => $reply_msg
                ];
                if (executeQuery($query, $params) !== false) {
                    return [
                        'type' => 'create_post',
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Replied to post'
                    ];
                }
                break;

        case 'discussion':
            $post_id = $request['post_id'];
            $initial_post = executeQuery("SELECT id, user_id, post_title, content, created FROM Discussion_Posts WHERE id = :p_id", [":p_id" => $post_id]);
            $replies = executeQuery("SELECT Post_Replies.user_id, Post_Replies.content, Post_Replies.created, Users.username FROM Post_Replies INNER JOIN Users ON Post_Replies.user_id = Users.id WHERE Post_Replies.post_id = :p_id", [":p_id" => $post_id]);
           
            if ($initial_post !== false) {
                return [
                    'type' => 'discussion',
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Returned Initial Post',
                    'initial_post' => $initial_post,
                    'replies' => $replies ? $replies : 'None'
                ];
            }
            break;

        default:
            return [
                'type' => 'forum',
                'code' => 401,
                'status' => 'error',
                'message' => 'Unexpected Error'
            ];
    }
    return [
        'type' => $request['type'],
        'code' => 500,
        'status' => 'error',
        'message' => 'An error occurred while processing the request',
    ];
}