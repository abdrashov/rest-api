<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PostRequest;
use App\Http\Resources\Api\V1\PostShowResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends JwtController
{
    /**
     * Constructor to set middleware for specific actions.
     */
    public function __construct()
    {
        $this->middleware('jwt.verify')
            ->only('store', 'update', 'destroy');
    }

    /**
     * Retrieve a paginated list of posts with optional filtering and user-related filtering.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\Api\V1\PostsResource
     */
    public function index(Request $request)
    {
        return \App\Http\Resources\Api\V1\PostsResource::collection(
            Post::filter($request->except('user', 'page'))
                ->withWhereHas('user', fn($q) => $q->filter($request->input('user', [])))
                ->paginate($request->input('paginate', 20))
        );
    }

    /**
     * Display a specific post.
     *
     * @param \App\Models\Post $post
     * @return \App\Http\Resources\Api\V1\PostShowResource
     */
    public function show(Post $post)
    {
        return PostShowResource::make($post);
    }

    /**
     * Create a new post.
     *
     * @param \App\Http\Requests\Api\V1\PostRequest $request
     * @return \App\Http\Resources\Api\V1\PostShowResource
     */
    public function store(PostRequest $request)
    {
        return PostShowResource::make(
            Auth::user()->posts()->create($request->validated())
        );
    }

    /**
     * Update an existing post.
     *
     * @param \App\Http\Requests\Api\V1\PostRequest $request
     * @param \App\Models\Post $post
     * @return \App\Http\Resources\Api\V1\PostShowResource
     */
    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->validated());

        return PostShowResource::make($post);
    }

    /**
     * Delete a specific post.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'message' => __('success.deleted', ['attribute' => 'Post'])
        ]);
    }
}
