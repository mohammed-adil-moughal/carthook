<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

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

        if (!$cacheExpired) {
            Comment::truncate();
            $this->fetchAndPopulateComments($id);
        }

        $comments = Comment::where('postId', $id)->get();
        return response($comments)->header('Content-Type', 'application/json');
    }
}
