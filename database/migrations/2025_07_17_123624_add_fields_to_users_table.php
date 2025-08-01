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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->comment('联系电话');
            $table->string('nickname', 100)->nullable()->comment('昵称');
            $table->unsignedBigInteger('referrer_id')->nullable()->comment('推荐人ID');
            $table->string('address', 500)->nullable()->comment('地址');
            $table->text('dating_requirements')->nullable()->comment('交友要求说明');
            $table->text('hobbies')->nullable()->comment('爱好');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->comment('性别');
            $table->date('birth_date')->nullable()->comment('出生年月日');
            $table->string('id_card', 18)->nullable()->comment('身份证号码');
            $table->decimal('paid_amount', 10, 2)->default(0)->comment('已付费金额');
            $table->enum('member_level', ['bronze', 'silver', 'gold', 'diamond'])->default('bronze')->comment('会员级别');
            $table->text('notes')->nullable()->comment('备注');
            
            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('set null');
            $table->index('referrer_id');
            $table->index('member_level');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referrer_id']);
            $table->dropIndex(['referrer_id']);
            $table->dropIndex(['member_level']);
            $table->dropIndex(['phone']);
            
            $table->dropColumn([
                'phone',
                'nickname',
                'referrer_id',
                'address',
                'dating_requirements',
                'hobbies',
                'gender',
                'birth_date',
                'id_card',
                'paid_amount',
                'member_level',
                'notes'
            ]);
        });
    }
};
