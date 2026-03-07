<x-app-layout>
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-y-16">
            
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto">
                 <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center text-yemdat-gold mx-auto mb-6">
                     <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-yemdat-brown mb-6">
                    {{ app()->getLocale() == 'ar' ? ($settings['trainer_form_title_ar'] ?? 'كن مدرباً') : ($settings['trainer_form_title_en'] ?? 'Be A Trainer') }}
                </h1>
                <p class="text-xl text-gray-600">
                    {{ app()->getLocale() == 'ar' ? ($settings['trainer_form_notes_ar'] ?? 'يرجى ملء النموذج أدناه إذا كنت مهتمًا بتقديم دورة تدريبية معنا.') : ($settings['trainer_form_notes_en'] ?? 'Please fill out the form below if you are interested in making a course with us.') }}
                </p>
            </div>

            <div class="max-w-3xl mx-auto w-full">
                
                <!-- Trainer Form -->
                <div class="bg-white p-8 md:p-10 rounded-2xl border border-gray-200 shadow-sm" x-data="{ showSuccess: {{ session('success') ? 'true' : 'false' }} }">
                    <h2 class="text-2xl font-bold text-yemdat-brown mb-8 text-center">
                        {{ app()->getLocale() == 'ar' ? 'نموذج المدرب' : 'Trainer Registration Form' }}
                    </h2>

                    <!-- Success Message -->
                    <div x-show="showSuccess" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">{{ app()->getLocale() == 'ar' ? 'نجاح!' : 'Success!' }}</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="showSuccess = false">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                        </span>
                    </div>

                    <form id="trainer-form" action="{{ route('trainer.store') }}" method="POST" class="flex flex-col gap-6">
                        @csrf
                        
                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium text-yemdat-brown mb-2">
                                    {{ app()->getLocale() == 'ar' ? ($settings['trainer_label_name_ar'] ?? 'الاسم الكامل') : ($settings['trainer_label_name_en'] ?? 'Full Name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition">
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-yemdat-brown mb-2">
                                    {{ app()->getLocale() == 'ar' ? ($settings['trainer_label_phone_ar'] ?? 'رقم الهاتف') : ($settings['trainer_label_phone_en'] ?? 'Phone Number') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition" dir="ltr">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-yemdat-brown mb-2">
                                    {{ app()->getLocale() == 'ar' ? ($settings['trainer_label_email_ar'] ?? 'البريد الإلكتروني') : ($settings['trainer_label_email_en'] ?? 'Email Address') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition" dir="ltr">
                            </div>

                            <!-- Country -->
                            <div>
                                <label class="block text-sm font-medium text-yemdat-brown mb-2">
                                    {{ app()->getLocale() == 'ar' ? ($settings['trainer_label_country_ar'] ?? 'بلد الإقامة') : ($settings['trainer_label_country_en'] ?? 'Country of Residency') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="country" value="{{ old('country') }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition">
                            </div>

                            <!-- LinkedIn Profile -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-yemdat-brown mb-2">
                                    {{ app()->getLocale() == 'ar' ? 'رابط حساب لينكد إن (اختياري)' : 'LinkedIn Profile URL (Optional)' }}
                                </label>
                                <input type="url" name="linkedin_url" value="{{ old('linkedin_url') }}" placeholder="https://linkedin.com/in/username" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition" dir="ltr">
                            </div>

                        </div> <!-- End of grid -->

                        <!-- Help Topic (Full Width) -->
                        <div class="mt-6 w-full">
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">
                                {{ app()->getLocale() == 'ar' ? ($settings['trainer_label_help_ar'] ?? 'بماذا يمكنك مساعدتنا؟') : ($settings['trainer_label_help_en'] ?? 'What can you help us with?') }} <span class="text-red-500">*</span>
                            </label>
                            @if(isset($settings['trainer_topic_notes_en']) || isset($settings['trainer_topic_notes_ar']))
                            <div class="mb-4 text-sm text-gray-600 bg-blue-50 p-4 border-l-4 border-blue-400 rounded-md">
                                {{ app()->getLocale() == 'ar' ? ($settings['trainer_topic_notes_ar'] ?? '') : ($settings['trainer_topic_notes_en'] ?? '') }}
                            </div>
                            @endif
                            <input type="hidden" name="help_topic" id="help_topic_input" value="{{ old('help_topic') }}">
                            <div class="bg-white rounded-lg shadow-sm" style="border: 1px solid #d1d5db; border-radius: 0.5rem; overflow: hidden;">
                                <div id="editor-container" style="height: 250px; font-size: 16px; border: none;"></div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 text-center">
                            <button type="submit" class="bg-yemdat-brown text-white font-bold py-3 px-8 rounded-full hover:bg-yemdat-gold transition duration-300 w-full md:w-auto shadow-md hover:shadow-lg">
                                {{ app()->getLocale() == 'ar' ? 'إرسال الطلب' : 'Submit Request' }}
                            </button>
                        </div>
                    </form>
                </div>
                
            </div>
            
        </div>
    </div>

    <!-- Quill Editor Assets -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var quill = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: '...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'direction': 'rtl' }],
                        ['clean']
                    ]
                }
            });

            // Set text direction based on locale
            var isRtl = document.documentElement.dir === 'rtl';
            if (isRtl) {
                quill.format('direction', 'rtl');
                quill.format('align', 'right');
            }
            
            // Populate old data
            var oldContent = document.getElementById('help_topic_input').value;
            if (oldContent) {
                quill.root.innerHTML = oldContent;
            }

            var form = document.getElementById('trainer-form');
            if(form) {
                form.addEventListener('submit', function() {
                    var html = quill.root.innerHTML;
                    if(html === '<p><br></p>' || html.trim() === '') html = '';
                    document.getElementById('help_topic_input').value = html;
                });
            }
        });
    </script>
</x-app-layout>
