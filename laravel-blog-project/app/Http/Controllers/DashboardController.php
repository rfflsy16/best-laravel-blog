<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $posts = $user->posts()->withCount('comments')->latest()->paginate(5);
        $comments = $user->comments()->with('post')->latest()->paginate(5);
        
        return view('dashboard.index', compact('user', 'posts', 'comments'));
    }
    
    /**
     * Display user's posts.
     */
    public function posts()
    {
        $user = Auth::user();
        $posts = $user->posts()->withCount('comments')->latest()->paginate(10);
        
        return view('dashboard.posts', compact('user', 'posts'));
    }
    
    /**
     * Display user's comments.
     */
    public function comments()
    {
        $user = Auth::user();
        $comments = $user->comments()->with('post')->latest()->paginate(10);
        
        return view('dashboard.comments', compact('user', 'comments'));
    }
}
