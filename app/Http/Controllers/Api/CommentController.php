<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// ⬇️４行追加(資料3.31)
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Services\CommentService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
// 🔽 2行追加
use App\Models\Comment;
use App\Models\Tweet;

class CommentController extends Controller
{
    // ⬇️追加(資料3.31)
    protected $commentService;
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Tweet $tweet)
    {
        // 編集(資料3.31)
        Gate::authorize('viewAny', Comment::class);
        $comments = $this->commentService->tweetComments($tweet);
        return response()->json($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    // ⬇️編集(資料3.31)
    public function store(StoreCommentRequest $request, Tweet $tweet)
    {
        Gate::authorize('create', Comment::class);
        $comment = $this->commentService->createComment($request, $tweet);
        return response()->json($comment, 201);
    }

    /**
     * Display the specified resource.
     */
    // ⬇️編集(資料3.31)
    public function show(Tweet $tweet, Comment $comment)
    {
      Gate::authorize('view', $comment);
      return response()->json($comment->load(['user', 'tweet']));
    }

    /**
     * Update the specified resource in storage.
     */
    // ⬇️編集(資料3.29)
    public function update(UpdateCommentRequest $request, Tweet $tweet, Comment $comment)
    {
      Gate::authorize('update', $comment);
      $comment = $this->commentService->updateComment($request, $comment);
      return response()->json($comment);
    }
  

    /**
     * Remove the specified resource from storage.
     */
    // ⬇️編集(資料3.31)
    public function destroy(Tweet $tweet, Comment $comment)
    {
      Gate::authorize('delete', $comment);
      $this->commentService->deleteComment($comment);
      return response()->json(['message' => 'Comment deleted successfully']);
    }
}
