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
        Schema::table('wechat_groups', function (Blueprint $table) {
            $table->string('nickname', 50)->nullable()->comment('群昵称（如：群1、群2等）')->after('group_name');
            $table->text('tags')->nullable()->comment('群标签（多个标签用逗号分隔）')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wechat_groups', function (Blueprint $table) {
            $table->dropColumn(['nickname', 'tags']);
        });
    }
};