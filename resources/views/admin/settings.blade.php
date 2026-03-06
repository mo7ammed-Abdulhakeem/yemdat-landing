<x-admin-layout>
    <x-slot name="header">
        Site Settings
    </x-slot>

    <div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">General Settings</h3>
                    <p class="text-sm text-gray-500">Manage global site configurations.</p>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8">
                        @csrf
                        
                        <!-- Notification Bar -->
                        <div class="space-y-4">
                            <h4 class="text-md font-semibold text-yemdat-brown border-b pb-2">Top Notification Bar</h4>
                            
                            <div class="flex items-center">
                                <input type="checkbox" id="notification_bar_enabled" name="notification_bar_enabled" value="1" class="h-4 w-4 text-yemdat-gold focus:ring-yemdat-gold border-gray-300 rounded" {{ $settings['notification_bar_enabled'] ? 'checked' : '' }}>
                                <label for="notification_bar_enabled" class="ml-2 block text-sm text-gray-900">
                                    Enable Notification Bar
                                </label>
                            </div>

                            <div>
                                <label for="notification_bar_text" class="block text-sm font-medium text-gray-700">Notification Text</label>
                                <input type="text" name="notification_bar_text" id="notification_bar_text" value="{{ $settings['notification_bar_text'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">This text will appear at the very top of the website if enabled.</p>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="space-y-4">
                            <h4 class="text-md font-semibold text-yemdat-brown border-b pb-2">Contact Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="site_email" class="block text-sm font-medium text-gray-700">Public Contact Email Adress</label>
                                    <input type="email" name="site_email" id="site_email" value="{{ $settings['site_email'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold sm:text-sm" placeholder="contact@yemdat.com">
                                </div>
                                <div>
                                    <label for="admin_email" class="block text-sm font-medium text-gray-700">Internal Admin Email Address (Receives form submissions)</label>
                                    <input type="email" name="admin_email" id="admin_email" value="{{ $settings['admin_email'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold sm:text-sm" placeholder="admin@yemdat.com">
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div class="space-y-4">
                            <h4 class="text-md font-semibold text-yemdat-brown border-b pb-2">Social Media Links</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="site_facebook" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                                    <input type="url" name="site_facebook" id="site_facebook" value="{{ $settings['site_facebook'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold sm:text-sm" placeholder="https://facebook.com/yourpage">
                                </div>

                                <div>
                                    <label for="site_twitter" class="block text-sm font-medium text-gray-700">Twitter (X) URL</label>
                                    <input type="url" name="site_twitter" id="site_twitter" value="{{ $settings['site_twitter'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold sm:text-sm" placeholder="https://twitter.com/yourhandle">
                                </div>

                                <div>
                                    <label for="site_instagram" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                                    <input type="url" name="site_instagram" id="site_instagram" value="{{ $settings['site_instagram'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold sm:text-sm" placeholder="https://instagram.com/yourprofile">
                                </div>

                                <div>
                                    <label for="site_linkedin" class="block text-sm font-medium text-gray-700">LinkedIn URL</label>
                                    <input type="url" name="site_linkedin" id="site_linkedin" value="{{ $settings['site_linkedin'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold sm:text-sm" placeholder="https://linkedin.com/in/yourprofile">
                                </div>

                                <div>
                                    <label for="site_whatsapp" class="block text-sm font-medium text-gray-700">WhatsApp URL</label>
                                    <input type="url" name="site_whatsapp" id="site_whatsapp" value="{{ $settings['site_whatsapp'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold sm:text-sm" placeholder="https://wa.me/96777xxxxxx">
                                    <p class="mt-1 text-xs text-gray-500">Format: https://wa.me/PHONE_NUMBER_WITH_COUNTRY_CODE (e.g., https://wa.me/96777123456)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Trainer Form Settings -->
                        <div class="space-y-4">
                            <h4 class="text-md font-semibold text-yemdat-brown border-b pb-2">"Be a Trainer" Form Settings</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Form Title (English)</label>
                                    <input type="text" name="trainer_form_title_en" value="{{ $settings['trainer_form_title_en'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm" required>
                                </div>
                                <div dir="rtl">
                                    <label class="block text-sm font-medium text-gray-700">Form Title (Arabic)</label>
                                    <input type="text" name="trainer_form_title_ar" value="{{ $settings['trainer_form_title_ar'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm text-right" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Form Notes / Requirements (English)</label>
                                    <textarea name="trainer_form_notes_en" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm" required>{{ $settings['trainer_form_notes_en'] ?? '' }}</textarea>
                                </div>
                                <div class="md:col-span-2" dir="rtl">
                                    <label class="block text-sm font-medium text-gray-700">Form Notes / Requirements (Arabic)</label>
                                    <textarea name="trainer_form_notes_ar" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm text-right" required>{{ $settings['trainer_form_notes_ar'] ?? '' }}</textarea>
                                </div>
                                <!-- Input Labels -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name Label (English)</label>
                                    <input type="text" name="trainer_label_name_en" value="{{ $settings['trainer_label_name_en'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm" required>
                                </div>
                                <div dir="rtl">
                                    <label class="block text-sm font-medium text-gray-700">Name Label (Arabic)</label>
                                    <input type="text" name="trainer_label_name_ar" value="{{ $settings['trainer_label_name_ar'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm text-right" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email Label (English)</label>
                                    <input type="text" name="trainer_label_email_en" value="{{ $settings['trainer_label_email_en'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm" required>
                                </div>
                                <div dir="rtl">
                                    <label class="block text-sm font-medium text-gray-700">Email Label (Arabic)</label>
                                    <input type="text" name="trainer_label_email_ar" value="{{ $settings['trainer_label_email_ar'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm text-right" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone Label (English)</label>
                                    <input type="text" name="trainer_label_phone_en" value="{{ $settings['trainer_label_phone_en'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm" required>
                                </div>
                                <div dir="rtl">
                                    <label class="block text-sm font-medium text-gray-700">Phone Label (Arabic)</label>
                                    <input type="text" name="trainer_label_phone_ar" value="{{ $settings['trainer_label_phone_ar'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm text-right" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Country Label (English)</label>
                                    <input type="text" name="trainer_label_country_en" value="{{ $settings['trainer_label_country_en'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm" required>
                                </div>
                                <div dir="rtl">
                                    <label class="block text-sm font-medium text-gray-700">Country Label (Arabic)</label>
                                    <input type="text" name="trainer_label_country_ar" value="{{ $settings['trainer_label_country_ar'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm text-right" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Help Topic Label (English)</label>
                                    <input type="text" name="trainer_label_help_en" value="{{ $settings['trainer_label_help_en'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm" required>
                                </div>
                                <div dir="rtl">
                                    <label class="block text-sm font-medium text-gray-700">Help Topic Label (Arabic)</label>
                                    <input type="text" name="trainer_label_help_ar" value="{{ $settings['trainer_label_help_ar'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-gold sm:text-sm text-right" required>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-gray-100">
                            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yemdat-brown hover:bg-yemdat-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yemdat-gold transition-colors">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

    </div>
</x-admin-layout>
