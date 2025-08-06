<?php

namespace App\Http\Controllers;

use App\Events\NewPostEvent;
use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Admin;
use App\Models\Post;
use App\Notifications\NewPostNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return view('dashboard.posts.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $post = Post::create($request->validated());
        $admins = Admin::all();
        Notification::send($admins, new NewPostNotification($post->admin));

        broadcast(new NewPostEvent($post->admin));
        toastr()->success('Post created successfully');
        return redirect()->route('admin.posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request,Post $post)
    {
        $post = $post->update($request->validated());
        toastr()->success('Post updated successfully');
        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id)->delete();
        toastr()->success('post deleted successfully');
        return back();
    }
}
