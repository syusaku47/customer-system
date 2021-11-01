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
            $table->unsignedInteger('employee_id')->nullable()->comment('社員ID');

            $table->string('name', 255)->comment('名称');
            $table->integer('accunts_m')->nullable()->default(3)->comment('決算月');
            $table->string('prefix', 3)->nullable()->comment('社員IDの前に付く接頭詞');
            $table->integer('accounts')->nullable()->default(10)->comment('アカウント数');
            $table->string('operation_year', 4)->nullable()->default(2011)->comment('運用開始年');
            $table->string('operation_month', 2)->nullable()->default(5)->comment('運用開始月');
            $table->date('datetime_ins')->nullable()->comment('登録日時');
            $table->bigInteger('capacity')->comment('容量');

            $table->string('tel_no', 255)->nullable()->comment('電話番号');
            $table->string('post_no', 7)->nullable()->comment('郵便番号');
            $table->integer('prefecture')->nullable()->comment('都道府県');
            $table->string('city',  255)->nullable()->comment('市区町村');
            $table->string('address', 255)->nullable()->comment('地名・番地');
            $table->string('building_name', 255)->nullable()->comment('建築名等');
            $table->tinyInteger('status')->nullable()->default(2)->comment('ステータス（有償／無償）');
            $table->tinyInteger('authority1')->nullable()->default(0)->comment('権限');
            $table->tinyInteger('authority2')->nullable()->default(0)->comment('権限2');
            $table->tinyInteger('authority3')->nullable()->default(0)->comment('権限3');
            $table->tinyInteger('authority4')->nullable()->default(0)->comment('権限4');
            $table->tinyInteger('authority5')->nullable()->default(0)->comment('権限5');
            $table->tinyInteger('is_valid')->nullable()->default(1)->comment('有効フラグ');

//            $table->foreign('employee_id')->references('id')->on('m_employees')->onDelete('cascade');;
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
