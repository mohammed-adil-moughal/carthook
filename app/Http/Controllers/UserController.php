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

    private function fetchAndPopulateUsers()
    {
        $res = Http::get('http://jsonplaceholder.typicode.com/users');
        $usersJson = $res->json();
        foreach ($usersJson as $userRec) {
            $user = new User;
            $user->fill($userRec);
            $user->save();
        }

        $this->resetCache('user');
    }

    private function checkExpCache($tableName)
    {
        $cache = Cache::where('table_name', $tableName)->first();
        if (!$cache) {
            $cache = $this->resetCache($tableName);
        }

        $currentDateTime= new DateTime();

        return $cache->time_to_live < $currentDateTime ? false : true;
    }

    /**
     * @return void
     */
    public function getUsers()
    {
        $cacheExpired = $this->checkExpCache('user');

        if ($cacheExpired) {
            User::truncate();
            $this->fetchAndPopulateUsers();
        }

        $users = User::all();

        return response($users)->header('Content-Type', 'application/json');
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
