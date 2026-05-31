<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $copyright = html_entity_decode('&copy; 2026 Fasta Deliveries. All rights reserved.', ENT_QUOTES, 'UTF-8');

        if (Schema::hasTable('email_templates') && Schema::hasColumn('email_templates', 'copyright_text')) {
            DB::table('email_templates')->update(['copyright_text' => $copyright]);
        }

        if (Schema::hasTable('translations')) {
            DB::table('translations')
                ->where('key', 'copyright_text')
                ->where('translationable_type', 'App\Models\EmailTemplate')
                ->update(['value' => $copyright]);
        }
    }

    public function down(): void
    {
        // Intentionally blank. Previous template text can vary per language/template.
    }
};
