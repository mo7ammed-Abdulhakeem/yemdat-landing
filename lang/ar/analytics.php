<?php

return [
    'title' => 'التحليلات',

    'filters' => [
        'range' => 'الفترة الزمنية',
        'from' => 'من',
        'until' => 'إلى',
        'ranges' => [
            'last_7' => 'آخر 7 أيام',
            'last_30' => 'آخر 30 يوماً',
            'last_90' => 'آخر 90 يوماً',
            'last_365' => 'آخر 12 شهراً',
            'all' => 'كل الفترات',
            'custom' => 'فترة مخصصة',
        ],
    ],

    'sections' => [
        'growth' => [
            'heading' => 'النمو والاستقطاب',
            'description' => 'كيف ينمو المجتمع خلال الفترة المحددة.',
        ],
        'events' => [
            'heading' => 'أداء الفعاليات',
            'description' => 'التسجيلات والحضور وإتمام الفعاليات.',
        ],
        'demographics' => [
            'heading' => 'البيانات الديموغرافية للأعضاء',
            'description' => 'من هم أعضاؤك — لقطة لكل الفترات.',
        ],
        'communications' => [
            'heading' => 'التفاعل والتواصل',
            'description' => 'كيف يتفاعل الأعضاء مع رسائلك البريدية.',
        ],
        'operations' => [
            'heading' => 'سير العمليات',
            'description' => 'رسائل التواصل وطلبات المدربين التي تنتظر إجراءً.',
        ],
    ],

    'stats' => [
        'members' => 'الأعضاء',
        'new_members' => 'أعضاء جدد',
        'registrations' => 'التسجيلات في الفعاليات',
        'active_events' => 'الفعاليات النشطة',
        'certificates' => 'الشهادات الصادرة',
        'verified_rate' => 'نسبة توثيق البريد الإلكتروني',
        'new_contacts' => 'رسائل تواصل جديدة',
        'new_trainer_requests' => 'طلبات مدربين جديدة',
        'new_in_range' => ':count جديد خلال الفترة',
        'vs_previous' => ':percent% مقارنة بالفترة السابقة',
        'in_selected_period' => 'خلال الفترة المحددة',
        'upcoming_count' => ':count قادمة',
        'not_emailed' => ':count لم تُرسل بالبريد بعد',
        'verified_of_total' => ':verified من أصل :total عضو موثّق',
        'needs_reply' => 'بانتظار أول رد',
        'show_rate' => 'نسبة الحضور',
        'completion_rate' => 'نسبة الإتمام',
        'avg_registrations' => 'متوسط التسجيلات لكل فعالية',
        'past_events' => 'الفعاليات المنتهية',
        'attended_of_registered' => 'حضر :attended من أصل :registered مسجلاً',
        'completed_of_attended' => 'أتم :completed من أصل :attended حاضراً',
        'across_past_events' => 'عبر :count فعالية منتهية',
        'broadcasts_sent' => 'الرسائل الجماعية المرسلة',
        'emails_delivered' => 'رسائل البريد المسلّمة',
        'open_rate' => 'نسبة الفتح',
        'unsubscribes' => 'إلغاءات الاشتراك',
        'opened_of_sent' => 'فُتحت :opened من أصل :sent رسالة',
    ],

    'charts' => [
        'member_growth' => [
            'heading' => 'نمو الأعضاء',
            'dataset' => 'أعضاء جدد',
        ],
        'registrations' => [
            'heading' => 'التسجيلات في الفعاليات عبر الزمن',
            'dataset' => 'التسجيلات',
        ],
        'event_outcomes' => [
            'heading' => 'الحضور والإتمام (أحدث الفعاليات)',
            'registered' => 'مسجّل',
            'attended' => 'حضر',
            'completed' => 'أتم',
        ],
        'format_breakdown' => [
            'heading' => 'التسجيلات حسب نوع الفعالية',
            'by_format' => 'حسب النوع',
            'by_level' => 'حسب المستوى',
            'formats' => [
                'event' => 'فعالية',
                'workshop' => 'ورشة عمل',
                'course' => 'دورة تدريبية',
            ],
            'levels' => [
                'beginner' => 'مبتدئ',
                'intermediate' => 'متوسط',
                'advanced' => 'متقدم',
            ],
        ],
        'certificates' => [
            'heading' => 'الشهادات الصادرة',
            'issued' => 'صادرة',
            'emailed' => 'أُرسلت بالبريد',
        ],
        'tiers' => [
            'heading' => 'الأعضاء حسب فئة العضوية',
        ],
        'funnel' => [
            'heading' => 'قمع الوصول للأعضاء',
            'total' => 'جميع الأعضاء',
            'verified' => 'موثّقو البريد',
            'reachable' => 'يمكن الوصول إليهم (مشتركون)',
        ],
        'pipeline' => [
            'heading' => 'صندوق الوارد',
            'contacts' => 'رسائل التواصل',
            'trainer_requests' => 'طلبات المدربين',
        ],
        'gender' => [
            'heading' => 'الأعضاء حسب الجنس',
        ],
        'education' => [
            'heading' => 'الأعضاء حسب المستوى التعليمي',
        ],
        'countries' => [
            'heading' => 'أكثر الدول',
        ],
        'specialty' => [
            'heading' => 'الأعضاء حسب التخصص',
        ],
        'top_events' => [
            'heading' => 'أكثر الفعاليات تسجيلاً',
        ],
    ],

    'tables' => [
        'upcoming_events' => [
            'heading' => 'الفعاليات القادمة',
            'columns' => [
                'title' => 'الفعالية',
                'format' => 'النوع',
                'level' => 'المستوى',
                'starts' => 'تبدأ',
                'registrations' => 'التسجيلات',
                'trainer' => 'المدرب',
            ],
            'empty' => 'لا توجد فعاليات قادمة مجدولة.',
        ],
        'broadcasts' => [
            'heading' => 'أحدث الرسائل الجماعية',
            'columns' => [
                'subject' => 'الموضوع',
                'audience' => 'الجمهور',
                'sent_at' => 'تاريخ الإرسال',
                'recipients' => 'المستلمون',
                'opened' => 'فُتحت',
                'open_rate' => 'نسبة الفتح',
                'unsubscribed' => 'ألغوا الاشتراك',
            ],
            'empty' => 'لم تُرسل أي رسائل جماعية بعد.',
        ],
    ],

    'common' => [
        'all_time' => 'كل الفترات',
        'unspecified' => 'غير محدد',
        'members' => 'الأعضاء',
        'gender' => [
            'male' => 'ذكر',
            'female' => 'أنثى',
        ],
        'education' => [
            'high_school' => 'ثانوية عامة',
            'diploma' => 'دبلوم',
            'bachelor' => 'بكالوريوس',
            'master' => 'ماجستير',
            'phd' => 'دكتوراه',
            'other' => 'أخرى',
        ],
    ],
];
