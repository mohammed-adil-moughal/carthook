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
        if ($cacheExpired) {
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
/**
 * Improvements
 * 1) Move all logic to a service and have controllers explicitly receive requests and dispatch responses
 * 2) Move caching to a separate cron  (maybe a cron server as well) and have it run on regular intervals
 * 3) Improve caching to more intelligent and not just truncate and insert
 * 4) Implement caching on routes Cloudflare and bust cache when a valid update happens(tied to intelligent caching)
 */
