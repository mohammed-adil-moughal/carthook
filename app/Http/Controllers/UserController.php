<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Cache;
use App\User;

use DateTime;
use DateInterval;

class UserController extends Controller
{

    private function resetCache($tableName)
    {
        $dateTimeObj= new DateTime();
        $dateTimeObj->add(new DateInterval("PT1H"));
        $cache = new Cache;
        $cache->table_name = $tableName;
        $cache->time_to_live = $dateTimeObj;
        $cache->save();

        return $cache;
    }

    /**
     * @return void
     */
    public function getUsers()
    {
        $cache = Cache::where('table_name', 'user')->first();
        if (!$cache) {
            $cache = $this->resetCache('user');
        }

        $currentDateTime= new DateTime();

        if ($cache->time_to_live > $currentDateTime) {
            $users = User::all();
            $users->truncate();
        }

        $res = Http::get('http://jsonplaceholder.typicode.com/users');
        $usersJson = $res->json();
        foreach ($usersJson as $user) {
var_dump($user);die();
        }
        // echo $res->getStatusCode(); // 200
        // echo $res->getBody();


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
