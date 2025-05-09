@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Post</h1>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ route('posts.update', $post->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required 
                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-gray-800">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" id="content" rows="10" required
                              class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-gray-800">{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image</label>
                    @if($post->featured_image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($post->featured_image) }}" alt="Current image" class="w-64 h-auto rounded">
                            <p class="text-sm text-gray-500 mt-1">Current image</p>
                        </div>
                    @endif
                    <input type="file" name="featured_image" id="featured_image" 
                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <p class="text-sm text-gray-500 mt-1">Leave empty to keep the current image</p>
                    @error('featured_image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="is_published" class="flex items-center">
                        <input type="checkbox" name="is_published" id="is_published" value="1"
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                               {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Published</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('posts.show', $post->slug) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Update Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 