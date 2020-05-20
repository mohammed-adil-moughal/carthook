<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
   /**
     * @param $id
     * @return void
     */
    public function getPostComments($id)
    {
        var_dump("getPostComments".$id);die();
    }
}
