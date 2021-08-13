<?php

namespace App\Models;

use App\Libraries\CommonUtility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class TQuote<br>
 * 見積データ
 *
 * @package App\Models
 */
class TQuote extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（t_quotes）
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
        'project_id',
//        'company_id', // TODO 後で追加
        'order_flag',
        'quote_no',
        'quote_date',
        'quote_creator',
        'quote_price',
        'tax_amount_quote',
        'including_tax_total_quote',
        'cost_sum',
        'tax_amount_cost',
        'including_tax_total_cost',
        'adjustment_amount',
        'quote_expiration_date',
        'order_expected_date',
        'remarks',
        'field_cooperating_cost_estimate',
        'field_cooperating_cost',
        'call_cost_estimate',
        'call_cost',
        'is_editing',
        'last_updated_by',
    ];

    /**
     * 見積と紐づく案件データ取得（1対多（逆））
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TProject::class);
    }

    /**
     * 見積に紐づく社員マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function employee(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MEmployee::class, 'id', 'quote_creator');
    }

    /**
     * ソート用カラム定義（リスト表示時用）
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'tq.order_flag', // 受注フラグ
        1 => 'tq.quote_no', // 見積番号
        2 => 'tq.quote_date', // 見積日
        3 => 'tp.field_name', // 現場名称
        4 => 'tp.name', // 案件名
        5 => 'tp.project_representative', // 案件担当者
        6 => 'tq.quote_creator', // 見積作成者
        7 => 'tq.quote_price', // 見積金額
        8 => 'tq.tax_amount_quote', // 消費税額（見積）
        9 => 'tq.including_tax_total_quote', // 税込合計見積
        10 => 'tq.cost_sum', // 原価合計
        11 => 'tq.tax_amount_cost', // 消費税額（原価）
        12 => 'tq.including_tax_total_cost', // 税込合計原価
        13 => 'tq.adjustment_amount', // 調整額
        14 => 'tp.construction_period_start', // 受注工期_開始
        15 => 'tp.construction_period_end', // 受注工期_終了
    ];

    /**
     * ソート用カラム定義（案件詳細画面タブ内の見積情報絞り込み検索時用）
     *
     * @var string[]
     */
    protected const SORT_BY_DETAIL_COLUMN = [
        0 => 'tq.order_flag', // 受注フラグ
        1 => 'tq.quote_no', // 見積番号
        2 => 'tq.quote_date', // 作成日（見積日）
        3 => 'tq.quote_creator', // 見積作成者
        4 => 'tq.quote_price', // 見積金額
        5 => 'tq.tax_amount_quote', // 消費税額（見積）
        6 => 'tq.including_tax_total_quote', // 税込合計見積
        7 => 'tq.cost_sum', // 原価合計
        8 => 'tq.tax_amount_cost', // 消費税額（原価）
        9 => 'tq.including_tax_total_cost', // 税込合計原価
        10 => 'tq.adjustment_amount', // 調整額
        11 => 'tp.construction_period_start', // 受注工期_開始
        12 => 'tp.construction_period_end', // 受注工期_終了
        13 => 'tp.id', // 案件ID
    ];

    /**
     * 見積情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_list(Request $param)
    {
        // 取得項目
        $query = TQuote::select(
            // 見積データ
            'tq.project_id as tq_project_id',
            'tq.id as tq_id',
            'tq.order_flag as tq_order_flag',
            'tq.quote_no as tq_quote_no',
            'tq.quote_date as tq_quote_date',
            'tq.quote_creator as tq_quote_creator',
            DB::raw('sum(tq.quote_price) as tq_quote_price'),
            DB::raw('sum(tq.tax_amount_quote) as tq_tax_amount_quote'),
            DB::raw('sum(tq.including_tax_total_quote) as tq_including_tax_total_quote'),
            DB::raw('sum(tq.cost_sum) as tq_cost_sum'),
            DB::raw('sum(tq.tax_amount_cost) as tq_tax_amount_cost'),
            DB::raw('sum(tq.including_tax_total_cost) as tq_including_tax_total_cost'),
            DB::raw('sum(tq.adjustment_amount) as tq_adjustment_amount'),
            'tq.field_cooperating_cost as tq_field_cooperating_cost',
            'tq.call_cost as tq_call_cost',
            // 案件データ
            'tp.field_name as tp_field_name',
            'tp.name as tp_name as tp_name',
            'tp.construction_period_start as tp_construction_period_start',
            'tp.construction_period_end as tp_construction_period_end',
            DB::raw('sum(tp.order_price) as tp_order_price'),
            // 顧客データ
            'tc.name as tc_name',
            'tc.furigana as tc_furigana',
            // 受注データ
            'to.construction_start_date as to_construction_start_date',
            'to.completion_end_date as to_completion_end_date',
            'to.groundbreaking_ceremony as to_groundbreaking_ceremony',
            'to.completion_based as to_completion_based',
            DB::raw('sum(to.contract_money) as to_contract_money'),
            DB::raw('sum(to.start_construction_money) as to_start_construction_money'),
            DB::raw('sum(to.intermediate_gold1) as to_intermediate_gold1'),
            DB::raw('sum(to.intermediate_gold2) as to_intermediate_gold2'),
            DB::raw('sum(to.completion_money) as to_completion_money'),
            // 見積明細データ
            DB::raw('sum(tqd.prime_cost) as tqd_prime_cost'),
            DB::raw('sum(tqd.quantity) as tqd_quantity'),
            DB::raw('sum(tqd.quote_unit_price) as tqd_quote_unit_price'),
            // 社員マスタ（案件担当者取得用）
            'me1.name as me1_name',
            // 社員マスタ（見積作成者取得用）
            'me2.name as me2_name',
        )->distinct()->from('t_quotes as tq')->where('tq.is_editing', 0)
            ->leftjoin('t_quote_details as tqd', 'tq.id', '=', 'tqd.quote_id') // 見積明細データ
            ->join('t_projects as tp', 'tq.project_id', '=', 'tp.id') // 案件データ
            ->leftjoin('t_customers as tc', 'tp.customer_id', '=', 'tc.id') // 顧客データ
            ->leftjoin('t_orders as to', function ($join) {
                $join->on('tp.id', '=', 'to.project_id')
                    ->on('tq.id', '=', 'to.quote_id');
            }) // 受注データ
            ->leftjoin('m_employees as me1', function ($join) {
                $join->on('tp.project_representative', '=', 'me1.id');
            }) // 社員マスタ（案件担当者取得用）
            ->leftjoin('m_employees as me2', function ($join) {
                $join->on('tq.quote_creator', '=', 'me2.id');
            }) // 社員マスタ（見積作成者取得用）
            ->where('tc.is_editing', 0)
            ->where('tp.is_editing', 0)
            ->groupby('tq.id', 'tq.quote_no', 'to.construction_start_date'
                ,'to.completion_end_date', 'to.groundbreaking_ceremony'
                ,'to.completion_based');

        // 検索条件（where）
        self::set_where($query, $param); // 見積
        self::set_where_quote_detail($query, $param); // 見積明細
        self::set_where_customer($query, $param); // 顧客
        self::set_where_project($query, $param); // 案件
        // ソート条件（order by）
        if ($param->filled('sort_by')) {
            self::_set_order_by($query, $param->input('sort_by', 1), $param->input('highlow', 0), 1);
        } else {
            self::_set_order_by($query, $param->input('filter_by', 1), $param->input('highlow', 0), 2);
        }
        if ($param->filled('limit')) {
            // オフセット条件（offset）
            $query->skip($param->input('offset', 0));
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
     * 見積情報1件検索
     *
     * @param Request $param 検索パラメータ
     * @param int $id 見積ID
     * @return array|null 取得データ
     */
    public static function search_one(Request $param, int $id): ?array
    {
        // 取得項目
        $query = TQuote::select(
            'project_id',
            'id',
            'quote_creator',
            'quote_date',
            'quote_no',
            'quote_expiration_date',
            'order_expected_date',
            'remarks',
            'adjustment_amount',
            'field_cooperating_cost_estimate',
            'field_cooperating_cost',
            'call_cost_estimate',
            'call_cost',
        )->where('is_editing', 0)->with('project', function($q) {
            // With()で取得時、リレーション先のID項目の取得が必須
            $q->select('id', 'field_name', 'name', 'construction_period_start', 'construction_period_end');
        }) // 案件データ
        ->with(['employee' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]); // 社員マスタ

        // 検索条件（where）
        self::set_where($query, $param); // 見積

        $result = $query->find($id);
        if (is_null($result)) {
            return null;
        }

        // 取得結果整形
        return self::get_format_column_one($result);
    }

    /**
     * 見積情報保存（登録・更新）
     *
     * @param Request $param
     * @param int|null $id
     * @return string
     */
    public static function upsert(Request $param, int $id = null): string
    {
        $arr = $param->all();
        // DB登録・更新用にパラメータ変換（見積）
        // 現場協力費（見積）%
        if ($param->filled('field_cost_quote')) {
            $arr['field_cooperating_cost_estimate'] = $param->input('field_cost_quote');
        }
        // 現場協力費（原価）%
        if ($param->filled('field_cost')) {
            $arr['field_cooperating_cost'] = $param->input('field_cost');
        }
        // 呼び原価（見積）%
        if ($param->filled('call_cost_quote')) {
            $arr['call_cost_estimate'] = $param->input('call_cost_quote');
        }
        // 最終更新者
        // TODO ログインユーザー名を登録
        $arr['last_updated_by'] = '管理者';
        // 編集中フラグ 登録済みにする
        $arr['is_editing'] = 0;

        if ($id && $param->filled('project_id')) {
            // 更新
            $quote = TQuote::find($id); // 見積データ
            $project = TProject::find($param->input('project_id')); // 案件データ
            if (is_null($quote) || is_null($project)) {
                return '404';
            }

            // DB登録・更新用にパラメータ変換（案件）
            // 工期_開始
            if ($param->filled('order_construction_start')) {
                $arr['construction_period_start'] = $param->input('order_construction_start');
            }
            // 工期_終了
            if ($param->filled('order_construction_end')) {
                $arr['construction_period_end'] = $param->input('order_construction_end');
            }

            // 更新処理
            $quote->fill($arr)->save();

            // 備考
            if (isset($arr['remarks'])) {
                // キー名が同じため、案件情報更新前に削除しておく
                unset($arr['remarks']);
            }
            $project->fill($arr)->save();
        } else {
            // 見積データの見積番号最大値取得
            $max_quote_no = TQuote::get()->max('quote_no');
            // 見積番号
            if (is_null($max_quote_no)) {
                $arr['quote_no'] = 'E000000001';
            } else {
                $quote_no = substr($max_quote_no, 1,  strlen($max_quote_no));
                $arr['quote_no'] = 'E' . sprintf('%09d', ($quote_no + 1));
            }
            // 登録処理（見積）
            $quote = new TQuote();
            $quote->fill($arr)->save();

            $project = TProject::find($param->input('project_id')); // 案件データ
            if (is_null($project)) {
                return 'ok';
            }

            // DB登録・更新用にパラメータ変換（案件）
            // 工期_開始
            if ($param->filled('order_construction_start')) {
                $arr['construction_period_start'] = $param->input('order_construction_start');
            }
            // 工期_終了
            if ($param->filled('order_construction_end')) {
                $arr['construction_period_end'] = $param->input('order_construction_end');
            }

            // 備考
            if (isset($arr['remarks'])) {
                // キー名が同じため、案件情報更新前に削除しておく
                unset($arr['remarks']);
            }
            // 更新処理（案件）
            $project->fill($arr)->save();
        }

        return 'ok';
    }

    /**
     * 見積ID発行
     *
     * @param Request $param
     * @param int $project_id 案件ID
     * @return int
     */
    public static function get_id(Request $param, int $project_id): int
    {
        $arr = $param->all();
        // 必須項目をダミーで登録
        $arr['project_id'] = $project_id; // 案件ID
        $arr['last_updated_by'] = 'ダミー'; // 最終更新者
        $arr['is_editing'] = 1; // 編集中フラグ 編集中にする
        // 登録処理
        $customer = new TQuote();
        $customer->fill($arr)->save();

        return TQuote::get()->max('id');
    }

    /**
     * 編集中見積情報削除
     *
     * @param int $id 見積ID
     */
    public static function remove_edit_data(int $id)
    {
        // 見積明細情報削除処理
        TQuoteDetail::where('quote_id', $id)->delete();
        // 見積情報削除処理
        TQuote::destroy($id);

        return;
    }

    /**
     * 検索条件設定
     *
     * @param $query
     * @param Request $param 検索パラメータ
     */
    public static function set_where(&$query, Request $param)
    {
        // 共通項目
        // 案件ID
        if ($param->filled('project_id')) {
            $query = $query->where('tq.project_id', $param->input('project_id'));
        }
        // 見積作成者
        if ($param->filled('quote_creator')) {
            $query = $query->where('tq.quote_creator', $param->input('quote_creator'));
        }

        // 一覧画面の検索用項目
        // 発注案件も含む
        if ($param->filled('is_order_project')) {
            if ($param->input('is_order_project') == 0) {
                $query = $query->where(function($q) use ($param) {
                    $q->orwhereNull('tq.order_flag')
                        ->orwhere('tq.order_flag', '!=', 1); // 発注案件以外を取得
                });
            }
        }

        // 見積作成者（キーワード検索用）
        if ($param->filled('quote_creator_word')) {
            $query = $query->where(function($q) use ($param) {
                $q->orWhere('tq.quote_creator', $param->input('quote_creator')); // 見積作成者
            });
        }

        // 案件詳細画面内タブでの絞込み用
        // 受注フラグ
        if ($param->filled('is_order')) {
            if ($param->input('is_order') == 0) {
                $query = $query->where(function($q) use ($param) {
                    $q->orwhereNull('tq.order_flag')
                        ->orwhere('tq.order_flag', '!=', 1); // 発注案件以外を取得
                });
            }
        }
        // 見積番号
        if ($param->filled('quote_no')) {
            $query = $query->where('tq.quote_no', 'like', '%' . $param->input('quote_no') . '%');
        }
        // 作成日（見積作成日）
        if ($param->filled('quote_date')) {
            $query = $query->whereDate('tq.quote_date', $param->input('quote_date'));
        }
        // 見積金額
        if ($param->filled('quote_price')) {
            $query = $query->where('tq.quote_price', $param->input('quote_price'));
        }
        // 消費税額（見積）
        if ($param->filled('tax_amount_quote')) {
            $query = $query->where('tq.tax_amount_quote', $param->input('tax_amount_quote'));
        }
        // 税込合計見積
        if ($param->filled('including_tax_total_quote')) {
            $query = $query->where('tq.including_tax_total_quote', $param->input('including_tax_total_quote'));
        }
        // 原価合計
        if ($param->filled('cost_sum')) {
            $query = $query->where('tq.cost_sum', $param->input('cost_sum'));
        }
        // 消費税額（原価）
        if ($param->filled('tax_amount_cost')) {
            $query = $query->where('tq.tax_amount_cost', $param->input('tax_amount_cost'));
        }
        // 税込合計原価
        if ($param->filled('including_tax_total_cost')) {
            $query = $query->where('tq.including_tax_total_cost', $param->input('including_tax_total_cost'));
        }
        // 調整額
        if ($param->filled('adjustment_amount')) {
            $query = $query->where('tq.adjustment_amount', $param->input('adjustment_amount'));
        }

        return;
    }

    /**
     * 検索条件設定（見積明細情報）
     *
     * @param $query
     * @param Request $param 検索パラメータ
     */
    public static function set_where_quote_detail(&$query, Request $param)
    {
        // 過去見積から明細コピー時の検索用項目
        // 明細
        if ($param->filled('detail')) {
            $query = $query->where('tqd.koji_component_name', 'like', '%' . $param->input('detail') . '%');
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
        // 過去見積から明細コピー時の検索用項目
        // 顧客名
        if ($param->filled('customer_name')) {
            $query = $query->where('tc.name', 'like', '%' . $param->input('customer_name') . '%');
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
        // 共通項目
        // 案件名
        if ($param->filled('project_name')) {
            $query = $query->where('tp.name', 'like', '%' . $param->input('project_name') . '%');
        }

        // 一覧画面の検索用項目
        // 現場名称
        if ($param->filled('field_name')) {
            $query = $query->where('tp.field_name',  'like', '%' . $param->input('field_name') . '%');
        }
        // 案件営業担当店舗
        if ($param->filled('sales_shop')) {
            $query = $query->where('tp.project_store', $param->input('sales_shop'));
        }
        // 案件営業担当担当者
        if ($param->filled('sales_contact')) {
            $query = $query->where('tp.project_representative', $param->input('sales_contact'));
        }
        // 見積作成者（キーワード検索用）
        if ($param->filled('quote_creator_word')) {
            $query = $query->where(function($q) use ($param) {
                $q->orWhere('tp.field_name', 'like', '%' . $param->input('field_name') . '%') // 現場名称
                ->orWhere('tp.name', 'like', '%' . $param->input('project_name') . '%'); // 案件名
            });
        }

        // 過去見積から明細コピー時の検索用項目
        // 工事部位
        if (CommonUtility::is_exist_variable_array($param->input('construction_parts'))
            && !is_null($param->input('construction_parts')[0])) {
            $query = $query->where('tp.construction_parts', implode(' ', $param->input('construction_parts')));
        }

        // 案件詳細画面内タブでの絞込み用
        // 受注工期_開始
        if ($param->filled('order_construction_start')) {
            $query = $query->whereDate('tp.construction_period_start', $param->input('order_construction_start'));
        }
        // 受注工期_終了
        if ($param->filled('order_construction_end')) {
            $query = $query->whereDate('tp.construction_period_end', $param->input('order_construction_end'));
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
     * @param int $filter 対象の並替基準列一覧の選定
     */
    private static function _set_order_by(&$query, int $order_column_id = null, int $sort_id = null, int $filter = 1)
    {
        if (is_null($order_column_id) || is_null($sort_id)) {
            if ($filter == 1) {
                // 未指定の場合、見積番号の昇順
                $query->orderBy(self::SORT_BY_COLUMN[1], ModelBase::SORT_KIND[0]);
            } else {
                // 未指定の場合、見積番号の昇順
                $query->orderBy(self::SORT_BY_DETAIL_COLUMN[1], ModelBase::SORT_KIND[0]);
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
     * @return Collection
     */
    private static function get_format_column($collection): Collection
    {
        $results = new Collection();
        foreach ($collection as $item) {

            $arr = $item->toArray();

            // 計算処理
            // 見積金額
            // （見積明細データ．数量） × （見積明細データ．見積単価）
            $price = floatval($arr['tqd_quantity']) * floatval($arr['tqd_quote_unit_price']);
            // 原価金額
            // （見積明細データ．数量） × （見積明細データ．原価）
            $cost_amount = floatval($arr['tqd_quantity']) * floatval($arr['tqd_prime_cost']);
            // 原価合計
            // （見積明細データ．原価金額） + （見積明細データ．現場協力費（原価）） + （見積明細データ．予備原価（原価））
            $cost_total_amount = floatval($cost_amount) + floatval($arr['tq_field_cooperating_cost']) + floatval($arr['tq_call_cost']);
            // 未割当金
            // （案件データ．受注金額）－（受注データ．契約金）－（受注データ．着工金）－（受注データ．中間金1）－（受注データ．中間金2）－（受注データ．完工金）
            $unallocated_money =
                floatval($arr['tp_order_price'] - $arr['to_contract_money'] - $arr['to_start_construction_money'] - $arr['to_intermediate_gold1'] - $arr['to_intermediate_gold2'] - $arr['to_completion_money']);

            $data = [
                'project_id' => $arr['tq_project_id'], // 案件ID
                'id' => $arr['tq_id'], // 見積ID
                'order_flag' => $arr['tq_order_flag'], // 受注フラグ
                'quote_no' => $arr['tq_quote_no'], // 見積番号
                'quote_date' => $arr['tq_quote_date'], // 見積日
                'field_name' => $arr['tp_field_name'], // 現場名称
                'project_name' => $arr['tp_name'], // 案件名
                'project_representative_name' => $arr['me1_name'], // 案件担当者
                'quote_creator' => $arr['me2_name'], // 見積作成者
                'quote_price' => $price, // 見積金額
                'tax_amount_quote' => floatval($arr['tq_tax_amount_quote']), // 消費税額（見積）
                'including_tax_total_quote' => floatval($arr['tq_including_tax_total_quote']), // 税込合計見積
                'cost_sum' => $cost_total_amount, // 原価合計
                'tax_amount_cost' => floatval($arr['tq_tax_amount_cost']), // 消費税額（原価）
                'including_tax_total_cost' => floatval($arr['tq_including_tax_total_cost']), // 税込合計原価
                'adjustment_amount' => floatval($arr['tq_adjustment_amount']), // 調整額
                'order_construction_start' => $arr['tp_construction_period_start'], // 受注工期_開始
                'order_construction_end' => $arr['tp_construction_period_end'], // 受注工期_終了
                'customer_name' => $arr['tc_name'], // 顧客名
                'furigana' => $arr['tc_furigana'], // フリガナ
                'order_price' => floatval($arr['tp_order_price']), // 受注金額
                'order_cost' => floatval($arr['tqd_prime_cost']), // 受注原価
                'construction_start_date' => $arr['to_construction_start_date'], // 着工予定日
                'completion_end_date' => $arr['to_completion_end_date'], // 完工予定日
                'groundbreaking_ceremony' => $arr['to_groundbreaking_ceremony'], // 着工式
                'completion_based' => $arr['to_completion_based'], // 完工式
                'unallocated_money' => $unallocated_money, // 未割当金
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
        $data[] = [
            'project_id' => $obj->project_id, // 案件ID
            'id' => $obj->id, // 見積ID
            'field_name' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['field_name'] : '', // 現場名称
            'project_name' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['name'] : '', // 案件名
            'quote_creator' => $obj->quote_creator, // 見積作成者
            'quote_creator_name' => CommonUtility::is_exist_variable($obj->employee) ? $obj->employee['name'] : '', // 見積作成者名称
            'quote_date' => $obj->quote_date, // 見積日付
            'quote_no' => $obj->quote_no, // 見積番号
            'order_construction_start' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['construction_period_start'] : '', // 工期_開始
            'order_construction_end' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['construction_period_end'] : '', // 工期_終了
            'quote_expiration_date' => $obj->quote_expiration_date, // 見積有効期限
            'order_expected_date' => $obj->order_expected_date, // 発注予定日
            'remarks' => $obj->remarks, // 備考
            'adjustment_amount' => floatval($obj->adjustment_amount), // 調整額
            'field_cost_quote' => floatval($obj->field_cooperating_cost_estimate), // 現場協力費（見積）%
            'field_cost' => floatval($obj->field_cooperating_cost), // 現場協力費（原価）%
            'call_cost_quote' => floatval($obj->call_cost_estimate), // 呼び原価（見積）%
            'call_cost' => floatval($obj->call_cost), // 呼び原価（原価）%
        ];

        return $data;
    }
}
