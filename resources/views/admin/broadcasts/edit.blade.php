@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-container { font-size: 14px; min-height: 200px; }
    .ql-editor { min-height: 200px; }
    .ql-toolbar.ql-snow { border-radius: 6px 6px 0 0; border-color: #d1d5db; }
    .ql-container.ql-snow { border-radius: 0 0 6px 6px; border-color: #d1d5db; }
</style>
@endpush

<x-admin-layout>
    <x-slot name="header">
        Edit Broadcast
    </x-slot>

    <div class="max-w-4xl">
        <div class="mb-4">
            <a href="{{ route('admin.broadcasts.show', $broadcast) }}" class="text-sm text-yemdat-brown hover:text-yemdat-gold flex items-center gap-1">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Broadcast
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.broadcasts.update', $broadcast) }}" method="POST" x-data="broadcastComposer()">
            @csrf
            @method('PUT')

            {{-- ── AUDIENCE & SETTINGS ──────────────────────────────── --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Audience & Settings</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Audience Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Audience <span class="text-red-500">*</span>
                        </label>
                        <select name="audience_type" x-model="audienceType"
                            class="w-full border-gray-300 rounded-md focus:border-yemdat-gold focus:ring-yemdat-gold text-sm">
                            <option value="all_members">All Community Members</option>
                            <option value="event_members">Specific Event Registrants</option>
                        </select>
                    </div>

                    {{-- Event picker (shown only for event_members) --}}
                    <div x-show="audienceType === 'event_members'" x-transition>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Select Event <span class="text-red-500">*</span>
                        </label>
                        <select name="event_id"
                            class="w-full border-gray-300 rounded-md focus:border-yemdat-gold focus:ring-yemdat-gold text-sm">
                            <option value="">— Choose an event —</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id', $broadcast->event_id) === $event->id ? 'selected' : '' }}>
                                    {{ $event->title_en }} ({{ $event->start_date->format('M d, Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Language --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Send Language <span class="text-red-500">*</span>
                        </label>
                        <select name="language" x-model="language"
                            class="w-full border-gray-300 rounded-md focus:border-yemdat-gold focus:ring-yemdat-gold text-sm">
                            <option value="en" {{ old('language', $broadcast->language) === 'en' ? 'selected' : '' }}>English only</option>
                            <option value="ar" {{ old('language', $broadcast->language) === 'ar' ? 'selected' : '' }}>Arabic only (العربية)</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Only the selected language section below is required.</p>
                    </div>

                    {{-- From Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Email</label>
                        <input type="email" name="from_email" value="{{ old('from_email', $broadcast->from_email) }}"
                            placeholder="Leave blank to use server default"
                            class="w-full border-gray-300 rounded-md focus:border-yemdat-gold focus:ring-yemdat-gold text-sm">
                    </div>

                    {{-- From Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Name</label>
                        <input type="text" name="from_name" value="{{ old('from_name', $broadcast->from_name) }}"
                            placeholder="e.g. Yemdat Team"
                            class="w-full border-gray-300 rounded-md focus:border-yemdat-gold focus:ring-yemdat-gold text-sm">
                    </div>
                </div>
            </div>

            {{-- ── ENGLISH CONTENT ─────────────────────────────────── --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6" x-show="language === 'en'" x-transition>
                <h2 class="text-base font-semibold text-gray-900 mb-4">English Content</h2>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Subject (EN) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="subject_en" value="{{ old('subject_en', $broadcast->subject_en) }}"
                        class="w-full border-gray-300 rounded-md focus:border-yemdat-gold focus:ring-yemdat-gold text-sm"
                        placeholder="Email subject in English">
                </div>

                {{-- Body tabs EN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Body (EN) <span class="text-red-500">*</span></label>
                    <div class="flex border-b border-gray-200 mb-0">
                        <button type="button" @click="activeTabEn = 'visual'"
                            :class="activeTabEn === 'visual' ? 'border-yemdat-brown text-yemdat-brown' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-4 py-2 text-sm font-medium border-b-2 -mb-px transition">
                            Visual Editor
                        </button>
                        <button type="button" @click="switchToHtmlEn()"
                            :class="activeTabEn === 'html' ? 'border-yemdat-brown text-yemdat-brown' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-4 py-2 text-sm font-medium border-b-2 -mb-px transition">
                            HTML Source
                        </button>
                    </div>

                    <div x-show="activeTabEn === 'visual'" class="border border-gray-300 rounded-b-md rounded-tr-md overflow-hidden">
                        <div id="quill-en"></div>
                    </div>
                    <div x-show="activeTabEn === 'html'">
                        <textarea id="html-source-en" name="body_en_display"
                            class="w-full border border-gray-300 rounded-b-md rounded-tr-md focus:border-yemdat-gold focus:ring-yemdat-gold text-sm font-mono p-3"
                            rows="10" placeholder="Paste HTML here..."></textarea>
                    </div>
                    {{-- Hidden field that actually gets submitted --}}
                    <textarea name="body_en" id="body-en-hidden" class="hidden">{{ old('body_en', $broadcast->body_en) }}</textarea>
                </div>
            </div>

            {{-- ── ARABIC CONTENT ──────────────────────────────────── --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6" x-show="language === 'ar'" x-transition>
                <h2 class="text-base font-semibold text-gray-900 mb-4">Arabic Content <span class="text-sm font-normal text-gray-400">(المحتوى العربي)</span></h2>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Subject (AR) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="subject_ar" value="{{ old('subject_ar', $broadcast->subject_ar) }}" dir="rtl"
                        class="w-full border-gray-300 rounded-md focus:border-yemdat-gold focus:ring-yemdat-gold text-sm"
                        placeholder="موضوع البريد الإلكتروني بالعربية">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Body (AR) <span class="text-red-500">*</span></label>
                    <div class="flex border-b border-gray-200 mb-0">
                        <button type="button" @click="activeTabAr = 'visual'"
                            :class="activeTabAr === 'visual' ? 'border-yemdat-brown text-yemdat-brown' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-4 py-2 text-sm font-medium border-b-2 -mb-px transition">
                            Visual Editor
                        </button>
                        <button type="button" @click="switchToHtmlAr()"
                            :class="activeTabAr === 'html' ? 'border-yemdat-brown text-yemdat-brown' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="px-4 py-2 text-sm font-medium border-b-2 -mb-px transition">
                            HTML Source
                        </button>
                    </div>

                    <div x-show="activeTabAr === 'visual'" class="border border-gray-300 rounded-b-md rounded-tr-md overflow-hidden" dir="rtl">
                        <div id="quill-ar"></div>
                    </div>
                    <div x-show="activeTabAr === 'html'">
                        <textarea id="html-source-ar" name="body_ar_display"
                            class="w-full border border-gray-300 rounded-b-md rounded-tr-md focus:border-yemdat-gold focus:ring-yemdat-gold text-sm font-mono p-3"
                            rows="10" dir="rtl" placeholder="ضع HTML هنا..."></textarea>
                    </div>
                    <textarea name="body_ar" id="body-ar-hidden" class="hidden">{{ old('body_ar', $broadcast->body_ar) }}</textarea>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex flex-wrap justify-end gap-3">
                <a href="{{ route('admin.broadcasts.show', $broadcast) }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" @click="syncBeforeSubmit()"
                    class="bg-yemdat-brown hover:bg-yemdat-gold text-white font-bold py-2 px-6 rounded-md shadow transition text-sm">
                    Update Broadcast
                </button>
            </div>
        </form>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
function broadcastComposer() {
    return {
        audienceType: '{{ old('audience_type', $broadcast->audience_type) }}',
        language: '{{ old('language', $broadcast->language) }}',
        activeTabEn: 'visual',
        activeTabAr: 'visual',
        quillEn: null,
        quillAr: null,

        init() {
            this.$nextTick(() => {
                // English Quill
                this.quillEn = new Quill('#quill-en', {
                    theme: 'snow',
                    modules: { toolbar: [
                        [{ header: [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ color: [] }, { background: [] }],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link', 'image'],
                        ['clean']
                    ]}
                });
                const initEn = document.getElementById('body-en-hidden').value;
                if (initEn) this.quillEn.clipboard.dangerouslyPasteHTML(initEn);
                this.quillEn.on('text-change', () => {
                    document.getElementById('body-en-hidden').value = this.quillEn.root.innerHTML;
                });

                // Arabic Quill
                this.quillAr = new Quill('#quill-ar', {
                    theme: 'snow',
                    modules: { toolbar: [
                        [{ header: [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ color: [] }, { background: [] }],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link', 'image'],
                        ['clean']
                    ]}
                });
                const initAr = document.getElementById('body-ar-hidden').value;
                if (initAr) this.quillAr.clipboard.dangerouslyPasteHTML(initAr);
                this.quillAr.on('text-change', () => {
                    document.getElementById('body-ar-hidden').value = this.quillAr.root.innerHTML;
                });
            });
        },

        switchToHtmlEn() {
            document.getElementById('html-source-en').value = this.quillEn ? this.quillEn.root.innerHTML : '';
            this.activeTabEn = 'html';
        },

        switchToHtmlAr() {
            document.getElementById('html-source-ar').value = this.quillAr ? this.quillAr.root.innerHTML : '';
            this.activeTabAr = 'html';
        },

        syncBeforeSubmit() {
            if (this.activeTabEn === 'html') {
                document.getElementById('body-en-hidden').value = document.getElementById('html-source-en').value;
            } else {
                document.getElementById('body-en-hidden').value = this.quillEn ? this.quillEn.root.innerHTML : '';
            }
            if (this.activeTabAr === 'html') {
                document.getElementById('body-ar-hidden').value = document.getElementById('html-source-ar').value;
            } else {
                document.getElementById('body-ar-hidden').value = this.quillAr ? this.quillAr.root.innerHTML : '';
            }
        }
    }
}
</script>
@endpush
</x-admin-layout>
