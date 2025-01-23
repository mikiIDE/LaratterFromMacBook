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
        // 🔽 追加(資料3.27)
        Gate::authorize('create', Tweet::class);
        // 🔽 追加
        return view('tweets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTweetRequest $request)
    {
        // 🔽 追加(資料3.27)
        Gate::authorize('create', Tweet::class);
        // バリデーションは削除
        $tweet = $this->tweetService->createTweet($request);
        return redirect()->route('tweets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        // 🔽 追加(資料3.27)
        Gate::authorize('view', $tweet);
        // $tweet->load('comments');
        return view('tweets.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet)
    {
        // 🔽 追加(資料3.27)
        Gate::authorize('update', $tweet);
        return view('tweets.edit', compact('tweet'));
    }

    /**
     * Update the specified resource in storage.
     */
    // 🔽 編集
    public function update(UpdateTweetRequest $request, Tweet $tweet)
    {
        // 🔽 追加(資料3.27)
        Gate::authorize('update', $tweet);
        // バリデーションは削除
        $updatedTweet = $this->tweetService->updateTweet($request, $tweet);
        return redirect()->route('tweets.show', $tweet);
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
        return redirect()->route('tweets.index');
    }
}
