<?php

namespace App\Models;

use App\Http\Controllers\Api\QuoteDetailsController;
use App\Libraries\CommonUtility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class TQuoteDetail<br>
 * 見積明細データ
 *
 * @package App\Models
 */
class TQuoteDetail extends ModelBase
{
    use HasFactory;


    // テーブル名はクラスの複数形のスネークケース（t_quote_details）
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
        'quote_id',
        'category_id',
        'sub_category_id',
        'item_kubun',
        'category_percent',
        'sub_category_percent',
        'koji_component_name',
        'print_name',
        'category_print_name',
        'sub_category_print_name',
        'standard',
        'quantity',
        'unit',
        'quote_unit_price',
        'price',
        'prime_cost',
        'cost_amount',
        'gross_profit_amount',
        'gross_profit_rate',
        'remarks',
        'category_index',
        'sub_category_index',
        'created_at',
        'updated_at',
        'last_updated_by',
    ];

    /**
     * 見積明細に紐づく明細マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detail(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MDetail::class, 'id', 'item_kubun');
    }

    /**
     * 見積明細に紐づく大分類マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MCategory::class, 'id', 'category_id');
    }

    /**
     * 見積明細に紐づく中分類マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sub_category(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MSubCategory::class, 'id', 'sub_category_id');
    }

    /**
     * 見積明細に紐づく単位マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function credit(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MCredit::class, 'id', 'unit');
    }

    /**
     * 見積明細情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @param int $id 見積ID
     * @return mixed 取得データ
     */
    public static function search_list(Request $param, int $id)
    {
        // 取得項目
        $query = TQuoteDetail::select(
            // 見積明細データ
            'tqd.quote_id as tqd_quote_id',
            'tqd.id as tqd_id',
            'tqd.koji_component_name as tqd_koji_component_name',
            'tqd.print_name as tqd_print_name',
            'tqd.standard as tqd_standard',
            DB::raw('sum(tqd.quantity) as tqd_quantity'),
            DB::raw('sum(tqd.quote_unit_price) as tqd_quote_unit_price'),
            DB::raw('sum(tqd.price) as price'),
            DB::raw('sum(tqd.prime_cost) as tqd_prime_cost'),
            DB::raw('sum(tqd.cost_amount) as tqd_cost_amount'),
            DB::raw('sum(tqd.gross_profit_amount) as tqd_gross_profit_amount'),
            'tqd.gross_profit_rate as tqd_gross_profit_rate',
            'tqd.remarks as tqd_remarks',
            'tqd.category_index as tqd_category_index',
            'tqd.sub_category_index as tqd_sub_category_index',
            // 見積データ
            'tq.field_cooperating_cost_estimate as tq_field_cooperating_cost_estimate',
            'tq.field_cooperating_cost as tq_field_cooperating_cost',
            'tq.call_cost_estimate as tq_call_cost_estimate',
            'tq.call_cost as tq_call_cost',
            // 案件データ
            'tp.contract_date as tp_contract_date',
            'tp.cancel_date as tp_cancel_date',
            // 大分類マスタ
            'mc.id as mc_id',
            'mc.name as mc_name',
            // 中分類マスタ
            'msc.id as msc_id',
            'msc.name as msc_name',
            // 単位マスタ
            'mcr.id as mcr_id',
            'mcr.name as mcr_name',
        )->distinct()->from('t_quote_details as tqd')
            ->leftjoin('t_quotes as tq', 'tqd.quote_id', '=', 'tq.id') // 見積データ
            ->leftjoin('t_projects as tp', 'tq.project_id', '=', 'tp.id') // 案件データ
            ->leftjoin('m_categories as mc', 'tqd.category_id', '=', 'mc.id') // 大分類マスタ
            ->leftjoin('m_sub_categories as msc', 'tqd.sub_category_id', '=', 'msc.id') // 中分類マスタ
            ->leftjoin('m_credits as mcr', 'tqd.unit', '=', 'mcr.id') // 単位マスタ
            ->groupby('mc.id', 'msc.id', 'tqd.id', 'tqd.quote_id');

        // 検索条件（where）
        self::set_where($query, $param);
        // ソート条件（order by）
//        self::_set_order_by($query, $param->input('sort_by', 1), $param->input('highlow', 0)); // TODO
        if ($param->filled('limit')) {
            // オフセット条件（offset）
            $query->skip($param->input('offset', 0));
            // リミット条件（limit）
            $query->take($param->input('limit'));
        }

        if ($id == 0) {
            // 全ての見積明細を取得
            $result = $query->get();
        } else {
            // 見積IDに紐づく見積明細を取得
            $result = $query->where('tqd.quote_id', $id)->get();
        }
        if ($result->count() == 0) {
            return $result;
        }

        // 取得結果整形
        return self::get_format_column($result);
    }

    /**
     * 見積明細情報1件検索
     *
     * @param int $id 見積ID
     * @param int $detail_id 見積明細ID
     * @return array|null 取得データ
     */
    public static function search_one(int $id, int $detail_id): ?array
    {
        // 取得項目
        $query = TQuoteDetail::select(
            'quote_id',
            'id',
            'item_kubun',
            'category_id',
            'sub_category_id',
            'koji_component_name',
            'standard',
            'quantity',
            'unit',
            'quote_unit_price',
            'prime_cost',
        )->where('quote_id', $id)
            ->with(['detail' => function($q) {
                $q->select('name', 'id')->where('is_valid', 1);
            }]) // 明細マスタ
            ->with(['category' => function($q) {
                $q->select('name', 'id')->where('is_valid', 1);
            }]) // 大分類マスタ
            ->with(['sub_category' => function($q) {
                $q->select('name', 'id')->where('is_valid', 1);
            }]) // 中分類マスタ
            ->with(['credit' => function($q) {
                $q->select('name', 'id')->where('is_valid', 1);
            }]); // 単位マスタ

        $result = $query->find($detail_id);
        if (is_null($result)) {
            return null;
        }

        // 取得結果整形
        return self::get_format_column_one($result);
    }

    /**
     * 見積明細ツリー情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @param int $id 見積ID
     * @return mixed 取得データ
     */
    public static function search_detail_tree(Request $param, int $id)
    {
        // 取得項目
        $query = TQuoteDetail::select(
            // 見積明細データ
            'tqd.id as tqd_id',
            'tqd.category_id as tqd_category_id',
            'tqd.sub_category_id as tqd_sub_category_id',
            'tqd.category_percent as tqd_category_percent',
            'tqd.sub_category_percent as tqd_sub_category_percent',
            'tqd.category_index as tqd_category_index',
            'tqd.sub_category_index as tqd_sub_category_index',
            'tqd.quantity as tqd_quantity',
            'tqd.quote_unit_price as tqd_quote_unit_price',
            'tqd.prime_cost as tqd_prime_cost',
            'category_print_name as tqd_category_print_name',
            'sub_category_print_name as tqd_sub_category_print_name',
            // 大分類マスタ
            'mc.id as mc_id',
            'mc.name as mc_name',
            // 中分類マスタ
            'msc.id as msc_id',
            'msc.name as msc_name',
        )->distinct()->from('t_quote_details as tqd')
            ->leftjoin('m_categories as mc', 'tqd.category_id', '=', 'mc.id') // 大分類マスタ
            ->leftjoin('m_sub_categories as msc', 'tqd.sub_category_id', '=', 'msc.id'); // 中分類マスタ

        // 検索条件（where）
        self::set_where($query, $param);
        // ソート条件（order by）
//        self::_set_order_by($query, $param->input('sort_by', 1), $param->input('highlow', 0)); // TODO
        $query->orderByRaw('mc_id, msc_id');
        if ($param->filled('limit')) {
            // オフセット条件（offset）
            $query->skip($param->input('offset', 0));
            // リミット条件（limit）
            $query->take($param->input('limit'));
        }

        if ($id == 0) {
            // 全ての見積明細を取得
            $result = $query->get();
        } else {
            // 見積IDに紐づく見積明細を取得
            $result = $query->where('tqd.quote_id', $id)->get();
        }
        if ($result->count() == 0) {
            return $result;
        }

        // 取得結果整形
        if ($id == 0) {
            return self::get_format_column_tree_all($result);
        } else {
            return self::get_format_column_tree($result);
        }
    }

    /**
     * 見積明細情報保存（登録・更新）
     *
     * @param Request $param
     * @param int $id 見積ID
     * @param int|null $detail_id 見積明細ID
     * @return string
     */
    public static function upsert(Request $param, int $id, int $detail_id = null): string
    {
        $arr = $param->all();
        // DB登録・更新用にパラメータ変換
        // 大分類
        if ($param->filled('category')) {
            $arr['category_id'] = $param->input('category');
        }
        // 中分類
        if ($param->filled('sub_category')) {
            $arr['sub_category_id'] = $param->input('sub_category');
        }
        // 工事・資材名
        if ($param->filled('construction_materials_name')) {
            $arr['koji_component_name'] = $param->input('construction_materials_name');
        }
        // 印刷名称
        if ($param->filled('construction_materials_name')) {
            // 工事・資材名と同様の値を設定
            $arr['print_name'] = $param->input('construction_materials_name');
        }

        // 最終更新者
        // TODO ログインユーザー名を登録
        $arr['last_updated_by'] = '管理者';

        if ($detail_id) {
            // 更新
            $obj = TQuoteDetail::select('*')->where('quote_id', $id)->find($detail_id);
            if (is_null($obj)) {
                return '404';
            }

            // 更新処理
            $obj->fill($arr)->save();
        } else {
            // 表示順
            if (is_null(TQuoteDetail::select('id')->get())) {
                $arr['category_index'] = 1;
                $arr['sub_category_index'] = 1;
            } else {
                $arr['category_index'] = TQuoteDetail::select('category_id')->get()->max('category_id') + 1;
                $arr['sub_category_index'] = TQuoteDetail::select('sub_category_id')->get()->max('sub_category_id') + 1;
            }
            // 登録処理
            $quote_detail = new TQuoteDetail();
            $quote_detail->fill($arr)->save();
        }

        return 'ok';
    }

    /**
     * 見積明細情報削除
     *
     * @param int $id 見積ID
     * @param int|null $detail_id 見積明細ID
     */
    public static function remove(int $id, int $detail_id)
    {
        $obj = TQuoteDetail::where('quote_id', $id)->find($detail_id);
        if (!is_null($obj)) {
            // 削除処理
            $obj->delete();
        }

        return;
    }

    /**
     * 過去見積明細情報一括登録
     *
     * @param Request $param
     * @param int $id 見積ID
     * @return void
     */
    public static function multi_insert_detail(Request $param, int $id)
    {
        $arr = $param->all();

        if ($param->filled('from_quote_id') && $param->filled('to_quote_id')) {
            // コピー元の見積明細データ
            $result = TQuoteDetail::select('*')->where('quote_id', $arr['from_quote_id'])->get();
            if ($result->count() == 0) {
                return $result;
            }

            foreach ($result as $item) {
                $ins_arr = $item->toArray();

                // コピー先の見積IDを設定
                $ins_arr['quote_id'] = $arr['to_quote_id'];
                // 印刷名称
                // 工事・資材名と同様の値を設定
                $ins_arr['print_name'] = $ins_arr['koji_component_name'];
                // 最終更新者
                // TODO ログインユーザー名を登録
                $ins_arr['last_updated_by'] = '管理者1';
                // 登録処理
                $quote_detail = new TQuoteDetail();
                $quote_detail->fill($ins_arr)->save();
            }
        } else {
            foreach ($arr['detail_id'] as $detail_id) {
                // 見積明細IDからコピーする過去見積明細情報を取得
                $obj = TQuoteDetail::select('*')->find($detail_id);
                if (!is_null($obj)) {
                    $ins_arr = $obj->toArray();
                    unset($ins_arr['id']); // 登録処理とするため、id項目を削除しておく
                    unset($ins_arr['created_at']); // 登録処理とするため、登録日時項目を削除しておく
                    unset($ins_arr['updated_at']); // 登録処理とするため、更新日時項目を削除しておく
                    // 見積ID
                    $ins_arr['quote_id'] = $id;
                    // 印刷名称
                    // 工事・資材名と同様の値を設定
                    $ins_arr['print_name'] = $ins_arr['koji_component_name'];
                    // 最終更新者
                    // TODO ログインユーザー名を登録
                    $ins_arr['last_updated_by'] = '管理者';

                    // 登録処理
                    $quote_detail = new TQuoteDetail();
                    $quote_detail->fill($ins_arr)->save();
                }
            }
        }

        return;
    }

    /**
     * 見積印刷名称変更
     *
     * @param Request $param
     * @param int $id 見積ID
     * @param int|null $detail_id 見積明細ID
     * @return string
     */
    public static function update_print_name(Request $param, int $id, int $detail_id): string
    {
        $arr = $param->all();
        // 最終更新者
        // TODO ログインユーザー名で更新
        $arr['last_updated_by'] = '管理者';

        // 更新
        $obj = TQuoteDetail::select('*')->where('quote_id', $id)->find($detail_id);
        if (is_null($obj)) {
            return '404';
        }

        // 更新処理
        $obj->fill($arr)->save();

        return 'ok';
    }

    /**
     * 見積印刷（大中分類）名称変更
     *
     * @param Request $param
     * @param int $id 見積ID
     * @param int|null $detail_id 見積明細ID
     * @return string
     */
    public static function update_print_cate_name(Request $param, int $id, int $detail_id): string
    {
        $arr = $param->all();
        if ($param->filled('sub_category')) {
            // 印刷名称（中分類名称）
            $arr['sub_category_print_name'] = $arr['print_name'];
        } else {
            // 印刷名称（大分類名称）
            $arr['category_print_name'] = $arr['print_name'];
        }
        // 最終更新者
        // TODO ログインユーザー名で更新
        $arr['last_updated_by'] = '管理者';

        // 更新
        $query = TQuoteDetail::select('*')
            ->where('quote_id', $id)
            ->where('category_id', $arr['category']);
        if (array_key_exists('sub_category', $arr)) {
            $query->where('sub_category_id', $arr['sub_category']);
        }
        $obj = $query->find($detail_id);
        if (is_null($obj)) {
            return '404';
        }

        // 更新処理
        $obj->fill($arr)->save();

        return 'ok';
    }

    /**
     * 検索条件設定
     *
     * @param $query
     * @param Request $param 検索パラメータ
     */
    public static function set_where(&$query, Request $param)
    {
        // 大分類ID
        if ($param->filled('category')) {
            $query = $query->where('tqd.category_id', $param->input('category'));
        }
        // 中分類ID
        if ($param->filled('sub_category')) {
            $query = $query->where('sub_category_id', $param->input('sub_category'));
        }
        // 明細
        if ($param->filled('detail')) {
            $query = $query->where('koji_component_name', 'like', '%' . $param->input('detail') . '%');
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
        // TODO ソート処理が必要なら追加する
//        if (is_null($order_column_id) || is_null($sort_id)) {
//            // 未指定の場合、OBフラグの昇順
//            $query->orderBy(self::SORT_BY_COLUMN[1], ModelBase::SORT_KIND[0]);
//        } else {
//            $query->orderBy(self::SORT_BY_COLUMN[$order_column_id], ModelBase::SORT_KIND[$sort_id]);
//        }

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

            // 大分類・中分類データ重複判定
            $same_flg = false;
            foreach ($results as $result) {
                if ((array_key_exists('category', $result)
                    && $arr['mc_id'] == $result['category'])
                    && (array_key_exists('sub_category', $result)
                    && $arr['msc_id'] == $result['sub_category'])) {
                    // 設定済みの分類のデータと同じ場合
                    $same_flg = true;
                }
            }
            if ($same_flg) {
                // 同じ分類のデータの場合、次の取得データへ飛ばす
                continue;
            }
            // 計算処理
            // 金額
            // （見積明細データ．数量） × （見積明細データ．見積単価）
            $price = floatval($arr['tqd_quantity']) * floatval($arr['tqd_quote_unit_price']);
            // 原価金額
            // （見積明細データ．数量） × （見積明細データ．原価）
            $cost_amount = floatval($arr['tqd_quantity']) * floatval($arr['tqd_prime_cost']);
            // 見積合計金額
            // （見積明細データ．見積金額 ）+ （見積明細データ．現場協力費（見積）） + （見積明細データ．予備原価（見積））
            $quote_total_amount = floatval($price) + floatval($arr['tq_field_cooperating_cost_estimate']) + floatval($arr['tq_call_cost_estimate']);
            // 原価合計金額
            // （見積明細データ．原価金額） + （見積明細データ．現場協力費（原価）） + （見積明細データ．予備原価（原価））
            $cost_total_amount = floatval($cost_amount) + floatval($arr['tq_field_cooperating_cost']) + floatval($arr['tq_call_cost']);
            // 粗利金額
            // 見積合計金額 - 原価合計金額
            $gross_profit_amount = $quote_total_amount - $cost_total_amount;
            // 粗利率
            // （(見積金額 - 原価金額) / 見積金額) × 100 ※四捨五入
            $gross_profit_rate = (floatval($price) - floatval($cost_amount)) == 0 ? 0 : round((floatval($price) - floatval($cost_amount)) / floatval($price) * 100);

            $data = [
                'quote_id' => $arr['tqd_quote_id'], // 見積ID
                'id' => $arr['tqd_id'], // 見積明細ID
                'category' => $arr['mc_id'], // 大分類
                'category_name' => $arr['mc_name'], // 大分類名称
                'sub_category' => $arr['msc_id'], // 中分類
                'sub_category_name' => $arr['msc_name'], // 中分類名称
                'component_name' => $arr['tqd_koji_component_name'], // 工事・部材名称
                'print_name' => $arr['tqd_print_name'], // 印刷名称
                'standard' => $arr['tqd_standard'], // 規格
                'quantity' => floatval($arr['tqd_quantity']), // 数量
                'unit' => $arr['mcr_id'], // 単位
                'unit_name' => $arr['mcr_name'], // 単位名称
                'quote_unit_price' => floatval($arr['tqd_quote_unit_price']), // 見積単価
                'price' => $price, // 金額
                'prime_cost' => floatval($arr['tqd_prime_cost']), // 原価
                'cost_amount' => $cost_amount, // 原価金額
                'gross_profit_amount' => $gross_profit_amount, // 粗利金額
                'gross_profit_rate' => $gross_profit_rate, // 粗利率
                'remarks' => $arr['tqd_remarks'], // 備考
                'index' => $arr['tqd_category_index'], // 大分類表示順
                'sub_index' => $arr['tqd_sub_category_index'], // 中分類表示順
                'order_flag' => ModelBase::is_order($arr['tp_contract_date'], $arr['tp_cancel_date']), // 受注フラグ
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
            'quote_id' => $obj->quote_id, // 見積ID
            'id' => $obj->id, // 見積明細ID
            'item_kubun' => $obj->item_kubun, // 商品区分
            'item_kubun_name' => array_key_exists($obj->item_kubun, ModelBase::SHOHIN_KUBUN) ? ModelBase::SHOHIN_KUBUN[$obj->item_kubun] : '', // 商品区分名称
            'category' => $obj->category_id, // 大分類
            'category_name' => CommonUtility::is_exist_variable($obj->category) ? $obj->category['name'] : '', // 大分類名称
            'sub_category' => $obj->sub_category_id, // 中分類
            'sub_category_name' => CommonUtility::is_exist_variable($obj->sub_category) ? $obj->sub_category['name'] : '', // 中分類名称
            'construction_materials_name' => $obj->koji_component_name, // 工事・資材名
            'standard' => $obj->standard, // 規格
            'quantity' => floatval($obj->quantity), // 数量
            'unit' => $obj->unit, // 単位
            'unit_name' => CommonUtility::is_exist_variable($obj->credit) ? $obj->credit['name'] : '', // 単位名称
            'quote_unit_price' => floatval($obj->quote_unit_price), // 見積単価
            'prime_cost' => floatval($obj->prime_cost), // 原価
        ];

        return $data;
    }

    /**
     * DB取得結果整形（ツリー一覧取得用）<br>
     * レスポンスの形に合わせ整形し、コレクションで返却
     *
     * @param $collection
     * @return Collection
     */
    private static function get_format_column_tree($collection): Collection
    {
        $results = new Collection();
        // 総パーセント（粗利率の総計）
        $gross_profit_rate = 0;
        foreach ($collection as $item) {

            $arr = $item->toArray();
            // 大分類データ重複判定
            $same_flg = false;
            foreach ($results as $result) {
                if (array_key_exists('parent_id', $result)
                    && $arr['tqd_category_id'] == $result['parent_id']) {
                    // 設定済みの大分類のデータと同じ場合
                    $same_flg = true;
                }
            }
            if ($same_flg) {
                // 同じ大分類のデータの場合、次の取得データへ飛ばす
                continue;
            }

            // 計算処理
            // 金額
            // （見積明細データ．数量） × （見積明細データ．見積単価）
            $price = floatval($arr['tqd_quantity']) * floatval($arr['tqd_quote_unit_price']);
            // 原価金額
            // （見積明細データ．数量） × （見積明細データ．原価）
            $cost_amount = floatval($arr['tqd_quantity']) * floatval($arr['tqd_prime_cost']);
            // 粗利率
            // （(見積金額 - 原価金額) / 見積金額) × 100 ※四捨五入
            $gross_profit_rate += (floatval($price) - floatval($cost_amount)) == 0 ? 0 : round((floatval($price) - floatval($cost_amount)) / floatval($price) * 100);

            // 大分類データ設定
            $data = [
                'detail_id' => $arr['tqd_id'], // 見積明細ID
                'parent_id' => $arr['tqd_category_id'], // 大分類ID
                'index' => $arr['tqd_category_index'], // 大分類表示順
                // 大分類名称の印刷名称が変更されていたら、その値を取得
                'parent_title' => is_null($arr['tqd_category_print_name']) ? $arr['mc_name'] : $arr['tqd_category_print_name'], // 大分類名
                'parent_percent' => floatval($arr['tqd_category_percent']), // 大分類パーセント
            ];
            // 中分類データ初回設定
            $data['sub'][] = [
                'id' => $arr['tqd_sub_category_id'], // 中分類ID
                'index' => $arr['tqd_sub_category_index'], // 中分類表示順
                // 中分類名称の印刷名称が変更されていたら、その値を取得
                'title' => is_null($arr['tqd_sub_category_print_name']) ? $arr['msc_name'] : $arr['tqd_sub_category_print_name'], // 中分類名
                'percent' => floatval($arr['tqd_sub_category_percent']), // 中分類パーセント
            ];
            // 中分類データループ処理
            foreach ($collection as $item2) {

                $arr2 = $item2->toArray();
                if ($data['parent_id'] == $arr2['tqd_category_id']) {
                    // 同じ大分類の場合
                    $same_sub_flag = false;
                    foreach ($data['sub'] as $sub) {
                        // 設定済みの中分類と比較
                        if ($sub['id'] == $arr2['tqd_sub_category_id']) {
                            // 同じ中分類の場合
                            $same_sub_flag = true;
                        }
                    }
                    if (!$same_sub_flag) {
                        // 同じ大分類に属する別の中分類の場合
                        // 大分類配列の中に中分類配列を追加
                        $data['sub'][] = [
                            'id' => $arr2['tqd_sub_category_id'], // 中分類ID
                            'index' => $arr2['tqd_sub_category_index'], // 中分類表示順
                            // 中分類名称の印刷名称が変更されていたら、その値を取得
                            'title' => is_null($arr2['tqd_sub_category_print_name']) ? $arr2['msc_name'] : $arr2['tqd_sub_category_print_name'], // 中分類名
                            'percent' => floatval($arr2['tqd_sub_category_percent']), // 中分類パーセント
                        ];
                    }
                }
            }
            $results->push($data);
        }
        $results->push(['percent' => $gross_profit_rate]);

        return $results;
    }

    /**
     * DB取得結果整形（ツリー一覧取得（マスタから登録時）用）<br>
     * レスポンスの形に合わせ整形し、コレクションで返却
     *
     * @param $collection
     * @return Collection
     */
    private static function get_format_column_tree_all($collection): Collection
    {
        $results = new Collection();
        foreach ($collection as $item) {

            $arr = $item->toArray();
            // 大分類データ重複判定
            $same_flg = false;
            foreach ($results as $result) {
                if (array_key_exists('parent_id', $result)
                    && $arr['tqd_category_id'] == $result['parent_id']) {
                    // 設定済みの大分類のデータと同じ場合
                    $same_flg = true;
                }
            }
            if ($same_flg) {
                // 同じ大分類のデータの場合、次の取得データへ飛ばす
                continue;
            }
            // 大分類データ設定
            $data = [
                'detail_id' => $arr['tqd_id'], // 見積明細ID
                'parent_id' => $arr['tqd_category_id'], // 大分類ID
                'index' => $arr['tqd_category_index'], // 大分類表示順
                'parent_title' => $arr['mc_name'], // 大分類名
            ];
            // 中分類データ初回設定
            $data['sub'][] = [
                'id' => $arr['tqd_sub_category_id'], // 中分類ID
                'index' => $arr['tqd_sub_category_index'], // 中分類表示順
                'title' => $arr['msc_name'], // 中分類名
            ];
            // 中分類データループ処理
            foreach ($collection as $item2) {

                $arr2 = $item2->toArray();
                if ($data['parent_id'] == $arr2['tqd_category_id']) {
                    // 同じ大分類の場合
                    $same_sub_flag = false;
                    foreach ($data['sub'] as $sub) {
                        // 設定済みの中分類と比較
                        if ($sub['id'] == $arr2['tqd_sub_category_id']) {
                            // 同じ中分類の場合
                            $same_sub_flag = true;
                        }
                    }
                    if (!$same_sub_flag) {
                        // 同じ大分類に属する別の中分類の場合
                        // 大分類配列の中に中分類配列を追加
                        $data['sub'][] = [
                            'id' => $arr2['tqd_sub_category_id'], // 中分類ID
                            'index' => $arr2['tqd_sub_category_index'], // 中分類表示順
                            'title' => $arr2['msc_name'], // 中分類名
                        ];
                    }
                }
            }
            $results->push($data);
        }

        return $results;
    }
}
