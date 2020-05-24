<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Cache;
use App\User;
use App\Post;

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


    private function fetchAndPopulatePosts()
    {
        $res = Http::get('http://jsonplaceholder.typicode.com/posts');
        $postJson = $res->json();
        foreach ($postJson as $postRec) {
            $post = new Post;
            $post->fill($postRec);
            $post->save();
        }

        $this->resetCache('posts');
    }

    /**
     * @param string $tableName
     * @return bool
     */
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
     * @param Request $request
     * @return void
     */
    public function getUsers(Request $request)
    {
        $cacheExpired = $this->checkExpCache('user');

        if ($cacheExpired) {
            User::truncate();
            $this->fetchAndPopulateUsers();
        }

        $emailQuery = $request->query('email');
        if ($emailQuery) {
            $users= User::firstWhere('email', $emailQuery);
            if (!$users) {
                return response()->json(['error' => 'Not found'], 404);
            }
            return response($users)->header('Content-Type', 'application/json');
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
        $cacheExpired = $this->checkExpCache('user');

        if ($cacheExpired) {
            User::truncate();
            $this->fetchAndPopulateUsers();
        }

        $users = User::find($id);

        if (!$users) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response($users)->header('Content-Type', 'application/json');
    }

    /**
     * @param $id
     * @return void
     */
    public function getUserPosts($id)
    {
        $cacheExpired = $this->checkExpCache('posts');
        if (!$cacheExpired) {
            Post::truncate();
            $this->fetchAndPopulatePosts();
        }

        $posts = Post::where('userId', $id)->get();
        return response($posts)->header('Content-Type', 'application/json');
    }

    /**
     * @param $postId
     * @param $id
     * @return void
     */
    public function getUserPost($id, $postId)
    {
        $cacheExpired = $this->checkExpCache('posts');
        if (!$cacheExpired) {
            Post::truncate();
            $this->fetchAndPopulatePosts();
        }

        $post = Post::find($postId);
        if (!$post || $post->userId !== $id) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response($post)->header('Content-Type', 'application/json');
    }
}
