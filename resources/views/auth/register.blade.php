<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12 flex flex-col justify-center sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="w-24 h-24 mx-auto bg-white rounded-full flex items-center justify-center p-2 mb-4 shadow-sm border border-gray-100">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Yemdat Logo" class="h-16 w-auto object-contain" onerror="this.outerHTML='<span class=\'text-2xl font-bold text-yemdat-brown\'>Y</span>'">
            </div>
            
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900 leading-tight">
                {{ app()->getLocale() == 'ar' ? 'إنشاء حساب جديد' : 'Create an Account' }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ app()->getLocale() == 'ar' ? 'أو' : 'Or' }}
                <a href="{{ route('public.login') }}" class="font-bold text-yemdat-brown hover:text-yemdat-gold transition">
                    {{ app()->getLocale() == 'ar' ? 'تسجيل الدخول إلى حسابك' : 'sign in to your existing account' }}
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-sm sm:rounded-2xl border border-gray-100 sm:px-10">
                
                @if ($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative">
                        <strong class="font-bold">{{ app()->getLocale() == 'ar' ? 'عذراً!' : 'Whoops!' }}</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('register.post') }}" method="POST">
                    @csrf
                    
                    <h3 class="text-xl font-bold text-yemdat-brown border-b border-gray-100 pb-2">
                        {{ app()->getLocale() == 'ar' ? 'بيانات تسجيل الدخول' : 'Login Credentials' }}
                    </h3>

                    <div class="space-y-6">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.label_fullname') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition" placeholder="{{ __('membership.placeholder_fullname') }}">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.label_email') }} <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition rtl:text-right" placeholder="{{ __('membership.placeholder_email') }}">
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ app()->getLocale() == 'ar' ? 'كلمة المرور' : 'Password' }} <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition">
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ app()->getLocale() == 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }} <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition">
                        </div>
                    </div>


                    <h3 class="text-xl font-bold text-yemdat-brown border-b border-gray-100 pb-2 mt-8">
                        {{ app()->getLocale() == 'ar' ? 'بيانات العضوية' : 'Membership Details' }}
                    </h3>

                    <div class="space-y-6" x-data="{ specialty: '{{ old('specialty') }}' }">
                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.label_phone') }} <span class="text-red-500">*</span></label>
                            <div class="flex gap-2" dir="ltr">
                                <input type="text" name="phone_code" value="{{ old('phone_code', '+967') }}" required placeholder="+967" class="w-1/4 sm:w-1/5 text-center rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition">
                                <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required class="flex-1 rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition" placeholder="{{ __('membership.placeholder_phone') }}">
                            </div>
                        </div>

                        <!-- Country of Residence -->
                        <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.label_country') }} <span class="text-red-500">*</span></label>
                            @php
                                $countries = [
                                    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Mauritania", "Mauritius", "Mexico", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Macedonia", "Norway", "Oman", "Pakistan", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Somalia", "South Africa", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tunisia", "Turkey", "Turkmenistan", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
                                ];
                            @endphp
                            <select id="country-select" name="country" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition" placeholder="{{ __('membership.select_country') }}">
                                <option value="">{{ __('membership.select_country') }}</option>
                                @foreach($countries as $c)
                                    <option value="{{ $c }}" {{ old('country') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ app()->getLocale() == 'ar' ? 'الجنس' : 'Gender' }} <span class="text-red-500">*</span></label>
                            <select name="gender" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition">
                                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>{{ app()->getLocale() == 'ar' ? 'اختر الجنس' : 'Select Gender' }}</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'ذكر' : 'Male' }}</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'أنثى' : 'Female' }}</option>
                            </select>
                        </div>
                        
                        <!-- Education Level -->
                        <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.education_level') }} <span class="text-red-500">*</span></label>
                            <select name="education_level" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition">
                                <option value="" disabled {{ old('education_level') ? '' : 'selected' }}>{{ __('membership.education_level') }}</option>
                                <option value="High School" {{ old('education_level') == 'High School' ? 'selected' : '' }}>{{ __('membership.edu_high_school') }}</option>
                                <option value="Bachelor's Degree" {{ old('education_level') == "Bachelor's Degree" ? 'selected' : '' }}>{{ __('membership.edu_bachelor') }}</option>
                                <option value="Master's Degree" {{ old('education_level') == "Master's Degree" ? 'selected' : '' }}>{{ __('membership.edu_master') }}</option>
                                <option value="PhD" {{ old('education_level') == 'PhD' ? 'selected' : '' }}>{{ __('membership.edu_phd') }}</option>
                                <option value="Other" {{ old('education_level') == 'Other' ? 'selected' : '' }}>{{ __('membership.edu_other') }}</option>
                            </select>
                        </div>

                        <!-- Specialty -->
                        <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.label_speciality') }} <span class="text-red-500">*</span></label>
                            <select name="specialty" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition" x-model="specialty">
                                <option value="" disabled {{ old('specialty') ? '' : 'selected' }}>{{ __('membership.select_speciality') }}</option>
                                <option value="Data Management" {{ old('specialty') == 'Data Management' ? 'selected' : '' }}>{{ __('membership.spec_data_mgmt') }}</option>
                                <option value="Data Governance" {{ old('specialty') == 'Data Governance' ? 'selected' : '' }}>{{ __('membership.spec_governance') }}</option>
                                <option value="Artificial Intelligence" {{ old('specialty') == 'Artificial Intelligence' ? 'selected' : '' }}>{{ __('membership.spec_ai') }}</option>
                                <option value="Data Analytics" {{ old('specialty') == 'Data Analytics' ? 'selected' : '' }}>{{ __('membership.spec_analytics') }}</option>
                                <option value="Other" {{ old('specialty') == 'Other' ? 'selected' : '' }}>{{ __('membership.spec_other') }}</option>
                            </select>
                        </div>
                        
                        <!-- Other Specialty -->
                        <div x-show="specialty === 'Other'" style="display: none;">
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.specialty_other') }}</label>
                            <input type="text" name="specialty_other" value="{{ old('specialty_other') }}" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition" placeholder="{{ __('membership.specialty_placeholder') }}">
                        </div>

                        <!-- Membership Type -->
                        <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.label_membership_type') }} <span class="text-red-500">*</span></label>
                            <select name="membership_type" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition">
                                <option value="" disabled {{ old('membership_type') ? '' : 'selected' }}>{{ __('membership.select_membership_type') }}</option>
                                @foreach($tiers as $tier)
                                    <option value="{{ $tier->slug }}" {{ old('membership_type') === $tier->slug ? 'selected' : '' }}>
                                        {{ app()->getLocale() == 'ar' ? $tier->name_ar : $tier->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 pt-4">
                        <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-lg shadow-sm text-lg font-bold text-yemdat-brown bg-yemdat-gold hover:bg-white hover:text-yemdat-brown hover:border-yemdat-gold transition">
                            {{ __('membership.btn_submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TomSelect Library for Searchable Dropdown -->
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#country-select", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    </script>
</x-app-layout>
