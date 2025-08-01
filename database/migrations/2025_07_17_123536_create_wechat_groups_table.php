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
        Schema::create('wechat_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('province_id')->comment('所属省份ID');
            $table->unsignedBigInteger('city_id')->comment('所属城市ID');
            $table->unsignedBigInteger('district_id')->nullable()->comment('所属县区ID');
            $table->string('category_name', 100)->comment('群分类名称');
            $table->string('group_name', 200)->comment('群名称');
            $table->string('qr_code_logo', 500)->nullable()->comment('群二维码LOGO');
            $table->string('group_number', 50)->unique()->comment('群编号');
            $table->integer('capacity')->default(500)->comment('群容量');
            $table->integer('current_members')->default(0)->comment('当前人数');
            $table->integer('paid_members')->default(0)->comment('付费人数');
            $table->boolean('is_hot')->default(false)->comment('是否热门');
            $table->decimal('price', 10, 2)->default(9.9)->comment('入群费用');
            $table->text('description')->nullable()->comment('群描述');
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->timestamps();
            
            $table->foreign('province_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('regions')->onDelete('cascade');
            
            $table->index(['province_id', 'city_id']);
            $table->index('is_hot');
            $table->index('category_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wechat_groups');
    }
};
