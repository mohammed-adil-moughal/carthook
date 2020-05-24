<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

use App\Post;
use App\Comment;

class PostController extends Controller
{

    private function fetchAndPopulateComments($id)
    {
        $res = Http::get('http://jsonplaceholder.typicode.com/post/'.$id.'/comments');
        $commentsJson = $res->json();
        foreach ($commentsJson as $commentRec) {
            $comment = new Comment;
            $comment->fill($commentRec);
            $comment->save();
        }

        $this->resetCache('comments'.$id);
    }
    /**
     * @param $id
     * @return void
     */
    public function getPostComments($id)
    {
        $cacheExpired = $this->checkExpCache('comments'.$id);

        if ($cacheExpired) {
            Comment::truncate();
            $this->fetchAndPopulateComments($id);
        }

        $comments = Comment::where('postId', $id)->get();

        if (!$comments || $comments->count() == 0) {
            $this->fetchAndPopulateComments($id);
            $comments = Comment::where('postId', $id)->get();
        }

        return response($comments)->header('Content-Type', 'application/json');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function getPosts(Request $request)
    {
        $cacheExpired = $this->checkExpCache('posts');
        if (!$cacheExpired) {
            Post::truncate();
            $this->fetchAndPopulatePosts();
        }

        $titleQuery = $request->query('title');
        if ($titleQuery) {
            $posts = Post::firstWhere('title', 'LIKE', "%$titleQuery%");
            if (!$posts) {
                return response()->json(['error' => 'Not found'], 404);
            }
            return response($posts)->header('Content-Type', 'application/json');
        }

        $posts = Post::all();
        return response($posts)->header('Content-Type', 'application/json');
    }
}
