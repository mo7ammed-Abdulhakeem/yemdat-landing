<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumbs -->
            <div class="max-w-4xl mx-auto mb-8 relative z-50">
                <nav class="flex mb-4 relative z-50" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 rtl:space-x-reverse relative z-50">
                        <li class="inline-flex items-center relative z-50">
                            <a href="{{ route('home') }}" class="text-sm font-medium text-gray-500 hover:text-yemdat-brown transition cursor-pointer relative z-50">
                                {{ app()->getLocale() == 'ar' ? 'الرئيسية' : 'Home' }}
                            </a>
                        </li>
                        <li class="relative z-50">
                            <div class="flex items-center relative z-50">
                                <svg class="w-3 h-3 text-gray-400 mx-1 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('news') }}" class="text-sm font-medium text-gray-500 hover:text-yemdat-brown transition ml-1 md:ml-2 cursor-pointer relative z-50">
                                    {{ __('nav.news') }}
                                </a>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <article class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                <!-- Post Cover Image -->
                @if($post->image)
                    <div class="w-full h-80 sm:h-96 md:h-[500px] relative border-b border-gray-100" style="border-bottom: 1px solid #e5e7eb;">
                         <!-- Inner ring for bounding white images -->
                         <div class="absolute inset-0 z-10 pointer-events-none" style="box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);"></div>
                         <!-- Cover Image scaling behavior controlled via object-cover. 
                              Note: the object handles arbitrary dimensioned uploads nicely. -->
                         <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                         
                         <!-- Overlay gradient for title legibility if we wanted title over image, but we'll put it below -->
                         <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                         
                         <div class="absolute bottom-6 left-6 rtl:left-auto rtl:right-6">
                            @php
                                $badgeClass = 'bg-yemdat-gold text-white';
                                $badgeStyle = '';
                                if($post->type === 'announcement') {
                                    $badgeClass = 'text-white';
                                    $badgeStyle = 'background-color: #2563eb; border: none;';
                                } elseif($post->type === 'update') {
                                    $badgeClass = 'text-white';
                                    $badgeStyle = 'background-color: #16a34a; border: none;';
                                }
                            @endphp
                            <span class="px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider shadow-lg backdrop-blur-md opacity-95 {{ $badgeClass }}" style="{{ $badgeStyle }}">
                                {{ app()->getLocale() == 'ar' ? __("global.{$post->type}") : ucfirst($post->type) }}
                            </span>
                         </div>
                    </div>
                @else
                    <!-- Fallback if no image -->
                    <div class="w-full h-32 bg-yemdat-beige/20 relative border-b border-gray-100">
                         <div class="absolute bottom-6 left-6 rtl:left-auto rtl:right-6">
                            @php
                                $badgeClass = 'bg-yemdat-gold text-white';
                                $badgeStyle = '';
                                if($post->type === 'announcement') {
                                    $badgeClass = 'text-white';
                                    $badgeStyle = 'background-color: #2563eb; border: none;';
                                } elseif($post->type === 'update') {
                                    $badgeClass = 'text-white';
                                    $badgeStyle = 'background-color: #16a34a; border: none;';
                                }
                            @endphp
                            <span class="px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wider shadow-sm {{ $badgeClass }}" style="{{ $badgeStyle }}">
                                {{ app()->getLocale() == 'ar' ? __("global.{$post->type}") : ucfirst($post->type) }}
                            </span>
                         </div>
                    </div>
                @endif

                <!-- Post Header & Meta -->
                <header class="pt-8 px-6 sm:px-10 pb-6 border-b border-gray-100">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-yemdat-brown leading-tight mb-6">
                        {{ $post->title }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-y-4 gap-x-6 text-sm text-gray-500 font-medium">
                        <!-- Date -->
                        <div class="flex items-center text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                            <svg class="w-4 h-4 mr-2 rtl:ml-2 text-yemdat-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <time datetime="{{ $post->created_at->toIso8601String() }}">
                                {{ $post->created_at->format('M d, Y') }}
                            </time>
                            <span class="mx-2 text-gray-300">|</span>
                            <span class="text-xs">{{ $post->created_at->format('h:i A') }}</span>
                        </div>
                        

                    </div>
                </header>

                <!-- Post Content (Rich Text) -->
                <div class="p-6 sm:p-10 prose max-w-none text-gray-700 leading-relaxed lg:prose-lg font-sans">
                     {!! $post->content !!}
                </div>

                <!-- Post Footer (Tags & Share) -->
                <footer class="p-6 sm:px-10 sm:py-8 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                    <!-- Tags -->
                    <div class="flex-grow">
                        @php
                            $tags = is_string($post->tags) ? json_decode($post->tags, true) : $post->tags;
                        @endphp
                        @if(is_array($tags) && count($tags) > 0)
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mr-2 rtl:ml-2">TAGS:</span>
                                @foreach($tags as $tag)
                                    <span class="px-3 py-1 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:text-yemdat-brown hover:border-yemdat-gold transition cursor-default shadow-sm">
                                        {{ trim($tag) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-gray-400 italic">No tags</div>
                        @endif
                    </div>
                    
                    <!-- Basic Share Link (Optional expansion later) -->
                    <div class="shrink-0">
                         <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied to clipboard!');" class="flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 hover:text-yemdat-brown transition shadow-sm">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            {{ app()->getLocale() == 'ar' ? 'نسخ الرابط' : 'Copy Link' }}
                        </button>
                    </div>
                </footer>
            </article>

            <!-- Related Posts Section (if any) -->
            @if($relatedPosts->count() > 0)
            <div class="max-w-4xl mx-auto mt-24 pt-8 border-t border-gray-200 pb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 border-l-4 rtl:border-l-0 rtl:border-r-4 border-yemdat-gold pl-4 rtl:pl-0 rtl:pr-4">
                    {{ app()->getLocale() == 'ar' ? 'أخبار ذات صلة' : 'Related Posts' }}
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                     @foreach($relatedPosts as $related)
                         <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 group flex flex-col">
                            <a href="{{ route('news.show', $related->slug) }}" class="block relative h-32 bg-gray-100 overflow-hidden shrink-0 border-b border-gray-100" style="height: 8rem; border-bottom: 1px solid #e5e7eb;">
                                 <div class="absolute inset-0 z-10 pointer-events-none" style="box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);"></div>
                                @if($related->image)
                                    <img src="{{ asset('storage/' . $related->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                         <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                            </a>
                            <div class="p-5 flex-grow flex flex-col">
                                <span class="text-[10px] font-bold text-yemdat-gold uppercase tracking-wider mb-2 block">
                                    {{ $related->created_at->format('M d, Y') }}
                                </span>
                                <h4 class="font-bold text-gray-900 mb-2 line-clamp-2 leading-tight group-hover:text-yemdat-brown flex-grow">
                                    <a href="{{ route('news.show', $related->slug) }}" class="before:absolute before:inset-0">
                                        {{ $related->title }}
                                    </a>
                                </h4>
                                 <span class="text-xs font-bold text-yemdat-brown flex items-center gap-1 mt-auto">
                                    <a href="{{ route('news.show', $related->slug) }}">{{ app()->getLocale() == 'ar' ? 'اقرأ' : 'Read' }} &rarr;</a>
                                </span>
                            </div>
                         </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
    
    <!-- Quill Style Reset for the frontend display so lists/formatting apply properly without quill JS loaded -->
    <style>
        .prose ul { list-style-type: disc; padding-left: 1.5rem; margin-top: 1em; margin-bottom: 1em; }
        .prose ol { list-style-type: decimal; padding-left: 1.5rem; margin-top: 1em; margin-bottom: 1em; }
        .prose a { color: #8C6A32; text-decoration: underline; font-weight: 500; }
        .prose a:hover { color: #B38840; }
        .prose strong { font-weight: 700; color: #111827; }
        .prose h1, .prose h2, .prose h3 { margin-top: 1.5em; margin-bottom: 0.5em; font-weight: 700; color: #111827; line-height: 1.2; }
        .prose h2 { font-size: 1.5em; }
        .prose h3 { font-size: 1.25em; }
        .prose p { margin-top: 1em; margin-bottom: 1em; line-height: 1.75; }
        .prose blockquote { border-left: 4px solid #F2CB57; padding-left: 1rem; font-style: italic; color: #4B5563; background: #fafafa; padding: 1rem; border-radius: 0.5rem; }
    </style>
</x-app-layout>
