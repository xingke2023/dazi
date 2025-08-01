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
        Schema::create('user_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('group_id')->comment('群ID');
            $table->timestamp('joined_at')->nullable()->comment('加入时间');
            $table->decimal('paid_amount', 10, 2)->default(0)->comment('付费金额');
            $table->boolean('is_active')->default(true)->comment('是否有效');
            $table->text('notes')->nullable()->comment('备注');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('wechat_groups')->onDelete('cascade');
            
            $table->unique(['user_id', 'group_id']);
            $table->index(['user_id', 'is_active']);
            $table->index(['group_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_groups');
    }
};
