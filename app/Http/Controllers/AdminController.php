<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Member;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Defaults
        $totalMembers = 0;
        $totalMessages = 0;
        $totalEvents = 0;
        $upcomingEventsCount = 0;
        $membershipTiers = collect();
        $memberCounts = collect();
        $genderCounts = collect();
        $membersJoinedToday = 0;
        $members = collect();
        $messages = collect();

        // 1. Members Permission
        if ($user->hasPermission('members')) {
            $totalMembers = Member::count();
            $membershipTiers = \App\Models\MembershipTier::all();
            $memberCounts = Member::select('membership_type', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('membership_type')
                ->pluck('total', 'membership_type');

            $genderCounts = Member::select('gender', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->whereNotNull('gender')
                ->groupBy('gender')
                ->pluck('total', 'gender');

            $membersJoinedToday = Member::whereDate('created_at', \Carbon\Carbon::today())->count();
            $members = Member::latest()->take(10)->get();
        }

        // 2. Messages Permission
        if ($user->hasPermission('messages')) {
            $totalMessages = Contact::count();
            $messages = Contact::latest()->take(10)->get();
        }

        // 3. Events Permission
        if ($user->hasPermission('events')) {
            $totalEvents = \App\Models\Event::count();
            $upcomingEventsCount = \App\Models\Event::where('start_date', '>', now())->count();
        }

        return view('admin.dashboard', compact('totalMembers', 'totalMessages', 'totalEvents', 'upcomingEventsCount', 'members', 'messages', 'membershipTiers', 'memberCounts', 'genderCounts', 'membersJoinedToday'));
    }

    public function showMember(Member $member)
    {
        $member->load('events');
        return view('admin.members.show', compact('member'));
    }

    public function exportSingleMember(Member $member)
    {
        $member->load('events');
        $fileName = 'member_' . \Illuminate\Support\Str::slug($member->full_name) . '_' . date('Y_m_d') . '.csv';

        // Add BOM for Excel UTF-8 Arabic support
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'ID', 'Full Name', 'Email', 'Phone Code', 'Phone Number',
            'Gender', 'Country', 'Education Level', 'Specialty', 'Other Specialty',
            'Membership Type', 'Bio', 'Joined Date', 'Total Registered Events', 'Event Names'
        ];

        $callback = function () use ($member, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM for utf-8
            fputcsv($file, $columns);

            $eventsJoined = $member->events->pluck('title_en')->implode(' | ');

            $row = [
                $member->id,
                $member->full_name,
                $member->email,
                $member->phone_code,
                $member->phone_number,
                $member->gender ?? 'N/A',
                $member->country,
                $member->education_level,
                $member->specialty,
                $member->specialty_other ?? '',
                $member->membershipTier ? $member->membershipTier->name : ucfirst($member->membership_type),
                $member->bio ?? '',
                $member->created_at->format('Y-m-d H:i:s'),
                $member->events->count(),
                $eventsJoined
            ];

            fputcsv($file, $row);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function editMember(Member $member)
    {
        $countries = [
            'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan',
            'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi',
            'Cabo Verde', 'Cambodia', 'Cameroon', 'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo (Congo-Brazzaville)', 'Costa Rica', 'Croatia', 'Cuba', 'Cyprus', 'Czechia (Czech Republic)',
            'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic',
            'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini (fmr. "Swaziland")', 'Ethiopia',
            'Fiji', 'Finland', 'France',
            'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana',
            'Haiti', 'Holy See', 'Honduras', 'Hungary',
            'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy',
            'Jamaica', 'Japan', 'Jordan',
            'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan',
            'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg',
            'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar (formerly Burma)',
            'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea', 'North Macedonia', 'Norway',
            'Oman',
            'Pakistan', 'Palau', 'Palestine State', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal',
            'Qatar',
            'Romania', 'Russia', 'Rwanda',
            'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria',
            'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu',
            'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States of America', 'Uruguay', 'Uzbekistan',
            'Vanuatu', 'Venezuela', 'Vietnam',
            'Yemen',
            'Zambia', 'Zimbabwe'
        ];

        $membershipTiers = \App\Models\MembershipTier::all();
        return view('admin.members.edit', compact('member', 'countries', 'membershipTiers'));
    }

    public function updateMember(Request $request, Member $member)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:members,email,' . $member->id,
            'phone_number' => 'required|string|max:20|unique:members,phone_number,' . $member->id,
            'gender' => 'nullable|string|in:male,female',
            'country' => 'required|string',
            'education_level' => 'required|string',
            'specialty' => 'required|string',
            'membership_type' => 'required|string|exists:membership_tiers,slug',
        ]);

        $member->update($validated);

        return redirect()->route('admin.members.show', $member)->with('success', 'Member details updated successfully.');
    }

    public function showMessage(Contact $contact)
    {
        return view('admin.messages.show', compact('contact'));
    }

    public function members(Request $request)
    {
        $query = Member::latest();

        if ($request->has('search') && $request->get('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $members = $query->paginate(20)->withQueryString();
        return view('admin.members.index', compact('members'));
    }

    public function messages(Request $request)
    {
        $query = Contact::latest();

        if ($request->has('search') && $request->get('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(20)->withQueryString();
        return view('admin.messages.index', compact('messages'));
    }

    public function destroyMember(Member $member)
    {
        $member->delete();
        return redirect()->route('admin.members.index')->with('success', 'Member deleted successfully.');
    }

    public function destroyMessage(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully.');
    }

    public function exportMembers()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=members.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Full Name', 'Email', 'Phone Code', 'Phone Number', 'Gender', 'Country', 'Education Level', 'Specialty', 'Other Specialty', 'Membership Type', 'Joined Date'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // Add BOM for Excel UTF-8
            fputcsv($file, $columns);

            Member::with('membershipTier')->chunk(100, function ($members) use ($file) {
                    foreach ($members as $member) {
                        $row['Full Name'] = $member->full_name;
                        $row['Email'] = $member->email;
                        $row['Phone Code'] = $member->phone_code;
                        $row['Phone Number'] = $member->phone_number;
                        $row['Gender'] = ucfirst($member->gender ?? '');
                        $row['Country'] = $member->country;
                        $row['Education Level'] = $member->education_level;
                        $row['Specialty'] = $member->specialty;
                        $row['Other Specialty'] = $member->specialty_other;
                        $row['Membership Type'] = $member->membershipTier ? $member->membershipTier->name_en : ucfirst($member->membership_type);
                        $row['Joined Date'] = $member->created_at->format('Y-m-d H:i:s');

                        fputcsv($file, array_values($row));
                    }
                }
                );

                fclose($file);
            };

        return response()->stream($callback, 200, $headers);
    }

    public function exportMessages()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=contact_messages.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Sender Name', 'Email', 'Subject', 'Message', 'Sent Date'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // Add BOM for Excel UTF-8
            fputcsv($file, $columns);

            Contact::chunk(100, function ($messages) use ($file) {
                    foreach ($messages as $message) {
                        $row['Sender Name'] = $message->name;
                        $row['Email'] = $message->email;
                        $row['Subject'] = $message->subject;
                        $row['Message'] = $message->message;
                        $row['Sent Date'] = $message->created_at->format('Y-m-d H:i:s');

                        fputcsv($file, array_values($row));
                    }
                }
                );

                fclose($file);
            };

        return response()->stream($callback, 200, $headers);
    }
    public function settings()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except('_token', '_method');

        // Handle checkbox for notification bar enabled (if unchecked, it won't be in request)
        if (!isset($data['notification_bar_enabled'])) {
            $data['notification_bar_enabled'] = '0';
        }

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
