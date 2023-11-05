<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Comment;

use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index() {
        return view('home');
    }

    public function getUserContent(Request $request, Comment $comment) {
        $resp = array(
            'user_name'  => null,
            'store_name' => null,
            'st'         => false,
            'msg'        => ''
        );

        if ($request->session()->get('user_account')) {
            $model_user = new User();

            $user_info = $model_user->getUserInfoByAccount($request->session()->get('user_account'));
            if ($user_info['name']) {
                $resp['user_name'] = $user_info['name'];
            } else {
                $resp['user_name'] = '使用者';
            }
            if ($user_info['store_name']) {
                $resp['store_name'] = $user_info['store_name'];
            } else {
                $resp['store_name'] = '未知商家';
            }

            $resp['email'] = $user_info['email'];
            $resp['phone'] = $user_info['phone'];
            $resp['store_address'] = $user_info['store_address'];
            $resp['store_type'] = $user_info['store_type'];
            $resp['reply_style'] = $user_info['reply_style'];
            $resp['star'] = number_format($comment->getCommentAvg($request->session()->get('user_id')), 1);
            $resp['comment_len'] = $comment->getCommentQuantity($request->session()->get('user_id'));

            $resp['st'] = true;
            $resp['msg'] = '';
        } else {
            $resp['st'] = false;
            $resp['msg'] = 'have not user session';
        }

        return response()->json($resp);
    }

    public function updateStoreInfo(Request $request, User $user) {
        $resp = array(
            'st'  => false,
            'msg' => ''
        );

        if ($request->session()->get('user_id')) {
            $user_id = $request->session()->get('user_id');
        } else {
            $user_id = '';
        }
        if (isset($request->user_name) && $request->user_name) {
            $user_name = $request->user_name;
        } else {
            $user_name = '';
        }
        if (isset($request->email) && $request->email) {
            $email = $request->email;
        } else {
            $email = '';
        }
        if (isset($request->phone) && $request->phone) {
            $phone = $request->phone;
        } else {
            $phone = '';
        }
        if (isset($request->store_name) && $request->store_name) {
            $store_name = $request->store_name;
        } else {
            $store_name = '';
        }
        if (isset($request->store_address) && $request->store_address) {
            $store_address = $request->store_address;
        } else {
            $store_address = '';
        }
        if (isset($request->store_type) && $request->store_type) {
            $store_type = $request->store_type;
        } else {
            $store_type = '';
        }
        if (isset($request->reply_style) && $request->reply_style) {
            $reply_style = $request->reply_style;
        } else {
            $reply_style = '';
        }

        if ($user_id && $user_name && $email && $phone && $store_name && $store_address && $store_type && $reply_style) {
            $store_info = array(
                'user_id'       => $user_id,
                'user_name'     => $user_name,
                'email'         => $email,
                'phone'         => $phone,
                'store_name'    => $store_name,
                'store_address' => $store_address,
                'store_type'    => $store_type,
                'reply_style'   => $reply_style
            );
            
            $update_st = $user->updateUserInfo($store_info);
            if ($update_st) {
                $resp['st'] = true;
                $resp['msg'] = '';
            } else {
                $resp['st'] = false;
                $resp['msg'] = 'database error';
            }
        } else {
            $resp['st'] = false;
            $resp['msg'] = 'require parameters error';
        }

        return response()->json($resp);
    }
}
