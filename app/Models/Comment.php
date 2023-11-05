<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'comment';

    function getCommentExists($id) {
        $exists_st = null;

        if ($id) {
            $exists_st = DB::table('comment')->where('id', $id)->exists();
        }
        
        return $exists_st;
    }

    function getCommentQuantity($user_id) {
        $quantity = DB::table('comment')->where('user_id', $user_id)->count();
        return $quantity;
    }

    function getCommentNotRespQuantity($user_id) {
        $quantity = DB::table('comment')->where('response', '')->where('user_id', $user_id)->count();
        return $quantity;
    }

    function getCommentAlreradyRespQuantity($user_id) {
        $quantity = DB::table('comment')->where('response', '!=', '')->where('user_id', $user_id)->count();
        return $quantity;
    }

    function getCommentAvg($user_id) {
        $comment_avg = null;

        if ($user_id) {
            $comment_avg = DB::table('comment')->where('user_id', $user_id)->avg('score');
        }

        return $comment_avg;
    }

    function insertComment($comment_info) {
        $insert_st = null;

        if ($comment_info['id'] && $comment_info['content'] && $comment_info['user_name'] && $comment_info['date_commented'] && $comment_info['user_id']) {
            $insert_info = array(
                'id'             => $comment_info['id'],
                'score'          => $comment_info['score'],
                'content'        => $comment_info['content'],
                'response'       => $comment_info['response'],
                'user_name'      => $comment_info['user_name'],
                'date_commented' => $comment_info['date_commented'],
                'date_added'     => NOW(),
                'date_modified'  => NOW(),
                'user_id'        => $comment_info['user_id']
            );

            $insert_st = DB::table('comment')->insert($insert_info);
        }

        return $insert_st;
    }

    function getCommentList($user_id) {
        $comment_list = [];

        $query = DB::table('comment')->where('user_id', $user_id)->get();
        foreach ($query as $comment_child) {
            $comment = array(
                'id'             => $comment_child->id,
                'user_name'      => $comment_child->user_name,
                'content'        => $comment_child->content,
                'date_commented' => $comment_child->date_commented
            );

            $comment_list[] = $comment;
        }

        return $comment_list;
    }

    function getCommentResponse($reply_info) {
        $comment_resp = null;

        if ($reply_info['store_type'] && $reply_info['reply_style'] && $reply_info['store_name'] && $reply_info['comment_content']) {
            $fields = '{ "food_type": "' . $reply_info['store_type'] . '", "reply_type": "' . $reply_info['reply_style'] . '", "shop_name": "' . $reply_info['store_name'] . '", "comment": "' . $reply_info['comment_content'] . '" }';
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => '127.0.0.1:3000/reply',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $fields,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            $reply_content = json_decode($response, true);
            $comment_resp = $reply_content['reply'];
        }
        
        return $comment_resp;
    }

    function updateCommentResponse($comment_info) {
        $update_st = null;

        if ($comment_info['id'] && $comment_info['reply']) {
            $update_info = array(
                'response' => $comment_info['reply']
            );
            $update_st = DB::table('comment')->where('id', $comment_info['id'])->update($update_info);
        }

        return $update_st;
    }

    function getCommentInfo($select_info) {
        $comment_info = null;

        if ($select_info['id']) {
            $query = DB::table('comment')->where('id', $select_info['id'])->get();

            $comment_info = array(
                'id'             => $query[0]->id,
                'score'          => $query[0]->score,
                'content'        => $query[0]->content,
                'response'       => $query[0]->response,
                'user_name'      => $query[0]->user_name,
                'date_commented' => $query[0]->date_commented
            );
        }

        return $comment_info;
    }
}
