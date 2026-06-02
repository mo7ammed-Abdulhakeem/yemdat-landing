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

                @php
                    // Re-open the wizard on the step that holds the first validation error.
                    $step1Fields = ['full_name', 'email', 'password', 'password_confirmation'];
                    $step2Fields = ['phone_code', 'phone_number', 'country', 'gender', 'education_level'];
                    $errorStep = $errors->hasAny($step1Fields) ? 1 : ($errors->hasAny($step2Fields) ? 2 : ($errors->any() ? 3 : 1));
                @endphp

                <form class="space-y-6" action="{{ route('register.post') }}" method="POST" x-data="{ step: {{ $errorStep }} }">
                    @csrf

                    <!-- Progress indicator -->
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-ink-soft">
                            {{ app()->getLocale() == 'ar' ? 'الخطوة' : 'Step' }}
                            <span x-text="step"></span>
                            {{ app()->getLocale() == 'ar' ? 'من 3' : 'of 3' }}
                        </p>
                        <div class="flex items-center gap-1.5">
                            <span class="h-2 w-8 rounded-full transition-colors" :class="step >= 1 ? 'bg-accent' : 'bg-gray-200'"></span>
                            <span class="h-2 w-8 rounded-full transition-colors" :class="step >= 2 ? 'bg-accent' : 'bg-gray-200'"></span>
                            <span class="h-2 w-8 rounded-full transition-colors" :class="step >= 3 ? 'bg-accent' : 'bg-gray-200'"></span>
                        </div>
                    </div>

                    <!-- ───────── Step 1: Login Credentials ───────── -->
                    <div x-show="step === 1" class="space-y-6">
                        <h3 class="text-xl font-bold text-primary border-b border-gray-100 pb-2">
                            {{ app()->getLocale() == 'ar' ? 'بيانات تسجيل الدخول' : 'Login Credentials' }}
                        </h3>

                        <x-ui.input name="full_name" :label="__('membership.label_fullname')" :required="true" :placeholder="__('membership.placeholder_fullname')" />
                        <x-ui.input name="email" type="email" :label="__('membership.label_email')" :required="true" :placeholder="__('membership.placeholder_email')" />
                        <x-ui.input name="password" type="password" :label="app()->getLocale() == 'ar' ? 'كلمة المرور' : 'Password'" :required="true" />
                        <x-ui.input name="password_confirmation" type="password" :label="app()->getLocale() == 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password'" :required="true" />
                    </div>

                    <!-- ───────── Step 2: Contact ───────── -->
                    <div x-show="step === 2" x-cloak class="space-y-6">
                        <h3 class="text-xl font-bold text-primary border-b border-gray-100 pb-2">
                            {{ app()->getLocale() == 'ar' ? 'بيانات التواصل' : 'Contact Information' }}
                        </h3>

                        <!-- Phone (intl-tel-input: dial code auto-detected, synced into hidden phone_code) -->
                        <div class="space-y-1">
                            <x-ui.label :required="true">{{ __('membership.label_phone') }}</x-ui.label>
                            <div dir="ltr">
                                <input type="hidden" name="phone_code" id="phone_code" value="{{ old('phone_code', '+967') }}">
                                <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required placeholder="{{ __('membership.placeholder_phone') }}"
                                    class="block w-full rounded-btn bg-surface-raised border border-border text-ink shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:border-primary focus:ring-accent/50">
                            </div>
                            @error('phone_number')<p class="text-xs text-danger">{{ $message }}</p>@enderror
                            @error('phone_code')<p class="text-xs text-danger">{{ $message }}</p>@enderror
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
                    </div>

                    <!-- ───────── Step 3: Membership ───────── -->
                    <div x-show="step === 3" x-cloak class="space-y-6" x-data="{ specialty: '{{ old('specialty') }}' }">
                        <h3 class="text-xl font-bold text-primary border-b border-gray-100 pb-2">
                            {{ app()->getLocale() == 'ar' ? 'بيانات العضوية' : 'Membership Details' }}
                        </h3>

                        <!-- Specialty (TomSelect — searchable) -->
                        <x-ui.select name="specialty" id="specialty-select" :label="__('membership.label_speciality')" :required="true" x-model="specialty">
                            <option value="" disabled {{ old('specialty') ? '' : 'selected' }}>{{ __('membership.select_speciality') }}</option>
                            @foreach($specialties as $s)
                                <option value="{{ $s->slug }}" {{ old('specialty') == $s->slug ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </x-ui.select>

                        <!-- Other Specialty -->
                        <div x-show="specialty === 'other'" style="display: none;">
                            <x-ui.input name="specialty_other" :label="__('membership.specialty_other')" :placeholder="__('membership.specialty_placeholder')" />
                        </div>

                        <!-- Membership Type (pre-selected from a tier card, if any) -->
                        <x-ui.select name="membership_type" :label="__('membership.label_membership_type')" :required="true">
                            <option value="" disabled {{ old('membership_type', $selectedTier ?? '') ? '' : 'selected' }}>{{ __('membership.select_membership_type') }}</option>
                            @foreach($tiers as $tier)
                                <option value="{{ $tier->slug }}" {{ old('membership_type', $selectedTier ?? '') === $tier->slug ? 'selected' : '' }}>
                                    {{ app()->getLocale() == 'ar' ? $tier->name_ar : $tier->name_en }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </div>

                    <!-- Wizard navigation -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <x-ui.button type="button" variant="ghost" x-show="step > 1" x-cloak x-on:click="step--">
                            {{ app()->getLocale() == 'ar' ? 'السابق' : 'Back' }}
                        </x-ui.button>
                        <x-ui.button type="button" variant="accent" x-show="step < 3" x-on:click="step++" class="ms-auto">
                            {{ app()->getLocale() == 'ar' ? 'التالي' : 'Next' }}
                        </x-ui.button>
                        <x-ui.button variant="accent" size="lg" type="submit" x-show="step === 3" x-cloak class="ms-auto">
                            {{ __('membership.btn_submit') }}
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </div>

    <!-- TomSelect (searchable country / specialty) -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <!-- intl-tel-input (phone code with IP-based auto-detection) -->
    <link href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

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
        /* Full width so the field renders correctly even when initialised inside a hidden wizard step */
        .ts-wrapper { width: 100% !important; }
        .iti { width: 100% !important; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tsCountry = new TomSelect("#country-select", {
                create: false,
                sortField: { field: "text", direction: "asc" }
            });

            // Searchable specialty picker. TomSelect hides the native <select>, so we
            // re-dispatch a native `change` on it whenever the value changes — this keeps
            // Alpine's x-model (and the "Other" free-text toggle) in sync.
            const tsSpecialty = new TomSelect("#specialty-select", {
                create: false,
                sortField: { field: "text", direction: "asc" },
                onChange: function () {
                    this.input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });

            // The TomSelect controls are initialised while their wizard step is hidden
            // (display:none), which can leave them zero-width. Re-sync once they're shown.
            document.querySelector('form').addEventListener('click', function () {
                requestAnimationFrame(() => { tsCountry.sync(); tsSpecialty.sync(); });
            });

            // Phone: auto-detect the user's country (and dial code) by IP, then keep the
            // hidden phone_code field in sync so the existing backend validation is unchanged.
            const phoneInput = document.querySelector('#phone_number');
            const phoneCode = document.querySelector('#phone_code');
            if (phoneInput && window.intlTelInput) {
                const iti = window.intlTelInput(phoneInput, {
                    initialCountry: 'auto',
                    separateDialCode: true,
                    geoIpLookup: function (callback) {
                        fetch('https://ipapi.co/json')
                            .then(res => res.json())
                            .then(data => callback(data && data.country_code ? data.country_code : 'ye'))
                            .catch(() => callback('ye'));
                    },
                    utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js'
                });

                const syncDialCode = function () {
                    const data = iti.getSelectedCountryData();
                    if (data && data.dialCode) {
                        phoneCode.value = '+' + data.dialCode;
                    }
                };
                phoneInput.addEventListener('countrychange', syncDialCode);
                phoneInput.closest('form').addEventListener('submit', syncDialCode);
            }
        });
    </script>
</x-app-layout>
