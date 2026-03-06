<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex items-center gap-4">
                <a href="{{ route('profile.show') }}" class="text-gray-500 hover:text-yemdat-brown">
                    <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900 leading-tight">
                    {{ app()->getLocale() == 'ar' ? 'تعديل الملف الشخصي' : 'Edit Profile' }}
                </h1>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative">
                    <strong class="font-bold">{{ app()->getLocale() == 'ar' ? 'عذراً!' : 'Whoops!' }}</strong>
                    <span class="block sm:inline">{{ app()->getLocale() == 'ar' ? 'حدثت بعض الأخطاء.' : 'Something went wrong.' }}</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @csrf
                @method('PUT')
                
                <div class="p-8 space-y-8">
                    
                    <!-- Core Info -->
                    <div>
                        <h3 class="text-lg font-bold text-yemdat-brown mb-4 border-b border-gray-100 pb-2">
                            {{ app()->getLocale() == 'ar' ? 'المعلومات الأساسية' : 'Basic Information' }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Full Name -->
                            <div>
                                <label for="full_name" class="block text-sm font-bold text-gray-700">{{ app()->getLocale() == 'ar' ? 'الاسم الكامل' : 'Full Name' }}</label>
                                <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $member->full_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                            </div>

                            <!-- Email (LOCKED) -->
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-700">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                                <input type="email" id="email" value="{{ $member->email }}" class="mt-1 block w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 shadow-sm cursor-not-allowed" disabled>
                                <p class="text-xs text-gray-500 mt-1">{{ app()->getLocale() == 'ar' ? 'لا يمكن تغيير البريد الإلكتروني لدواعي الأمان.' : 'Email address cannot be changed for security reasons.' }}</p>
                            </div>

                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div>
                        <h3 class="text-lg font-bold text-yemdat-brown mb-4 border-b border-gray-100 pb-2">
                            {{ app()->getLocale() == 'ar' ? 'بيانات الاتصال' : 'Contact Details' }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Phone -->
                            <div class="col-span-1">
                                <label class="block text-sm font-bold text-gray-700">{{ app()->getLocale() == 'ar' ? 'رقم الهاتف' : 'Phone Number' }} <span class="text-red-500">*</span></label>
                                <div class="mt-1 flex gap-2" dir="ltr">
                                    <input type="text" name="phone_code" value="{{ old('phone_code', $member->phone_code) }}" placeholder="+967" class="text-center px-2 rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required style="width: 80px; flex: none;">
                                    <input type="text" name="phone_number" value="{{ old('phone_number', $member->phone_number) }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                                </div>
                            </div>

                            <!-- Country of Residence -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700">{{ __('membership.label_country') }} <span class="text-red-500">*</span></label>
                                @php
                                    $countries = [
                                        "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Mauritania", "Mauritius", "Mexico", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Macedonia", "Norway", "Oman", "Pakistan", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Somalia", "South Africa", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tunisia", "Turkey", "Turkmenistan", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
                                    ];
                                @endphp
                                <select id="country-select" name="country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                                    <option value="">{{ __('membership.select_country') }}</option>
                                    @foreach($countries as $c)
                                        <option value="{{ $c }}" {{ old('country', $member->country) == $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700">{{ app()->getLocale() == 'ar' ? 'الجنس' : 'Gender' }} <span class="text-red-500">*</span></label>
                                <select name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                                    <option value="Male" {{ old('gender', $member->gender) == 'Male' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'ذكر' : 'Male' }}</option>
                                    <option value="Female" {{ old('gender', $member->gender) == 'Female' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'أنثى' : 'Female' }}</option>
                                </select>
                            </div>
                            
                            <!-- Bio -->
                            <div class="md:col-span-2">
                                <label for="bio" class="block text-sm font-bold text-gray-700">{{ app()->getLocale() == 'ar' ? 'نبذة تعريفية' : 'Biography' }}</label>
                                <textarea name="bio" id="bio" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50">{{ old('bio', $member->bio) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">{{ app()->getLocale() == 'ar' ? 'اكتب نبذة قصيرة عن نفسك لتظهر في ملفك الشخصي.' : 'Write a short bio to display on your profile.' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Info -->
                    <div>
                        <h3 class="text-lg font-bold text-yemdat-brown mb-4 border-b border-gray-100 pb-2">
                            {{ app()->getLocale() == 'ar' ? 'المعلومات المهنية' : 'Professional Information' }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Education Level -->
                            <div>
                                <label for="education_level" class="block text-sm font-bold text-gray-700">{{ app()->getLocale() == 'ar' ? 'المستوى التعليمي' : 'Education Level' }} <span class="text-red-500">*</span></label>
                                <select name="education_level" id="education_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" required>
                                    <option value="High School" {{ old('education_level', $member->education_level) == 'High School' ? 'selected' : '' }}>{{ __('membership.edu_high_school') }}</option>
                                    <option value="Bachelor's Degree" {{ old('education_level', $member->education_level) == "Bachelor's Degree" ? 'selected' : '' }}>{{ __('membership.edu_bachelor') }}</option>
                                    <option value="Master's Degree" {{ old('education_level', $member->education_level) == "Master's Degree" ? 'selected' : '' }}>{{ __('membership.edu_master') }}</option>
                                    <option value="PhD" {{ old('education_level', $member->education_level) == 'PhD' ? 'selected' : '' }}>{{ __('membership.edu_phd') }}</option>
                                    <option value="Other" {{ old('education_level', $member->education_level) == 'Other' ? 'selected' : '' }}>{{ __('membership.edu_other') }}</option>
                                </select>
                            </div>

                            <!-- Specialty -->
                            <div>
                                <label for="specialty" class="block text-sm font-bold text-gray-700">{{ __('membership.label_speciality') }} <span class="text-red-500">*</span></label>
                                <select name="specialty" id="specialty" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" onchange="toggleSpecialtyOther()" required>
                                    <option value="Data Management" {{ old('specialty', $member->specialty) == 'Data Management' ? 'selected' : '' }}>{{ __('membership.spec_data_mgmt') }}</option>
                                    <option value="Data Governance" {{ old('specialty', $member->specialty) == 'Data Governance' ? 'selected' : '' }}>{{ __('membership.spec_governance') }}</option>
                                    <option value="Artificial Intelligence" {{ old('specialty', $member->specialty) == 'Artificial Intelligence' ? 'selected' : '' }}>{{ __('membership.spec_ai') }}</option>
                                    <option value="Data Analytics" {{ old('specialty', $member->specialty) == 'Data Analytics' ? 'selected' : '' }}>{{ __('membership.spec_analytics') }}</option>
                                    <option value="Other" {{ old('specialty', $member->specialty) == 'Other' ? 'selected' : '' }}>{{ __('membership.spec_other') }}</option>
                                </select>
                            </div>
                            
                            <!-- Other Specialty -->
                            <div id="specialty_other_wrapper" class="{{ old('specialty', $member->specialty) === 'Other' ? '' : 'hidden' }}">
                                <label for="specialty_other" class="block text-sm font-bold text-gray-700">{{ __('membership.specialty_other') }}</label>
                                <input type="text" name="specialty_other" id="specialty_other" value="{{ old('specialty_other', $member->specialty_other) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50">
                            </div>

                            <!-- LinkedIn Profile -->
                            <div class="md:col-span-2">
                                <label for="linkedin_url" class="block text-sm font-bold text-gray-700">{{ app()->getLocale() == 'ar' ? 'رابط حساب لينكد إن (اختياري)' : 'LinkedIn Profile URL (Optional)' }}</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $member->linkedin_url) }}" placeholder="https://linkedin.com/in/username" class="flex-1 block w-full min-w-0 rounded-none rounded-r-md sm:text-sm border-gray-300 focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="px-8 space-y-8 mt-8 border-t border-gray-100 pt-8">
                    <!-- Update Password -->
                    <div>
                        <h3 class="text-lg font-bold text-yemdat-brown mb-4 border-b border-gray-100 pb-2">
                            {{ app()->getLocale() == 'ar' ? 'تحديث كلمة المرور' : 'Update Password' }}
                        </h3>
                        <p class="text-xs text-gray-500 mb-4">{{ app()->getLocale() == 'ar' ? 'اترك هذه الحقول فارغة إذا كنت لا ترغب في تغيير كلمة المرور.' : 'Leave these fields blank if you do not wish to change your password.' }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-bold text-gray-700">{{ app()->getLocale() == 'ar' ? 'كلمة المرور الجديدة' : 'New Password' }}</label>
                                <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" autocomplete="new-password">
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-gray-700">{{ app()->getLocale() == 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yemdat-brown focus:ring focus:ring-yemdat-gold focus:ring-opacity-50" autocomplete="new-password">
                            </div>

                        </div>
                    </div>
                </div>

                <div class="px-8 py-5 bg-gray-50 mt-8 border-t border-gray-100 flex justify-end gap-3 rounded-b-2xl">
                    <a href="{{ route('profile.show') }}" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition">
                        {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                    </a>
                    <button type="submit" class="px-6 py-2 bg-yemdat-brown text-white font-bold rounded-xl hover:bg-yemdat-gold transition shadow-sm">
                        {{ app()->getLocale() == 'ar' ? 'حفظ التغييرات' : 'Save Changes' }}
                    </button>
                </div>
            </form>

            <!-- Account Deletion -->
            <div class="mt-8 bg-white rounded-2xl shadow-sm overflow-hidden mb-8" style="border: 1px solid #fee2e2;">
                <div class="p-8">
                    <h3 class="text-lg font-bold mb-2" style="color: #dc2626;">
                        {{ app()->getLocale() == 'ar' ? 'حذف حسابك (سيتم مسح بياناتك نهائياً)' : 'Delete your account and your data will be deleted' }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">
                        {{ app()->getLocale() == 'ar' ? 'بمجرد حذف حسابك، سيتم مسح جميع بياناتك بشكل نهائي. للمتابعة، انقر على الزر أدناه وسنرسل لك رمز تحقق إلى بريدك الإلكتروني.' : 'Once your account is deleted, all of its resources and data will be permanently deleted. To proceed, click the button below and we will send a verification code to your email.' }}
                    </p>
                    <form action="{{ route('profile.delete.request') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full md:w-auto px-6 py-3 text-white font-bold rounded-xl transition shadow-sm inline-flex justify-center items-center gap-2" style="background-color: #dc2626; border: 1px solid #b91c1c;" onclick="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد أنك تريد طلب حذف الحساب؟' : 'Are you sure you want to request account deletion?' }}');">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            {{ app()->getLocale() == 'ar' ? 'طلب حذف الحساب' : 'Request Account Deletion' }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <script>
        function toggleSpecialtyOther() {
            const select = document.getElementById('specialty');
            const wrapper = document.getElementById('specialty_other_wrapper');
            if (select.value === 'Other') {
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('country-select')) {
                new TomSelect("#country-select", {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            }
        });
    </script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        html[dir="rtl"] .ts-wrapper,
        html[dir="rtl"] .ts-control, 
        html[dir="rtl"] .ts-dropdown {
            text-align: right !important;
            direction: rtl !important;
        }
        html[dir="rtl"] .ts-control {
            padding-right: 12px !important;
            padding-left: 30px !important;
        }
        html[dir="rtl"] .ts-control:after {
            right: auto !important;
            left: 12px !important;
        }
        .ts-control {
            border: 1px solid #D1D5DB !important;
            border-radius: 0.5rem !important;
            background-color: #F9FAFB !important;
            min-height: 42px !important;
            display: flex !important;
            align-items: center !important;
        }
        .ts-control.focus {
            border-color: #CA8E34 !important;
            box-shadow: 0 0 0 2px rgba(202, 142, 52, 0.2) !important;
        }
    </style>
</x-app-layout>
