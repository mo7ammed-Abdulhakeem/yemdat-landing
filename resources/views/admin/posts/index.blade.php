<x-admin-layout>
    <x-slot name="header">
        News and Posts Management
    </x-slot>

    <div>
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                {{-- Breadcrumbs --}}
            </div>
            <a href="{{ route('admin.posts.create') }}" class="bg-yemdat-brown hover:bg-yemdat-gold text-white font-bold py-2 px-4 rounded shadow transition">
                + Add New Post
            </a>
        </div>

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($posts as $post)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($post->image)
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $post->image) }}" alt="">
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($post->title_en, 40) }}</div>
                                                    <div class="text-xs text-gray-500">{{ $post->created_at->format('M d, Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 capitalize">
                                                {{ $post->type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-800 border border-blue-200">
                                                {{ $post->author->name ?? 'System' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                             @if($post->is_published)
                                                <span class="text-green-600 font-semibold text-sm">Published</span>
                                            @else
                                                <span class="text-yellow-600 font-semibold text-sm">Draft</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($post->created_by === auth()->id() || auth()->user()->isSuperAdmin())
                                                <form action="{{ route('admin.posts.toggle_featured', $post) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yemdat-gold {{ $post->is_featured ? 'bg-blue-500' : 'bg-gray-200' }}">
                                                        <span class="sr-only">Toggle featured status</span>
                                                        <span class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform {{ $post->is_featured ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-xs italic">No Access</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($post->created_by === auth()->id() || auth()->user()->isSuperAdmin())
                                                <form action="{{ route('admin.posts.toggle', $post) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yemdat-gold {{ $post->is_published ? 'bg-green-500' : 'bg-gray-200' }}">
                                                        <span class="sr-only">Toggle publish status</span>
                                                        <span class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform {{ $post->is_published ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-xs italic">No Access</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            @if($post->created_by === auth()->id() || auth()->user()->isSuperAdmin())
                                                <a href="{{ route('admin.posts.edit', $post) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-xs italic">View Only (Frontend)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
    </div>
</x-admin-layout>
