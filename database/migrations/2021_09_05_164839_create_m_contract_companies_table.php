<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMContractCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_contract_companies', function (Blueprint $table) {
            $table->increments('id')->comment('契約会社マスタID');
            $table->string('name', 30)->comment('名称');
            $table->string('mail_address', 50)->comment('メールアドレス（管理者用）');
            $table->string('password', 255)->comment('パスワード');
            $table->string('tel_no', 20)->nullable()->comment('電話番号');
            $table->string('post_no', 7)->nullable()->comment('郵便番号');
            $table->string('prefecture', 10)->nullable()->comment('都道府県');
            $table->string('city',  30)->nullable()->comment('市区町村');
            $table->string('address', 60)->nullable()->comment('地名・番地');
            $table->string('building_name', 64)->nullable()->comment('建築名等');
            $table->tinyInteger('status')->nullable()->default(2)->comment('ステータス（有償／無償）');
            $table->tinyInteger('authority1')->nullable()->default(0)->comment('権限');
            $table->tinyInteger('authority2')->nullable()->default(0)->comment('権限2');
            $table->tinyInteger('authority3')->nullable()->default(0)->comment('権限3');
            $table->tinyInteger('authority4')->nullable()->default(0)->comment('権限4');
            $table->tinyInteger('authority5')->nullable()->default(0)->comment('権限5');
            $table->integer('data_limit')->nullable()->comment('データ制限');
            $table->integer('accounts')->nullable()->default(0)->comment('アカウント数');
            $table->tinyInteger('is_valid')->nullable()->default(1)->comment('有効フラグ');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_contract_companies');
    }
}
