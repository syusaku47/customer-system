<?php

namespace App\Models;

use App\Libraries\CommonUtility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Class TProject<br>
 * 案件データ
 *
 * @package App\Models
 */
class TProject extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（t_projects）
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
//        'company_id', // TODO 後で追加
        'field_name',
        'name',
        'field_address',
        'field_tel_no',
        'field_fax_no',
        'construction_period_start',
        'construction_period_end',
        'construction_start_date',
        'construction_date',
        'completion_end_date',
        'completion_date',
        'source_id',
        'contract_no',
        'contract_date',
        'cancel_date',
        'expected_amount',
        'order_price',
        'project_rank',
        'project_rank_filter',
        'project_store',
        'project_representative',
        'post_no',
        'prefecture',
        'city',
        'address',
        'building_name',
        'construction_parts',
        'complete_date',
        'failure_date',
        'failure_cause',
        'failure_remarks',
        'cancel_reason',
        'execution_end',
        'order_detail1',
        'order_detail2',
        'construction_status',
        'complete_flag',
        'alert_flag',
        'remarks',
        'lat',
        'lng',
        'is_editing',
        'last_updated_by',
    ];

    /**
     * 案件と紐づく顧客データ取得（1対多（逆））
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TCustomer::class);
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
     * 案件に紐づく発生源マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function source(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MSource::class, 'id', 'source_id');
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
     * 案件に紐づく案件ランクマスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rank_project(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MProjectRank::class, 'id', 'project_rank');
    }

    /**
     * 案件に紐づく失注理由マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lost_order(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MLostorder::class, 'id', 'failure_cause');
    }

    /**
     * ソート用カラム定義（リスト表示時用）
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'tp.complete_flag', // 対応完了フラグ
        1 => 'tp.alert_flag', // アラートフラグ
        2 => 'tp.id', // 案件ID
        3 => 'tp.field_name', // 現場名称
        4 => 'tc.rank', // 顧客ランク
        5 => 'tp.name', // 案件名
        6 => 'tp.field_tel_no', // 現場電話番号
        7 => 'field_place', // 現場住所
        8 => 'tp.construction_start_date', // 着工予定日
        9 => 'tp.completion_end_date', // 完工予定日
        10 => 'tp.construction_date', // 着工日
        11 => 'tp.completion_date', // 完工日
        12 => 'tp.contract_no', // 契約番号
        13 => 'tp.source_id', // 発生源
        14 => 'tp.remarks', // 備考
        15 => 'tp.project_representative', // 担当名
        16 => 'tp.contract_date', // 契約日
    ];

    /**
     * ソート用カラム定義（顧客詳細画面タブ内の案件情報絞り込み検索時用）
     *
     * @var string[]
     */
    protected const SORT_BY_DETAIL_COLUMN = [
        0 => 'tp.name', // 案件名
        1 => 'tp.order_price', // 受注金額（契約金額）
        2 => 'tp.project_representative', // 案件担当者
        3 => 'tp.created_at', // 登録日
        4 => 'tp.construction_date', // 着工日
        5 => 'tp.completion_date', // 完工日
        6 => 'tp.complete_date', // 完了日
        7 => 'tp.source_id', // 発生源
        8 => 'tp.contract_date', // 契約日
        9 => 'tp.failure_date', // 失注日
        10 => 'tp.cancel_date', // キャンセル日
        11 => 'tp.remarks', // 備考
        12 => 'tp.id', // 案件ID
        13 => 'tc.id', // 顧客ID
    ];

    /**
     * 案件情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed $query 取得クエリー
     */
    private static function _search_list(Request $param)
    {

        //サブクエリ作成
        //LEFT JOIN (SELECT COUNT(id) AS estimate_count, project_id FROM estimate GROUP BY project_id) AS estimate_status ON estimate_status.project_id = vtb_project.id)
        $sub_query = TQuote::select("project_id", DB::raw("COUNT(project_id) as quotes_count"))->groupby('project_id');
        // TODO 見積データテーブルのカラムが確定してから作成する
        ////LEFT JOIN (SELECT jyutyu_yotei_dt, gokei_kin as estimate_kin, estimate_dt, project_id, id FROM estimate as e where e.saisyu_f = '1') AS estimate_last ON estimate_last.project_id = vtb_project.id)
        //$sub_query2 = TQuote::select("id", "project_id", "including_tax_total_quote", "quote_date")->where('saisyu_f', 1);

        // 取得項目
        $query = TProject::select(
            // 案件データ
            'tp.customer_id',
            'tp.id',
            'tp.name',
            'tp.post_no',
            'tp.prefecture',
            'tp.city',
            'tp.address',
            'tp.building_name',
            DB::raw('CONCAT(IFNULL(tp.prefecture, ""), IFNULL(tp.city, ""), IFNULL(tp.address, ""), IFNULL(tp.building_name, "")) as field_place'),
            'tp.project_rank',
            'tp.project_rank',
            'tp.field_tel_no',
            'tp.field_fax_no',
            'tp.project_store',
            'tp.project_representative',
//            'tp.image', // TODO GoogleMapAPIで取得した画像を保存し、それを取得する処理を追加予定
            'tp.complete_flag',
            'tp.alert_flag',
            'tp.field_name',
            'tp.construction_start_date',
            'tp.completion_end_date',
            'tp.construction_date',
            'tp.completion_date',
            'tp.source_id',
            'tp.contract_no',
            'tp.remarks',
            'tp.contract_date',
            'tp.lat',
            'tp.lng',
            'tp.order_price',
            'tp.created_at',
            'tp.complete_date',
            'tp.failure_date',
            'tp.cancel_date',
            // 顧客データ
            'tc.id as tc_id',
            'tc.name as tc_name',
            'tc.furigana as tc_furigana',
            'tc.post_no as tc_post_no',
            'tc.prefecture as tc_prefecture',
            'tc.city as tc_city',
            'tc.address as tc_address',
            'tc.building_name as tc_building_name',
            'tc.rank as tc_rank',
            // 発生源マスタ
            'ms.id as ms_id',
            'ms.name as ms_name',
            //見積
            'tq.quotes_count',
        )->distinct()->where('tp.is_editing', 0)->from('t_projects as tp')->join('t_customers as tc', 'tp.customer_id', '=', 'tc.id') // 顧客データ
            ->leftjoin('m_sources as ms', 'tp.source_id', '=', 'ms.id') // 発生源マスタ
        ->with(['store' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 店舗マスタ
        ->with(['employee' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 社員マスタ
        ->with(['rank_project' => function($q) {
            $q->select('name', 'id');
        }])// 案件ランクマスタ
        ->leftJoin(\DB::raw("({$sub_query->toSql()}) AS tq"), 'tq.project_id', '=', 'tp.id');
        $query->mergeBindings($sub_query->getQuery());

        // 検索条件（where）
        self::set_where_join($query, $param); // 案件
        self::set_where_customer_join($query, $param); // 顧客

        return $query;
    }

    /**
     * 案件情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return Collection 取得データ
     */
    public static function search_list(Request $param): Collection
    {
        $query = self::_search_list($param);

//DB::enableQueryLog();
        $result_all = $query->get();
//Log::error(DB::getQueryLog());
        if ($result_all->count() == 0) {
            return $result_all;
        }

        // ソート条件（order by）
        if ($param->filled('sort_by')) {
            self::_set_order_by($query, $param->input('sort_by', 2), $param->input('highlow', 0), 1);
        } else {
            self::_set_order_by($query, $param->input('filter_by', 12), $param->input('highlow', 0), 2);
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
     * 案件情報一覧検索（全件）
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_list_count(Request $param)
    {
        $query = self::_search_list($param);

        return $query->get();
    }

    /**
     * 案件情報1件検索
     *
     * @param Request $param 検索パラメータ
     * @param int $id 案件ID
     * @return array|null 取得データ
     */
    public static function search_one(Request $param, int $id): ?array
    {
        // 取得項目
        $query = TProject::select(
            'customer_id',
            'id',
            'name',
            'source_id',
            'project_rank',
            'project_store',
            'project_representative',
            'field_name',
            'post_no',
            'prefecture',
            'city',
            'address',
            'building_name',
            'field_tel_no',
            'field_fax_no',
            'construction_parts',
            'expected_amount',
            'contract_no',
            'contract_date',
            'construction_period_start',
            'construction_period_end',
            'construction_start_date',
            'completion_end_date',
            'construction_date',
            'completion_date',
            'complete_date',
            'failure_date',
            'failure_cause',
            'failure_remarks',
            'cancel_date',
            'cancel_reason',
            'execution_end',
            'order_detail1',
            'order_detail2',
            'lat',
            'lng',
        )->where('is_editing', 0)->with(['customer' => function($q) {
            // With()で取得時、リレーション先のID項目の取得が必須
            $q->select('id', 'name', 'post_no', 'prefecture', 'city', 'address', 'building_name', 'rank');
        }])  // 顧客データ
        ->with(['store' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 店舗マスタ
        ->with(['employee' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 社員マスタ
        ->with(['source' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 発生源マスタ
        ->with(['rank_project' => function($q) {
            $q->select('name', 'id');
        }]) // 案件ランクマスタ
        ->with(['lost_order' => function($q) {
            $q->select('lost_reason', 'id')->where('is_valid', 1);
        }]); // 失注理由マスタ

        // 検索条件（where）
        self::set_where_customer($query, $param); // 顧客

        $result = $query->find($id);
        if (is_null($result)) {
            return null;
        }

        // 取得結果整形
        return self::get_format_column_one($result);
    }

    /**
     * 案件情報保存（登録・更新）
     *
     * @param Request $param
     * @param int|null $id 案件ID
     * @return string
     */
    public static function upsert(Request $param, int $id = null): string
    {
        $arr = $param->all();

        // DB登録・更新用にパラメータ変換
        // 発生源
        if ($param->filled('source')) {
            $arr['source_id'] = $param->input('source');
        }
        // 案件担当店舗
        if ($param->filled('sales_shop')) {
            $arr['project_store'] = $param->input('sales_shop');
        }
        // 案件担当担当者
        if ($param->filled('sales_contact')) {
            $arr['project_representative'] = $param->input('sales_contact');
        }
        // 現場郵便番号
        if ($param->filled('field_post_no')) {
            $arr['post_no'] = $param->input('field_post_no');
        }
        // 現場都道府県
        if ($param->filled('field_prefecture')) {
            $arr['prefecture'] = ModelBase::PREFECTURE[$param->input('field_prefecture')];
        }
        // 現場市区町村
        if ($param->filled('field_city')) {
            $arr['city'] = $param->input('field_city');
        }
        // 現場地名、番地
        if ($param->filled('field_address')) {
            $arr['address'] = $param->input('field_address');
        }
        // 現場建物名等
        if ($param->filled('field_building_name')) {
            $arr['building_name'] = $param->input('field_building_name');
        }
        // 工事部位
        if ($param->filled('construction_parts')) {
            //$arr['construction_parts'] = implode(' ', $param->input('construction_parts'));
            $construction_parts = $param->input('construction_parts');
            if (! empty($construction_parts) && is_array($construction_parts)) {
                foreach ($construction_parts as $key => $value) {
                    $construction_parts[$key] = str_pad($value, 3, '0', STR_PAD_LEFT);
                }
                $arr['construction_parts'] = implode(' ', $construction_parts);
            } else {
                $arr['construction_parts'] = null;
            }
        }
        // 最終更新者
        // TODO ログインユーザー名を登録
        $arr['last_updated_by'] = '管理者';
        // 編集中フラグ 登録済みにする
        $arr['is_editing'] = 0;

        if ($id) {
            // 更新
            $project = TProject::find($id);
            if (is_null($project)) {
                return '404';
            }

            //顧客情報の取得
            $customer = TCustomer::where('id', $arr['customer_id'])->first();
            if (is_null($customer)) {
                return '404';
            }

            //メンテナンス情報自動作成レコード有無
            $m_count = TMaintenance::where('project_id', $id)->where('auto_flag', 1)->count();

            //アフターメンテナンスマスタ取得
            $amaintenance = MAfterMaintenance::where('is_valid', 1)->get();
            if (is_null($amaintenance)) {
                return '404';
            }

            // トランザクション開始
            DB::beginTransaction();
            try {
                // 更新処理
                $project->fill($arr)->save();

                // 顧客情報の更新
                if ($customer->ob_flag == ModelBase::NOT_OB && $param->filled('complete_date')) {
                    $customer->ob_flag = ModelBase::OB;
                    $customer->save();
                }
                //自動作成メンテナンス情報あり、かつ完工日未設定
                if ($m_count > 0 && !$param->filled('completion_date')) {

                    TMaintenance::where('project_id', $id)->where('auto_flag', 1)->delete();

                //自動作成メンテナンス情報なし、かつ完工日設定
                } else if ($m_count == 0 && $param->filled('completion_date')) {

                    $tm_param = [];
                    foreach ($amaintenance as $val) {
                        $tm_param[] = [
                            'customer_id'       => $arr['customer_id'],
                            'project_id'        => $id,
                            'maintenance_date'  => (new Carbon(Carbon::today()))->addMonthsNoOverflow($val['ins_expected_date'])->format("Y-m-d"),
                            'supported_kubun'   => 0,
                            'title'             => "{$customer->name}（{$project->name}）" . $val['ins_expected_date'] . "ヶ月後アフターメンテナンス",
                            'supported_date'    => null,
                            'is_valid'          => 1,
                            'lat'               => $arr['lat'],
                            'lng'               => $arr['lng'],
                            'created_at'       => Carbon::now(),
                            'updated_at'       => Carbon::now(),
                            'last_updated_by'   => '管理者',// TODO ログインユーザー名を登録
                            'auto_flag'         => 1,
                        ];
                    }
                    //自動メンテナンス情報発行
                    TMaintenance::insert($tm_param);
                }
                //コミット
                DB::commit();
            }
            catch (\Exception $e) {
                Log::error($e->getMessage());
    
                //ロールバック
                DB::rollBack();
                throw new \Exception("案件情報の更新に失敗しました");
            }
        } else {
            // 登録処理
            $project = new TProject();
            // トランザクション開始
            DB::beginTransaction();
            try {
                $project->fill($arr)->save();
                //コミット
                DB::commit();
            }
            catch (\Exception $e) {
                Log::error($e->getMessage());
    
                //ロールバック
                DB::rollBack();
                throw new \Exception("案件情報の更新に失敗しました");
            }
        }

        return 'ok';
    }

    /**
     * 案件ID発行
     *
     * @param Request $param
     * @param int $customer_id 顧客ID
     * @return int
     */
    public static function get_id(Request $param, int $customer_id): int
    {
        try {
            $arr = $param->all();
            // 必須項目をダミーで登録
            $arr['customer_id'] = $customer_id; // 顧客ID
            $arr['last_updated_by'] = 'ダミー'; // 最終更新者
            $arr['is_editing'] = 1; // 編集中フラグ 編集中にする
            // 登録処理
            $project = new TProject();
            $project->fill($arr)->save();
            //コミット
            DB::commit();
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
    
            //ロールバック
            DB::rollBack();
            throw new \Exception("案件IDの発行に失敗しました");
        }

        return TProject::get()->max('id');
    }

    /**
     * 編集中案件情報削除
     *
     * @param int $id 案件ID
     */
    public static function remove_edit_data(int $id)
    {
        // 案件情報削除処理
        TProject::destroy($id);

        return;
    }

    /**
     * 検索条件設定（JOIN時用）
     *
     * @param $query
     * @param Request $param 検索パラメータ
     */
    public static function set_where_join(&$query, Request $param)
    {
        // 簡易検索
        // 営業担当（店舗）
        if ($param->filled('sales_shop')) {
            $query = $query->where('tp.project_store', $param->input('sales_shop'));
        }
        // 営業担当（担当者）
        if ($param->filled('sales_contact')) {
            $query = $query->where('tp.project_representative', $param->input('sales_contact'));
        }
        // 案件名
        if ($param->filled('name')) {
            $query = $query->where('tp.name', 'like', '%' . $param->input('name') . '%');
        }
        // 現場名称
        if ($param->filled('field_name')) {
            $query = $query->where('tp.field_name', 'like', '%' . $param->input('field_name') . '%');
        }
        // 現場電話番号
        if ($param->filled('field_tel_no')) {
            $query = $query->where('tp.field_tel_no', 'like', '%' . str_replace('-', '', $param->input('field_tel_no')) . '%');
        }
        // 工事状況
        if (CommonUtility::is_exist_variable_array($param->input('construction_status'))
            && !is_null($param->input('construction_status')[0])) {
        //    $query = $query->where('tp.construction_status', ModelBase::CONSTRUCTION_STATUS[$param->input('construction_status')]);
        //}
            $construction_status = $param->input('construction_status');
            $check_value = 0;
            foreach ($construction_status as $value) {
                $check_value += (2 ** ($value - 1));
            }
            $query = $query->where(function($query) use($check_value) {
                $where_cnt = 0;

                if (($check_value & bindec('1')) > 0) {        // 案件化
                    //$tmp_value .= "( vtb_project.id > '0' AND keiyaku_dt IS NULL AND estimate_status.estimate_count IS NULL AND cancel_dt IS NULL AND valid_dt IS NULL )";
                    if ($where_cnt > 0) {
                        $query = $query->orWhere(function($query) {
                            $query->where('tp.id', '>', 0)->whereNull('contract_date')->whereNull('tq.quotes_count')->whereNull('tp.failure_date')->whereNull('tp.cancel_date');
                        });

                    } else {
                        $query = $query->Where(function($query) {
                            $query->where('tp.id', '>', 0)->whereNull('contract_date')->whereNull('tq.quotes_count')->whereNull('tp.failure_date')->whereNull('tp.cancel_date');
                        });
                        $where_cnt++;
                    }
                }
                if (($check_value & bindec('10')) > 0) {       // 見積中
                    //$tmp_value .= "( keiyaku_dt IS NULL AND estimate_status.estimate_count IS NOT NULL AND cancel_dt IS NULL AND valid_dt IS NULL )";
                    if ($where_cnt > 0) {
                        $query = $query->orWhere(function($query) {
                            $query->whereNull('tp.contract_date')->whereNotNull('tq.quotes_count')->whereNull('tp.failure_date')->whereNull('tp.cancel_date');
                        });

                    } else {
                        $query = $query->Where(function($query) {
                            $query->whereNull('tp.contract_date')->whereNotNull('tq.quotes_count')->whereNull('tp.failure_date')->whereNull('tp.cancel_date');
                        });
                        $where_cnt++;
                    }
                }
                if (($check_value & bindec('100')) > 0) {      // 工事中
                    //$tmp_value .= "( keiyaku_dt IS NOT NULL AND kankou_dt IS NULL AND cancel_dt IS NULL AND valid_dt IS NULL )";
                    if ($where_cnt > 0) {
                        $query = $query->orWhere(function($query) {
                            $query->whereNotNull('tp.contract_date')->whereNull('tp.completion_date')->whereNull('tp.failure_date')->whereNull('tp.cancel_date');
                        });

                    } else {
                        $query = $query->Where(function($query) {
                            $query->whereNotNull('tp.contract_date')->whereNull('tp.completion_date')->whereNull('tp.failure_date')->whereNull('tp.cancel_date');
                        });
                        $where_cnt++;
                    }
                }
                if (($check_value & bindec('1000')) > 0) {     // 完工
                    //$tmp_value .= "( kankou_dt IS NOT NULL AND cancel_dt IS NULL AND valid_dt IS NULL )";
                    if ($where_cnt > 0) {
                        $query = $query->orWhere(function($query) {
                            $query->whereNotNull('tp.completion_date')->whereNull('tp.failure_date')->whereNull('tp.cancel_date');
                        });

                    } else {
                        $query = $query->Where(function($query) {
                            $query->whereNotNull('tp.completion_date')->whereNull('tp.failure_date')->whereNull('tp.cancel_date');
                        });
                        $where_cnt++;
                    }
                }
                //if (($check_value & bindec(10000)) > 0) {    // 未入金
                //    //$tmp_value .= "( seikyu_status.nyukin_flag ='0' AND cancel_dt IS NULL AND valid_dt IS NULL )";
                //}

                if (($check_value & bindec('100000')) > 0) {   // 完了
                    //$tmp_value .= "( end_dt IS NOT NULL AND cancel_dt IS NULL AND valid_dt IS NULL )";
                    if ($where_cnt > 0) {
                        $query = $query->orWhere(function($query) {
                            $query->whereNotNull('tp.complete_date')->whereNull('tp.failure_date')->whereNull('tp.cancel_date');
                        });

                    } else {
                        $query = $query->Where(function($query) {
                            $query->whereNotNull('tp.complete_date')->whereNull('tp.failure_date')->whereNull('tp.cancel_date');
                        });
                        $where_cnt++;
                    }
                }
                if (($check_value & bindec('1000000')) > 0) {  // 失注
                    //$tmp_value .= "( cancel_dt IS NOT NULL )";
                    if ($where_cnt > 0) {
                        $query = $query->orWhere(function($query) {
                            $query->whereNotNull('tp.failure_date');
                        });

                    } else {
                        $query = $query->Where(function($query) {
                            $query->whereNotNull('tp.failure_date');
                        });
                        $where_cnt++;
                    }
                }
                if (($check_value & bindec('10000000')) > 0) { // キャンセル
                    //$tmp_value .= "( valid_dt IS NOT NULL )";
                    if ($where_cnt > 0) {
                        $query = $query->orWhere(function($query) {
                            $query->whereNotNull('tp.cancel_date');
                        });

                    } else {
                        $query = $query->Where(function($query) {
                            $query->whereNotNull('tp.cancel_date');
                        });
                        $where_cnt++;
                    }
                }
            });
        }

        // 詳細検索
        // 現場住所
        if ($param->filled('field_place')) {
            $query = $query->where('tp.field_address', 'like', '%' . $param->input('field_place') . '%');
        }
        // 案件ランク
        if ($param->filled('project_rank')) {
            //$query = $query->where('tp.project_rank', $param->input('project_rank'));
            if ($param->filled('project_rank_filter')) {
                switch ($param->input('project_rank_filter')) {
                    case 1:
                        $query = $query->where('tp.project_rank', $param->input('project_rank'));
                        break;
                    case 2:
                        $query = $query->where('tp.project_rank', '>=', $param->input('project_rank'));
                        break;
                    case 3:
                        $query = $query->where('tp.project_rank', '<=', $param->input('project_rank'));
                        break;
                }
            } else {
                $query = $query->where('tp.project_rank', $param->input('project_rank'));
            }
        }

        // 工事部位
        if (CommonUtility::is_exist_variable_array($param->input('construction_parts'))
            && !is_null($param->input('construction_parts')[0])) {
            //$query = $query->where('tp.construction_parts', implode(' ', $param->input('construction_parts')));
            $construction_parts = $param->input('construction_parts');

            $query = $query->where(function($query) use($construction_parts) {
                $cnt = 0;
                foreach ($construction_parts as $value) {
                    $value = str_pad($value, 3, '0', STR_PAD_LEFT);
                    if ($cnt === 0) {
                        $query->where('tp.construction_parts', 'LIKE', '%'.$value.'%');
                    } else {
                        $query->orWhere('tp.construction_parts', 'LIKE', '%'.$value.'%');
                    }
                    $cnt++;
                }
            });
        }

        // 顧客詳細画面内タブでの絞込み用
        // 受注金額（契約金額）
        if ($param->filled('order_price')) {
            $query = $query->where('tp.order_price', $param->input('order_price'));
        }
        // 案件担当者
        if ($param->filled('project_representative_name')) {
            $query = $query->where('tp.project_representative', $param->input('project_representative_name'));
        }
        // 登録日
        if ($param->filled('ins_date')) {
            $query = $query->whereDate('tp.created_at', $param->input('ins_date'));
        }
        // 着工日
        if ($param->filled('construction_date')) {
            $query = $query->whereDate('tp.construction_date', $param->input('construction_date'));
        }
        // 完工日
        if ($param->filled('completion_date')) {
            $query = $query->whereDate('tp.completion_date', $param->input('completion_date'));
        }
        // 完了日
        if ($param->filled('complete_date')) {
            $query = $query->whereDate('tp.complete_date', $param->input('complete_date'));
        }
        // 契約日
        if ($param->filled('contract_date')) {
            $query = $query->whereDate('tp.contract_date', $param->input('contract_date'));
        }
        // 失注日
        if ($param->filled('failure_date')) {
            $query = $query->whereDate('tp.failure_date', $param->input('failure_date'));
        }
        // キャンセル日
        if ($param->filled('cancel_date')) {
            $query = $query->whereDate('tp.cancel_date', $param->input('cancel_date'));
        }
        // 備考
        if ($param->filled('remarks')) {
            $query = $query->where('tp.remarks', $param->input('remarks'));
        }
        // 発生源
        if ($param->filled('source')) {
            $query = $query->where('tp.source_id', $param->input('source'));
        }
        // 現場郵便番号
        if ($param->filled('field_post_no')) {
            $query = $query->where('tp.post_no', 'like', '%' . str_replace('-', '', $param->input('field_post_no')) . '%');
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
            $query = $query->where('customer_id', $param->input('customer_id'));
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
            $query = $query->where('tp.customer_id', $param->input('customer_id'));
        }

        // 顧客名
        if ($param->filled('customer_name')) {
            $query = $query->where('tc.name', 'like', '%' . $param->input('customer_name') . '%');
        }

        // 詳細検索
        // 顧客都道府県
        if ($param->filled('customer_prefecture')) {
            $query = $query->where('tc.prefecture', ModelBase::PREFECTURE[$param->input('customer_prefecture')]);
        }

        //// 顧客詳細画面内タブでの絞込み用
        //// 発生源
        //if ($param->filled('source')) {
        //    $query = $query->where('tc.source_id', $param->input('source'));
        //}

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
                // 未指定の場合、案件IDの昇順
                $query->orderBy(self::SORT_BY_COLUMN[2], ModelBase::SORT_KIND[0]);
            } else {
                // 未指定の場合、案件IDの昇順
                $query->orderBy(self::SORT_BY_DETAIL_COLUMN[12], ModelBase::SORT_KIND[0]);
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

            $koji_flag = 2; // 工事中
            // 工事フラグ取得
            if (is_null($arr['contract_date'])) {
                $koji_flag = 1; // 未契約
            } else if (!is_null($arr['completion_date'])) {
                $koji_flag = 3; // 完工
            }

            $data = [
                'customer_id' => $arr['customer_id'], // 顧客ID
                'id' => $arr['id'], // 案件ID
                'customer_name' => $arr['tc_name'], // 顧客名
                'furigana' => $arr['tc_furigana'], // 顧客名フリガナ
                'name' => $arr['name'], // 案件名
                'field_post_no' => $arr['post_no'], // 現場郵便番号
                'field_prefecture' => array_search($arr['prefecture'], ModelBase::PREFECTURE), // 現場都道府県
                'field_prefecture_name' => $arr['prefecture'], // 現場都道府県名称
                'field_city' => $arr['city'], // 現場市区町村
                'field_address' => $arr['address'], // 現場地名、番地
                'field_building_name' => $arr['building_name'], // 現場建物名等
                'post_no' => $arr['tc_post_no'], // 顧客_郵便番号
                'prefecture' => array_search($arr['tc_prefecture'], ModelBase::PREFECTURE), // 顧客_都道府県
                'prefecture_name' => $arr['tc_prefecture'], // 顧客_都道府県名称
                'city' => $arr['tc_city'], // 顧客_市区町村
                'address' => $arr['tc_address'], // 顧客_地名、番地
                'building_name' => $arr['tc_building_name'], // 顧客_建物名等
                'project_rank_name' => CommonUtility::is_exist_variable_array($arr['rank_project']) ? $arr['rank_project']['name'] : '', // 案件ランク（見込みランク）名称
                'project_rank' => $arr['project_rank'], // 案件ランク（見込みランク）
                'field_tel_no' => $arr['field_tel_no'], // 現場電話番号
                'field_fax_no' => $arr['field_fax_no'], // 現場FAX
                'project_store' => CommonUtility::is_exist_variable_array($arr['store']) ? $arr['store']['id'] : '', // 案件担当店舗
                'project_store_name' => CommonUtility::is_exist_variable_array($arr['store']) ? $arr['store']['name'] : '', // 案件担当店舗名称
                'project_representative' => CommonUtility::is_exist_variable_array($arr['employee']) ? $arr['employee']['id'] : '', // 案件担当者
                'project_representative_name' => CommonUtility::is_exist_variable_array($arr['employee']) ? $arr['employee']['name'] : '', // 案件担当者名称
                'image' => null, // 案件に紐づいている画像 // TODO GoogleMapAPIで取得した画像を保存し、それを取得する処理を追加予定
                'complete_flag' => $arr['complete_flag'], // 対応完了フラグ
                'alert_flag' => $arr['alert_flag'], // アラートフラグ
                'field_name' => $arr['field_name'], // 現場名称
                'customer_rank' => $rank, // 顧客ランク
                'customer_rank_name' => $rank_name, // 顧客ランク名称
                'customer_place' => '', // 顧客住所 別途結合してマージする
                'field_place' => '', // 現場住所 別途結合してマージする
                'construction_start_date' => $arr['construction_start_date'], // 着工予定日
                'completion_end_date' => $arr['completion_end_date'], // 完工予定日
                'construction_date' => $arr['construction_date'], // 着工日
                'completion_date' => $arr['completion_date'], // 完工日
                'contract_no' => $arr['contract_no'], // 契約番号
                'source' => $arr['ms_id'], // 発生源
                'source_name' => $arr['ms_name'], // 発生源名称
                'remarks' => $arr['remarks'], // 備考
                'contract_date' => $arr['contract_date'], // 契約日
                'lat' => $arr['lat'], // 緯度
                'lng' => $arr['lng'], // 経度
                'order_price' => $arr['order_price'], // 受注金額（契約金額）
                'ins_date' => '', // 登録日 別途結合してマージする
                'complete_date' => $arr['complete_date'], // 完了日
                'failure_date' => $arr['failure_date'], // 失注日
                'cancel_date' => $arr['cancel_date'], // キャンセル日
                'koji_flag' => $koji_flag, // 工事フラグ
                'order_flag' => ModelBase::is_order($arr['contract_date'], $arr['cancel_date']), // 受注フラグ
            ];
            // 別途変換要の項目の処理
            $data2 = [
                // 文字列結合演算子（.） null値は常に空文字として結合される
                'customer_place' => $data['prefecture_name'] . $data['city'] . $data['address'] . $data['building_name'], // 顧客住所
                'field_place' => $data['field_prefecture_name'] . $data['field_city'] . $data['field_address'] . $data['field_building_name'], // 現場住所
                'ins_date' => CommonUtility::convert_timestamp($arr['created_at'], 'Y-m-d'), // 登録日
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
        // 工事部位リスト取得
        //$construction_parts = array_map('intval', explode(' ', $obj->construction_parts));
        $construction_parts      = null;
        $construction_part_names = null;
        if (CommonUtility::is_exist_variable($obj->construction_parts)) {
            $construction_parts = array_map('intval', explode(' ', $obj->construction_parts));

            foreach ($construction_parts as $item) {
                if (MPart::find($item)) {
                    $construction_part_names[] = MPart::find($item)->name;
                }
            }
        }

        $data = [
            'customer_id'                 => $obj->customer_id, // 顧客ID
            'id'                          => $obj->id, // 案件ID
            // 顧客データはbelongsToなので、取得した値はオブジェクトとしてチェック
            'customer_name'               => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['name'] : '', // 顧客名
            'name'                        => $obj->name, // 案件名
            'source'                      => $obj->source_id, // 発生源
            'source_name'                 => CommonUtility::is_exist_variable($obj->source) ? $obj->source['name'] : '', // 発生源名称
            'project_rank'                => $obj->project_rank, // 案件ランク（見込みランク）
            'project_rank_name'           => CommonUtility::is_exist_variable($obj->rank_project) ? $obj->rank_project['name'] : '', // 案件ランク（見込みランク）名称
            'project_store'               => $obj->project_store, // 案件担当店舗
            'project_store_name'          => CommonUtility::is_exist_variable($obj->store) ? $obj->store['name'] : '', // 案件担当店舗名称
            'project_representative'      => $obj->project_representative, // 案件担当者
            'project_representative_name' => CommonUtility::is_exist_variable($obj->employee) ? $obj->employee['name'] : '', // 案件担当者名称
            'field_name'                  => $obj->field_name, // 現場名称
            'field_post_no'               => $obj->post_no, // 現場郵便番号
            'field_prefecture'            => array_search($obj->prefecture, ModelBase::PREFECTURE), // 現場都道府県
            'field_prefecture_name'       => $obj->prefecture, // 現場都道府県名称
            'field_city'                  => $obj->city, // 現場市区町村
            'field_address'               => $obj->address, // 現場地名、番地
            'field_building_name'         => $obj->building_name, // 現場建物名等
            'post_no'                     => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['post_no'] : '', // 顧客_郵便番号
            'prefecture'                  => CommonUtility::is_exist_variable($obj->customer) ? array_search($obj->customer['prefecture'], ModelBase::PREFECTURE) : '', // 顧客_都道府県
            'prefecture_name'             => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['prefecture'] : '', // 顧客_都道府県名称
            'city'                        => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['city'] : '', // 顧客_市区町村
            'address'                     => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['address'] : '', // 顧客_地名、番地
            'building_name'               => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['building_name'] : '', // 顧客_建物名等
            'customer_place'              => '', // 顧客住所 別途結合してマージする
            'field_place'                 => '', // 現場住所 別途結合してマージする
            'field_tel_no'                => $obj->field_tel_no, // 現場電話番号
            'field_fax_no'                => $obj->field_fax_no, // 現場FAX
            //'construction_parts'          => array_map('intval', explode(' ', $obj->construction_parts)), // 工事部位
            'construction_parts'          => $construction_parts, // 工事部位
            'construction_part_names'     => $construction_part_names, // 工事部位名称
            'expected_amount'             => $obj->expected_amount, // 見込み金額
            'contract_no'                 => $obj->contract_no, // 契約番号
            'contract_date'               => $obj->contract_date, // 契約日
            'construction_period_start'   => $obj->construction_period_start, // 受注工期（開始）
            'construction_period_end'     => $obj->construction_period_end, // 受注工期（終了）
            'construction_start_date'     => $obj->construction_start_date, // 着工予定日
            'completion_end_date'         => $obj->completion_end_date, // 完工予定日
            'construction_date'           => $obj->construction_date, // 着工日
            'completion_date'             => $obj->completion_date, // 完工日
            'complete_date'               => $obj->complete_date, // 完了日
            'failure_date'                => $obj->failure_date, // 失注日
            'failure_cause'               => $obj->failure_cause, // 失注理由
            'failure_cause_name'          => CommonUtility::is_exist_variable($obj->lost_order) ? $obj->lost_order['lost_reason'] : '', // 失注理由名称
            'failure_remarks'             => $obj->failure_remarks, // 失注備考
            'cancel_date'                 => $obj->cancel_date, // キャンセル日
            'cancel_reason'               => $obj->cancel_reason, // キャンセル理由
            'execution_end'               => $obj->execution_end, // 実行終了
            'order_detail1'               => $obj->order_detail1, // 受注詳細（追加1 – 最終原価）
            'order_detail2'               => $obj->order_detail2, // 受注詳細（追加2 – 最終原価）
            'order_flag'                  => ModelBase::is_order($obj->contract_date, $obj->cancel_date), // 受注フラグ
            'lat'                         => $obj->lat, // 緯度
            'lng'                         => $obj->lng, // 経度
        ];
        // 別途変換要の項目の処理
        $data2 = [
            // 文字列結合演算子（.） null値は常に空文字として結合される
            'customer_place' => $data['prefecture_name'] . $data['city'] . $data['address'] . $data['building_name'], // 顧客住所
            'field_place' => $data['field_prefecture_name'] . $data['field_city'] . $data['field_address'] . $data['field_building_name'], // 現場住所
        ];
        $result[] = array_merge($data, $data2);

        return $result;
    }

    /**
     * 案件情報フリーワード検索
     *
     * @param Request $param 検索パラメータ
     * @return Collection 取得データ
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
            self::_set_order_by($query, $param->input('sort_by', 2), $param->input('highlow', 0), 1);
        } else {
            self::_set_order_by($query, $param->input('filter_by', 12), $param->input('highlow', 0), 2);
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
            // 案件名
            $_query->orWhere('tp.name', 'like', '%' . $keyword . '%');
            // 現場電話番号
            $_query->orWhere('tp.field_tel_no', 'like', '%' . str_replace('-', '', $keyword) . '%');
            // 現場住所
            $_query->orWhere('tp.field_address', 'like', '%' . $keyword . '%');
            // 顧客名
            $_query->orWhere('tc.name', 'like', '%' . $keyword . '%');
        });
        return;
    }
}
