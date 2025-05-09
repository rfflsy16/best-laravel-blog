<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'search']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::query()->where('is_published', true);
        
        // Apply sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        if (in_array($sortField, ['title', 'created_at', 'view_count'])) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }
        
        $posts = $query->with(['user'])->paginate(10);
        
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($data['title']);
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('posts', 'public');
            $data['featured_image'] = $path;
        }
        
        $post = Post::create($data);
        
        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Post berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->with(['user', 'comments' => function($query) {
                $query->whereNull('parent_id')
                    ->with(['user', 'replies.user']);
            }])
            ->firstOrFail();
            
        // Increment view count
        $post->increment('view_count');
        
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        
        // Check if user is authorized to edit this post
        $this->authorize('update', $post);
        
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        
        // Check if user is authorized to update this post
        $this->authorize('update', $post);
        
        $data = $request->validated();
        
        // Update slug if title was changed
        if ($request->has('title') && $post->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']);
        }
        
        // Handle featured image update
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            
            $path = $request->file('featured_image')->store('posts', 'public');
            $data['featured_image'] = $path;
        }
        
        $post->update($data);
        
        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Post berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        
        // Check if user is authorized to delete this post
        $this->authorize('delete', $post);
        
        // Delete featured image if exists
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        
        $post->delete();
        
        return redirect()->route('posts.index')
            ->with('success', 'Post berhasil dihapus!');
    }
    
    /**
     * Search for posts based on title.
     */
    public function search(Request $request)
    {
        $query = Post::query()->where('is_published', true);
        
        // Apply search term
        if ($request->has('q') && $request->q != '') {
            $query->where('title', 'like', '%' . $request->q . '%');
        }
        
        // Apply sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        if (in_array($sortField, ['title', 'created_at', 'view_count'])) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }
        
        $posts = $query->with(['user'])->paginate(10);
        
        return view('posts.search', compact('posts'));
    }
}
