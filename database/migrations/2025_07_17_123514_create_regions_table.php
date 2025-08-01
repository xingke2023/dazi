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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('地区名称');
            $table->string('code', 20)->unique()->comment('地区编码');
            $table->tinyInteger('level')->comment('级别：1省，2市，3县区');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('上级地区ID');
            $table->integer('sort_order')->default(0)->comment('排序');
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->timestamps();
            
            $table->foreign('parent_id')->references('id')->on('regions')->onDelete('cascade');
            $table->index(['parent_id', 'level']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
