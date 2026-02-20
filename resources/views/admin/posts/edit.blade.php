<x-admin-layout>
    <x-slot name="header">
        Edit Post / News
    </x-slot>

    <!-- Include Quill Styles -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <div>
        <div class="mb-6">
            <a href="{{ route('admin.posts.index') }}" class="text-gray-600 hover:text-yemdat-brown flex items-center gap-1">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Posts
            </a>
        </div>

            <!-- Validation Errors Display -->
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">Something went wrong.</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                <div class="p-8 bg-white border-b border-gray-200">
                    <form id="editPostForm" action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <h3 class="text-lg font-medium text-yemdat-brown mb-4 border-b pb-2">Post Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Title EN -->
                            <div>
                                <label for="title_en" class="block text-sm font-medium text-gray-700">Title (English) <span class="text-red-500">*</span></label>
                                <input type="text" name="title_en" id="title_en" value="{{ old('title_en', $post->title_en) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                                @error('title_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Title AR -->
                            <div dir="rtl">
                                <label for="title_ar" class="block text-sm font-medium text-gray-700">العنوان (Arabic) <span class="text-red-500">*</span></label>
                                <input type="text" name="title_ar" id="title_ar" value="{{ old('title_ar', $post->title_ar) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                                @error('title_ar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Post Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Post Type <span class="text-red-500">*</span></label>
                                <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold" required>
                                    <option value="article" {{ old('type', $post->type) == 'article' ? 'selected' : '' }}>Article</option>
                                    <option value="announcement" {{ old('type', $post->type) == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                    <option value="update" {{ old('type', $post->type) == 'update' ? 'selected' : '' }}>Update</option>
                                </select>
                                @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Tags -->
                            <div>
                                <label for="tags" class="block text-sm font-medium text-gray-700">Tags (Comma separated)</label>
                                @php
                                    $tagsVal = $post->tags;
                                    if(is_string($tagsVal)) {
                                        $tagsVal = json_decode($tagsVal, true);
                                    }
                                @endphp
                                <input type="text" name="tags" id="tags" value="{{ old('tags', $tagsVal && is_array($tagsVal) ? implode(', ', $tagsVal) : '') }}" placeholder="e.g. SEO, Medicine, News" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50">
                                @error('tags') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Image -->
                            <div class="col-span-2">
                                <label for="image" class="block text-sm font-medium text-gray-700">Cover Image</label>
                                 @if($post->image)
                                    <div class="mt-2 mb-4">
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="Current Image" class="h-32 rounded object-cover">
                                        <p class="text-sm text-gray-500 mt-1">Current Image. Upload a new one below to replace it.</p>
                                    </div>
                                @endif
                                <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yemdat-beige file:text-yemdat-brown hover:file:bg-yemdat-gold hover:file:text-white transition">
                                <p class="text-xs text-gray-500 mt-1">Max size: 2MB.</p>
                                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Content English -->
                        <div class="mb-8">
                            <label for="content_en" class="block text-sm font-medium text-gray-700 mb-2">Content (English) <span class="text-red-500">*</span></label>
                            <div id="editor-container-en" class="h-64 bg-white rounded-md"></div>
                            <input type="hidden" name="content_en" id="content_en" value="{{ old('content_en', $post->content_en) }}">
                            @error('content_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Content Arabic -->
                        <div class="mb-8" dir="rtl">
                            <label for="content_ar" class="block text-sm font-medium text-gray-700 mb-2">المحتوى (Arabic) <span class="text-red-500">*</span></label>
                            <div id="editor-container-ar" class="h-64 bg-white rounded-md"></div>
                            <input type="hidden" name="content_ar" id="content_ar" value="{{ old('content_ar', $post->content_ar) }}">
                            @error('content_ar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Published Toggle -->
                        <div class="flex items-center mb-6">
                            <input type="checkbox" name="is_published" id="is_published" class="h-4 w-4 text-yemdat-gold focus:ring-yemdat-brown border-gray-300 rounded" {{ $post->is_published ? 'checked' : '' }}>
                            <label for="is_published" class="ml-2 block text-sm text-gray-900">
                                Publish immediately
                            </label>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Cancel</a>
                            <button type="submit" class="px-6 py-2 bg-yemdat-brown text-white font-bold rounded-md hover:bg-yemdat-gold transition shadow-lg">
                                Update Post
                            </button>
                        </div>
                    </form>
                </div>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var toolbarOptions = [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'align': [] }],
            [{ 'direction': 'rtl' }],     // text direction
            ['link', 'clean']
        ];

        // Init English Editor
        var quillEn = new Quill('#editor-container-en', {
            theme: 'snow',
            placeholder: 'Post content in English...',
            modules: { toolbar: toolbarOptions }
        });
        // Load old data if exists
        var oldContentEn = document.getElementById('content_en').value;
        if (oldContentEn) {
            quillEn.root.innerHTML = oldContentEn;
        }

        // Init Arabic Editor
        var quillAr = new Quill('#editor-container-ar', {
            theme: 'snow',
            placeholder: 'محتوى المنشور بالعربية...',
            modules: { toolbar: toolbarOptions }
        });
        // Load old data if exists
        var oldContentAr = document.getElementById('content_ar').value;
        if (oldContentAr) {
            quillAr.root.innerHTML = oldContentAr;
        }

        // Sync Quill content to hidden input
        var form = document.getElementById('editPostForm');
        form.onsubmit = function() {
            var contentEn = document.querySelector('input[name=content_en]');
            contentEn.value = quillEn.root.innerHTML;
            
            var contentAr = document.querySelector('input[name=content_ar]');
            contentAr.value = quillAr.root.innerHTML;
        };
    </script>
</x-admin-layout>
