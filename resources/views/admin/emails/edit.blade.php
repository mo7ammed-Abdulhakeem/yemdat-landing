<x-admin-layout>
    <div class="max-w-4xl space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.emails.index') }}" class="text-gray-400 hover:text-yemdat-brown transition-colors">
                <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="text-3xl font-bold tracking-tight text-yemdat-brown">Edit Template: <span class="text-yemdat-gold">{{ $email->mailable_class }}</span></h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.emails.update', $email) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-8" id="emailTemplateForm">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                        <ul class="list-disc px-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="bg-blue-50 border border-blue-100 text-blue-800 rounded-xl p-4 text-sm">
                    <strong>Available placeholders:</strong> <code>{name}</code>, <code>{otp}</code>, <code>{event_title}</code>, <code>{start_date}</code>, <code>{location}</code>, <code>{join_url_text}</code>
                </div>

                {{-- Active toggle --}}
                <div class="flex items-center justify-between bg-gray-50 rounded-xl px-5 py-4 border border-gray-200">
                    <div>
                        <p class="text-sm font-bold text-gray-700">Send this email</p>
                        <p class="text-xs text-gray-400 mt-0.5">When off, this email will not be sent regardless of trigger.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer" x-data="{ on: {{ $email->is_active ? 'true' : 'false' }} }">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                            x-model="on" {{ $email->is_active ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-green-300"></div>
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </label>
                </div>

                <div class="space-y-6">
                    <!-- From Email -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700">Sender Email (From) - Optional</label>
                        <p class="text-xs text-gray-500 mb-2">Leave blank to use the system default sender address.</p>
                        <input type="email" name="from_email" value="{{ old('from_email', $email->from_email) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" placeholder="e.g. event@yemdat.com">
                    </div>

                    <div class="border-t border-gray-100 pt-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- English Section -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-bold text-yemdat-brown border-b pb-2">English Content</h3>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700">Subject (English) <span class="text-red-500">*</span></label>
                                    <input type="text" name="subject_en" value="{{ old('subject_en', $email->subject_en) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700">Email Body (English) <span class="text-red-500">*</span></label>
                                    <p class="text-xs text-gray-500 mb-2">HTML is supported.</p>
                                    <textarea name="body_en" rows="12" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>{{ old('body_en', $email->body_en) }}</textarea>
                                </div>
                            </div>

                            <!-- Arabic Section -->
                            <div class="space-y-6" dir="rtl">
                                <h3 class="text-lg font-bold text-yemdat-brown border-b pb-2 text-right">المحتوى العربي</h3>
                                
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700 text-right">الموضوع (عربي) <span class="text-red-500">*</span></label>
                                    <input type="text" name="subject_ar" value="{{ old('subject_ar', $email->subject_ar) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50 text-right" required>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-gray-700 text-right">محتوى الإيميل (عربي) <span class="text-red-500">*</span></label>
                                    <p class="text-xs text-gray-500 mb-2 text-right">يدعم استخدام أكواد HTML.</p>
                                    <textarea name="body_ar" rows="12" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50 text-right" required>{{ old('body_ar', $email->body_ar) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banner Image -->
                <div class="border-t border-gray-100 pt-8 mt-8">
                    <h3 class="text-lg font-bold text-yemdat-brown mb-4">Email Banner Header Image</h3>
                    <div class="flex items-start gap-8">
                        @if($email->banner_image)
                            <div class="shrink-0">
                                <img src="{{ filter_var($email->banner_image, FILTER_VALIDATE_URL) ? $email->banner_image : asset('storage/' . $email->banner_image) }}" alt="Banner" class="w-48 h-auto object-contain rounded-lg border border-gray-200 shadow-sm">
                            </div>
                        @else
                            <div class="shrink-0 w-48 h-24 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-sm italic">
                                No banner
                            </div>
                        @endif
                        <div class="space-y-2 flex-1">
                            <label class="block text-sm font-medium text-gray-700">Upload New Banner (Optional)</label>
                            <input type="file" name="banner_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yemdat-gold file:text-yemdat-brown hover:file:bg-yemdat-orange w-full rounded-xl border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50 bg-white">
                            <p class="text-xs text-gray-500 mt-1">Leave blank to keep existing. High-resolution rectangle recommended.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('admin.emails.index') }}" class="px-6 py-2.5 text-sm font-bold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-yemdat-brown bg-yemdat-gold rounded-xl shadow-sm hover:shadow-md hover:bg-yemdat-orange transition-all duration-200">
                        Save Template Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
<script>
document.getElementById('emailTemplateForm').addEventListener('submit', function () {
    function b64(str) {
        const bytes = new TextEncoder().encode(str);
        let binary = '';
        bytes.forEach(b => binary += String.fromCharCode(b));
        return btoa(binary);
    }
    const subjectAr = this.querySelector('[name="subject_ar"]');
    const bodyAr    = this.querySelector('[name="body_ar"]');
    subjectAr.value = b64(subjectAr.value);
    bodyAr.value    = b64(bodyAr.value);
    const flag = document.createElement('input');
    flag.type = 'hidden'; flag.name = '_b64'; flag.value = '1';
    this.appendChild(flag);
});
</script>
</x-admin-layout>
