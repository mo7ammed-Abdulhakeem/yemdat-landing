<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12 flex flex-col justify-center sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="w-24 h-24 mx-auto bg-white rounded-full flex items-center justify-center p-2 mb-4 shadow-sm border border-gray-100">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Yemdat Logo" class="h-16 w-auto object-contain" onerror="this.outerHTML='<span class=\'text-2xl font-bold text-yemdat-brown\'>Y</span>'">
            </div>

            <h2 class="mt-2 text-center text-3xl font-extrabold text-ink leading-tight">
                {{ app()->getLocale() == 'ar' ? 'إنشاء حساب جديد' : 'Create an Account' }}
            </h2>
            <p class="mt-2 text-center text-sm text-ink-soft">
                {{ app()->getLocale() == 'ar' ? 'أو' : 'Or' }}
                <a href="{{ route('public.login') }}" class="font-bold text-primary hover:text-accent-hover transition">
                    {{ app()->getLocale() == 'ar' ? 'تسجيل الدخول إلى حسابك' : 'sign in to your existing account' }}
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <x-ui.card padding="py-8 px-4 sm:px-10">
                @if ($errors->any())
                    <x-ui.alert variant="danger" :title="app()->getLocale() == 'ar' ? 'عذراً!' : 'Whoops!'" class="mb-6">
                        <ul class="mt-1 list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                @endif

                <form class="space-y-6" action="{{ route('register.post') }}" method="POST">
                    @csrf

                    <h3 class="text-xl font-bold text-primary border-b border-gray-100 pb-2">
                        {{ app()->getLocale() == 'ar' ? 'بيانات تسجيل الدخول' : 'Login Credentials' }}
                    </h3>

                    <div class="space-y-6">
                        <x-ui.input name="full_name" :label="__('membership.label_fullname')" :required="true" :placeholder="__('membership.placeholder_fullname')" />
                        <x-ui.input name="email" type="email" :label="__('membership.label_email')" :required="true" :placeholder="__('membership.placeholder_email')" />
                        <x-ui.input name="password" type="password" :label="app()->getLocale() == 'ar' ? 'كلمة المرور' : 'Password'" :required="true" />
                        <x-ui.input name="password_confirmation" type="password" :label="app()->getLocale() == 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password'" :required="true" />
                    </div>

                    <h3 class="text-xl font-bold text-primary border-b border-gray-100 pb-2 mt-8">
                        {{ app()->getLocale() == 'ar' ? 'بيانات العضوية' : 'Membership Details' }}
                    </h3>

                    <div class="space-y-6" x-data="{ specialty: '{{ old('specialty') }}' }">
                        <!-- Phone -->
                        <div class="space-y-1">
                            <x-ui.label :required="true">{{ __('membership.label_phone') }}</x-ui.label>
                            <div class="flex gap-2" dir="ltr">
                                <input type="text" name="phone_code" value="{{ old('phone_code', '+967') }}" required placeholder="+967"
                                    class="text-center rounded-btn bg-surface-raised border border-border text-ink shadow-sm px-2 py-2 focus:outline-none focus:ring-2 focus:border-primary focus:ring-accent/50"
                                    style="width: 80px; flex: none;">
                                <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required placeholder="{{ __('membership.placeholder_phone') }}"
                                    class="flex-1 rounded-btn bg-surface-raised border border-border text-ink shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:border-primary focus:ring-accent/50">
                            </div>
                            @error('phone_number')<p class="text-xs text-danger">{{ $message }}</p>@enderror
                        </div>

                        <!-- Country of Residence (TomSelect) -->
                        @php
                            $countries = [
                                "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Mauritania", "Mauritius", "Mexico", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Macedonia", "Norway", "Oman", "Pakistan", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Somalia", "South Africa", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tunisia", "Turkey", "Turkmenistan", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
                            ];
                        @endphp
                        <x-ui.select name="country" id="country-select" :label="__('membership.label_country')" :required="true">
                            <option value="">{{ __('membership.select_country') }}</option>
                            @foreach($countries as $c)
                                <option value="{{ $c }}" {{ old('country') == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </x-ui.select>

                        <!-- Gender -->
                        <x-ui.select name="gender" :label="app()->getLocale() == 'ar' ? 'الجنس' : 'Gender'" :required="true">
                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>{{ app()->getLocale() == 'ar' ? 'اختر الجنس' : 'Select Gender' }}</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'ذكر' : 'Male' }}</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>{{ app()->getLocale() == 'ar' ? 'أنثى' : 'Female' }}</option>
                        </x-ui.select>

                        <!-- Education Level -->
                        <x-ui.select name="education_level" :label="__('membership.education_level')" :required="true">
                            <option value="" disabled {{ old('education_level') ? '' : 'selected' }}>{{ __('membership.education_level') }}</option>
                            <option value="High School" {{ old('education_level') == 'High School' ? 'selected' : '' }}>{{ __('membership.edu_high_school') }}</option>
                            <option value="Bachelor's Degree" {{ old('education_level') == "Bachelor's Degree" ? 'selected' : '' }}>{{ __('membership.edu_bachelor') }}</option>
                            <option value="Master's Degree" {{ old('education_level') == "Master's Degree" ? 'selected' : '' }}>{{ __('membership.edu_master') }}</option>
                            <option value="PhD" {{ old('education_level') == 'PhD' ? 'selected' : '' }}>{{ __('membership.edu_phd') }}</option>
                            <option value="Other" {{ old('education_level') == 'Other' ? 'selected' : '' }}>{{ __('membership.edu_other') }}</option>
                        </x-ui.select>

                        <!-- Specialty -->
                        <x-ui.select name="specialty" :label="__('membership.label_speciality')" :required="true" x-model="specialty">
                            <option value="" disabled {{ old('specialty') ? '' : 'selected' }}>{{ __('membership.select_speciality') }}</option>
                            @foreach($specialties as $s)
                                <option value="{{ $s->slug }}" {{ old('specialty') == $s->slug ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </x-ui.select>

                        <!-- Other Specialty -->
                        <div x-show="specialty === 'other'" style="display: none;">
                            <x-ui.input name="specialty_other" :label="__('membership.specialty_other')" :placeholder="__('membership.specialty_placeholder')" />
                        </div>

                        <!-- Membership Type -->
                        <x-ui.select name="membership_type" :label="__('membership.label_membership_type')" :required="true">
                            <option value="" disabled {{ old('membership_type') ? '' : 'selected' }}>{{ __('membership.select_membership_type') }}</option>
                            @foreach($tiers as $tier)
                                <option value="{{ $tier->slug }}" {{ old('membership_type') === $tier->slug ? 'selected' : '' }}>
                                    {{ app()->getLocale() == 'ar' ? $tier->name_ar : $tier->name_en }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </div>

                    <div class="mt-8 pt-4">
                        <x-ui.button variant="accent" size="lg" type="submit" class="w-full">
                            {{ __('membership.btn_submit') }}
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
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
        /* Match the design-system field styling (border / radius / surface tokens) */
        .ts-control {
            border: 1px solid #E6DFD2 !important;
            border-radius: 0.625rem !important;
            background-color: #FFFFFF !important;
            min-height: 42px !important;
            display: flex !important;
            align-items: center !important;
        }
        .ts-control.focus {
            border-color: #593E2D !important;
            box-shadow: 0 0 0 2px rgba(242, 203, 87, 0.5) !important;
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
