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
            $table->increments('id')->comment('顧客ID');
            $table->integer('sales_shop')->nullable()->comment('営業担当（店舗）');
            $table->integer('sales_contact')->comment('営業担当（担当者）');
            $table->string('name',30)->comment('顧客_名称');
            $table->string('keisho',5)->nullable()->comment('顧客_敬称');
            $table->string('furigana',50)->nullable()->comment('顧客_フリガナ');
            $table->string('tel_no',20)->nullable()->comment('電話番号');
            $table->string('tel_no2',20)->nullable()->comment('電話番号2');
            $table->string('tel_no3',20)->nullable()->comment('電話番号3');
            $table->tinyInteger('is_deficiency')->default(0)->nullable()->comment('不備情報のみ');
            $table->string('fax_no',20)->nullable()->comment('FAX番号');
            $table->string('mail_address',50)->nullable()->comment('メールアドレス');
            $table->string('mail_address2',50)->nullable()->comment('メールアドレス2');
            $table->string('mail_address3',50)->nullable()->comment('メールアドレス3');
            $table->string('post_no',7)->comment('郵便番号');
            $table->string('prefecture',10)->comment('住所_都道府県');
            $table->string('city',30)->comment('住所_市区町村');
            $table->string('address',60)->comment('住所_地名番地');
            $table->string('building_name',64)->nullable()->comment('住所_建物名等');
            $table->string('line_id',64)->nullable()->comment('LINEID');
            $table->string('facebook_id',64)->nullable()->comment('FacebookID');
            $table->string('twitter_id',64)->nullable()->comment('TwitterID');
            $table->string('instagram_id',64)->nullable()->comment('InstagramID');
            $table->integer('rank')->nullable()->comment('顧客ランク');
            $table->tinyInteger('rank_filter')->nullable()->comment('顧客ランクフィルタ');
            $table->integer('estimated_rank')->nullable()->comment('顧客見込みランク');
            $table->tinyInteger('estimated_rank_filter')->nullable()->comment('顧客見込みランクフィルタ');
            $table->integer('source_id')->nullable()->comment('発生源');
            $table->integer('area_id')->nullable()->comment('エリア');
            $table->integer('building_category_id')->nullable()->comment('建物分類');
            $table->integer('madori_id')->nullable()->comment('間取り');
            $table->integer('building_age')->nullable()->comment('築年数');
            $table->string('completion_start_year', 4)->nullable()->comment('完工時期（開始年）');
            $table->string('completion_start_month', 2)->nullable()->comment('完工時期（開始月）');
            $table->string('completion_end_year', 4)->nullable()->comment('完工時期（終了年）');
            $table->string('completion_end_month', 2)->nullable()->comment('完工時期（終了月）');
            $table->string('last_completion_start_year', 4)->nullable()->comment('最終完工時期（開始年）');
            $table->string('last_completion_start_month', 2)->nullable()->comment('最終完工時期（開始月）');
            $table->string('last_completion_end_year', 4)->nullable()->comment('最終完工時期（終了年）');
            $table->string('last_completion_end_month', 2)->nullable()->comment('最終完工時期（終了月）');
            $table->decimal('total_work_price_min', 20, 2)->nullable()->comment('総工事金額（最小値）');
            $table->decimal('total_work_price_max', 20, 2)->nullable()->comment('総工事金額（最大値）');
            $table->integer('work_times_min')->nullable()->comment('工事回数（最小値）');
            $table->integer('work_times_max')->nullable()->comment('工事回数（最大値）');
            $table->string('tag_list')->nullable()->comment('関連タグ');
            $table->string('part_list')->nullable()->comment('部位');
            $table->string('expected_part_list')->nullable()->comment('見込み部位');
            $table->string('remarks')->nullable()->comment('備考');
            $table->string('memo1')->nullable()->comment('社内メモ1');
            $table->string('memo2')->nullable()->comment('社内メモ2');
            $table->string('my_car_type')->nullable()->comment('マイカー種別');
            $table->string('my_car_type_other')->nullable()->comment('マイカー種別_その他');
            $table->string('introducer')->nullable()->comment('紹介者');
            $table->date('wedding_anniversary')->nullable()->comment('結婚記念日');
            $table->string('friend_meeting')->nullable()->comment('友の会');
            $table->string('reform_album')->nullable()->comment('リフォームアルバム');
            $table->string('case_permit')->nullable()->comment('事例許可');
            $table->string('field_tour_party')->nullable()->comment('現場見学会');
            $table->string('lat', 20)->nullable()->comment('緯度');
            $table->string('lng', 20)->nullable()->comment('経度');
            $table->timestamp('created_at')->comment('登録日時');
            $table->timestamp('updated_at')->nullable()->comment('更新日時');
            $table->string('last_updated_by')->comment('最終更新者');
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
