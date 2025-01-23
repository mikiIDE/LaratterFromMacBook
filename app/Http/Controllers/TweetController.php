<?php

namespace App\Http\Controllers;
// 🔽 追加(いつの間にか)
use App\Http\Requests\StoreTweetRequest;
// 🔽 追加(資料3.26)
use App\Http\Requests\UpdateTweetRequest;
use App\Models\Tweet;
use Illuminate\Http\Request;
// 🔽 追加
use App\Services\TweetService;

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
        // 🔽 編集
        $tweets = $this->tweetService->allTweets();
        return view('tweets.index', compact('tweets'));
        // 🔽 liked のデータも合わせて取得するよう修正
        // $tweets = Tweet::with(['user', 'liked'])->latest()->get();
        // return view('tweets.index', compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 🔽 追加
        return view('tweets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTweetRequest $request)
    {
        // バリデーションは削除
        $tweet = $this->tweetService->createTweet($request);
        return redirect()->route('tweets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        $tweet->load('comments');
        return view('tweets.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet)
    {
        return view('tweets.edit', compact('tweet'));
    }

    /**
     * Update the specified resource in storage.
     */
    // 🔽 編集
    public function update(UpdateTweetRequest $request, Tweet $tweet)
    {
        // バリデーションは削除
        $updatedTweet = $this->tweetService->updateTweet($request, $tweet);
        return redirect()->route('tweets.show', $tweet);
        // 🔽 編集
        // $this->tweetService->updateTweet($request, $tweet);
        // return redirect()->route('tweets.show', $tweet);
        // $tweet->update($request->only('tweet'));
        // return redirect()->route('tweets.show', $tweet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        // 🔽 編集
        $this->tweetService->deleteTweet($tweet);
        return redirect()->route('tweets.index');
        // $tweet->delete();
        // return redirect()->route('tweets.index');
    }
}
