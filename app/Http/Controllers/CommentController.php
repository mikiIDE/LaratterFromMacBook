<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tweet;
use Illuminate\Http\Request;
// ⬇️４行追加(資料3.31)
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Services\CommentService;
use Illuminate\Support\Facades\Gate;

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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    // 🔽 引数に Tweet を入力する
    public function create(Tweet $tweet)
    {
        // ⬇️編集(資料3.31)
        Gate::authorize('create', Comment::class);
        return view('tweets.comments.create', compact('tweet'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // 🔽 引数に Tweet を追加する
    // ⬇️編集(資料3.31)
    public function store(StoreCommentRequest $request, Tweet $tweet)
    {
        Gate::authorize('create', Comment::class);
        $this->commentService->createComment($request, $tweet);
        return redirect()->route('tweets.show', $tweet);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet, Comment $comment)
    {
        return view('tweets.comments.show', compact('tweet', 'comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet, Comment $comment)
    {
        return view('tweets.comments.edit', compact('tweet', 'comment'));
    }


    /**
     * Update the specified resource in storage.
     */
    // ⬇️編集(資料3.31)
    public function update(UpdateCommentRequest $request, Tweet $tweet, Comment $comment)
    {
        Gate::authorize('update', $comment);
        $this->commentService->updateComment($request, $comment);
        return redirect()->route('tweets.comments.show', [$tweet, $comment]);
    }

    /**
     * Remove the specified resource from storage.
     */
    // ⬇️編集(資料3.31)
    public function destroy(Tweet $tweet, Comment $comment)
    {
        Gate::authorize('delete', $comment);
        $this->commentService->deleteComment($comment);
        return redirect()->route('tweets.show', $tweet);
    }
}
