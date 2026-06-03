<?php

namespace App\Mail;

use App\Models\BusinessSetting;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PolicyUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected string $policyName, protected string $url)
    {
    }

    public function build()
    {
        $companyName = BusinessSetting::where('key', 'business_name')->first()?->value ?? config('app.name');
        $data = EmailTemplate::where('type', 'user')->where('email_type', 'policy_update')->first();
        $template = $data ? $data->email_template : 5;
        $variables = [
            '{company_name}' => $companyName,
            '{policy_name}' => $this->policyName,
        ];
        $title = strtr($data['title'] ?? 'Policy update from {company_name}', $variables);
        $body = strtr($data['body'] ?? 'We updated our {policy_name}. Please review the latest version.', $variables);
        $footerText = strtr($data['footer_text'] ?? '', $variables);
        $copyrightText = strtr($data['copyright_text'] ?? '', $variables);

        return $this->subject($this->policyName . ' Updated')
            ->view('email-templates.new-email-format-' . $template, [
                'company_name' => $companyName,
                'data' => $data,
                'title' => $title,
                'body' => $body,
                'footer_text' => $footerText,
                'copyright_text' => $copyrightText,
                'url' => $this->url,
            ]);
    }
}
