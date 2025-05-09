<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created comment.
     */
    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        
        $post = Post::findOrFail($data['post_id']);
        
        $comment = Comment::create($data);
        
        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Store a reply to a comment.
     */
    public function reply(StoreCommentRequest $request, Comment $comment)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['post_id'] = $comment->post_id;
        $data['parent_id'] = $comment->id;
        
        $reply = Comment::create($data);
        
        $post = Post::findOrFail($comment->post_id);
        
        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Balasan komentar berhasil ditambahkan!');
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
     * Update the specified comment.
     */
    public function update(StoreCommentRequest $request, Comment $comment)
    {
        // Check if user is authorized to update this comment
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $data = $request->validated();
        $comment->update(['content' => $data['content']]);
        
        $post = Post::findOrFail($comment->post_id);
        
        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Komentar berhasil diupdate!');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        // Check if user is authorized to delete this comment
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $post = Post::findOrFail($comment->post_id);
        $comment->delete();
        
        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Komentar berhasil dihapus!');
    }
}
