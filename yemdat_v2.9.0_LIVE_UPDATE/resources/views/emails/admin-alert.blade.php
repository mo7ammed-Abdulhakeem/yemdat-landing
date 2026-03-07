<div style="font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; color: #333;">
    <h2 style="color: #634731;">New Contact Submission</h2>
    <p>A new message was received from the Yemdat Contact Us form:</p>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold; width: 120px;">Name</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $contact->firstname }} {{ $contact->surname }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Email</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $contact->email }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Phone</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $contact->phone_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Message</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $contact->message }}</td>
        </tr>
    </table>
    <p style="margin-top: 30px; font-size: 12px; color: #777;">This is an automated administrative notification from the Yemdat platform.</p>
</div>
