<div class="border-t border-gray-100 bg-gray-50/60 px-8 py-6">
    <dl class="space-y-4">
        <div>
            <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $isAr ? 'الاسم' : 'Awarded to' }}</dt>
            <dd class="text-base font-bold text-gray-900">{{ optional($certificate->member)->full_name ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $isAr ? 'البرنامج' : 'Program' }}</dt>
            <dd class="text-base text-gray-900">{{ optional($certificate->event)->title ?? '—' }}</dd>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $isAr ? 'تاريخ الإصدار' : 'Issued on' }}</dt>
                <dd class="text-sm text-gray-900" dir="ltr">{{ optional($certificate->issued_at)->format('F j, Y') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $isAr ? 'الرقم التسلسلي' : 'Serial' }}</dt>
                <dd class="text-sm font-bold text-yemdat-brown" dir="ltr">{{ $certificate->serial }}</dd>
            </div>
        </div>
    </dl>
</div>
