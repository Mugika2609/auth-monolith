<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('books', function (Blueprint $table) {
        $table->foreignId('publisher_id')->nullable()->after('author_id')->constrained('publishers')->nullOnDelete();
        $table->softDeletes();
    });
}

public function down(): void
{
    Schema::table('books', function (Blueprint $table) {
        $table->dropConstrainedForeignId('publisher_id');
        $table->dropSoftDeletes();
    });
}
};
