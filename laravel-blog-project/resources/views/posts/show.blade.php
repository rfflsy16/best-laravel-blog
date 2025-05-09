@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-6">
        <div class="flex justify-between items-center">
            <a href="{{ route('posts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                &larr; Back to Posts
            </a>
            @can('update', $post)
                <div class="flex space-x-2">
                    <a href="{{ route('posts.edit', $post->slug) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-600 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Edit Post
                    </a>
                    <form action="{{ route('posts.destroy', $post->slug) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Delete Post
                        </button>
                    </form>
                </div>
            @endcan
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $post->title }}</h1>
                </div>
                <div class="text-right text-sm text-gray-500">
                    <p>{{ $post->view_count }} views</p>
                    <p>{{ $post->created_at->format('F j, Y') }}</p>
                </div>
            </div>

            <div class="mb-4 text-sm text-gray-500">
                <p>By: {{ $post->user->name }}</p>
            </div>

            <!-- Like button -->
            <div class="flex items-center mb-6">
                @auth
                    <button id="like-button" 
                            class="flex items-center gap-1 mr-4 px-3 py-1 rounded-full {{ $post->isLikedByUser() ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }} hover:bg-red-100 hover:text-red-600 transition-colors"
                            data-post-id="{{ $post->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                        <span id="likes-count">{{ $post->likesCount() }}</span>
                    </button>
                @else
                    <div class="flex items-center gap-1 mr-4 px-3 py-1 rounded-full bg-gray-100 text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ $post->likesCount() }}</span>
                    </div>
                @endauth
            </div>

            @if($post->featured_image)
                <div class="mb-6">
                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="rounded-lg w-full max-h-96 object-cover">
                </div>
            @endif

            <div class="prose max-w-none mb-8 text-gray-700">
                {!! $post->content !!}
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Comments ({{ $post->comments->whereNull('parent_id')->count() }})</h2>

            @auth
                <div class="mb-8">
                    <form action="{{ route('comments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Leave a comment</label>
                            <textarea name="content" id="content" rows="3" required
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Post Comment
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="mb-8 bg-gray-100 p-4 rounded">
                    <p class="text-gray-600">Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> to leave a comment.</p>
                </div>
            @endauth

            @if($post->comments->whereNull('parent_id')->count() > 0)
                @foreach($post->comments->whereNull('parent_id') as $comment)
                    <div class="border-b border-gray-200 pb-6 mb-6" id="comment-{{ $comment->id }}">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center">
                                <div class="font-medium text-gray-900">{{ $comment->user->name }}</div>
                                <span class="text-sm text-gray-500 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            @can('delete', $comment)
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-xs hover:text-red-700">Delete</button>
                                </form>
                            @endcan
                        </div>
                        <div class="text-gray-800 mb-3">
                            {{ $comment->content }}
                        </div>
                        
                        @auth
                            <div class="mb-4">
                                <button onclick="toggleReplyForm({{ $comment->id }})" class="text-sm text-blue-600 hover:text-blue-800">Reply</button>
                                
                                <div id="reply-form-{{ $comment->id }}" class="hidden mt-4">
                                    <form action="{{ route('comments.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                        <div class="mb-2">
                                            <textarea name="content" rows="2" required
                                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                Reply
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endauth
                        
                        <!-- Replies -->
                        @if($comment->replies->count() > 0)
                            <div class="ml-8 pl-4 border-l-2 border-gray-200 mt-4 space-y-4">
                                @foreach($comment->replies as $reply)
                                    <div class="pt-2" id="comment-{{ $reply->id }}">
                                        <div class="flex justify-between items-start mb-1">
                                            <div class="flex items-center">
                                                <div class="font-medium text-gray-900">{{ $reply->user->name }}</div>
                                                <span class="text-sm text-gray-500 ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            @can('delete', $reply)
                                                <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reply?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 text-xs hover:text-red-700">Delete</button>
                                                </form>
                                            @endcan
                                        </div>
                                        <div class="text-gray-800">
                                            {{ $reply->content }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="text-gray-500 text-center py-4">
                    No comments yet. Be the first to comment!
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleReplyForm(commentId) {
        const replyForm = document.getElementById(`reply-form-${commentId}`);
        replyForm.classList.toggle('hidden');
    }
    
    // Like functionality
    document.addEventListener('DOMContentLoaded', function() {
        const likeButton = document.getElementById('like-button');
        
        if (likeButton) {
            likeButton.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                
                fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    const likesCount = document.getElementById('likes-count');
                    likesCount.textContent = data.count;
                    
                    if (data.liked) {
                        likeButton.classList.remove('bg-gray-100', 'text-gray-600');
                        likeButton.classList.add('bg-red-100', 'text-red-600');
                    } else {
                        likeButton.classList.remove('bg-red-100', 'text-red-600');
                        likeButton.classList.add('bg-gray-100', 'text-gray-600');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }
    });
</script>
@endpush
@endsection 