@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">All Posts</h1>
        @auth
            <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                Create New Post
            </a>
        @endauth
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar with sorting and search -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Sort By</h2>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('posts.index', ['sort' => 'created_at', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}" 
                               class="text-blue-600 hover:underline">
                                Newest First
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('posts.index', ['sort' => 'created_at', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}" 
                               class="text-blue-600 hover:underline">
                                Oldest First
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('posts.index', ['sort' => 'title', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}" 
                               class="text-blue-600 hover:underline">
                                Title (A-Z)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('posts.index', ['sort' => 'view_count', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}" 
                               class="text-blue-600 hover:underline">
                                Most Popular
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Search</h2>
                    <form action="{{ route('posts.search') }}" method="GET">
                        <div class="flex items-center">
                            <input type="text" name="q" value="{{ request('q') }}" 
                                   class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block w-full" 
                                   placeholder="Search posts...">
                            <button type="submit" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main content with posts -->
        <div class="lg:col-span-3 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($posts->count() > 0)
                @foreach($posts as $post)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">
                                        <a href="{{ route('posts.show', $post->slug) }}" class="text-gray-900 hover:text-blue-600">
                                            {{ $post->title }}
                                        </a>
                                    </h2>
                                </div>
                                <div class="text-right text-sm text-gray-500">
                                    <p>{{ $post->view_count }} views</p>
                                    <p>{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            @if($post->featured_image)
                                <div class="mb-4">
                                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="rounded-lg w-full h-64 object-cover">
                                </div>
                            @endif

                            <div class="mb-4 prose max-w-none text-gray-700">
                                {{ Str::limit(strip_tags($post->content), 200) }}
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="text-sm">
                                        <p class="text-gray-500">By: {{ $post->user->name }}</p>
                                    </div>
                                    @auth
                                        <button class="like-button flex items-center gap-1 text-gray-500 hover:text-red-600 transition-colors"
                                                data-post-id="{{ $post->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $post->isLikedByUser() ? 'text-red-600' : '' }}" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="likes-count">{{ $post->likesCount() }}</span>
                                        </button>
                                    @else
                                        <div class="flex items-center gap-1 text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                            </svg>
                                            <span>{{ $post->likesCount() }}</span>
                                        </div>
                                    @endauth
                                </div>
                                <a href="{{ route('posts.show', $post->slug) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-6">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 text-center">
                        <p class="text-gray-500">No posts found.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const likeButtons = document.querySelectorAll('.like-button');
        
        if (likeButtons.length) {
            likeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    const svg = this.querySelector('svg');
                    const likesCount = this.querySelector('.likes-count');
                    
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
                        // Update like count
                        likesCount.textContent = data.count;
                        
                        // Toggle like status
                        if (data.liked) {
                            svg.classList.add('text-red-600');
                        } else {
                            svg.classList.remove('text-red-600');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        }
    });
</script>
@endpush
@endsection 