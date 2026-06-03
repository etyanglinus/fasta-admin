<?php

use App\Models\BusinessSetting;
use App\Models\EmailTemplate;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        EmailTemplate::firstOrCreate(
            ['type' => 'user', 'email_type' => 'policy_update'],
            [
                'email_template' => 5,
                'title' => 'Policy update from {company_name}',
                'body' => 'We updated our {policy_name}. Please review the latest version.',
                'button_name' => 'Review policy',
                'footer_text' => 'Thank you for staying informed.',
                'copyright_text' => '{company_name}',
            ]
        );

        BusinessSetting::firstOrCreate(
            ['key' => 'policy_update_mail_status_user'],
            ['value' => '1']
        );
    }

    public function down(): void
    {
        EmailTemplate::where('type', 'user')->where('email_type', 'policy_update')->delete();
        BusinessSetting::where('key', 'policy_update_mail_status_user')->delete();
    }
};
