<x-mail::message>
# {{ app()->getLocale() == 'ar' ? 'تأكيد عملية حذف الحساب' : 'Account Deletion Confirmation' }}

{{ app()->getLocale() == 'ar' ? 'لقد طلبت حذف حسابك. يرجى استخدام رمز التحقق أدناه لتأكيد هذه العملية:' : 'You requested to delete your account. Please use the verification code below to confirm this action:' }}

<x-mail::panel>
**{{ $otp }}**
</x-mail::panel>

{{ app()->getLocale() == 'ar' ? 'إذا لم تقم بهذا الطلب، يمكنك تجاهل هذه الرسالة بأمان للحفاظ على حسابك.' : 'If you did not request this, you can safely ignore this email to keep your account safe.' }}

{{ app()->getLocale() == 'ar' ? 'شكراً لك،' : 'Thanks,' }}<br>
{{ config('app.name') }}
</x-mail::message>
