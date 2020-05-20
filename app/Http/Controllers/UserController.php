<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * @return void
     */
    public function getUsers()
    {
        var_dump("here");die();
    }

    /**
     * @param $id
     * @return void
     */
    public function getUser($id)
    {
        var_dump("getUser".$id);die();
    }

    /**
     * @param $id
     * @return void
     */
    public function getUserPosts($id)
    {
        var_dump("getUserPosts".$id);die();
    }

    /**
     * @param $postId
     * @param $id
     * @return void
     */
    public function getUserPost($id, $postId)
    {
        var_dump("getUserPostsId".$id.$postId);die();
    }
}
