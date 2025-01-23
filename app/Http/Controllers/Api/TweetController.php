<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// 🔽 追加(いつの間にか)
use App\Http\Requests\StoreTweetRequest;
// 🔽 追加(資料3.26)
use App\Http\Requests\UpdateTweetRequest;
use Illuminate\Http\Request;
// 🔽 追加
use App\Models\Tweet;
// 🔽 追加
use App\Services\TweetService;
// 🔽 追加(資料3.27)
use Illuminate\Support\Facades\Gate;


class TweetController extends Controller
{
    // 🔽 追加
    protected $tweetService;

    // 🔽 追加
    public function __construct(TweetService $tweetService)
    {
        $this->tweetService = $tweetService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 🔽 追加(資料3.27)
        Gate::authorize('viewAny', Tweet::class);
        // 🔽 編集
        $tweets = $this->tweetService->allTweets();
        return response()->json($tweets);
    }

    /**
     * Store a newly created resource in storage.
     */
    // 🔽 編集
    public function store(StoreTweetRequest $request)
    {
        // 🔽 追加(資料3.27)
        Gate::authorize('create', Tweet::class);
        // バリデーションは削除
        $tweet = $this->tweetService->createTweet($request);
        return response()->json($tweet, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        // 🔽 追加(資料3.27)
        Gate::authorize('view', $tweet);
        return response()->json($tweet);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTweetRequest $request, Tweet $tweet)
    {
        // 🔽 追加(資料3.27)
        Gate::authorize('update', $tweet);
        // 🔽 編集 + バリデーションは削除
        $updatedTweet = $this->tweetService->updateTweet($request, $tweet);
        return response()->json($updatedTweet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        // 🔽 追加(資料3.27)
        Gate::authorize('delete', $tweet);
        // 🔽 編集
        $this->tweetService->deleteTweet($tweet);
        return response()->json(['message' => 'Tweet deleted successfully']);
    }
}
