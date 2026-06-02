<x-app-layout>
    @php $ar = app()->getLocale() === 'ar'; @endphp
    <div class="bg-surface min-h-screen py-16" dir="{{ $ar ? 'rtl' : 'ltr' }}">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="text-center max-w-2xl mx-auto mb-10">
                <div class="w-16 h-16 rounded-2xl bg-accent/15 flex items-center justify-center text-primary mx-auto mb-6">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-ink mb-4">
                    {{ $ar ? ($settings['trainer_form_title_ar'] ?? 'علّم معنا') : ($settings['trainer_form_title_en'] ?? 'Teach with Us') }}
                </h1>
                <p class="text-lg text-ink-soft">
                    {{ $ar ? ($settings['trainer_form_notes_ar'] ?? 'يرجى ملء النموذج أدناه إذا كنت مهتمًا بتقديم دورة تدريبية معنا.') : ($settings['trainer_form_notes_en'] ?? 'Please fill out the form below if you are interested in making a course with us.') }}
                </p>
            </div>

            <x-ui.card class="p-8 md:p-10">
                <h2 class="text-2xl font-bold text-primary mb-8 text-center">
                    {{ $ar ? 'نموذج التقديم' : 'Application Form' }}
                </h2>

                @if ($errors->any())
                    <x-ui.alert variant="danger" :title="$ar ? 'عذراً!' : 'Whoops!'" class="mb-6">
                        <ul class="mt-1 list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                @endif

                <form id="trainer-form" action="{{ route('trainer.store') }}" method="POST" class="flex flex-col gap-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-ui.input
                            name="name"
                            :label="$ar ? ($settings['trainer_label_name_ar'] ?? 'الاسم الكامل') : ($settings['trainer_label_name_en'] ?? 'Full Name')"
                            :required="true" />

                        <x-ui.input
                            name="phone_number"
                            dir="ltr"
                            :label="$ar ? ($settings['trainer_label_phone_ar'] ?? 'رقم الهاتف') : ($settings['trainer_label_phone_en'] ?? 'Phone Number')"
                            :required="true" />

                        <x-ui.input
                            type="email"
                            name="email"
                            dir="ltr"
                            :label="$ar ? ($settings['trainer_label_email_ar'] ?? 'البريد الإلكتروني') : ($settings['trainer_label_email_en'] ?? 'Email Address')"
                            :required="true" />

                        <div class="md:col-span-2">
                            <x-ui.input
                                type="url"
                                name="linkedin_url"
                                dir="ltr"
                                placeholder="https://linkedin.com/in/username"
                                :label="$ar ? 'رابط حساب لينكد إن (اختياري)' : 'LinkedIn Profile URL (Optional)'" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-ui.select
                            name="program_type"
                            :label="$ar ? ($settings['trainer_label_program_type_ar'] ?? 'نوع البرنامج') : ($settings['trainer_label_program_type_en'] ?? 'Program Type')"
                            :required="true">
                            <option value="">--</option>
                            <option value="workshop" {{ old('program_type') == 'workshop' ? 'selected' : '' }}>{{ $ar ? 'ورشة عمل' : 'Workshop' }}</option>
                            <option value="course" {{ old('program_type') == 'course' ? 'selected' : '' }}>{{ $ar ? 'دورة تدريبية' : 'Course' }}</option>
                        </x-ui.select>

                        <x-ui.input
                            type="number"
                            name="duration_days"
                            min="1"
                            step="1"
                            :label="$ar ? ($settings['trainer_label_days_ar'] ?? 'المدة (بالأيام)') : ($settings['trainer_label_days_en'] ?? 'Duration (Days)')"
                            :required="true" />

                        <x-ui.input
                            type="number"
                            name="duration_hours"
                            min="1"
                            step="1"
                            :label="$ar ? ($settings['trainer_label_duration_ar'] ?? 'المدة (بالساعات)') : ($settings['trainer_label_duration_en'] ?? 'Duration (Hours)')"
                            :required="true" />
                    </div>

                    <!-- Agenda (rich text via Quill) -->
                    <div class="space-y-1">
                        <x-ui.label :required="true">
                            {{ $ar ? ($settings['trainer_label_agenda_ar'] ?? 'أجندة البرنامج/الدورة') : ($settings['trainer_label_agenda_en'] ?? 'Program/Workshop Agenda') }}
                        </x-ui.label>

                        @php
                            $notesEn = trim(strip_tags($settings['trainer_topic_notes_en'] ?? ''));
                            $notesAr = trim(strip_tags($settings['trainer_topic_notes_ar'] ?? ''));
                            $hasNotes = $ar ? ! empty($notesAr) : ! empty($notesEn);
                        @endphp

                        @if($hasNotes)
                            <x-ui.alert variant="info" class="mb-2">
                                {!! $ar ? nl2br(e($settings['trainer_topic_notes_ar'])) : nl2br(e($settings['trainer_topic_notes_en'])) !!}
                            </x-ui.alert>
                        @endif

                        <input type="hidden" name="agenda" id="agenda_input" value="{{ old('agenda') }}">
                        <div class="rounded-btn border {{ $errors->has('agenda') ? 'border-danger' : 'border-border' }} bg-surface-raised overflow-hidden">
                            <div id="editor-container" style="height: 250px; font-size: 16px; border: none;"></div>
                        </div>
                        @error('agenda')<p class="text-xs text-danger">{{ $message }}</p>@enderror
                    </div>

                    <!-- Mandatory agreement -->
                    <div class="bg-surface-sunken p-4 rounded-btn border border-border">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" name="agreed_to_free_provision" value="1" required
                                class="mt-0.5 w-5 h-5 rounded border border-border accent-primary cursor-pointer focus:outline-none focus:ring-2 focus:ring-accent/50"
                                {{ old('agreed_to_free_provision') ? 'checked' : '' }}>
                            <span class="text-sm text-ink-soft font-medium group-hover:text-ink transition-colors">
                                {{ $ar ? ($settings['trainer_agreement_text_ar'] ?? 'أوافق على أنه في حال الموافقة على برنامجي، سيتم تقديمه مجاناً لجميع المشاركين.') : ($settings['trainer_agreement_text_en'] ?? 'I agree that if my program is approved, it will be provided for free for all participants.') }}
                                <span class="text-danger">*</span>
                            </span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2 text-center">
                        <x-ui.button type="submit" size="lg" class="w-full md:w-auto">
                            {{ $ar ? 'إرسال الطلب' : 'Submit Request' }}
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>

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
            if (form) {
                form.addEventListener('submit', function() {
                    var html = quill.root.innerHTML;
                    if (html === '<p><br></p>' || html.trim() === '') html = '';
                    document.getElementById('agenda_input').value = html;
                });
            }
        });
    </script>
</x-app-layout>
