<?php

/*
|--------------------------------------------------------------------------
| Manageable public pages
|--------------------------------------------------------------------------
|
| Registry of public pages an admin can turn on/off from
| Settings → Page Visibility. Each entry lists the route names it owns
| (the EnsurePageActive middleware 404s them when inactive) and a default
| active state (used until an admin saves an explicit value).
|
| The on/off state is stored in the `settings` table under "page_{key}_active".
| Core pages (home, events, contact) are intentionally omitted so links from
| the home page / paths never break.
|
*/

return [
    'paths' => [
        'label_en' => 'Learning Paths',
        'label_ar' => 'مسارات التعلّم',
        'routes' => ['paths.index', 'paths.show'],
        'default' => false, // hidden until we're ready to make it live
    ],
    'news' => [
        'label_en' => 'News',
        'label_ar' => 'الأخبار',
        'routes' => ['news', 'news.show'],
        'default' => true,
    ],
    'membership' => [
        'label_en' => 'Membership',
        'label_ar' => 'العضوية',
        'routes' => ['membership', 'membership.store'],
        'default' => true,
    ],
    'trainer' => [
        'label_en' => 'Be a Trainer',
        'label_ar' => 'كن مدرباً',
        'routes' => ['trainer.create', 'trainer.store'],
        'default' => true,
    ],
];
