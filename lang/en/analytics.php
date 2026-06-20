<?php

return [
    'title' => 'Analytics',

    'filters' => [
        'range' => 'Date range',
        'from' => 'From',
        'until' => 'Until',
        'ranges' => [
            'last_7' => 'Last 7 days',
            'last_30' => 'Last 30 days',
            'last_90' => 'Last 90 days',
            'last_365' => 'Last 12 months',
            'all' => 'All time',
            'custom' => 'Custom',
        ],
    ],

    'sections' => [
        'growth' => [
            'heading' => 'Growth & acquisition',
            'description' => 'How the community is growing over the selected period.',
        ],
        'events' => [
            'heading' => 'Event performance',
            'description' => 'Registrations, attendance and completion across events.',
        ],
        'demographics' => [
            'heading' => 'Member demographics',
            'description' => 'Who your members are — all-time snapshot.',
        ],
        'communications' => [
            'heading' => 'Engagement & communications',
            'description' => 'How members respond to your email broadcasts.',
        ],
        'operations' => [
            'heading' => 'Operations pipeline',
            'description' => 'Contact messages and trainer applications awaiting action.',
        ],
    ],

    'stats' => [
        'members' => 'Members',
        'new_members' => 'New members',
        'registrations' => 'Event registrations',
        'active_events' => 'Active events',
        'certificates' => 'Certificates issued',
        'verified_rate' => 'Email verification rate',
        'new_contacts' => 'New contact messages',
        'new_trainer_requests' => 'New trainer requests',
        'new_in_range' => ':count new in period',
        'vs_previous' => ':percent% vs previous period',
        'in_selected_period' => 'in the selected period',
        'upcoming_count' => ':count upcoming',
        'not_emailed' => ':count not yet emailed',
        'verified_of_total' => ':verified of :total members verified',
        'needs_reply' => 'awaiting first reply',
        'show_rate' => 'Show rate',
        'completion_rate' => 'Completion rate',
        'avg_registrations' => 'Avg registrations / event',
        'past_events' => 'Past events held',
        'attended_of_registered' => ':attended of :registered registered attended',
        'completed_of_attended' => ':completed of :attended attendees completed',
        'across_past_events' => 'across :count past events',
        'broadcasts_sent' => 'Broadcasts sent',
        'emails_delivered' => 'Emails delivered',
        'open_rate' => 'Open rate',
        'unsubscribes' => 'Unsubscribes',
        'opened_of_sent' => ':opened of :sent emails opened',
    ],

    'charts' => [
        'member_growth' => [
            'heading' => 'Member growth',
            'dataset' => 'New members',
        ],
        'registrations' => [
            'heading' => 'Event registrations over time',
            'dataset' => 'Registrations',
        ],
        'event_outcomes' => [
            'heading' => 'Attendance & completion (recent events)',
            'registered' => 'Registered',
            'attended' => 'Attended',
            'completed' => 'Completed',
        ],
        'format_breakdown' => [
            'heading' => 'Registrations by event type',
            'by_format' => 'By format',
            'by_level' => 'By level',
            'formats' => [
                'event' => 'Event',
                'workshop' => 'Workshop',
                'course' => 'Course',
            ],
            'levels' => [
                'beginner' => 'Beginner',
                'intermediate' => 'Intermediate',
                'advanced' => 'Advanced',
            ],
        ],
        'certificates' => [
            'heading' => 'Certificates issued',
            'issued' => 'Issued',
            'emailed' => 'Emailed',
        ],
        'tiers' => [
            'heading' => 'Members by tier',
        ],
        'funnel' => [
            'heading' => 'Member reach funnel',
            'total' => 'All members',
            'verified' => 'Email verified',
            'reachable' => 'Reachable (subscribed)',
        ],
        'pipeline' => [
            'heading' => 'Inbox pipeline',
            'contacts' => 'Contact messages',
            'trainer_requests' => 'Trainer requests',
        ],
        'gender' => [
            'heading' => 'Members by gender',
        ],
        'education' => [
            'heading' => 'Members by education level',
        ],
        'countries' => [
            'heading' => 'Top countries',
        ],
        'specialty' => [
            'heading' => 'Members by specialty',
        ],
        'top_events' => [
            'heading' => 'Top events by registrations',
        ],
    ],

    'tables' => [
        'upcoming_events' => [
            'heading' => 'Upcoming events',
            'columns' => [
                'title' => 'Event',
                'format' => 'Format',
                'level' => 'Level',
                'starts' => 'Starts',
                'registrations' => 'Registrations',
                'trainer' => 'Trainer',
            ],
            'empty' => 'No upcoming events scheduled.',
        ],
        'broadcasts' => [
            'heading' => 'Recent broadcasts',
            'columns' => [
                'subject' => 'Subject',
                'audience' => 'Audience',
                'sent_at' => 'Sent',
                'recipients' => 'Recipients',
                'opened' => 'Opened',
                'open_rate' => 'Open rate',
                'unsubscribed' => 'Unsubscribed',
            ],
            'empty' => 'No broadcasts sent yet.',
        ],
    ],

    'common' => [
        'all_time' => 'All time',
        'unspecified' => 'Not specified',
        'members' => 'Members',
        'gender' => [
            'male' => 'Male',
            'female' => 'Female',
        ],
        'education' => [
            'high_school' => 'High School',
            'diploma' => 'Diploma',
            'bachelor' => "Bachelor's Degree",
            'master' => "Master's Degree",
            'phd' => 'PhD',
            'other' => 'Other',
        ],
    ],
];
