<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $posts = Post::get();

        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        //Because of the auth middleware is being applied to this route, no need
        //to worry about call id() on null. Can't even reach this method if not authenticated.
        $id = auth()->id();

        $this->validatePost();

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'body' => $request->body,
            'image' => $request->image?->store('post', 'public')
        ]);

        return response()->json(['message' => 'Post Created Successfully', 'post' => $post]);
    }

    /**
     * Display the specified resource.
    *
    * @param  \App\Models\Post  $post
    * @return \Illuminate\Http\Response
    */
    public function show(Post $post)
    {
        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Post  $post
    * @return \Illuminate\Http\Response
    */

    /**
     * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Post  $post
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Post $post)
    {
        // $this->authorize('update-post', $post);
        //* Athother way to do so:
        // Gate::authorize('update', $post);

        $this->validatePost();

        if($request->has('image')){
            $this->storeImage($post);
            $post->update(['image' => 'post/' . $request->image->hashName()]);
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return response()->json(['message' => 'Post Edited Successfully']);
    }

    /**
     * Remove the specified resource from storage.
    *
    * @param  \App\Models\Post  $post
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, Post $post)
    {
        // $this->authorize('delete-post', $post);

        $post->delete();

        return response()->json([
            'message' => 'Post Successfully Deleted',
            'post' => $post
        ]);
    }

    private function validatePost()
    {
        request()->validate([
            'title' => ['required', 'min:10'],
            'body' => ['required', 'min:10'],
            'image' => ['sometimes','image'],
        ]);
    }

    private function storeImage($post){
        request()->image->store('post', 'public');
    }
}
