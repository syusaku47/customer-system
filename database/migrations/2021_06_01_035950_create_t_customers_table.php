<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTCustomersTable extends Migration
{
    /**
     * Run the migrations.<br>
     * 顧客データ
     *
     * @return void
     */
    public function up()
    {

        Schema::create('t_customers', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('顧客ID');
            $table->integer('company_id')->comment('会社ID');
            $table->integer('internal_id')->comment('内部ID');
//            $table->integer('sales_shop')->nullable()->comment('営業担当（店舗）');
            $table->string('name',255)->comment('顧客_名称');
            $table->string('keisho',255)->nullable()->comment('顧客_敬称');
            $table->string('name_short',255)->nullable()->comment('顧客_略称');
            $table->string('furigana',255)->nullable()->comment('顧客_フリガナ');
            $table->string('tel_no',255)->comment('電話番号');
            $table->string('tel_no2',255)->nullable()->comment('電話番号2');
            $table->string('tel_no3',255)->nullable()->comment('電話番号3');
//            $table->tinyInteger('is_deficiency')->default(0)->nullable()->comment('不備情報のみ');
            $table->string('fax_no',255)->nullable()->comment('FAX番号');
            $table->string('kinkyu_tel',255)->nullable()->comment('緊急連絡先');
            $table->char('post_no',7)->comment('郵便番号');
            $table->char('jisx0401_code',2)->comment('JISX0401コード');
            $table->char('jisx0402_code',5)->comment('JISX0402コード');
            $table->integer('prefecture')->comment('住所_都道府県');
            $table->string('city',255)->comment('住所_市区町村');
            $table->string('address',255)->comment('住所_地名番地');
            $table->string('building_name',255)->nullable()->comment('住所_建物名等');
            $table->string('mail_address',255)->nullable()->comment('メールアドレス');
            $table->string('mail_address2',255)->nullable()->comment('メールアドレス2');
            $table->string('mail_address3',255)->nullable()->comment('メールアドレス3');

            $table->integer('area_id')->default(0)->nullable()->comment('エリア');
            $table->text('bank_account')->nullable()->comment('振込先');
            $table->string('sales_contact',8)->comment('営業担当（担当者） 社員マスタのID');
            $table->integer('building_category_id')->default(0)->nullable()->comment('建物分類 建物分類マスタのID');
            $table->integer('madori_id')->default(0)->nullable()->comment('間取り');
            $table->date('wedding_anniversary')->nullable()->comment('結婚記念日');
            $table->integer('building_age')->default(0)->nullable()->comment('築年数');
            $table->integer('rank_koji')->default(0)->nullable()->comment('顧客ランク（工事金額）');
            $table->text('remarks')->nullable()->comment('備考');
            $table->date('datetime_upd')->nullable()->comment('更新日');
            $table->string('last_updated_by',255)->nullable()->comment('最終更新者');
            $table->integer('option_f1')->default(0)->nullable()->comment('オプションフラグ1');
            $table->integer('option_f2')->default(0)->nullable()->comment('オプションフラグ2');
            $table->integer('option_f3')->default(0)->nullable()->comment('オプションフラグ3');
            $table->integer('option_f4')->default(0)->nullable()->comment('オプションフラグ4');
            $table->integer('option_f5')->default(0)->nullable()->comment('オプションフラグ5');
            $table->integer('option_f6')->default(0)->nullable()->comment('オプションフラグ6');
            $table->integer('option_f7')->default(0)->nullable()->comment('オプションフラグ7');
            $table->integer('option_f8')->default(0)->nullable()->comment('オプションフラグ8');
            $table->integer('option_f9')->default(0)->nullable()->comment('オプションフラグ9');
            $table->integer('option_f10')->default(0)->nullable()->comment('オプションフラグ10');
            $table->tinyInteger('valid_flag')->default(1)->nullable()->comment('有効フラグ');
            $table->integer('input_comp_flag')->default(0)->nullable()->comment('入力完了');
            $table->text('history')->nullable()->comment('家歴');
            $table->integer('estimated_rank')->nullable()->comment('顧客見込みランク');
            $table->tinyInteger('ob_flag')->default(0)->nullable()->comment('有効フラグ');
            $table->integer('source_id')->nullable()->comment('発生源');
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

            $table->string('option_t1',255)->nullable()->comment('オプションテキスト1');
            $table->string('option_t2',255)->nullable()->comment('オプションテキスト2');
            $table->string('option_t3',255)->nullable()->comment('オプションテキスト3');
            $table->string('option_t4',255)->nullable()->comment('オプションテキスト4');
            $table->string('option_t5',255)->nullable()->comment('オプションテキスト5');
            $table->string('option_t6',255)->nullable()->comment('オプションテキスト6');
            $table->string('option_t7',255)->nullable()->comment('オプションテキスト7');
            $table->string('option_t8',255)->nullable()->comment('オプションテキスト8');
            $table->string('option_t9',255)->nullable()->comment('オプションテキスト9');
            $table->string('option_t10',255)->nullable()->comment('オプションテキスト10');

            $table->integer('mycar_c1')->default(0)->nullable()->comment('マイカー種別1');
            $table->integer('mycar_c2')->default(0)->nullable()->comment('マイカー種別2');
            $table->integer('mycar_c3')->default(0)->nullable()->comment('マイカー種別3');
            $table->integer('mycar_c4')->default(0)->nullable()->comment('マイカー種別4');
            $table->integer('mycar_c5')->default(0)->nullable()->comment('マイカー種別5');
            $table->integer('mycar_c6')->default(0)->nullable()->comment('マイカー種別6');
            $table->integer('mycar_c7')->default(0)->nullable()->comment('マイカー種別7');
            $table->integer('mycar_c8')->default(0)->nullable()->comment('マイカー種別8');
            $table->integer('mycar_c9')->default(0)->nullable()->comment('マイカー種別9');
            $table->integer('mycar_c10')->default(0)->nullable()->comment('マイカー種別10');
            $table->string('my_car_type_other',255)->nullable()->comment('マイカー種別_その他');

            $table->integer('mikomi_bui1')->default(0)->nullable()->comment('見込み部位1');
            $table->integer('mikomi_bui2')->default(0)->nullable()->comment('見込み部位2');
            $table->integer('mikomi_bui3')->default(0)->nullable()->comment('見込み部位3');
            $table->integer('mikomi_bui4')->default(0)->nullable()->comment('見込み部位4');
            $table->integer('mikomi_bui5')->default(0)->nullable()->comment('見込み部位5');
            $table->integer('mikomi_bui6')->default(0)->nullable()->comment('見込み部位6');
            $table->integer('mikomi_bui7')->default(0)->nullable()->comment('見込み部位7');
            $table->integer('mikomi_bui8')->default(0)->nullable()->comment('見込み部位8');
            $table->integer('mikomi_bui9')->default(0)->nullable()->comment('見込み部位9');
            $table->integer('mikomi_bui10')->default(0)->nullable()->comment('見込み部位10');
            $table->integer('mikomi_bui11')->default(0)->nullable()->comment('見込み部位11');
            $table->integer('mikomi_bui12')->default(0)->nullable()->comment('見込み部位12');
            $table->integer('mikomi_bui13')->default(0)->nullable()->comment('見込み部位13');
            $table->integer('mikomi_bui14')->default(0)->nullable()->comment('見込み部位14');
            $table->integer('mikomi_bui15')->default(0)->nullable()->comment('見込み部位15');
            $table->integer('mikomi_bui16')->default(0)->nullable()->comment('見込み部位16');
            $table->integer('mikomi_bui17')->default(0)->nullable()->comment('見込み部位17');
            $table->integer('mikomi_bui18')->default(0)->nullable()->comment('見込み部位18');
            $table->integer('mikomi_bui19')->default(0)->nullable()->comment('見込み部位19');
            $table->integer('mikomi_bui20')->default(0)->nullable()->comment('見込み部位20');


            $table->integer('mycar_c11')->default(0)->nullable()->comment('マイカー種別11');
            $table->integer('mycar_c12')->default(0)->nullable()->comment('マイカー種別12');
            $table->integer('mycar_c13')->default(0)->nullable()->comment('マイカー種別13');
            $table->integer('mycar_c14')->default(0)->nullable()->comment('マイカー種別14');
            $table->integer('mycar_c15')->default(0)->nullable()->comment('マイカー種別15');
            $table->integer('mycar_c16')->default(0)->nullable()->comment('マイカー種別16');
            $table->integer('mycar_c17')->default(0)->nullable()->comment('マイカー種別17');
            $table->integer('mycar_c18')->default(0)->nullable()->comment('マイカー種別18');
            $table->integer('mycar_c19')->default(0)->nullable()->comment('マイカー種別19');
            $table->integer('mycar_c20')->default(0)->nullable()->comment('マイカー種別20');

            $table->integer('employee_id')->nullable()->comment('営業担当社員ID');
            $table->integer('store_id')->nullable()->comment('営業担当（店舗）');
            $table->string('line_id',255)->nullable()->comment('LINEID');
            $table->string('facebook_id',255)->nullable()->comment('FacebookID');
            $table->string('twitter_id',255)->nullable()->comment('TwitterID');
            $table->string('instagram_id',255)->nullable()->comment('InstagramID');

//            $table->tinyInteger('rank_filter')->nullable()->comment('顧客ランクフィルタ');
//            $table->tinyInteger('estimated_rank_filter')->nullable()->comment('顧客見込みランクフィルタ');
//            $table->string('completion_start_year', 4)->nullable()->comment('完工時期（開始年）');
//            $table->string('completion_start_month', 2)->nullable()->comment('完工時期（開始月）');
//            $table->string('completion_end_year', 4)->nullable()->comment('完工時期（終了年）');
//            $table->string('completion_end_month', 2)->nullable()->comment('完工時期（終了月）');
//            $table->string('last_completion_start_year', 4)->nullable()->comment('最終完工時期（開始年）');
//            $table->string('last_completion_start_month', 2)->nullable()->comment('最終完工時期（開始月）');
//            $table->string('last_completion_end_year', 4)->nullable()->comment('最終完工時期（終了年）');
//            $table->string('last_completion_end_month', 2)->nullable()->comment('最終完工時期（終了月）');
//            $table->decimal('total_work_price_min', 20, 2)->nullable()->comment('総工事金額（最小値）');
//            $table->decimal('total_work_price_max', 20, 2)->nullable()->comment('総工事金額（最大値）');
//            $table->integer('work_times_min')->nullable()->comment('工事回数（最小値）');
//            $table->integer('work_times_max')->nullable()->comment('工事回数（最大値）');
            $table->text('tag_list')->nullable()->comment('関連タグ');
            $table->string('tag_list_other')->nullable()->comment('関連タグ－その他（自由入力）');
            $table->text('part_list')->nullable()->comment('部位');
            $table->text('expected_part_list')->nullable()->comment('見込み部位');
            $table->string('memo1',255)->nullable()->comment('社内メモ1');
            $table->string('memo2',255)->nullable()->comment('社内メモ2');
            $table->text('my_car_type')->nullable()->comment('マイカー種別');
//            $table->string('my_car_type_other')->nullable()->comment('マイカー種別_その他');
            $table->string('introducer',255)->nullable()->comment('紹介者');
//            $table->string('friend_meeting')->nullable()->comment('友の会');
//            $table->string('reform_album')->nullable()->comment('リフォームアルバム');
//            $table->string('case_permit')->nullable()->comment('事例許可');
//            $table->string('field_tour_party')->nullable()->comment('現場見学会');
            $table->string('lat', 20)->nullable()->comment('緯度');
            $table->string('lng', 20)->nullable()->comment('経度');
//            $table->timestamp('created_at')->comment('登録日時');
//            $table->timestamp('updated_at')->nullable()->comment('更新日時');
            $table->integer('rank_last')->default(0)->nullable()->comment('顧客ランク（最終完工日）');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_customers');
    }
}
