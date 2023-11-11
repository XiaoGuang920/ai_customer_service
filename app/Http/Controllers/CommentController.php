<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Comment;

use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index() {
        return view('comment');
    }

    public function getComment(Request $request, User $user, Comment $comment) {
        $resp = array(
            'comment_list'           => null,
            'total_comment_quantity' => 0,
            'already_reply_quantity' => 0,
            'not_reply_quantity'     => 0,
            'st'                     => false,
            'msg'                    => ''
        );

        if ($request->session()->get('user_account')) {
            $account = $request->session()->get('user_account');
        } else {
            $account = '';
        }

        if ($account) {
            $user_info = $user->getUserInfoByAccount($account);
            
            $fields = '{ "store_name": "' . $user_info['store_name'] . '" }';
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => '127.0.0.1:3000/getData',
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

            $comment_list = json_decode($response, true);
            foreach ($comment_list as $comment_child) {
                $exists_st = $comment->getCommentExists($comment_child['link_id']);

                if (!$exists_st) {
                    $comment_child_info = array(
                        'id'             => $comment_child['link_id'],
                        'score'          => $comment_child['star'],
                        'content'        => $comment_child['context'],
                        'user_name'      => $comment_child['username'],
                        'response'       => '',
                        'date_commented' => $comment_child['time'],
                        'user_id'        => $request->session()->get('user_id')
                    );
                    $comment->insertComment($comment_child_info);
                }
            }

            $resp['comment_list'] = $comment->getCommentList($request->session()->get('user_id'));
            $resp['total_comment_quantity'] = $comment->getCommentQuantity($request->session()->get('user_id'));
            $resp['already_reply_quantity'] = $comment->getCommentAlreradyRespQuantity($request->session()->get('user_id'));
            $resp['not_reply_quantity'] = $comment->getCommentNotRespQuantity($request->session()->get('user_id'));

            $resp['st'] = true;
            $resp['msg'] = '';
        } else {
            $resp['st'] = false;
            $resp['msg'] = 'require parameters error';
        }

        return response()->json($resp);
    }

    public function getCommentInfo(Request $request, User $user, Comment $comment) {
        $resp = array(
            'comment_info' => null,
            'st'           => false,
            'msg'          => ''
        ); 

        if ($request->session()->get('user_account')) {
            $account = $request->session()->get('user_account');
        } else {
            $account = '';
        }
        if (isset($request->id) && $request->id) {
            $id = $request->id;
        } else {
            $id = '';
        }

        if ($account && $id) {
            $select_info = array(
                'id' => $id
            );
            
            $comment_info = $comment->getCommentInfo($select_info);

            if ($comment_info['response'] == '') {
                $user_info = $user->getUserInfoByAccount($account);

                $reply_info = array(
                    'store_type'      => $user_info['store_type'],
                    'reply_style'      => $user_info['reply_style'],
                    'store_name'      => $user_info['store_name'],
                    'comment_content' => $comment_info['content']
                );
                $reply = $comment->getCommentResponse($reply_info);

                $update_info = array(
                    'id'    => $comment_info['id'],
                    'reply' => $reply
                );
                $comment->updateCommentResponse($update_info);

                $comment_info['response'] = $reply;
            }

            $resp['comment_info'] = $comment_info;

            $resp['st'] = true;
            $resp['msg'] = '';
        } else {
            $resp['st'] = false;
            $resp['msg'] = 'require parameters error';
        }

        return response()->json($resp);
    }
}
