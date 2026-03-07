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

                            <!-- LinkedIn Profile -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-yemdat-brown mb-2">
                                    {{ app()->getLocale() == 'ar' ? 'رابط حساب لينكد إن (اختياري)' : 'LinkedIn Profile URL (Optional)' }}
                                </label>
                                <input type="url" name="linkedin_url" value="{{ old('linkedin_url') }}" placeholder="https://linkedin.com/in/username" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition" dir="ltr">
                            </div>

                        </div> <!-- End of grid -->

                        </div> <!-- End of grid -->

                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                            <!-- Program Type -->
                            <div>
                                <label class="block text-sm font-medium text-yemdat-brown mb-2">
                                    {{ app()->getLocale() == 'ar' ? ($settings['trainer_label_program_type_ar'] ?? 'نوع البرنامج') : ($settings['trainer_label_program_type_en'] ?? 'Program Type') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="program_type" class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-yemdat-gold focus:bg-white focus:ring-2 focus:ring-yemdat-gold/20 outline-none transition duration-200" required>
                                    <option value="">--</option>
                                    <option value="workshop" {{ old('program_type') == 'workshop' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'ورشة عمل' : 'Workshop' }}</option>
                                    <option value="course" {{ old('program_type') == 'course' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'دورة تدريبية' : 'Course' }}</option>
                                </select>
                            </div>

                            <!-- Duration (Days) -->
                            <div>
                                <label class="block text-sm font-medium text-yemdat-brown mb-2">
                                    {{ app()->getLocale() == 'ar' ? ($settings['trainer_label_days_ar'] ?? 'المدة (بالأيام)') : ($settings['trainer_label_days_en'] ?? 'Duration (Days)') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="duration_days" value="{{ old('duration_days') }}" min="1" step="1" class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-yemdat-gold focus:bg-white focus:ring-2 focus:ring-yemdat-gold/20 outline-none transition duration-200" required>
                            </div>

                            <!-- Duration (Hours) -->
                            <div>
                                <label class="block text-sm font-medium text-yemdat-brown mb-2">
                                    {{ app()->getLocale() == 'ar' ? ($settings['trainer_label_duration_ar'] ?? 'المدة (بالساعات)') : ($settings['trainer_label_duration_en'] ?? 'Duration (Hours)') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="duration_hours" value="{{ old('duration_hours') }}" min="1" step="1" class="w-full px-5 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:border-yemdat-gold focus:bg-white focus:ring-2 focus:ring-yemdat-gold/20 outline-none transition duration-200" required>
                            </div>
                        </div>

                        <!-- Agenda (Full Width Quill) -->
                        <div class="mt-6 w-full">
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">
                                {{ app()->getLocale() == 'ar' ? ($settings['trainer_label_agenda_ar'] ?? 'أجندة البرنامج/الدورة') : ($settings['trainer_label_agenda_en'] ?? 'Program/Workshop Agenda') }} <span class="text-red-500">*</span>
                            </label>
                            
                            @php
                                $notesEn = trim(strip_tags($settings['trainer_topic_notes_en'] ?? ''));
                                $notesAr = trim(strip_tags($settings['trainer_topic_notes_ar'] ?? ''));
                                $hasNotes = app()->getLocale() == 'ar' ? !empty($notesAr) : !empty($notesEn);
                            @endphp

                            @if($hasNotes)
                            <div class="mb-4 text-sm text-gray-600 bg-blue-50 p-4 border-l-4 border-blue-400 rounded-md">
                                {!! app()->getLocale() == 'ar' ? nl2br(e($settings['trainer_topic_notes_ar'])) : nl2br(e($settings['trainer_topic_notes_en'])) !!}
                            </div>
                            @endif

                            <input type="hidden" name="agenda" id="agenda_input" value="{{ old('agenda') }}">
                            <div class="bg-white rounded-lg shadow-sm" style="border: 1px solid #d1d5db; border-radius: 0.5rem; overflow: hidden;">
                                <div id="editor-container" style="height: 250px; font-size: 16px; border: none;"></div>
                            </div>
                        </div>

                        <!-- Mandatory Agreement Checkbox -->
                        <div class="mt-6 w-full bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="flex items-start cursor-pointer group">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="agreed_to_free_provision" value="1" class="w-5 h-5 border border-gray-300 rounded bg-white text-yemdat-gold focus:ring-yemdat-gold/50 cursor-pointer" {{ old('agreed_to_free_provision') ? 'checked' : '' }} required>
                                </div>
                                <div class="ml-3 {{ app()->getLocale() == 'ar' ? 'mr-3 ml-0' : '' }} text-sm text-gray-700 font-medium group-hover:text-yemdat-brown transition-colors">
                                    {{ app()->getLocale() == 'ar' ? ($settings['trainer_agreement_text_ar'] ?? 'أوافق على أنه في حال الموافقة على برنامجي، سيتم تقديمه مجاناً لجميع المشاركين.') : ($settings['trainer_agreement_text_en'] ?? 'I agree that if my program is approved, it will be provided for free for all participants.') }}
                                    <span class="text-red-500">*</span>
                                </div>
                            </label>
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
            var oldContent = document.getElementById('agenda_input').value;
            if (oldContent) {
                quill.root.innerHTML = oldContent;
            }

            var form = document.getElementById('trainer-form');
            if(form) {
                form.addEventListener('submit', function() {
                    var html = quill.root.innerHTML;
                    if(html === '<p><br></p>' || html.trim() === '') html = '';
                    document.getElementById('agenda_input').value = html;
                });
            }
        });
    </script>
</x-app-layout>
