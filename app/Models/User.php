<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class User extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'user';

    public function getUserInfoByAccount($account) {
        $user_info = null;

        if ($account) {
            $bindings = [$account];
            $query = DB::select('SELECT * FROM user WHERE account = ?', $bindings);
            
            if (isset($query[0])) {
                $user_info = array(
                    'id'            => $query[0]->id,
                    'name'          => $query[0]->name,
                    'account'       => $query[0]->account,
                    'password'      => $query[0]->password,
                    'email'         => $query[0]->email,
                    'phone'         => $query[0]->phone,
                    'store_name'    => $query[0]->store_name,
                    'store_address' => $query[0]->store_address,
                    'store_type'    => $query[0]->store_type,
                    'reply_style'   => $query[0]->reply_style,
                    'date_added'    => $query[0]->date_added,
                    'date_modified' => $query[0]->date_modified
                );
            }
        }

        return $user_info;
    }

    public function insertUserInfo($user_info) {
        $insert_st = null;

        if (isset($user_info['account']) && $user_info['account'] && isset($user_info['password']) && $user_info['password'] && isset($user_info['email']) && $user_info['email']) {
            $bindings = [$user_info['name'], $user_info['account'], $user_info['password'], $user_info['email'], $user_info['phone'], $user_info['store_name'], $user_info['store_address'], $user_info['store_type'], $user_info['reply_style']];
            $insert_st = DB::insert('INSERT user SET name = ?, account = ?, password = ?, email = ?, phone = ?, store_name = ?, store_address = ?, store_type = ?, reply_style = ?, date_added = NOW(), date_modified = NOW()', $bindings);
        }

        return $insert_st;
    }

    public function updateUserInfo($user_info) {
        $update_st = null;

        if (isset($user_info['user_id']) && $user_info['user_id']) {
            $bindings = [$user_info['user_name'], $user_info['email'], $user_info['phone'], $user_info['store_name'], $user_info['store_address'], $user_info['store_type'], $user_info['reply_style'], $user_info['user_id']];
            $update_st = DB::update('UPDATE user SET name = ?, email = ?, phone = ?, store_name = ?, store_address = ?, store_type = ?, reply_style = ?, date_modified = NOW() WHERE id = ?', $bindings);
        }

        return $update_st;
    }
}
