<?php

namespace App\Models;

use App\Libraries\CommonUtility;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class TCustomer<br>
 * 顧客データ
 *
 * @package App\Models
 */
class TCustomer extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（t_customers）
    // 主キーのデフォルト名はid
    // 主キーはデフォルトではINT型のAuto Increment
    // デフォルトではタイムスタンプを自動更新（created_at、updated_atを生成）
    // デフォルトの接続データベースは .env の DB_CONNECTION の定義内容

    /* モデルにタイムスタンプを付けるか
    *
    * @var bool
         */
    public $timestamps = false;
    /**
     * モデルの属性のデフォルト値
     *
     * @var array
     */
    protected $attributes = [
        'is_deficiency' => 0,
        'ob_flag' => 2,
    ];

    /**
     * ホワイトリスト
     *
     * @var string[]
     */
    protected $fillable = [
//        'company_id', // TODO 後で追加
        'sales_shop',
        'sales_contact',
        'name',
        'keisho',
        'furigana',
        'tel_no',
        'tel_no2',
        'tel_no3',
        'is_deficiency',
        'fax_no',
        'mail_address',
        'mail_address2',
        'mail_address3',
        'post_no',
        'prefecture',
        'city',
        'address',
        'building_name',
        'line_id',
        'facebook_id',
        'twitter_id',
        'instagram_id',
        'rank',
        'rank_filter',
        'estimated_rank',
        'estimated_rank_filter',
        'source_id',
        'area_id',
        'building_category_id',
        'madori_id',
        'building_age',
        'completion_start_year',
        'completion_start_month',
        'completion_end_year',
        'completion_end_month',
        'last_completion_start_year',
        'last_completion_start_month',
        'last_completion_end_year',
        'last_completion_end_month',
        'total_work_price_min',
        'total_work_price_max',
        'work_times_min',
        'work_times_max',
        'tag_list',
        'part_list',
        'expected_part_list',
        'remarks',
        'memo1',
        'memo2',
        'my_car_type',
        'my_car_type_other',
        'introducer',
        'wedding_anniversary',
        'friend_meeting',
        'reform_album',
        'case_permit',
        'field_tour_party',
        'lat',
        'lng',
        'ob_flag',
        'is_editing',
        'last_updated_by',
        'koji_rank',
        'last_rank',
    ];

    /*
     * 形のキャストを定義
     */
    protected $casts = [
        'is_deficiency' => 'boolean'
    ];

    protected static $csv_columns = [
        'sales_shop' => '営業担当（店舗）',
        'sales_contact' => '営業担当（担当者）',
        'name' => '顧客_名称',
        'keisho' => '顧客_敬称',
        'furigana' => '顧客_フリガナ',
        'tel_no' => '電話番号',
        'tel_no2' => '電話番号2',
        'tel_no3' => '電話番号3',
        'is_deficiency' => '不備情報のみ',
        'fax_no' => 'FAX番号',
        'mail_address' => 'メールアドレス',
        'mail_address2' => 'メールアドレス2',
        'mail_address3' => 'メールアドレス3',
        'post_no' => '郵便番号',
        'prefecture' => '住所_都道府県',
        'city' => '住所_市区町村',
        'address' => '住所_地名番地',
        'building_name' => '住所_建物名等',
        'line_id' => 'LINEID',
        'facebook_id' => 'FacebookID',
        'twitter_id' => 'TwitterID',
        'instagram_id' => 'InstagramID',
        'rank' => '顧客ランク',
        'rank_filter' => '顧客ランクフィルタ',
        'estimated_rank' => '顧客見込みランク',
        'estimated_rank_filter' => '顧客見込みランクフィルタ',
        'source_id' => '発生源',
        'area_id' => 'エリア',
        'building_category_id' => '建物分類',
        'madori_id' => '間取り',
        'building_age' => '築年数',
        'completion_start_year' => '完工時期（開始年）',
        'completion_start_month' => '完工時期（開始月）',
        'completion_end_year' => '完工時期（終了年）',
        'completion_end_month' => '完工時期（終了月）',
        'last_completion_start_year' => '最終完工時期（開始年）',
        'last_completion_start_month' => '最終完工時期（開始月）',
        'last_completion_end_year' => '最終完工時期（終了年）',
        'last_completion_end_month' => '最終完工時期（終了月）',
        'total_work_price_min' => '総工事金額（最小値）',
        'total_work_price_max' => '総工事金額（最大値）',
        'work_times_min' => '工事回数（最小値）',
        'work_times_max' => '工事回数（最大値）',
        'tag_list' => '関連タグ',
        'part_list' => '部位',
        'expected_part_list' => '見込み部位',
        'remarks' => '備考',
        'memo1' => '社内メモ1',
        'memo2' => '社内メモ2',
        'my_car_type' => 'マイカー種別',
        'my_car_type_other' => 'マイカー種別_その他',
        'introducer' => '紹介者',
        'wedding_anniversary' => '結婚記念日',
        'friend_meeting' => '友の会',
        'reform_album' => 'リフォームアルバム',
        'case_permit' => '事例許可',
        'field_tour_party' => '現場見学会',
        'lat' => '緯度',
        'lng' => '経度',
        'ob_flag' => 'OBフラグ',
    ];

    protected static $employee_cd = [
        "nagai" => 1,
        "amino" => 2,
        "admin" => 3,
        "bunkop" => 4,
        "niikura" => 5,
        "yajima" => 6,
        "chinen" => 7,
        "kato" => 8,
        "nojiri" => 9,
        "yosinaga" => 10,
        "mitsuru" => 11,
        "tano" => 12,
        "yamada" => 13,
        "hosouchi" => 14,
        "matsui" => 15,
        "saito" => 16,
        "yashima" => 17,
        "yoshinag" => 18,
        "mochida" => 19,
        "suzuki" => 20,
        "kawamura" => 21,
        "soumu" => 22,
        "ikegami" => 23,
        "kobayash" => 24,
        "handa" => 25,
        "tsuji" => 26,
        "miyazaki" => 27,
        "watanabe" => 28,
        "kodama" => 29,
        "wata330" => 30,
        "aoki" => 31,
        "kaneko" => 32,
        "ezaki" => 33,
        "uemori" => 34,
        "kojima" => 35,
        "tanaka" => 36,
    ];

    protected static function get_columns()
    {
        return static::$csv_columns;
    }

    protected static function get_employee_codes()
    {
        return static::$employee_cd;
    }

    /**
     * 顧客と紐づく案件データ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function project(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(TProject::class, 'id');
    }

    /**
     * 顧客と紐づく家族データ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function family(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(TFamily::class, 'id');
    }

    /**
     * 顧客に紐づく店舗マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function store(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MStore::class, 'id', 'sales_shop');
    }

    /**
     * 顧客に紐づく社員マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function employee(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MEmployee::class, 'id', 'sales_contact');
    }

    /**
     * 顧客に紐づくエリアマスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function area(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MArea::class, 'id', 'area_id');
    }

    /**
     * 顧客に紐づく建物分類マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function building(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MBuilding::class, 'id', 'building_category_id');
    }

    /**
     * 顧客に紐づく間取りマスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function madori(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MMadori::class, 'id', 'madori_id');
    }

    /**
     * 顧客に紐づく顧客見込みランクマスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer_estimated_rank(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MCustomerEstimatedRank::class, 'id', 'estimated_rank');
    }

    /**
     * 顧客に紐づく発生源マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function source(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MSource::class, 'id', 'source_id');
    }

    /**
     * ソート用カラム定義（リスト表示時用）
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'is_deficiency', // 入力不備
        1 => 'ob_flag', // OBフラグ
        2 => 'sales_contact', // 顧客担当営業
        3 => 'id', // 顧客ID
        4 => 'name', // 顧客名
        5 => 'furigana', // フリガナ
        6 => 'post_no', // 郵便番号
        7 => 'prefecture', // 都道府県
        8 => 'address', // 住所
        9 => 'tel_no', // 電話番号
        10 => 'estimated_rank', // 顧客見込みランク
        11 => 'rank', // 顧客ランク
        12 => 'area_id', // エリア
        13 => 'building_category_id', // 建物分類
        14 => 'madori_id', // 間取り
        15 => 'building_age', // 築年数
        16 => 'remarks', // 備考
    ];

    /**
     * 顧客情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed $query 取得クエリー
     * @throws Exception
     */
    private static function _search_list(Request $param)
    {
        // 取得項目
        $query = TCustomer::select(
            'id',
            'sales_contact',
            'name',
            'keisho',
            'furigana',
            'tel_no',
            'mail_address',
            'is_deficiency',
            'post_no',
            'prefecture',
            'city',
            'address',
            'building_name',
            'rank',
            'estimated_rank',
            'ob_flag',
            'area_id',
            'building_category_id',
            'madori_id',
            'building_age',
            'remarks',
            'friend_meeting',
            'reform_album',
            'case_permit',
            'field_tour_party',
            'lat',
            'lng',
        )->where('is_editing', 0)->with(['employee' => function ($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 社員マスタ
        ->with(['customer_estimated_rank' => function ($q) {
            $q->select('name', 'id', 'order');
        }]) // 顧客見込みランクマスタ
        ->with(['area' => function ($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // エリアマスタ
        ->with(['building' => function ($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 建物分類マスタ
        ->with(['madori' => function ($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]); // 間取りマスタ

        // 検索条件（where）
        self::set_where($query, $param);

        return $query;
    }

    /**
     * 顧客情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return Collection 取得データ
     * @throws Exception
     */
    public static function search_list(Request $param): Collection
    {
        $query = self::_search_list($param);

        $result_all = $query->get();
        if ($result_all->count() == 0) {
            return $result_all;
        }

        // ソート条件（order by）
        self::_set_order_by($query, $param->input('sort_by', 3), $param->input('highlow', 0));
        if ($param->filled('limit')) {
            // オフセット条件（offset）
            $query->skip(($param->input('offset', 0) > 0) ? ($param->input('offset') * $param->input('limit')) : 0);
            // リミット条件（limit）
            $query->take($param->input('limit'));
        }

        $result = $query->get();
        if ($result->count() == 0) {
            return $result;
        }

        // 取得結果整形
        return self::get_format_column($result, $param);
    }

    /**
     * 顧客情報一覧検索（全件）
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     * @throws Exception
     */
    public static function search_list_count(Request $param)
    {
        $query = self::_search_list($param);

        return $query->get();
    }

    /**
     * CSV出力管理用顧客情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed $query 取得クエリー
     * @throws Exception
     */
    private static function _search_csv_list(Request $param)
    {
        // 取得項目
        $query = TCustomer::select(
            'id',
            'name',
            'tel_no',
            'prefecture',
            'city',
            'address',
            'building_name',
            'ob_flag',
            'rank',
            'total_work_price_max',
            'work_times_max',
            'post_no',
            'wedding_anniversary',
            'sales_contact',
        )->where('is_editing', 0)->with(['project' => function ($q) use ($param) {
            $q->select('id', 'customer_id', 'construction_status', 'completion_date');
            // 工事状況
            if (CommonUtility::is_exist_variable_array($param->input('construction_status'))
                && !is_null($param->input('construction_status')[0])) {
                foreach ($param->input('construction_status') as $item) {
                    $q->orWhere('construction_status', ModelBase::CONSTRUCTION_STATUS[$item]);
                }
            }
        }]) // 案件データ
        ->with(['family' => function ($q) use ($param) {
            $q->select('id', 'name', 'relationship', 'mobile_phone', 'birth_date');
            // 家族お名前
            if ($param->filled('family_name')) {
                $q->where($param->input('name'));
            }
            // 続柄
            if ($param->filled('relationship')) {
                $q->where($param->input('relationship'));
            }
            // 携帯番号
            if ($param->filled('mobile_phone')) {
                $q->where($param->input('mobile_phone'));
            }
            // 生年月日開始～終了
            if (($param->filled('birth_month_start') && $param->filled('birth_day_start'))
                && ($param->filled('birth_month_end') && $param->filled('birth_day_end'))) {
                $q->where(function ($q) use ($param) {
                    // 開始月0埋め
                    $birth_month_start = strlen($param->input('birth_month_start')) == 1
                        ? printf('%01d', $param->input('birth_month_start')) : $param->input('birth_month_start');
                    // 開始日0埋め
                    $birth_day_start = strlen($param->input('birth_day_start')) == 1
                        ? printf('%01d', $param->input('birth_day_start')) : $param->input('birth_day_start');
                    // 終了月0埋め
                    $birth_month_end = strlen($param->input('birth_month_end')) == 1
                        ? printf('%01d', $param->input('birth_month_end')) : $param->input('birth_month_end');
                    // 終了日0埋め
                    $birth_day_end = strlen($param->input('birth_day_end')) == 1
                        ? printf('%01d', $param->input('birth_day_end')) : $param->input('birth_day_end');

                    // 生年月日_開始、終了の期間
                    $q->where('wedding_anniversary', '>=', new \DateTime($birth_month_start . '/' . $birth_day_start))
                        ->where('wedding_anniversary', '<=', new \DateTime($birth_month_end . '/' . $birth_day_end));
                });
            } else if (($param->filled('birth_month_start') && $param->filled('birth_day_start'))
                && (is_null($param->input('birth_month_end')) || is_null($param->input('birth_day_end')))) {
                // 開始月0埋め
                $birth_month_start = strlen($param->input('birth_month_start')) == 1
                    ? printf('%01d', $param->input('birth_month_start')) : $param->input('birth_month_start');
                // 開始日0埋め
                $birth_day_start = strlen($param->input('birth_day_start')) == 1
                    ? printf('%01d', $param->input('birth_day_start')) : $param->input('birth_day_start');

                // 生年月日_開始以降
                $q->where('wedding_anniversary', '>=', new \DateTime($birth_month_start . '/' . $birth_day_start));
            } else if ((is_null($param->input('birth_month_start')) || is_null($param->input('birth_day_start')))
                && ($param->filled('birth_month_end') && $param->filled('birth_day_end'))) {
                // 終了月0埋め
                $birth_month_end = strlen($param->input('birth_month_end')) == 1
                    ? printf('%01d', $param->input('birth_month_end')) : $param->input('birth_month_end');
                // 終了日0埋め
                $birth_day_end = strlen($param->input('birth_day_end')) == 1
                    ? printf('%01d', $param->input('birth_day_end')) : $param->input('birth_day_end');

                // 生年月日_終了以前
                $q->where('wedding_anniversary', '<=', new \DateTime($birth_month_end . '/' . $birth_day_end));
            }
        }]) // 家族データ
        ->with(['employee' => function ($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]); // 社員マスタ

        // 検索条件（where）
        self::set_where($query, $param);

        return $query;
    }

    /**
     * CSV出力管理用顧客情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return Collection 取得データ
     * @throws Exception
     */
    public static function search_csv_list(Request $param): Collection
    {
        $query = self::_search_csv_list($param);

        $result_all = $query->get();
        if ($result_all->count() == 0) {
            return $result_all;
        }

        // ソート条件（order by）
        self::_set_order_by($query, $param->input('sort_by', 3), $param->input('highlow', 0));
        if ($param->filled('limit')) {
            // オフセット条件（offset）
            $query->skip(($param->input('offset', 0) > 0) ? ($param->input('offset') * $param->input('limit')) : 0);
            // リミット条件（limit）
            $query->take($param->input('limit'));
        }

        $result = $query->get();

        // 取得結果整形
        if ($param->path() == 'api/csv/customer') {
            return self::get_format_column_csv_customer($result, $param);
        } else if ($param->path() == 'api/csv/birthday') {
            return self::get_format_column_csv_birthday($result);
        } else if ($param->path() == 'api/csv/weddinganniversary') {
            return self::get_format_column_csv_wedding($result);
        }

        return self::get_format_column_csv_customer($result, $param);
    }

    /**
     * CSV出力管理用顧客情報一覧検索（全件）
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     * @throws Exception
     */
    public static function search_csv_list_count(Request $param)
    {
        $query = self::_search_csv_list($param);

        return $query->get();
    }

    /**
     * 顧客情報1件検索
     *
     * @param int $id 顧客ID
     * @return array|null 取得データ
     */
    public static function search_one(int $id): ?array
    {
        // 取得項目
        $query = TCustomer::select(
            'id',
            'sales_shop',
            'sales_contact',
            'name',
            'keisho',
            'furigana',
            'tel_no',
            'tel_no2',
            'tel_no3',
            'fax_no',
            'mail_address',
            'mail_address2',
            'mail_address3',
            'post_no',
            'prefecture',
            'city',
            'address',
            'building_name',
            'line_id',
            'facebook_id',
            'twitter_id',
            'instagram_id',
            'rank',
            'source_id',
            'estimated_rank',
            'ob_flag',
            'area_id',
            'expected_part_list',
            'part_list',
            'building_category_id',
            'madori_id',
            'building_age',
            'remarks',
            'memo1',
            'memo2',
            'my_car_type',
            'my_car_type_other',
            'tag_list',
            'introducer',
            'wedding_anniversary',
            'lat',
            'lng',
        )->where('is_editing', 0)->with(['store' => function ($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 店舗マスタ
        ->with(['employee' => function ($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 社員マスタ
        ->with(['source' => function ($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 発生源マスタ
        ->with(['customer_estimated_rank' => function ($q) {
            $q->select('name', 'id');
        }]) // 顧客見込みランクマスタ
        ->with(['area' => function ($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // エリアマスタ
        ->with(['building' => function ($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 建物分類マスタ
        ->with(['madori' => function ($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]); // 間取りマスタ

        $result = $query->where('is_editing', 0)->find($id);
        if (is_null($result)) {
            return null;
        }

        // 取得結果整形
        return self::get_format_column_one($result);
    }

    /**
     * 顧客情報保存（登録・更新）
     *
     * @param Request $param
     * @param int|null $id
     * @return array
     */
    public static function upsert(Request $param, int $id = null): array
    {
        $arr = $param->all();
        // DB登録・更新用にパラメータ変換
        // 都道府県
        if ($param->filled('prefecture')) {
            $arr['prefecture'] = ModelBase::PREFECTURE[$param->input('prefecture')];
        }
        // 発生源
        $arr['source_id'] = $param->input('source');
        // エリア
        $arr['area_id'] = $param->input('area');
        // 建物分類
        $arr['building_category_id'] = $param->input('building_category');
        // 間取り
        $arr['madori_id'] = $param->input('madori');
        // 築年数
        $arr_building_age = explode("-", $param->input('building_age'));
        $now = Carbon::now();
        $target = Carbon::create($arr_building_age[0], $arr_building_age[1]);
        echo "【 " . str_replace('-', '', $target->diffInYears($now)) . " 】\n";
        $arr['building_age'] = str_replace('-', '', $target->diffInYears($now));
        // 見込み部位リスト
        if ($param->filled('expected_part_list')) {
            $arr['expected_part_list'] = implode(' ', $param->input('expected_part_list'));
        }
        // 部位リスト
        if ($param->filled('part_list')) {
            $arr['part_list'] = implode(' ', $param->input('part_list'));
        }
        // マイカー種別
        if ($param->filled('my_car_type')) {
            $arr['my_car_type'] = implode(' ', $param->input('my_car_type'));
        }
        // 関連タグリスト
        if ($param->filled('tag_list')) {
            $arr['tag_list'] = implode(' ', $param->input('tag_list'));
        }
        // 最終更新者
        // TODO ログインユーザー名を登録
        $arr['last_updated_by'] = '管理者';
        // 編集中フラグ 登録済みにする
        $arr['is_editing'] = 0;

        if ($id) {
            // 更新
            $customer = TCustomer::find($id);
            if (is_null($customer)) {
                return ["code" => '404'];
            }

            if (strcmp($customer->lat, $param->input('lat')) !== 0 || strcmp($customer->lng, $param->input('lng')) !== 0) {
                //ジオコーディング重複チェック
                $result = TCustomer::
                where('lat', $param->input('lat'))->
                where('lng', $param->input('lng'))->
                first();
                if (!empty($result)) {
                    return [
                        "code" => 'err_geocoding',
                        "name" => $result->name,
                        "keisho" => $result->keisho,
                    ];
                }
            }

            // 顧客TEL重複チェック
            if ($param->filled('tel_no')) {
                // -を除いた数字で検索する
                $result = TCustomer::whereRaw('replace(tel_no, "-", "") like ?', ['tel_no' => '%' . str_replace('-', '', $param->input('tel_no')) . '%'])->where('id', '<>', $customer->id)->first(); // 電話番号
                if (!is_null($result)) {
                    return ["code" => 'err_tel_no'];
                }
            }
            // 更新処理
            //$customer->fill($arr)->save();
        } else {

            //ジオコーディング重複チェック
            $result = TCustomer::
            where('lat', $param->input('lat'))->
            where('lng', $param->input('lng'))->
            first();
            if (!empty($result)) {
                return [
                    "code" => 'err_geocoding',
                    "name" => $result->name,
                    "keisho" => $result->keisho,
                ];
            }
            // 顧客TEL重複チェック
            if ($param->filled('tel_no')) {
                // -を除いた数字で検索する
                $result = TCustomer::whereRaw('replace(tel_no, "-", "") like ?', ['tel_no' => '%' . str_replace('-', '', $param->input('tel_no')) . '%'])->first(); // 電話番号
                if (!is_null($result)) {
                    return ["code" => 'err_tel_no'];
                }
            }
            // 登録処理
            $customer = new TCustomer();
            //$customer->fill($arr)->save();
        }
        // トランザクション開始
        DB::beginTransaction();
        try {
            // 登録・更新処理
            $customer->fill($arr)->save();

            // 入力不備フラグ更新
            $is_deficiency = 0;
            if (empty($customer->name) || empty($customer->furigana)
                || empty($customer->post_no) || empty($customer->prefecture)
                || empty($customer->city) || empty($customer->address)
                || empty($customer->area_id) || empty($customer->tel_no)
            ) {
                // 入力不備フラグをON
                $is_deficiency = 1;
            }
            $customer->is_deficiency = $is_deficiency;
            $customer->save();

            //コミット
            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            //ロールバック
            DB::rollBack();
            throw new \Exception("顧客情報の更新に失敗しました");
        }

        return ["code" => 'ok'];
    }

    /**
     * 顧客ID発行
     *
     * @param Request $param
     * @return int
     */
    public static function get_id(Request $param): int
    {
        // トランザクション開始
        DB::beginTransaction();
        try {
            $arr = $param->all();
            // 必須項目をダミーで登録
            $arr['sales_contact'] = 1; // 営業担当（担当者）
            $arr['name'] = 'ダミー'; // 顧客_名称
            $arr['post_no'] = '1112222'; // 郵便番号
            $arr['prefecture'] = 'ダミー'; // 住所_都道府県
            $arr['city'] = 'ダミー'; // 住所_市区町村
            $arr['address'] = 'ダミー'; // 住所_地名番地
            $arr['last_updated_by'] = 'ダミー'; // 最終更新者
            $arr['is_editing'] = 1; // 編集中フラグ 編集中にする
            // 登録処理
            $customer = new TCustomer();
            $customer->fill($arr)->save();

            //コミット
            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            //ロールバック
            DB::rollBack();
            throw new \Exception("顧客IDの発行に失敗しました");
        }

        return TCustomer::get()->max('id');
    }

    /**
     * 編集中顧客情報削除
     *
     * @param int $id 顧客ID
     */
    public static function remove_edit_data(int $id)
    {
        // トランザクション開始
        DB::beginTransaction();
        try {
            // ペット情報削除処理
            TPet::where('customer_id', $id)->delete();
            // ご家族情報削除処理
            TFamily::where('customer_id', $id)->delete();
            // 顧客情報削除処理
            TCustomer::destroy($id);

            //コミット
            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            //ロールバック
            DB::rollBack();
            throw new \Exception("顧客情報の削除に失敗しました");
        }

        return;
    }

    /**
     * 検索条件設定
     *
     * @param $query
     * @param Request $param 検索パラメータ
     * @throws Exception
     */
    public static function set_where(&$query, Request $param)
    {
        // 簡易検索
        // 営業担当（店舗）
        if ($param->filled('sales_shop')) {
            $query = $query->where('sales_shop', $param->input('sales_shop'));
        }
        // 営業担当（担当者）
        if ($param->filled('sales_contact')) {
            $query = $query->where('sales_contact', $param->input('sales_contact'));
        }
        // 顧客名
        if ($param->filled('name')) {
            $query = $query->where('name', 'like', '%' . $param->input('name') . '%');
        }
        // 顧客名フリガナ
        if ($param->filled('furigana')) {
            $query = $query->where('furigana', 'like', '%' . $param->input('furigana') . '%');
        }
        // 顧客TEL
        if ($param->filled('tel_no')) {
            // -を除いた数字で検索する
            $query = $query->where(function ($q) use ($param) {
                $q->whereRaw('replace(tel_no, "-", "") like ?', ['tel_no' => '%' . str_replace('-', '', $param->input('tel_no')) . '%']) // 電話番号
                ->orWhereRaw('replace(tel_no2, "-", "") like ?', ['tel_no2' => '%' . str_replace('-', '', $param->input('tel_no')) . '%']) // 電話番号2
                ->orWhereRaw('replace(tel_no3, "-", "") like ?', ['tel_no3' => '%' . str_replace('-', '', $param->input('tel_no')) . '%']); // 電話番号3
            });
        }
        // 不備情報のみ
        if ($param->input('is_deficiency') == 1) {
            $query = $query->where('is_deficiency', 1);
        }
        // 郵便番号
        if ($param->filled('post_no')) {
            $query = $query->whereRaw('replace(post_no, "-", "") like ?', ['post_no' => '%' . str_replace('-', '', $param->input('post_no')) . '%']);
        }

        // 詳細検索
        // 都道府県
        if ($param->filled('prefecture')) {
            $query = $query->where('prefecture', ModelBase::PREFECTURE[$param->input('prefecture')]);
        }
        // 顧客住所
        if ($param->filled('address')) {
            $query = $query->where(function ($q) use ($param) {
                $q->orWhere('city', 'like', '%' . $param->input('address') . '%') // 市区町村
                ->orWhere('address', 'like', '%' . $param->input('address') . '%') // 地名、番地
                ->orWhere('building_name', 'like', '%' . $param->input('address') . '%'); // 建物名等
            });
        }
        // 顧客ランク
        if ($param->filled('rank')) {
            if ($param->input('rank_filter') == '2') {
                $query = $query->where('rank', '>=', $param->input('rank'));
            } else if ($param->input('rank_filter') == '3') {
                $query = $query->where('rank', '<=', $param->input('rank'));
            } else {
                $query = $query->where('rank', $param->input('rank'));
            }
        }
        // 顧客見込みランク
        if ($param->filled('estimated_rank')) {
            if ($param->input('estimated_rank_filter') == '2') {
                $query = $query->where('estimated_rank', '>=', $param->input('estimated_rank'));
            } else if ($param->input('estimated_rank_filter') == '3') {
                $query = $query->where('estimated_rank', '<=', $param->input('estimated_rank'));
            } else {
                $query = $query->where('estimated_rank', $param->input('estimated_rank'));
            }
        }
        // エリア
        if ($param->filled('area')) {
            $query = $query->where('area_id', $param->input('area'));
        }
        // 建物分類
        if ($param->filled('building_category_id')) {
            $query = $query->where('building_category', $param->input('building_category'));
        }
        // 間取り
        if ($param->filled('madori')) {
            $query = $query->where('madori_id', $param->input('madori'));
        }
        // 築年数
        if ($param->filled('building_age')) {
            $arr_building_age = explode("-", $param->input('building_age'));
            $now = Carbon::now();
            $target = Carbon::create($arr_building_age[0], $arr_building_age[1]);
            $years = $target->diffInYears($now);

            // 築年数フィルタ
            if ($param->filled('building_age_filter')) {
                if ($param->input('building_age_filter') == 1) {
                    $query = $query->where('building_age', str_replace('-', '', $years)); // のみ
                } else if ($param->input('building_age_filter') == 2) {
                    $query = $query->where('building_age', '>=', str_replace('-', '', $years)); // 以上
                } else if ($param->input('building_age_filter') == 3) {
                    $query = $query->where('building_age', '<=', str_replace('-', '', $years)); // 以下
                }
            } else {
                $query = $query->where('building_age', str_replace('-', '', $years));
            }
        }
        // 完工時期（開始年）
        if ($param->filled('completion_start_year')) {
            $query = $query->where('completion_start_year', $param->input('completion_start_year'));
        }
        // 完工時期（開始月）
        if ($param->filled('completion_start_month')) {
            $query = $query->where('completion_start_month', $param->input('completion_start_month'));
        }
        // 完工時期（終了年）
        if ($param->filled('completion_end_year')) {
            $query = $query->where('completion_end_year', $param->input('completion_end_year'));
        }
        // 完工時期（終了月）
        if ($param->filled('completion_end_month')) {
            $query = $query->where('completion_end_month', $param->input('completion_end_month'));
        }
        // 最終完工時期（開始年）
        if ($param->filled('last_completion_start_year')) {
            $query = $query->where('last_completion_start_year', $param->input('last_completion_start_year'));
        }
        // 最終完工時期（開始月）
        if ($param->filled('last_completion_start_month')) {
            $query = $query->where('last_completion_start_month', $param->input('last_completion_start_month'));
        }
        // 最終完工時期（終了年）
        if ($param->filled('last_completion_end_year')) {
            $query = $query->where('last_completion_end_year', $param->input('last_completion_end_year'));
        }
        // 最終完工時期（終了月）
        if ($param->filled('last_completion_end_month')) {
            $query = $query->where('last_completion_end_month', $param->input('last_completion_end_month'));
        }
        // 総工事金額（最小値）
        if ($param->filled('total_work_price_min')) {
            $query = $query->where('total_work_price_min', $param->input('total_work_price_min'));
        }
        // 総工事金額（最大値）
        if ($param->filled('total_work_price_max')) {
            $query = $query->where('total_work_price_max', $param->input('total_work_price_max'));
        }
        // 工事回数（最小値）
        if ($param->filled('work_times_min')) {
            $query = $query->where('work_times_min', $param->input('work_times_min'));
        }
        // 工事回数（最大値）
        if ($param->filled('work_times_max')) {
            $query = $query->where('work_times_max', $param->input('work_times_max'));
        }
        // 関連タグ
        if (CommonUtility::is_exist_variable_array($param->input('tags'))
            && !is_null($param->input('tags')[0])) {
            $query = $query->where('tag_list', implode(' ', $param->input('tags')));
        }
        // 部位
        if (CommonUtility::is_exist_variable_array($param->input('parts'))
            && !is_null($param->input('parts')[0])) {
            $query = $query->where('part_list', implode(' ', $param->input('parts')));
        }
        // 備考
        if ($param->filled('remarks')) {
            $query = $query->where('remarks', 'like', '%' . $param->input('remarks') . '%');
        }

        // Googleマップ地図範囲内に存在する位置情報
        if ($param->filled('lat')
            && $param->filled('lng')
            && $param->filled('lnt2')
            && $param->filled('lng2')) {
            $query = $query->where('lat', '<', floatval($param->input('north_lat')))
                ->where('lng', '<', floatval($param->input('north_lng')))
                ->where('lat', '>', floatval($param->input('south_lat')))
                ->where('lng', '>', floatval($param->input('south_lng')));
        }

        // CSV出力管理顧客情報取得用
        // 簡易検索
        // 顧客分類
        if ($param->filled('customer_classification')) {
            if ($param->input('customer_classification') != 1) {
                $query = $query->where('ob_flag', $param->input('customer_classification'));
            }
        }

        // 詳細検索
        // メールアドレス
        if ($param->filled('mail_address')) {
            $query = $query->where(function ($q) use ($param) {
                $q->orWhere('mail_address', 'like', '%' . $param->input('mail_address') . '%') // メールアドレス
                ->orWhere('mail_address2', 'like', '%' . $param->input('mail_address2') . '%') // メールアドレス2
                ->orWhere('mail_address3', 'like', '%' . $param->input('mail_address3') . '%'); // メールアドレス3
            });
        }

        // CSV出力管理結婚記念日リスト取得用
        // 結婚記念日開始～終了
        if (($param->filled('wedding_anniversary_start_year') && $param->filled('wedding_anniversary_start_month'))
            && ($param->filled('wedding_anniversary_end_year') && $param->filled('wedding_anniversary_end_month'))) {
            $query = $query->where(function ($q) use ($param) {
                // 開始月0埋め
                $wedding_anniversary_start_month = strlen($param->input('wedding_anniversary_start_month')) == 1
                    ? printf('%01d', $param->input('wedding_anniversary_start_month')) : $param->input('wedding_anniversary_start_month');
                // 終了月0埋め
                $wedding_anniversary_end_month = strlen($param->input('wedding_anniversary_end_month')) == 1
                    ? printf('%01d', $param->input('wedding_anniversary_end_month')) : $param->input('wedding_anniversary_end_month');

                // 結婚記念日_開始、終了の期間
                $q->where('wedding_anniversary', '>=', new \DateTime($param->input('wedding_anniversary_start_year') . '/' . $wedding_anniversary_start_month))
                    ->where('wedding_anniversary', '<=', new \DateTime($param->input('wedding_anniversary_end_year') . '/' . $wedding_anniversary_end_month));
            });
        } else if (($param->filled('wedding_anniversary_start_year') && $param->filled('wedding_anniversary_start_month'))
            && (is_null($param->input('wedding_anniversary_end_year')) || is_null($param->input('wedding_anniversary_end_month')))) {
            // 開始月0埋め
            $wedding_anniversary_start_month = strlen($param->input('wedding_anniversary_start_month')) == 1
                ? printf('%01d', $param->input('wedding_anniversary_start_month')) : $param->input('wedding_anniversary_start_month');

            // 結婚記念日_開始以降
            $query = $query->where('wedding_anniversary', '>=', new \DateTime($param->input('wedding_anniversary_start_year') . '/' . $wedding_anniversary_start_month));
        } else if ((is_null($param->input('wedding_anniversary_start_year')) || is_null($param->input('wedding_anniversary_start_month')))
            && ($param->filled('wedding_anniversary_end_year') && $param->filled('wedding_anniversary_end_month'))) {
            // 終了月0埋め
            $wedding_anniversary_end_month = strlen($param->input('wedding_anniversary_end_month')) == 1
                ? printf('%01d', $param->input('wedding_anniversary_end_month')) : $param->input('wedding_anniversary_end_month');

            // 結婚記念日_終了以前
            $query = $query->where('wedding_anniversary', '<=', new \DateTime($param->input('wedding_anniversary_end_year') . '/' . $wedding_anniversary_end_month));
        }

        return;
    }

    /**
     * ソート条件設定
     *
     * @param $query
     * @param int $order_column_id int 並替基準列
     * @param int $sort_id int 並替方法
     */
    public static function set_order_by(&$query, int $order_column_id, int $sort_id)
    {
        self::_set_order_by($query, $order_column_id, $sort_id);

        return;
    }

    /**
     * ソート条件設定
     *
     * @param $query
     * @param int|null $order_column_id 並替基準列
     * @param int|null $sort_id 並替方法
     */
    private static function _set_order_by(&$query, int $order_column_id = null, int $sort_id = null)
    {
        if (is_null($order_column_id) || is_null($sort_id)) {
            // 未指定の場合、顧客IDの昇順
            $query->orderBy(self::SORT_BY_COLUMN[3], ModelBase::SORT_KIND[0]);
        } else {
            $query->orderBy(self::SORT_BY_COLUMN[$order_column_id], ModelBase::SORT_KIND[$sort_id]);
        }

        return;
    }

    /**
     * DB取得結果整形（一覧取得用）<br>
     * レスポンスの形に合わせ整形し、コレクションで返却
     *
     * @param $collection
     * @param null $param
     * @return Collection
     */
    private static function get_format_column($collection, $param = null): Collection
    {
        // 顧客ランクマスタ情報
        $ranks = MCustomerRankKoji::search_customer_rank_list(new Request());

        $results = new Collection();
        foreach ($collection as $item) {

            $arr = $item->toArray();
            // 顧客ランク名称取得
            $rank_name = '';
            $rank_order = 999;
            if (($ranks->count() > 0) && ($arr['rank'] > 0)) {
                $rank_name = $ranks[$arr['rank'] - 1]['name']; // 顧客ランク名称
                $rank_order = $ranks[$arr['rank'] - 1]['order']; // 顧客ランク順位
            }

            // 顧客ランク系のフィルタ処理
            if (self::filter_rank($param->filled('rank'), $param->filled('rank_filter'), $rank_order)) {
                // 顧客ランク順位がフィルタされたらレスポンスに設定しない
                continue;
            }
            $estimated_order = CommonUtility::is_exist_variable_array($arr['customer_estimated_rank']) ? $arr['customer_estimated_rank']['order'] : 999;
            if (self::filter_rank($param->filled('customer_estimated_rank'), $param->filled('estimated_rank_filter'), $estimated_order)) {
                // 顧客見込みランク順位がフィルタされたらレスポンスに設定しない
                continue;
            }

            $data = [
                'id' => $arr['id'], // 顧客ID
                'sales_contact' => CommonUtility::is_exist_variable_array($arr['employee']) ? $arr['employee']['id'] : '', // 営業担当（担当者）
                'sales_contact_name' => CommonUtility::is_exist_variable_array($arr['employee']) ? $arr['employee']['name'] : '', // 営業担当（担当者）名称
                'name' => $arr['name'], // 顧客名
                'keisho' => $arr['keisho'], // 敬称
                'furigana' => $arr['furigana'], // 顧客名フリガナ
                'tel_no' => $arr['tel_no'], // 顧客TEL
                'mail_address' => $arr['mail_address'], // メールアドレス
                'deficiency_flag' => $arr['is_deficiency'], // 不備情報のみ
                'post_no' => $arr['post_no'], // 郵便番号
                'prefecture' => array_search($arr['prefecture'], ModelBase::PREFECTURE), // 都道府県
                'prefecture_name' => $arr['prefecture'], // 都道府県名称
                'city' => $arr['city'], // 市区町村
                'address' => $arr['address'], // 地名、番地
                'building_name' => $arr['building_name'], // 建物名等
                'rank' => $arr['rank'], // 顧客ランク
                'rank_name' => $rank_name, // 顧客ランク名称
                'estimated_rank' => CommonUtility::is_exist_variable_array($arr['customer_estimated_rank']) ? $arr['customer_estimated_rank']['id'] : '', // 顧客見込みランク
                'estimated_rank_name' => CommonUtility::is_exist_variable_array($arr['customer_estimated_rank']) ? $arr['customer_estimated_rank']['name'] : '', // 顧客見込みランク名称
                'ob_flag' => $arr['ob_flag'], // OBフラグ
                'area' => CommonUtility::is_exist_variable_array($arr['area']) ? $arr['area']['id'] : '', // エリア
                'area_name' => CommonUtility::is_exist_variable_array($arr['area']) ? $arr['area']['name'] : '', // エリア名称
                'building_category' => CommonUtility::is_exist_variable_array($arr['building']) ? $arr['building']['id'] : '', // 建物分類
                'building_category_name' => CommonUtility::is_exist_variable_array($arr['building']) ? $arr['building']['name'] : '', // 建物分類名称
                'madori' => CommonUtility::is_exist_variable_array($arr['madori']) ? $arr['madori']['id'] : '', // 間取り
                'madori_name' => CommonUtility::is_exist_variable_array($arr['madori']) ? $arr['madori']['name'] : '', // 間取り名称
                'building_age' => $arr['building_age'], // 築年数
                'remarks' => $arr['remarks'], // 備考
                'friend_meeting' => $arr['friend_meeting'], // 友の会
                'reform_album' => $arr['reform_album'], // リフォームアルバム
                'case_permit' => $arr['case_permit'], // 事例許可
                'field_tour_party' => $arr['field_tour_party'], // 現場見学会
                'lat' => $arr['lat'], // 緯度
                'lng' => $arr['lng'], // 経度
            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     * DB取得結果整形（CSV出力管理顧客情報一覧取得用）<br>
     * レスポンスの形に合わせ整形し、コレクションで返却
     *
     * @param $collection
     * @param null $param
     * @return Collection
     */
    private static function get_format_column_csv_customer($collection, $param = null): Collection
    {
        // 顧客ランクマスタ情報
        $ranks = MCustomerRankKoji::search_customer_rank_list(new Request());

        $results = new Collection();
        foreach ($collection as $item) {

            $arr = $item->toArray();
            // 顧客ランク名称取得
            $rank_name = '';
            $rank_order = 999;
            if (($ranks->count() > 0) && ($arr['rank'] > 0)) {
                $rank_name = $ranks[$arr['rank'] - 1]['name']; // 顧客ランク名称
                $rank_order = $ranks[$arr['rank'] - 1]['order']; // 顧客ランク順位
            }

            // 顧客ランクのフィルタ処理
            if (self::filter_rank($param->filled('rank'), $param->filled('rank_filter'), $rank_order)) {
                // 顧客ランク順位がフィルタされたらレスポンスに設定しない
                continue;
            }

            $data = [
                'customer_id' => $arr['id'], // 顧客ID
                'customer_name' => $arr['name'], // 顧客名称
                'tel_no' => $arr['tel_no'], // 電話番号
                'prefecture' => $arr['prefecture'], // 都道府県
                'address' => $arr['city'] . $arr['address'] . $arr['building_name'], // 住所
                'ob' => $arr['ob_flag'] == ModelBase::OB ? ModelBase::OB_CUSTOMER[ModelBase::OB] : ModelBase::OB_CUSTOMER[ModelBase::NOT_OB], // OB客（OBの場合「〇」、見込みは空欄）
                'rank' => $rank_name, // 顧客ランク
                'last_completion_date' => CommonUtility::is_exist_variable_array($arr['project']) ? $arr['project']['completion_date'] : '', // 最終完工日
                'total_work_price' => $arr['total_work_price_max'], // 総工事金額
                'work_times' => $arr['work_times_max'], // 工事回数
                'construction_status' => CommonUtility::is_exist_variable_array($arr['project']) ? $arr['project']['construction_status'] : '', // 状況
                'sales_contact' => CommonUtility::is_exist_variable_array($arr['employee']) ? $arr['employee']['name'] : '', // 営業担当
            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     * DB取得結果整形（CSV出力管理生年月日リスト一覧取得用）<br>
     * レスポンスの形に合わせ整形し、コレクションで返却
     *
     * @param $collection
     * @return Collection
     */
    private static function get_format_column_csv_birthday($collection): Collection
    {
        $results = new Collection();
        foreach ($collection as $item) {

            $arr = $item->toArray();

            $data = [
                'customer_id' => $arr['id'], // 顧客ID
                'customer_name' => $arr['name'], // 顧客名称
                'family_name' => CommonUtility::is_exist_variable_array($arr['family']) ? $arr['family']['name'] : '', // 家族お名前
                'birth_date' => CommonUtility::is_exist_variable_array($arr['family']) ? $arr['family']['birth_date'] : '', // 生年月日
                'relationship' => CommonUtility::is_exist_variable_array($arr['family']) ? $arr['family']['relationship'] : '', // 続柄
                'mobile_phone' => CommonUtility::is_exist_variable_array($arr['family']) ? $arr['family']['mobile_phone'] : '', // 携帯番号
                'post_no' => $arr['post_no'], // 郵便番号
                'prefecture' => $arr['prefecture'], // 都道府県
                'address' => $arr['city'] . $arr['address'] . $arr['building_name'], // 住所
                'tel_no' => $arr['tel_no'], // 顧客電話番号
                'sales_contact' => CommonUtility::is_exist_variable_array($arr['employee']) ? $arr['employee']['name'] : '', // 営業担当
            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     * DB取得結果整形（CSV出力管理結婚記念日リスト一覧取得用）<br>
     * レスポンスの形に合わせ整形し、コレクションで返却
     *
     * @param $collection
     * @return Collection
     */
    private static function get_format_column_csv_wedding($collection): Collection
    {
        $results = new Collection();
        foreach ($collection as $item) {

            $arr = $item->toArray();

            $data = [
                'customer_id' => $arr['id'], // 顧客ID
                'customer_name' => $arr['name'], // 顧客名称
                'wedding_anniversary' => $arr['wedding_anniversary'], // 結婚記念日
                'post_no' => $arr['post_no'], // 郵便番号
                'prefecture' => $arr['prefecture'], // 都道府県
                'address' => $arr['city'] . $arr['address'] . $arr['building_name'], // 住所
                'tel_no' => $arr['tel_no'], // 顧客電話番号
                'sales_contact' => CommonUtility::is_exist_variable_array($arr['employee']) ? $arr['employee']['name'] : '', // 営業担当
            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     * DB取得結果整形（1件取得用）<br>
     * レスポンスの形に合わせ整形し、配列で返却
     *
     * @param $obj
     * @return array $data 整形後データ
     */
    private static function get_format_column_one($obj): ?array
    {
        // 顧客ランクマスタ情報
        $ranks = MCustomerRankKoji::search_customer_rank_list(new Request());
        $rank_name = '';
        if (($ranks->count() > 0) && ($obj->rank > 0)) {
            $rank_name = $ranks[$obj->rank - 1]['name']; // 顧客ランク名称
        }
        // 部位リスト取得
        $part_list = array_map('intval', explode(' ', $obj->part_list));
        $part_name_list = null;
        foreach ($part_list as $item) {
            if (MPart::find($item)) {
                $part_name_list[] = MPart::find($item)->name;
            }
        }
        // 見込み部位リスト取得
        $expected_part_list = array_map('intval', explode(' ', $obj->expected_part_list));
        $expected_part_name_list = null;
        foreach ($expected_part_list as $item) {
            if (MPart::find($item)) {
                $expected_part_name_list[] = MPart::find($item)->name;
            }
        }
        // 関連タグリスト取得
        $tag_list = array_map('intval', explode(' ', $obj->tag_list));
        $tag_name_list = null;
        foreach ($tag_list as $item) {
            if (MTag::find($item)) {
                $tag_name_list[] = MTag::find($item)->name;
            }
        }
        // マイカー種別リスト取得
        $my_car_type_list = array_map('intval', explode(' ', $obj->my_car_type));
        $my_car_type_name_list = null;
        foreach ($my_car_type_list as $item) {
            if (MMyCarType::find($item)) {
                $my_car_type_name_list[] = MMyCarType::find($item)->name;
            }
        }

        $data[] = [
            'id' => $obj->id, // 顧客ID
            'sales_shop' => $obj->sales_shop, // 営業担当（店舗）
            'sales_shop_name' => CommonUtility::is_exist_variable($obj->store) ? $obj->store['name'] : '', // 営業担当（店舗）名称
            'sales_contact' => $obj->sales_contact, // 営業担当（担当者）
            'sales_contact_name' => CommonUtility::is_exist_variable($obj->employee) ? $obj->employee['name'] : '', // 営業担当（担当者）名称
            'name' => $obj->name, // 顧客名
            'keisho' => $obj->keisho, // 敬称
            'furigana' => $obj->furigana, // 顧客名フリガナ
            'tel_no' => $obj->tel_no, // 顧客TEL
            'tel_no2' => $obj->tel_no2, // 電話番号2
            'tel_no3' => $obj->tel_no3, // 電話番号3
            'fax_no' => $obj->fax_no, // FAX番号
            'mail_address' => $obj->mail_address, // メールアドレス
            'mail_address2' => $obj->mail_address2, // メールアドレス2
            'mail_address3' => $obj->mail_address3, // メールアドレス3
            'post_no' => $obj->post_no, // 郵便番号
            'prefecture' => array_search($obj->prefecture, ModelBase::PREFECTURE), // 都道府県
            'prefecture_name' => $obj->prefecture, // 都道府県名称
            'city' => $obj->city, // 市区町村
            'address' => $obj->address, // 地名、番地
            'building_name' => $obj->building_name, // 建物名等
            'line_id' => $obj->line_id, // line_id
            'facebook_id' => $obj->facebook_id, // facebook_id
            'twitter_id' => $obj->twitter_id, // twitter_id
            'instagram_id' => $obj->instagram_id, // instagram_id
            'rank' => $obj->rank, // 顧客ランク
            'rank_name' => $rank_name, // 顧客ランク名称
            'source' => $obj->source_id, // 発生源
            'source_name' => CommonUtility::is_exist_variable($obj->source) ? $obj->source['name'] : '', // 発生源名称
            'estimated_rank' => $obj->estimated_rank, // 顧客見込みランク
            'estimated_rank_name' => CommonUtility::is_exist_variable($obj->customer_estimated_rank) ? $obj->customer_estimated_rank['name'] : '', // 顧客見込みランク名称
            'ob_flag' => $obj->ob_flag, // OBフラグ
            'area' => $obj->area_id, // エリア
            'area_name' => CommonUtility::is_exist_variable($obj->area) ? $obj->area['name'] : '', // エリア名称
            'expected_part_list' => array_map('intval', explode(' ', $obj->expected_part_list)), // 見込み部位リスト
            'expected_part_list_name' => $expected_part_name_list, // 見込み部位リスト名称
            'part_list' => array_map('intval', explode(' ', $obj->part_list)), // 部位リスト
            'part_list_name' => $part_name_list, // 部位リスト名称
            'building_category' => $obj->building_category_id, // 建物分類
            'building_category_name' => CommonUtility::is_exist_variable($obj->building) ? $obj->building['name'] : '', // 建物分類名称
            'madori' => $obj->madori_id, // 間取り
            'madori_name' => CommonUtility::is_exist_variable($obj->madori) ? $obj->madori['name'] : '', // 間取り名称
            'building_age' => $obj->building_age, // 築年数
            'remarks' => $obj->remarks, // 備考
            'memo1' => $obj->memo1, // 社内メモ1
            'memo2' => $obj->memo2, // 社内メモ2
            'my_car_type' => array_map('intval', explode(' ', $obj->my_car_type)), // マイカー種別
            'my_car_type_name' => $my_car_type_name_list, // マイカー種別名称
            'my_car_type_other' => $obj->my_car_type_other, // マイカー種別_その他
            'tag_list' => array_map('intval', explode(' ', $obj->tag_list)), // 関連タグリスト
            'tag_list_name' => $tag_name_list, // 関連タグリスト名称
            'introducer' => $obj->introducer, // 紹介者
            'wedding_anniversary' => $obj->wedding_anniversary, // 結婚記念日
            'lat' => $obj->lat, // 緯度
            'lng' => $obj->lng, // 経度
        ];

        return $data;
    }

    /**
     * ランクをフィルタする（顧客ランク、顧客見込みランク用）
     *
     * @param $rank
     * @param $filter
     * @param int $order 順位
     * @return bool
     */
    private static function filter_rank($rank, $filter, int $order = 999): bool
    {
        if ($filter && $order < 999) {
            // ランクフィルタが「のみ」（1）なら、ランクで条件一致条件を指定して検索しているため、ここでの判定は不要

            if ($filter == 2) { // 以上
                // ランク順位が入力値未満ならレスポンスに設定しない
                if ($rank > $order) {
                    return true;
                }
            } else if ($filter == 3) { // 以下
                // ランク順位が入力値より大きいならレスポンスに設定しない
                if ($rank < $order) {
                    return true;
                }
            }
        }

        return false;
    }


    public static function csv_upsert($request, $is_data_migration = false)
    {

        // アップロードファイルのファイルパスを取得
        $file_path = $request->file('filedata')->path();
        // CSV取得
        $file = new \SplFileObject($file_path);
        $file->setFlags(
            \SplFileObject::READ_CSV |// CSVとして行を読み込み
            \SplFileObject::READ_AHEAD |// 先読み／巻き戻しで読み込み
            \SplFileObject::SKIP_EMPTY | // 空行を読み飛ばす
            \SplFileObject::DROP_NEW_LINE// 行末の改行を読み飛ばす
        );
        $current_row = 1;
        $header = [];
        $error = [];
        foreach ($file as $line) {
//          2行目からスタート
            $current_row++;

            // 最終行をスキップ
            if ($line === null) continue;


//            $line = mb_convert_encoding($line, 'UTF-8', 'SJIS-win');
            $line = mb_convert_encoding($line, 'UTF-8');//本番環境移行用
            // ヘッダーを取得
            if (empty($header)) {

                $header = array_keys(static::get_columns());
                continue;
            }

//            データ移行か？
            if ($is_data_migration) {
//                self::data_migration();
                $items[$current_row]['id'] = $line[0];
                $items[$current_row]['sales_contact'] = static::get_employee_codes()[$line[22]];// employee_cd コードから社員 IDを挿入
                $items[$current_row]['name'] = $line[1];
                $items[$current_row]['keisho'] = $line[2];
                $items[$current_row]['furigana'] = $line[4];
                $items[$current_row]['tel_no'] = $line[5];
                $items[$current_row]['tel_no2'] = $line[6];
                $items[$current_row]['tel_no3'] = $line[7];
                $items[$current_row]['is_deficiency'] = 0; //default 0
                $items[$current_row]['fax_no'] = $line[8];
                $items[$current_row]['mail_address'] = $line[17];
                $items[$current_row]['mail_address2'] = $line[18];
                $items[$current_row]['mail_address3'] = $line[19];
                $items[$current_row]['post_no'] = $line[10];
                $items[$current_row]['prefecture'] = $line[13];
                $items[$current_row]['city'] = $line[14];
                $items[$current_row]['address'] = $line[15];
                $items[$current_row]['building_name'] = $line[16];
                $items[$current_row]['line_id'] = null;
                $items[$current_row]['facebook_id'] = null;
                $items[$current_row]['twitter_id'] = null;
                $items[$current_row]['instagram_id'] = null;
                $items[$current_row]['rank'] = intval($line[27]) + 1;
                $items[$current_row]['rank_filter'] = null;
                $items[$current_row]['estimated_rank'] = intval($line[44]) + 1;
                $items[$current_row]['estimated_rank_filter'] = null;
                $items[$current_row]['source_id'] = intval($line[46]) + 1; //未指定分＋
                $items[$current_row]['area_id'] = intval($line[20]) + 1; //未指定分＋
                $items[$current_row]['building_category_id'] = intval($line[23]) + 1; //未指定分＋
                $items[$current_row]['madori_id'] = intval($line[24]) + 1; //未指定分＋
                $items[$current_row]['building_age'] = intval($line[26]);
                $items[$current_row]['completion_start_year'] = null;
                $items[$current_row]['completion_start_month'] = null;
                $items[$current_row]['completion_end_year'] = null;
                $items[$current_row]['completion_end_month'] = null;
                $items[$current_row]['last_completion_start_year'] = null;
                $items[$current_row]['last_completion_start_month'] = null;
                $items[$current_row]['last_completion_end_year'] = null;
                $items[$current_row]['last_completion_end_month'] = null;
                $items[$current_row]['total_work_price_min'] = null;
                $items[$current_row]['total_work_price_max'] = null;
                $items[$current_row]['work_times_min'] = null;
                $items[$current_row]['work_times_max'] = null;
                $items[$current_row]['tag_list'] = null;

                $items[$current_row]['part_list'] = self::create_list($line, 47, 66);
                $items[$current_row]['expected_part_list'] = self::create_list($line, 89, 108);
                $items[$current_row]['remarks'] = $line[28];
                $items[$current_row]['memo1'] = $line[67];
                $items[$current_row]['memo2'] = $line[68];
                $str = null;
                //マイカーの始まりと終わりのカラム
                $i = 0;
                foreach (range(77, 118) as $val) {
                    if (!($val >= 87 && $val <= 108)) {
                        $i++;
                        $str .= (strval(intval($line[$val]) * $i)) . " ";
                    }
                }
                $items[$current_row]['my_car_type'] = $str;
                $items[$current_row]['my_car_type_other'] = $line[87];
                $items[$current_row]['introducer'] = $line[69];
                $items[$current_row]['wedding_anniversary'] = null;
                $items[$current_row]['friend_meeting'] = null;
                $items[$current_row]['reform_album'] = null;
                $items[$current_row]['case_permit'] = null;
                $items[$current_row]['field_tour_party'] = null;
                $items[$current_row]['lat'] = null;
                $items[$current_row]['lng'] = null;
                $items[$current_row]['ob_flag'] = $line[45];
                $items[$current_row]['is_editing'] = 0; //default 0
                $items[$current_row]['created_at'] = $line[29];
                $items[$current_row]['updated_at'] = $line[29];
                $items[$current_row]['last_updated_by'] = $line[30];

                $employee = MEmployee::findOrFail($items[$current_row]['sales_contact']); //社員マスタから所属の店舗取得
//                dd($items[$current_row]['sales_contact']);
                $items[$current_row]['sales_shop'] = $employee->store_id;
            } else {

                //            行にnullがあるかチェック
                $current_col = 0;
                foreach ($line as $key => $val) {
                    if ($val == "") {
                        $error[] = sprintf("%d行目、%d列が空です", $current_row, $current_col);
                    }
                    $current_col++;
                }

                // csvヘッダーをキーにして値を格納
                $items[$current_row] = array_combine($header, $line);
                $items[$current_row]['prefecture'] = array_search($items[$current_row]['prefecture'], ModelBase::PREFECTURE);
                $items[$current_row]['created_at'] = Carbon::now();
                $items[$current_row]['updated_at'] = Carbon::now();
                $items[$current_row]['last_updated_by'] = "山田孝之";
            }

        }
        if ($error) {
            return $error;
        }
        foreach ($items as $item) {
            TCustomer::insert($item);
        }
    }

    public static function create_list($line, $start, $end)
    {
        $str = null;
        $i = 0;
        foreach (range($start, $end) as $val) {
            $i++;
            $str .= (strval(intval($line[$val]) * $i)) . " ";
        }
        return $str; //連結した文字列+スペース返却
    }

    /**
     * 顧客フリーワード検索
     *
     * @param Request $param 検索パラメータ
     * @return Collection 取得データ
     * @throws Exception
     */
    public static function search_list_freeword(Request $param): Collection
    {
        $query = self::_search_list($param);

        // 検索条件（or）
        if ($param->filled('keyword') || !is_null($param->input('keyword'))) {
            self::set_orwhere($query, $param->input('keyword'));
        }

        // ソート条件（order by）
        self::_set_order_by($query, $param->input('sort_by', 3), $param->input('highlow', 0));
        if ($param->filled('limit')) {
            // オフセット条件（offset）
            $query->skip(($param->input('offset', 0) > 0) ? ($param->input('offset') * $param->input('limit')) : 0);
            // リミット条件（limit）
            $query->take($param->input('limit'));
        }

        $result = $query->get();

        if ($result->count() == 0) {
            return $result;
        }

        // 取得結果整形
        return self::get_format_column($result, $param);
    }

    public static function set_orwhere(&$query, string $keyword)
    {
        $query->where(function ($_query) use ($keyword) {
            // 顧客名
            $_query->orWhere('name', 'like', '%' . $keyword . '%');
            // 顧客TEL
            // -を除いた数字で検索する
            $_query->orWhere(function ($q) use ($keyword) {
                $q->whereRaw('replace(tel_no, "-", "") like ?', ['tel_no' => '%' . str_replace('-', '', $keyword) . '%']) // 電話番号
                ->orWhereRaw('replace(tel_no2, "-", "") like ?', ['tel_no2' => '%' . str_replace('-', '', $keyword) . '%']) // 電話番号2
                ->orWhereRaw('replace(tel_no3, "-", "") like ?', ['tel_no3' => '%' . str_replace('-', '', $keyword) . '%']); // 電話番号3
            });
            // 顧客住所
            $_query->orWhere(function ($q) use ($keyword) {
                $q->orWhere('city', 'like', '%' . $keyword . '%') // 市区町村
                ->orWhere('address', 'like', '%' . $keyword . '%') // 地名、番地
                ->orWhere('building_name', 'like', '%' . $keyword . '%'); // 建物名等
            });
        });
        return;
    }

    public static function updateRank($r_param)
    {

        try {

            DB::beginTransaction();

//            新カラムを詰める
            $columns = array(
                'id',
                'rank_koji',
                'rank_last',
            );

            $customer = TCustomer::find($r_param['id']);

            foreach ($columns as $column) {
                if ($r_param[$column] != '') {
                    $customer->{$column} = $r_param[$column];
                }
            }
            $customer->update();
//            dd($customer);


            DB::commit();
            return ["code" => ""];
        } catch (Throwable $e) {
            DB::rollback();
            \Log::debug($e);
//            トランザクションエラー
            dd('heyhey');

            return ["code" => 'rank_fail'];
        }

    }


    public static function getCsvSearchResultList($perPage, $offset)
    {
        //      セッションからログインユーザーのcompany_idを取得
        $company_id = session()->get('company_id');

        // 取得項目
        $query = DB::table('t_customers as c')
            ->select(
                'c.id',
                'c.sales_contact',
                'c.name',
                'c.keisho',
                'c.furigana',
                'c.tel_no',
                'c.mail_address',
                'c.post_no',
                'c.prefecture',
                'c.city',
                'c.address',
                'c.building_name',
                'c.rank_koji',
                'c.rank_last',
                'c.ob_flag',
                'c.area_id',
                'c.building_category_id',
                'c.madori_id',
                'c.building_age',
                'c.remarks',
                'c.lat',
                'c.lng',
                'p.completion_date',
                'p.jyutyu_kin_all',
//                DB::raw('SUM(p2.jyutyu_kin_all) as koji_kin'),
                'e.name as employee_name',
                'k.name as koji_name',
                'l.name as last_name',
            )->leftJoin('t_projects as p', 'c.id', 'p.customer_id')
//            ->leftJoin('t_projects as p2',function($q){
//                $q->on('c.id', '=','p2.customer_id')
////                    ->select(DB::raw('SUM(p2.jyutyu_kin_all) as koji_kin'))
//                    ->whereNull('p2.failure_date')//失注日
//                    ->whereNotNull('p2.contract_date')//契約日
//                    ->whereNotNull('p2.completion_date');//完工日
//
//            })->groupBy('c.id')
//            ->groupBy('p2.customer_id')

            ->leftJoin('m_employees as e', 'c.sales_contact', 'e.employee_cd')
            ->leftJoin('m_customer_rank_kojis as k', 'c.rank_koji', 'k.id')
            ->leftJoin('m_customer_rank_last_completions as l', 'c.rank_last', 'l.id')
            ->where('c.company_id', $company_id)
        ->skip($offset)
        ->take($perPage);

        $query2 = TCustomer::select(DB::raw('SUM(p2.jyutyu_kin_all) as koji_kin'), 'p2.customer_id')
            ->leftjoin('t_projects as p2', function ($q) {
                $q->on('t_customers.id', '=', 'p2.customer_id')
                    ->whereNull('p2.failure_date')//失注日
                    ->whereNotNull('p2.contract_date')//契約日
                    ->whereNotNull('p2.completion_date');//完工日
            })->groupBy('p2.customer_id');


        $result = $query->get();
        return $result;


    }
}

