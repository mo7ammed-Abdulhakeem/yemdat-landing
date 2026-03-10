<x-admin-layout>
    <x-slot name="header">
        Edit Event
    </x-slot>

    <!-- Include Quill Styles -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <div>
        <div class="mb-6">
            <a href="{{ route('admin.events.index') }}" class="text-gray-600 hover:text-yemdat-brown flex items-center gap-1">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Events
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
                    <form id="editEventForm" action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Event Details Section -->
                        <h3 class="text-lg font-medium text-yemdat-brown mb-4 border-b pb-2">Event Basic Info</h3>
                        {{-- Cache Bust v2 --}}
                        <div class="flex flex-col gap-6 mb-6">
                            <!-- Title EN -->
                            <div>
                                <label for="title_en" class="block text-sm font-medium text-gray-700">Event Title (English) <span class="text-red-500">*</span></label>
                                <input type="text" name="title_en" id="title_en" value="{{ old('title_en', $event->title_en) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                                @error('title_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Title AR -->
                            <div dir="rtl">
                                <label for="title_ar" class="block text-sm font-medium text-gray-700">عنوان الحدث (Arabic) <span class="text-red-500">*</span></label>
                                <input type="text" name="title_ar" id="title_ar" value="{{ old('title_ar', $event->title_ar) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                                @error('title_ar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date & Time <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                                @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date & Time</label>
                                <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50">
                                <p class="text-xs text-gray-500 mt-1">Must be after Start Date.</p>
                                @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Location -->
                            <div class="col-span-2">
                                <label for="location" class="block text-sm font-medium text-gray-700">Location / Link</label>
                                <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" placeholder="e.g. Zoom Link or Sana'a, Yemen" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50">
                                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Image -->
                            <div class="col-span-2">
                                <label for="image" class="block text-sm font-medium text-gray-700">Event Cover Image</label>
                                @if($event->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="Current Image" class="h-32 w-auto rounded-lg shadow object-cover">
                                        <p class="text-xs text-gray-500 mt-1">Current Image</p>
                                    </div>
                                @endif
                                <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yemdat-beige file:text-yemdat-brown hover:file:bg-yemdat-gold hover:file:text-white transition">
                                <p class="text-xs text-gray-500 mt-1">Max size: 1MB. Recommended: 800x600px (Landscape).</p>
                                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Description English -->
                        <div class="mb-8">
                            <label for="description_en" class="block text-sm font-medium text-gray-700 mb-2">Description (English) <span class="text-red-500">*</span></label>
                            <div id="editor-container-en" class="h-64 bg-white rounded-md">
                                {!! old('description_en', $event->description_en) !!}
                            </div>
                            <input type="hidden" name="description_en" id="description_en" value="{{ old('description_en', $event->description_en) }}">
                            @error('description_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Description Arabic -->
                        <div class="mb-8" dir="rtl">
                            <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-2">الوصف (Arabic) <span class="text-red-500">*</span></label>
                            <div id="editor-container-ar" class="h-64 bg-white rounded-md">
                                {!! old('description_ar', $event->description_ar) !!}
                            </div>
                            <input type="hidden" name="description_ar" id="description_ar" value="{{ old('description_ar', $event->description_ar) }}">
                            @error('description_ar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Lecturer Info Section -->
                        <h3 class="text-lg font-medium text-yemdat-brown mb-4 border-b pb-2">Lecturer Information</h3>
                        <div class="flex flex-col gap-6 mb-6">
                            <!-- Lecturer Name EN -->
                            <div>
                                <label for="lecturer_name_en" class="block text-sm font-medium text-gray-700">Lecturer Name (English) <span class="text-red-500">*</span></label>
                                <input type="text" name="lecturer_name_en" id="lecturer_name_en" value="{{ old('lecturer_name_en', $event->lecturer_name_en) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                                @error('lecturer_name_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                             <!-- Lecturer Name AR -->
                             <div dir="rtl">
                                <label for="lecturer_name_ar" class="block text-sm font-medium text-gray-700">اسم المحاضر (Arabic) <span class="text-red-500">*</span></label>
                                <input type="text" name="lecturer_name_ar" id="lecturer_name_ar" value="{{ old('lecturer_name_ar', $event->lecturer_name_ar) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                                @error('lecturer_name_ar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Lecturer Title EN -->
                            <div>
                                <label for="lecturer_title_en" class="block text-sm font-medium text-gray-700">Title / Designation (English)</label>
                                <input type="text" name="lecturer_title_en" id="lecturer_title_en" value="{{ old('lecturer_title_en', $event->lecturer_title_en) }}" placeholder="e.g. Senior Developer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50">
                                @error('lecturer_title_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                             <!-- Lecturer Title AR -->
                             <div dir="rtl">
                                <label for="lecturer_title_ar" class="block text-sm font-medium text-gray-700">المسمى الوظيفي (Arabic)</label>
                                <input type="text" name="lecturer_title_ar" id="lecturer_title_ar" value="{{ old('lecturer_title_ar', $event->lecturer_title_ar) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50">
                                @error('lecturer_title_ar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- LinkedIn -->
                            <div class="col-span-2">
                                <div>
                                    <label for="lecturer_linkedin" class="block text-sm font-medium text-gray-700">{{ __('LinkedIn Profile') }}</label>
                                    <input id="lecturer_linkedin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" type="url" name="lecturer_linkedin" value="{{ old('lecturer_linkedin', $event->lecturer_linkedin) }}" placeholder="https://linkedin.com/in/..." />
                                    @error('lecturer_linkedin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            
                            <!-- Join URL (Meeting Link) -->
                            <div class="col-span-2">
                                <h3 class="text-lg font-medium text-yemdat-brown mb-4">Event Registration / Joining Link</h3>
                                <div>
                                    <label for="join_url" class="block text-sm font-medium text-gray-700">{{ __('Registration or Join Link (Google Form, Zoom, etc.)') }}</label>
                                    <input id="join_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" type="url" name="join_url" value="{{ old('join_url', $event->join_url) }}" placeholder="https://..." />
                                    <p class="mt-1 text-sm text-gray-500">Add a link for users to register (before event starts) or to join the stream (when event starts).</p>
                                    <p class="text-sm text-gray-500" dir="rtl">أضف رابطاً لتسجيل المستخدمين (قبل بدئها) أو للانضمام للبث (عند بدئها).</p>
                                    @error('join_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- Lecturer Image -->
                            <div class="col-span-2">
                                <label for="lecturer_image" class="block text-sm font-medium text-gray-700">Lecturer Photo</label>
                                @if($event->lecturer_image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $event->lecturer_image) }}" alt="Current Lecturer" class="h-20 w-20 rounded-full shadow object-cover">
                                        <p class="text-xs text-gray-500 mt-1">Current Photo</p>
                                    </div>
                                @endif
                                <input type="file" name="lecturer_image" id="lecturer_image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yemdat-beige file:text-yemdat-brown hover:file:bg-yemdat-gold hover:file:text-white transition">
                                <p class="text-xs text-gray-500 mt-1">Max size: 1MB. SQUARE ratio recommended.</p>
                                @error('lecturer_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Active Toggle -->
                        <div class="flex items-center mb-6">
                            <input type="checkbox" name="is_active" id="is_active" class="h-4 w-4 text-yemdat-gold focus:ring-yemdat-brown border-gray-300 rounded" {{ $event->is_active ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Activate Event
                            </label>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.events.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Cancel</a>
                            <button type="submit" class="px-6 py-2 bg-yemdat-brown text-white font-bold rounded-md hover:bg-yemdat-gold transition shadow-lg">
                                Update Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- Quill JS Script -->
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

        var quillEn = new Quill('#editor-container-en', {
            theme: 'snow',
            placeholder: 'Event details in English...',
            modules: { toolbar: toolbarOptions }
        });

        var quillAr = new Quill('#editor-container-ar', {
            theme: 'snow',
            placeholder: 'تفاصيل الحدث بالعربية...',
            modules: { toolbar: toolbarOptions }
        });

        // Sync Quill content to hidden input
        var form = document.getElementById('editEventForm');
        form.onsubmit = function() {
            var descriptionEn = document.querySelector('input[name=description_en]');
            descriptionEn.value = quillEn.root.innerHTML;
            
            var descriptionAr = document.querySelector('input[name=description_ar]');
            descriptionAr.value = quillAr.root.innerHTML;
        };
    </script>
</x-admin-layout>
