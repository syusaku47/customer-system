<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTProjects extends Migration
{
    /**
     * Run the migrations.<br>
     * 案件データ
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_projects', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('案件ID');
            $table->integer('company_id')->comment('会社ID');
            $table->integer('internal_id')->comment('内部ID');
            $table->bigInteger('customer_id')->comment('顧客ID')->unsigned();
            $table->string('field_name', 255)->nullable()->comment('現場名称');
            $table->string('name',255)->nullable()->comment('案件名');
//            $table->string('field_address',255)->nullable()->comment('現場住所');
            $table->char('post_no',7)->comment('郵便番号');
            $table->char('jisx0401_code',2)->comment('JISX0401コード');
            $table->char('jisx0402_code',5)->comment('JISX0402コード');
            $table->integer('prefecture')->comment('都道府県');
            $table->string('city',255)->comment('市区町村');
            $table->string('address',255)->comment('地名、番地');
            $table->string('building_name',255)->nullable()->comment('建物名等');
            $table->string('field_tel_no',255)->nullable()->comment('現場電話番号');
            $table->string('field_fax_no',255)->nullable()->comment('現場FAX');
            $table->string('employee_cd',8)->comment('案件担当者');

            $table->date('construction_period_start')->nullable()->comment('受注工期（開始）');
            $table->date('construction_period_end')->nullable()->comment('受注工期（終了）');
            $table->date('construction_start_date')->nullable()->comment('着工予定日');
            $table->date('construction_date')->nullable()->comment('着工日');
            $table->date('completion_end_date')->nullable()->comment('完工予定日');
            $table->date('completion_date')->nullable()->comment('完工日');
            $table->integer('source_id')->default(0)->nullable()->comment('発生源');
            $table->string('contract_no',255)->nullable()->comment('契約番号');
            $table->date('contract_date')->nullable()->comment('契約日');
            $table->date('failure_date')->nullable()->comment('失注日');

            $table->decimal('mitumori_kin',12, 2)->default(0.0)->nullable()->comment('見積金額');
//            $table->decimal('expected_amount',20, 2)->nullable()->comment('見込み金額');
            $table->decimal('order_price',12, 2)->default(0.0)->nullable()->comment('受注金額（契約金額）');
            $table->decimal('jyutyu_kin_1',12, 2)->default(0.0)->nullable()->comment('受注金額増減1');
            $table->decimal('jyutyu_kin_2',12, 2)->default(0.0)->nullable()->comment('受注金額増減2');
            $table->decimal('jyutyu_kin_all',12, 2)->default(0.0)->nullable()->comment('最終受注金額');
            $table->decimal('jyutyu_kin_first',12, 2)->default(0.0)->nullable()->comment('最初の受注金額');
            $table->decimal('jyutyu_genka',12, 2)->default(0.0)->nullable()->comment('受注時原価');
            $table->decimal('hattyu_genka',12, 2)->default(0.0)->nullable()->comment('発注原価');
            $table->decimal('saisyu_genka',12, 2)->default(0.0)->nullable()->comment('最終原価');
            $table->decimal('jyutyu_sorieki',12, 2)->default(0.0)->nullable()->comment('受注時粗利益');
            $table->decimal('saisyu_sorieki',12, 2)->default(0.0)->nullable()->comment('最終粗利益');
            $table->decimal('kakutei_genka',12, 2)->default(0.0)->nullable()->comment('確定原価');
            $table->smallInteger('execution_end')->default(0)->nullable()->comment('実行終了');
            $table->integer('project_rank')->nullable()->comment('案件ランク（見込みランク）');
//            $table->integer('project_store')->nullable()->comment('案件担当店舗');
//            $table->integer('project_representative')->nullable()->comment('案件担当者');

            $table->date('construction_execution_date')->nullable()->comment('着工式実施日');
            $table->date('completion_execution_date')->nullable()->comment('完工式実施日');
            $table->date('enquete_send_dt')->nullable()->comment('アンケート送信日');
            $table->date('enquete_dt')->nullable()->comment('アンケート回収日');
            $table->date('complete_date')->nullable()->comment('完了日');
            $table->text('saisyu_meisai')->nullable()->comment('最終見積明細');
            $table->text('remarks')->nullable()->comment('備考');
            $table->text('failure_remarks')->nullable()->comment('失注備考');
            $table->date('entry_dt')->nullable()->comment('登録日');
            $table->date('datetime_upd')->nullable()->comment('更新日');
            $table->string('last_updated_by',255)->nullable()->comment('最終更新者');

            $table->integer('koujibui1')->default(0)->nullable()->comment('工事部位1');
            $table->integer('koujibui2')->default(0)->nullable()->comment('工事部位2');
            $table->integer('koujibui3')->default(0)->nullable()->comment('工事部位3');
            $table->integer('koujibui4')->default(0)->nullable()->comment('工事部位4');
            $table->integer('koujibui5')->default(0)->nullable()->comment('工事部位5');
            $table->integer('koujibui6')->default(0)->nullable()->comment('工事部位6');
            $table->integer('koujibui7')->default(0)->nullable()->comment('工事部位7');
            $table->integer('koujibui8')->default(0)->nullable()->comment('工事部位8');
            $table->integer('koujibui9')->default(0)->nullable()->comment('工事部位9');
            $table->integer('koujibui10')->default(0)->nullable()->comment('工事部位10');
            $table->integer('koujibui11')->default(0)->nullable()->comment('工事部位11');
            $table->integer('koujibui12')->default(0)->nullable()->comment('工事部位12');
            $table->integer('koujibui13')->default(0)->nullable()->comment('工事部位13');
            $table->integer('koujibui14')->default(0)->nullable()->comment('工事部位14');
            $table->integer('koujibui15')->default(0)->nullable()->comment('工事部位15');
            $table->integer('koujibui16')->default(0)->nullable()->comment('工事部位16');
            $table->integer('koujibui17')->default(0)->nullable()->comment('工事部位17');
            $table->integer('koujibui18')->default(0)->nullable()->comment('工事部位18');
            $table->integer('koujibui19')->default(0)->nullable()->comment('工事部位19');
            $table->integer('koujibui20')->default(0)->nullable()->comment('工事部位20');

            $table->tinyInteger('valid_flag')->default(1)->nullable()->comment('有効フラグ');
            $table->integer('enquete_point')->default(0)->nullable()->comment('アンケート得点');
            $table->integer('failure_cause')->default(0)->nullable()->comment('失注理由');
            $table->text('billing_remarks')->nullable()->comment('請求備考');
            $table->date('cancel_date')->nullable()->comment('案件キャンセル日');
            $table->text('cancel_reason')->nullable()->comment('案件キャンセル理由');
            $table->integer('employee_id')->nullable()->comment('案件担当社員ID');
            $table->integer('store_id')->nullable()->comment('案件担当店舗');
            $table->text('construction_parts')->nullable()->comment('工事部位');
            $table->string('lat', 20)->nullable()->comment('緯度');
            $table->string('lng', 20)->nullable()->comment('経度');

//            $table->decimal('order_detail1', 20, 2)->nullable()->comment('受注詳細（追加1 – 最終原価）');
//            $table->decimal('order_detail2', 20, 2)->nullable()->comment('受注詳細（追加2 – 最終原価）');
//            $table->string('construction_status', 15)->nullable()->comment('工事状況');
//            $table->tinyInteger('alert_flag')->nullable()->comment('アラートフラグ');
//            $table->timestamp('created_at')->comment('登録日時');
//            $table->timestamp('updated_at')->nullable()->comment('更新日時');
            // 外部キー成約
            $table->foreign('customer_id')->references('id')->on('t_customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_projects');
    }
}
