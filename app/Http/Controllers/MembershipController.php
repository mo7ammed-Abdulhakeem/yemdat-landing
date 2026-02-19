<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function create()
    {
        $countries = [
            'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan',
            'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi',
            'Cabo Verde', 'Cambodia', 'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo (Crazzaville)', 'Congo (Democratic Republic)', 'Costa Rica', 'Cote d\'Ivoire', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic',
            'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic',
            'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia',
            'Fiji', 'Finland', 'France',
            'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana',
            'Haiti', 'Honduras', 'Hungary',
            'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy',
            'Jamaica', 'Japan', 'Jordan',
            'Kazakhstan', 'Kenya', 'Kiribati', 'Kosovo', 'Kuwait', 'Kyrgyzstan',
            'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg',
            'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar',
            'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea', 'North Macedonia', 'Norway',
            'Oman',
            'Pakistan', 'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal',
            'Qatar',
            'Romania', 'Russia', 'Rwanda',
            'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria',
            'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu',
            'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan',
            'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam',
            'Yemen',
            'Zambia', 'Zimbabwe'
        ];

        $phoneCodes = [
            '+93' => 'Afghanistan', '+355' => 'Albania', '+213' => 'Algeria', '+1-684' => 'American Samoa', '+376' => 'Andorra', '+244' => 'Angola', '+1-264' => 'Anguilla', '+672' => 'Antarctica', '+1-268' => 'Antigua and Barbuda', '+54' => 'Argentina', '+374' => 'Armenia', '+297' => 'Aruba', '+61' => 'Australia', '+43' => 'Austria', '+994' => 'Azerbaijan',
            '+1-242' => 'Bahamas', '+973' => 'Bahrain', '+880' => 'Bangladesh', '+1-246' => 'Barbados', '+375' => 'Belarus', '+32' => 'Belgium', '+501' => 'Belize', '+229' => 'Benin', '+1-441' => 'Bermuda', '+975' => 'Bhutan', '+591' => 'Bolivia', '+387' => 'Bosnia and Herzegovina', '+267' => 'Botswana', '+55' => 'Brazil', '+246' => 'British Indian Ocean Territory', '+1-284' => 'British Virgin Islands', '+673' => 'Brunei', '+359' => 'Bulgaria', '+226' => 'Burkina Faso', '+257' => 'Burundi',
            '+855' => 'Cambodia', '+237' => 'Cameroon', '+1' => 'Canada', '+238' => 'Cape Verde', '+1-345' => 'Cayman Islands', '+236' => 'Central African Republic', '+235' => 'Chad', '+56' => 'Chile', '+86' => 'China', '+61' => 'Christmas Island', '+61' => 'Cocos Islands', '+57' => 'Colombia', '+269' => 'Comoros', '+682' => 'Cook Islands', '+506' => 'Costa Rica', '+385' => 'Croatia', '+53' => 'Cuba', '+599' => 'Curacao', '+357' => 'Cyprus', '+420' => 'Czech Republic',
            '+243' => 'Democratic Republic of the Congo', '+45' => 'Denmark', '+253' => 'Djibouti', '+1-767' => 'Dominica', '+1-809' => 'Dominican Republic',
            '+670' => 'East Timor', '+593' => 'Ecuador', '+20' => 'Egypt', '+503' => 'El Salvador', '+240' => 'Equatorial Guinea', '+291' => 'Eritrea', '+372' => 'Estonia', '+251' => 'Ethiopia', '+500' => 'Falkland Islands', '+298' => 'Faroe Islands', '+679' => 'Fiji', '+358' => 'Finland', '+33' => 'France', '+689' => 'French Polynesia',
            '+241' => 'Gabon', '+220' => 'Gambia', '+995' => 'Georgia', '+49' => 'Germany', '+233' => 'Ghana', '+350' => 'Gibraltar', '+30' => 'Greece', '+299' => 'Greenland', '+1-473' => 'Grenada', '+1-671' => 'Guam', '+502' => 'Guatemala', '+44-1481' => 'Guernsey', '+224' => 'Guinea', '+245' => 'Guinea-Bissau', '+592' => 'Guyana',
            '+509' => 'Haiti', '+504' => 'Honduras', '+852' => 'Hong Kong', '+36' => 'Hungary', '+354' => 'Iceland', '+91' => 'India', '+62' => 'Indonesia', '+98' => 'Iran', '+964' => 'Iraq', '+353' => 'Ireland', '+44-1624' => 'Isle of Man', '+972' => 'Israel', '+39' => 'Italy', '+225' => 'Ivory Coast',
            '+1-876' => 'Jamaica', '+81' => 'Japan', '+44-1534' => 'Jersey', '+962' => 'Jordan', '+7' => 'Kazakhstan', '+254' => 'Kenya', '+686' => 'Kiribati', '+383' => 'Kosovo', '+965' => 'Kuwait', '+996' => 'Kyrgyzstan',
            '+856' => 'Laos', '+371' => 'Latvia', '+961' => 'Lebanon', '+266' => 'Lesotho', '+231' => 'Liberia', '+218' => 'Libya', '+423' => 'Liechtenstein', '+370' => 'Lithuania', '+352' => 'Luxembourg',
            '+853' => 'Macau', '+389' => 'Macedonia', '+261' => 'Madagascar', '+265' => 'Malawi', '+60' => 'Malaysia', '+960' => 'Maldives', '+223' => 'Mali', '+356' => 'Malta', '+692' => 'Marshall Islands', '+222' => 'Mauritania', '+230' => 'Mauritius', '+262' => 'Mayotte', '+52' => 'Mexico', '+691' => 'Micronesia', '+373' => 'Moldova', '+377' => 'Monaco', '+976' => 'Mongolia', '+382' => 'Montenegro', '+1-664' => 'Montserrat', '+212' => 'Morocco', '+258' => 'Mozambique', '+95' => 'Myanmar',
            '+264' => 'Namibia', '+674' => 'Nauru', '+977' => 'Nepal', '+31' => 'Netherlands', '+599' => 'Netherlands Antilles', '+687' => 'New Caledonia', '+64' => 'New Zealand', '+505' => 'Nicaragua', '+227' => 'Niger', '+234' => 'Nigeria', '+683' => 'Niue', '+850' => 'North Korea', '+1-670' => 'Northern Mariana Islands', '+47' => 'Norway',
            '+968' => 'Oman', '+92' => 'Pakistan', '+680' => 'Palau', '+970' => 'Palestine', '+507' => 'Panama', '+675' => 'Papua New Guinea', '+595' => 'Paraguay', '+51' => 'Peru', '+63' => 'Philippines', '+64' => 'Pitcairn', '+48' => 'Poland', '+351' => 'Portugal', '+1-787' => 'Puerto Rico',
            '+974' => 'Qatar', '+242' => 'Republic of the Congo', '+262' => 'Reunion', '+40' => 'Romania', '+250' => 'Rwanda',
            '+590' => 'Saint Barthelemy', '+290' => 'Saint Helena', '+1-869' => 'Saint Kitts and Nevis', '+1-758' => 'Saint Lucia', '+508' => 'Saint Pierre and Miquelon', '+1-784' => 'Saint Vincent and the Grenadines', '+685' => 'Samoa', '+378' => 'San Marino', '+239' => 'Sao Tome and Principe', '+966' => 'Saudi Arabia', '+221' => 'Senegal', '+381' => 'Serbia', '+248' => 'Seychelles', '+232' => 'Sierra Leone', '+65' => 'Singapore', '+1-721' => 'Sint Maarten', '+421' => 'Slovakia', '+386' => 'Slovenia', '+677' => 'Solomon Islands', '+252' => 'Somalia', '+27' => 'South Africa', '+82' => 'South Korea', '+211' => 'South Sudan', '+34' => 'Spain', '+94' => 'Sri Lanka', '+249' => 'Sudan', '+597' => 'Suriname', '+47' => 'Svalbard and Jan Mayen', '+268' => 'Swaziland', '+46' => 'Sweden', '+41' => 'Switzerland', '+963' => 'Syria',
            '+886' => 'Taiwan', '+992' => 'Tajikistan', '+255' => 'Tanzania', '+66' => 'Thailand', '+228' => 'Togo', '+690' => 'Tokelau', '+676' => 'Tonga', '+1-868' => 'Trinidad and Tobago', '+216' => 'Tunisia', '+90' => 'Turkey', '+993' => 'Turkmenistan', '+1-649' => 'Turks and Caicos Islands', '+688' => 'Tuvalu',
            '+1-340' => 'U.S. Virgin Islands', '+256' => 'Uganda', '+380' => 'Ukraine', '+971' => 'United Arab Emirates', '+44' => 'United Kingdom', '+1' => 'United States', '+598' => 'Uruguay', '+998' => 'Uzbekistan',
            '+678' => 'Vanuatu', '+379' => 'Vatican', '+58' => 'Venezuela', '+84' => 'Vietnam', '+681' => 'Wallis and Futuna', '+212' => 'Western Sahara', '+967' => 'Yemen', '+260' => 'Zambia', '+263' => 'Zimbabwe'
        ];

        // Fetch active membership tiers from the database
        try {
            $membershipTiers = \App\Models\MembershipTier::where('is_active', true)->orderBy('sort_order')->get();
        }
        catch (\Exception $e) {
            // Fallback if table doesn't exist yet (e.g. before migration)
            $membershipTiers = collect([]);
        }

        return view('membership', compact('countries', 'phoneCodes', 'membershipTiers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:members,email',
            'phone_code' => 'required|string|max:10',
            'phone_number' => 'required|string|max:20|unique:members,phone_number',
            'gender' => 'required|in:male,female',
            'country' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'specialty_other' => 'nullable|string|max:255|required_if:specialty,other',
            'education_level' => 'required|string|max:255',
            'membership_type' => 'required|string|exists:membership_tiers,slug',
        ], [
            'email.unique' => 'This email already exists and cannot be saved. Please change the email.',
            'phone_number.unique' => 'This phone number already exists and cannot be saved. Please change the phone number.',
        ]);

        Member::create($validated);

        return back()->with('success', 'Registration submitted successfully!');
    }

}
