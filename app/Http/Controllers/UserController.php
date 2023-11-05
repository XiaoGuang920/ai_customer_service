<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function loginIndex() {
        $header = app('App\Http\Controllers\HeaderController')->index();
        $footer = app('App\Http\Controllers\FooterController')->index();

        return view('login', [
            'header' => $header,
            'footer' => $footer
        ]);
    }

    public function login(Request $request) {
        $resp = array(
            'auth' => false,
            'st'   => false,
            'msg'  => ''
        );

        if (isset($request->account) && $request->account) {
            $account = $request->account;
        } else {
            $account = '';
        }
        if (isset($request->password) && $request->password) {
            $password = $request->password;
        } else {
            $password = '';
        }

        if ($account && $password) {
            $model_user = new User();
            $user_info = $model_user->getUserInfoByAccount($account);
            
            if (isset($user_info)) {
                if (password_verify($password, $user_info['password'])) {
                    session([
                        'user_id'      => $user_info['id'],
                        'user_name'    => $user_info['name'], 
                        'user_account' => $account
                    ]);

                    $resp['auth'] = true;
                    $resp['st'] = true;
                    $resp['msg'] = 'verification successful';
                } else {
                    $resp['auth'] = false;
                    $resp['st'] = true;
                    $resp['msg'] = 'account or password is wrong';
                }
            } else {
                $resp['auth'] = false;
                $resp['st'] = false;
                $resp['msg'] = 'no such user';
            }
        } else {
            $resp['auth'] = false;
            $resp['st'] = false;
            $resp['msg'] = 'require parameters error';
        }

        return response()->json($resp);
    }

    public function logout() {
        session()->forget('user_id');
        session()->forget('user_account');
        session()->forget('user_name');

        return redirect('/login');
    }

    public function signUpIndex() {
        $header = app('App\Http\Controllers\HeaderController')->index();
        $footer = app('App\Http\Controllers\FooterController')->index();

        return view('sign_up', [
            'header' => $header,
            'footer' => $footer
        ]);
    }

    public function getBindStore(Request $request) {
        $resp = array(
            'store_name'    => null,
            'store_address' => null,
            'st'            => false,
            'msg'           => ''
        );

        if (isset($request->store_name) && $request->store_name) {
            $store_name = $request->store_name;
        } else {
            $store_name = '';
        }

        if ($store_name) {
            $fields = '{ "store_name": "' . $store_name . '" }';
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => '127.0.0.1:3000/confirm',
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

            $store_info = json_decode($response, true);
            if ($store_info['statue'] == '200') {
                $resp['store_name'] = $store_info['name'];
                $resp['store_address'] = $store_info['address'];

                $resp['st'] = true;
                $resp['msg'] = '';
            } else {
                $resp['st'] = false;
                $resp['msg'] = 'have not that store';
            }
        } else {
            $resp['st'] = false;
            $resp['msg'] = 'require parameters error';
        }

        return response()->json($resp);
    }

    public function signUp(Request $request, User $user) {
        $resp = array(
            'st'  => false,
            'msg' => ''
        );

        if (isset($request->name) && $request->name) {
            $name = $request->name;
        } else {
            $name = '';
        }
        if (isset($request->account) && $request->account) {
            $account = $request->account;
        } else {
            $account = '';
        }
        if (isset($request->password) && $request->password) {
            $password = password_hash($request->password, PASSWORD_DEFAULT);
        } else {
            $password = '';
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

        if ($name && $account && $password && $email && $phone && $store_name && $store_address && $store_type) {
            $user_info = array(
                'name'          => $name,
                'account'       => $account,
                'password'      => $password,
                'email'         => $email,
                'phone'         => $phone,
                'store_name'    => $store_name,
                'store_address' => $store_address,
                'store_type'    => $store_type,
                'reply_style'   => '友善'
            );
            
            $insert_st = $user->insertUserInfo($user_info);
            if ($insert_st) {
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
