<?php

namespace App\Models;

use App\Libraries\CommonUtility;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class TMaintenance<br>
 * メンテナンスデータ
 *
 * @package App\Models
 */
class TMaintenance extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（t_maintenances）
    // 主キーのデフォルト名はid
    // 主キーはデフォルトではINT型のAuto Increment
    // デフォルトではタイムスタンプを自動更新（created_at、updated_atを生成）
    // デフォルトの接続データベースは .env の DB_CONNECTION の定義内容

    /**
     * ホワイトリスト
     *
     * @var string[]
     */
    protected $fillable = [
        'customer_id',
        'project_id',
//        'company_id', // TODO 後で追加
        'maintenance_date',
        'supported_kubun',
        'title',
        'supported_date',
        'supported_content',
        'detail',
        'is_valid',
        'lat',
        'lng',
        'last_updated_by',
        'auto_flag',
    ];

    /**
     * メンテナンスと紐づく顧客データ取得（1対多（逆））
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TCustomer::class);
    }

    /**
     * メンテナンスと紐づく案件データ取得（1対多（逆））
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TProject::class);
    }

    /**
     * 案件に紐づく社員マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function employee(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MEmployee::class, 'id', 'project_representative');
    }

    /**
     * 案件に紐づく店舗マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function store(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MStore::class, 'id', 'project_store');
    }

    /**
     * ソート用カラム定義（リスト表示時用）
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'tm.is_valid', // メンテナンス過ぎているマーク // TODO 実装確認
        1 => 'tm.supported_kubun', // 対応済みマーク
        2 => 'tm.maintenance_date', // メンテナンス日
        3 => 'tm.title', // タイトル
        4 => 'tm.supported_date', // 対応日
        5 => 'tp.completion_date', // 完工日
        6 => 'tc.name', // 顧客名
        7 => 'tp.name', // 案件名
        8 => 'tp.project_representative', // 案件担当者
        9 => 'tm.id', // メンテナンスID
    ];

    /**
     * ソート用カラム定義（顧客・案件詳細画面タブ内のメンテナンス情報絞り込み検索時用）
     *
     * @var string[]
     */
    protected const SORT_BY_DETAIL_COLUMN = [
        0 => 'tm.supported_kubun', // 対応済みマーク
        1 => 'tp.name', // 案件名
        2 => 'tp.construction_date', // 着工日
        3 => 'tp.completion_date', // 完工日
        4 => 'tq.quote_creator', // 見積作成者
        5 => 'tm.maintenance_date', // メンテナンス日
        6 => 'tm.title', // タイトル
        7 => 'tm.supported_date', // 対応日
        8 => 'tc.id', // 顧客ID
        9 => 'tp.id', // 案件ID
    ];

    /**
     * メンテナンス情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed $query 取得クエリー
     * @throws Exception
     */
    private static function _search_list(Request $param)
    {
        // 取得項目
        $query = TMaintenance::select(
            // メンテナンスデータ
            'tm.customer_id',
            'tm.project_id',
            'tm.id',
            'tm.supported_kubun',
            'tm.maintenance_date',
            'tm.title',
            'tm.supported_date',
            'tm.is_valid',
            'tm.lat',
            'tm.lng',
            // 顧客データ
            'tc.id as tc_id',
            'tc.name as tc_name',
            'tc.furigana as tc_furigana',
            'tc.post_no as tc_post_no',
            'tc.prefecture as tc_prefecture',
            'tc.city as tc_city',
            'tc.address as tc_address',
            'tc.building_name as tc_building_name',
            'tc.tel_no as tc_tel_no',
            'tc.rank as tc_rank',
            // 案件データ
            'tp.id as tp_id',
            'tp.project_store as tp_project_store',
            'tp.project_representative as tp_project_representative',
            'tp.completion_date as tp_completion_date',
            'tp.name as tp_name',
            'tp.construction_date as tp_construction_date',
            // 見積データ
            'tq.quote_creator as tq_quote_creator',
            // 社員マスタ
            'me.name as project_representative_name',
            'me2.name as quote_creator_name',
        )->distinct()->from('t_maintenances as tm')
            ->join('t_customers as tc', 'tm.customer_id', '=', 'tc.id') // 顧客データ
            ->join('t_projects as tp', 'tm.project_id', '=', 'tp.id') // 案件データ
            ->leftjoin('t_quotes as tq', 'tp.id', '=', 'tq.project_id') // 見積データ
            ->leftjoin('m_employees as me', 'tp.project_representative', '=', 'me.id') // 案件担当者
            ->leftjoin('m_employees as me2', 'tq.quote_creator', '=', 'me2.id'); // 見積作成者

        // 検索条件（where）
        self::set_where_join($query, $param); // メンテナンス
        self::set_where_customer_join($query, $param); // 顧客
        self::set_where_project_join($query, $param); // 案件
        self::set_where_quote_join($query, $param); // 見積

        return $query;
    }

    /**
     * メンテナンス情報一覧検索
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
        if ($param->filled('sort_by')) {
            self::_set_order_by($query, $param->input('sort_by', 9), $param->input('highlow', 0), 1);
        } else {
            self::_set_order_by($query, $param->input('filter_by', 5), $param->input('highlow', 0), 2);
        }
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
        return self::get_format_column($result);
    }

    /**
     * メンテナンス情報一覧検索（全件）
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
     * メンテナンス情報1件検索
     *
     * @param Request $param 検索パラメータ
     * @param int $id メンテナンスID
     * @return array|null 取得データ
     */
    public static function search_one(Request $param, int $id): ?array
    {
        // 取得項目
        $query = TMaintenance::select(
            'customer_id',
            'project_id',
            'id',
            'title',
            'supported_kubun',
            'maintenance_date',
            'supported_date',
            'supported_content',
            'detail',
            'is_valid',
            'lat',
            'lng',
        )->with(['customer' => function($q) {
            // With()で取得時、リレーション先のID項目の取得が必須
            $q->select('id', 'name', 'furigana', 'post_no', 'prefecture', 'city', 'address', 'building_name', 'rank', 'tel_no');
        }]) // 顧客データ
        ->with(['project' => function($q) {
            // With()で取得時、リレーション先のID項目の取得が必須
            $q->select('id', 'name', 'project_store', 'project_representative', 'field_name', 'construction_start_date', 'completion_end_date', 'construction_date', 'completion_date', 'contract_date')
            ->with(['employee' => function($q) {
                $q->select('id', 'name');
            }]) // 社員マスタ
            ->with(['store' => function($q) {
                $q->select('id', 'name');
            }]); // 店舗マスタ
        }]); // 案件データ

        // 検索条件（where）
        self::set_where_customer($query, $param); // 顧客
        self::set_where_project($query, $param); // 案件

        $result = $query->find($id);
        if (is_null($result)) {
            return null;
        }

        // 取得結果整形
        return self::get_format_column_one($result);
    }

    /**
     * メンテナンス情報保存（登録・更新）
     *
     * @param Request $param
     * @param int|null $id メンテナンスID
     * @return string
     */
    public static function upsert(Request $param, int $id = null): string
    {
        $arr = $param->all();

        // DB登録・更新用にパラメータ変換
        // 対応区分
        if ($param->filled('is_fixed')) {
            if ($param->input('is_fixed') == 1) {
                $arr['supported_kubun'] = 2; // 対応済
            } else {
                $arr['supported_kubun'] = 0; // 未対応
            }
        }
        // 有効フラグ
        if ($param->filled('is_muko')) {
            if ($param->input('is_muko') == 0) {
                $arr['is_valid'] = 1; // 有効
            } else {
                $arr['is_valid'] = 0; // 無効
            }
        }
        // 最終更新者
        // TODO ログインユーザー名を登録
        $arr['last_updated_by'] = '管理者';

        if ($id) {
            // 更新
            $obj = TMaintenance::find($id);
            if (is_null($obj)) {
                return '404';
            }

            // 更新処理
            $obj->fill($arr)->save();
        } else {
            // 登録処理
            $customer = new TMaintenance();
            $customer->fill($arr)->save();
        }

        return 'ok';
    }

    /**
     * 検索条件設定（JOIN時用）
     *
     * @param $query
     * @param Request $param 検索パラメータ
     * @throws Exception
     */
    public static function set_where_join(&$query, Request $param)
    {
        // メンテナンス日開始～終了
        if ($param->filled('maintenance_date_start') && $param->filled('maintenance_date_end')) {
            $query = $query->where(function ($q) use ($param) {
                // メンテナンス日_開始、終了の期間
                $q->where('tm.maintenance_date', '>=', new \DateTime($param->input('maintenance_date_start')))
                    ->where('tm.maintenance_date', '<=', new \DateTime($param->input('maintenance_date_end')));
            });
        } else if ($param->filled('maintenance_date_start') && is_null($param->input('maintenance_date_end'))) {
            // メンテナンス日_開始以降
            $query = $query->where('tm.maintenance_date', '>=', new \DateTime($param->input('maintenance_date_start')));
        } else if (is_null($param->input('tm.maintenance_date_start')) && $param->filled('maintenance_date_end')) {
            // メンテナンス日_終了以前
            $query = $query->where('tm.maintenance_date', '<=', new \DateTime($param->input('maintenance_date_end')));
        }
        // 無効情報も含む
        if ($param->filled('is_muko') && $param->input('is_muko') == 0) {
            $query = $query->where('tm.is_valid', 1);
        }
        // 対応区分
        if ($param->filled('supported_kubun') && $param->input('supported_kubun') != 1) {
            $query = $query->where('tm.supported_kubun', $param->input('supported_kubun'));
        }
        // 文字列検索
        if ($param->filled('word')) {
            $query = $query->where(function ($q) use ($param) {
                $q->where('tm.title', 'like', '%' . $param->input('word') . '%') // タイトル
                ->orWhere('tm.detail', 'like', '%' . $param->input('word') . '%') // 詳細内容
                ->orWhere('tm.supported_content', 'like', '%' . $param->input('word') . '%'); // 対応内容
            });
        }
        //タイトル検索
        if ($param->filled('title')) {
            $query = $query->where('tm.title', 'like', '%' . $param->input('title') . '%');
        }
        // キーワード検索
        if ($param->filled('sp_word')) {
            $query = $query->where(function ($q) use ($param) {
                $q->where('tm.title', 'like', '%' . $param->input('sp_word') . '%'); // タイトル
            });
        }
        // メンテナンス日
        if ($param->filled('maintenance_date')) {
            $query = $query->whereDate('tm.maintenance_date', $param->input('maintenance_date'));
        }

        // 顧客・案件詳細画面内タブでの絞込み用
        // 対応済みマーク
        if ($param->filled('is_fixed')) {
            if ($param->input('is_fixed') == true) {
                $query = $query->where('tm.supported_kubun', 2); // 対応済
            } else {
                $query = $query->where('tm.supported_kubun', 0); // 未対応
            }
        }
        //// メンテナンスタイトル
        //if ($param->filled('title')) {
        //    $query = $query->where('tm.title', $param->input('title'));
        //}
        // 対応日
        if ($param->filled('supported_date')) {
            $query = $query->whereDate('tm.supported_date', $param->input('supported_date'));
        }

        return;
    }

    /**
     * 検索条件設定（顧客情報）
     *
     * @param $query
     * @param Request $param 検索パラメータ
     */
    public static function set_where_customer(&$query, Request $param)
    {
        // 顧客ID
        if ($param->filled('customer_id')) {
            $query = $query->where('id', $param->input('customer_id'));
        }

        return;
    }

    /**
     * 検索条件設定（顧客情報（JOIN時用））
     *
     * @param $query
     * @param Request $param 検索パラメータ
     */
    public static function set_where_customer_join(&$query, Request $param)
    {
        // 顧客ID
        if ($param->filled('customer_id')) {
            $query = $query->where('tc.id', $param->input('customer_id'));
        }
        // キーワード検索
        if ($param->filled('sp_word')) {
            $query = $query->where(function ($q) use ($param) {
                $q->where('tc.name', 'like', '%' . $param->input('sp_word') . '%') // 顧客名
                ->orWhere('tc.tel_no', 'like', '%' . $param->input('sp_word') . '%'); // 顧客TEL
            });
        }

        return;
    }

    /**
     * 検索条件設定（案件情報）
     *
     * @param $query
     * @param Request $param 検索パラメータ
     */
    public static function set_where_project(&$query, Request $param)
    {
        // 案件ID
        if ($param->filled('project_id')) {
            $query = $query->where('id', $param->input('project_id'));
        }

        return;
    }

    /**
     * 検索条件設定（案件情報（JOIN時用））
     *
     * @param $query
     * @param Request $param 検索パラメータ
     * @throws Exception
     */
    public static function set_where_project_join(&$query, Request $param)
    {
        // 案件ID
        if ($param->filled('project_id')) {
            $query = $query->where('tp.id', $param->input('project_id'));
        }
        // 案件営業担当店舗
        if ($param->filled('sales_shop')) {
            $query = $query->where('tp.project_store', $param->input('sales_shop'));
        }
        // 案件営業担当担当者
        if ($param->filled('sales_contact')) {
            $query = $query->where('tp.project_representative', $param->input('sales_contact'));
        }
        // 完工日
        if ($param->filled('completion_date_start') && $param->filled('completion_date_end')) {
            $query = $query->where(function ($q) use ($param) {
                // 完工日_開始、終了の期間
                $q->where('tp.completion_date', '>=', new \DateTime($param->input('completion_date_start')))
                    ->where('tp.completion_date', '<=', new \DateTime($param->input('completion_date_end')));
            });
        } else if ($param->filled('completion_date_start') && is_null($param->input('completion_date_end'))) {
            // 完工日_開始以降
            $query = $query->where('tp.completion_date', '>=', new \DateTime($param->input('completion_date_start')));
        } else if (is_null($param->input('completion_date_start')) && $param->filled('completion_date_end')) {
            // 完工日_終了以前
            $query = $query->where('tp.completion_date', '<=', new \DateTime($param->input('completion_date_end')));
        }
        // 案件名
        if ($param->filled('project_name')) {
            $query = $query->where('tp.name', 'like', '%' . $param->input('project_name') . '%');
        }
        // 着工日
        if ($param->filled('construction_date')) {
            $query = $query->whereDate('tp.construction_date', $param->input('construction_date'));
        }
        // 完工日
        if ($param->filled('completion_date')) {
            $query = $query->whereDate('tp.completion_date', $param->input('completion_date'));
        }

        return;
    }

    /**
     * 検索条件設定（見積情報（JOIN時用））
     *
     * @param $query
     * @param Request $param 検索パラメータ
     */
    public static function set_where_quote_join(&$query, Request $param)
    {
        // 見積作成者
        if ($param->filled('quote_creator')) {
            $query = $query->where('tq.quote_creator', $param->input('quote_creator'));
        }

        return;
    }

    /**
     * ソート条件設定
     *
     * @param $query
     * @param int $order_column_id 並替基準列
     * @param int $sort_id 並替方法
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
     * @param int $filter 対象の並替基準列一覧の選定
     */
    private static function _set_order_by(&$query, int $order_column_id = null, int $sort_id = null, int $filter = 1)
    {
        if (is_null($order_column_id) || is_null($sort_id)) {
            if ($filter == 1) {
                // 未指定の場合、メンテナンスIDの昇順
                $query->orderBy(self::SORT_BY_COLUMN[9], ModelBase::SORT_KIND[0]);
            } else {
                // 未指定の場合、メンテナンス日の昇順
                $query->orderBy(self::SORT_BY_DETAIL_COLUMN[5], ModelBase::SORT_KIND[0]);
            }
        } else {
            if ($filter == 1) {
                $query->orderBy(self::SORT_BY_COLUMN[$order_column_id], ModelBase::SORT_KIND[$sort_id]);
            } else {
                $query->orderBy(self::SORT_BY_DETAIL_COLUMN[$order_column_id], ModelBase::SORT_KIND[$sort_id]);
            }
        }

        return;
    }

    /**
     * DB取得結果整形（一覧取得用）<br>
     * レスポンスの形に合わせ整形し、コレクションで返却
     *
     * @param $collection
     * @return Collection $results 整形後データ
     */
    private static function get_format_column($collection): Collection
    {
        // 顧客ランクマスタ情報
        $ranks = MCustomerRankKoji::search_customer_rank_list(new Request());

        $results = new Collection();
        foreach ($collection as $item) {

            $arr = $item->toArray();
            // 顧客ランク
            $rank = $arr['tc_rank'];
            // 顧客ランク名称取得
            $rank_name = '';
            if (($ranks->count() > 0) && ($rank > 0)) {
                $rank_name = $ranks[$rank - 1]['name']; // 顧客ランク名称
            }

            $data = [
                'customer_id' => $arr['tc_id'], // 顧客ID
                'project_id' => $arr['tp_id'], // 案件ID
                'id' => $arr['id'], // メンテナンスID
                'maintenance_past_flag' => false, // メンテナンス過ぎているマーク // TODO 実装確認
                'fixed_flag' => $arr['supported_kubun'] == 2, // 対応済みマーク
                'maintenance_date' => $arr['maintenance_date'], // メンテナンス日
                'title' => $arr['title'], // メンテナンスタイトル
                'supported_date' => $arr['supported_date'], // 対応日
                'construction_date' => $arr['tp_construction_date'], // 着工日
                'completion_date' => $arr['tp_completion_date'], // 完工日
                'customer_name' => $arr['tc_name'], // 顧客名
                'project_name' => $arr['tp_name'], // 案件名
                'project_representative' => $arr['project_representative_name'], // 案件担当者
                'furigana' => $arr['tc_furigana'], // 顧客名フリガナ
                'maintenance_name' => $arr['title'], // メンテナンス名
                'post_no' => $arr['tc_post_no'], // 顧客_郵便番号
                'customer_place' => '', // 顧客住所 別途結合してマージする
                'tel_no' => $arr['tc_tel_no'], // 顧客TEL
                'customer_rank_name' => $rank_name, // 顧客ランク名称
                'lat' => $arr['lat'], // 緯度
                'lng' => $arr['lng'], // 経度
                'quote_creator' => $arr['quote_creator_name'], // 見積作成者
            ];
            // 別途変換要の項目の処理
            $data2 = [
                // 文字列結合演算子（.） null値は常に空文字として結合される
                'customer_place' => $arr['tc_prefecture'] . $arr['tc_city'] . $arr['tc_address'] . $arr['tc_building_name'], // 顧客住所
            ];
            $results->push(array_merge($data, $data2));
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
        $customer_rank = CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['rank'] : 0;
        $rank_name = '';
        if (($ranks->count() > 0) && ($customer_rank > 0)) {
            $rank_name = $ranks[$customer_rank - 1]['name']; // 顧客ランク名称
        }
        $data = [
            'customer_id' => $obj->customer_id, // 顧客ID
            'project_id' => $obj->project_id, // 案件ID
            'id' => $obj->id, // メンテナンスID
            'customer_name' => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['name'] : '', // 顧客名
            'furigana' => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['furigana'] : '', // 顧客名フリガナ
            'title' => $obj->title, // メンテナンスタイトル
            'post_no' => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['post_no'] : '', // 顧客_郵便番号
            'customer_place' => '', // 顧客住所 別途結合してマージする
            'prefecture' => CommonUtility::is_exist_variable($obj->customer) ? array_search($obj->customer['prefecture'], ModelBase::PREFECTURE) : '', // 顧客_都道府県
            'prefecture_name' => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['prefecture'] : '', // 顧客_都道府県名称
            'city' => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['city'] : '', // 顧客_市区町村
            'address' => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['address'] : '', // 顧客_地名、番地
            'building_name' => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['building_name'] : '', // 顧客_建物名等
            'customer_rank' => $customer_rank, // 顧客ランク
            'customer_rank_name' => $rank_name, // 顧客ランク名称
            'maintenance_past_flag' => false, // メンテナンス過ぎているマーク // TODO 実装確認
            'fixed_flag' => $obj->supported_kubun == 2, // 対応済みマーク
            'tel_no' => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['tel_no'] : '', // 顧客TEL
            'project_representative' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['project_representative'] : 0, // 案件担当者
            'project_representative_name' => CommonUtility::is_exist_variable($obj->project) ? (CommonUtility::is_exist_variable($obj->project->employee) ? $obj->project->employee['name'] : '') : '', // 案件担当者名称
            'maintenance_date' => $obj->maintenance_date, // メンテナンス日
            'supported_date' => $obj->supported_date, // 対応日
            'project_name' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['name'] : '', // 案件名
            'sales_shop' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['project_store'] : 0, // 案件営業担当店舗
            'sales_shop_name' => CommonUtility::is_exist_variable($obj->project) ? (CommonUtility::is_exist_variable($obj->project->store) ? $obj->project->store['name'] : '') : '', // 案件営業担当店舗名称
            'sales_contact' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['project_representative'] : '', // 案件営業担当担当者
            'sales_contact_name' => CommonUtility::is_exist_variable($obj->project) ? (CommonUtility::is_exist_variable($obj->project->employee) ? $obj->project->employee['name'] : '') : '', // 案件営業担当担当者名称
            'field_name' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['field_name'] : '', // 現場名称
            'construction_start_date' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['construction_start_date'] : '', // 着工予定日
            'completion_end_date' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['completion_end_date'] : '', // 完工予定日
            'construction_date' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['construction_date'] : '', // 着工日
            'completion_date' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['completion_date'] : '', // 完工日
            'supported_content' => $obj->supported_content, // 対応内容
            'detail' => $obj->detail, // 詳細内容
            'contract_date' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['contract_date'] : '', // 契約日
            'is_muko' => !$obj->is_valid, // 無効フラグ
            'lat' => $obj->lat, // 緯度
            'lng' => $obj->lng, // 経度
        ];
        // 別途変換要の項目の処理
        $data2 = [
            // 文字列結合演算子（.） null値は常に空文字として結合される
            'customer_place' => ModelBase::PREFECTURE[$data['prefecture']] . $data['city'] . $data['address'] . $data['building_name'], // 顧客住所
        ];
        $result[] = array_merge($data, $data2);

        return $result;
    }

    /**
     * メンテナンス情報フリーワード検索
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
        if ($param->filled('sort_by')) {
            self::_set_order_by($query, $param->input('sort_by', 9), $param->input('highlow', 0), 1);
        } else {
            self::_set_order_by($query, $param->input('filter_by', 5), $param->input('highlow', 0), 2);
        }
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
        return self::get_format_column($result);
    }

    public static function set_orwhere(&$query, String $keyword)
    {
        $query->where(function($_query) use ($keyword) {
            // 顧客名
            $_query->orWhere('tc.name', 'like', '%' . $keyword . '%');
            //タイトル検索
            $_query->orWhere('title', 'like', '%' . $keyword . '%');
            // -を除いた数字で検索する
            $_query->orWhere(function ($q) use ($keyword) {
                $q->whereRaw('replace(tc.tel_no, "-", "") like ?', ['tc.tel_no' => '%' . str_replace('-', '', $keyword) . '%']) // 電話番号
                    ->orWhereRaw('replace(tc.tel_no2, "-", "") like ?', ['tc.tel_no2' => '%' . str_replace('-', '', $keyword) . '%']) // 電話番号2
                    ->orWhereRaw('replace(tc.tel_no3, "-", "") like ?', ['tc.tel_no3' => '%' . str_replace('-', '', $keyword) . '%']); // 電話番号3
            });
            // 顧客住所
            $_query->orWhere(function ($q) use ($keyword) {
                $q->orWhere('tc.city', 'like', '%' . $keyword . '%') // 市区町村
                    ->orWhere('tc.address', 'like', '%' . $keyword . '%') // 地名、番地
                    ->orWhere('tc.building_name', 'like', '%' . $keyword . '%'); // 建物名等
            });
        });
        return;
    }
}
