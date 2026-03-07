<div style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; color: #333;">
    <h2 style="color: #634731;">Thank you for your interest!</h2>
    <p>Dear {{ $trainerRequest->name }},</p>
    <p>We have successfully received your application to become a Trainer at <strong>Yemdat</strong>.</p>
    <p>Our administrative team is currently reviewing your submission regarding <em>"{!! strip_tags($trainerRequest->help_topic) !!}"</em>.</p>
    <p>We appreciate the time you took to share your expertise with us, and we will be in touch soon at {{ $trainerRequest->email }} or {{ $trainerRequest->phone_number }}.</p>
    
    <p style="margin-top: 30px;">Best regards,<br>The Yemdat Team</p>
    <hr style="border:none; border-top: 1px solid #ddd; margin: 20px 0;">
    <p style="font-size: 11px; color: #aaa;">This is an automated confirmation email. Please do not reply directly to this message.</p>
</div>
