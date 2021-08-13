<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class MCustomerRankKoji<br>
 * 顧客ランク（工事金額）マスタ
 *
 * @package App\Models
 */
class MCustomerRankKoji extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（m_customer_rank_kojis）
    // 主キーのデフォルト名はid
    // 主キーはデフォルトではINT型のAuto Increment
    // デフォルトではタイムスタンプを自動更新（created_at、updated_atを生成）
    // デフォルトの接続データベースは .env の DB_CONNECTION の定義内容

    /**
     * テーブルに関連付ける主キー
     *
     * @var int
     */
    protected $primaryKey = 'customer_rank_koji_id';

    /**
     * モデルにタイムスタンプを付けるか
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * ホワイトリスト
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'abbreviation',
        'order',
        'text_color',
        'background_color',
        'amount',
    ];

    /**
     * 顧客ランク（工事金額）マスタに紐づく顧客ランク（最終完工日）マスタ取得（1対多）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rank_last(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MCustomerRankLastCompletion::class, 'customer_rank_last_completions_id', 'id');
    }

    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'customer_rank_koji_id', // 顧客ランク_工事金額マスタID
        1 => 'name', // 顧客ランク名称
        2 => 'abbreviation', // 顧客ランク略称
        3 => 'text_color', // 文字色
        4 => 'background_color', // 背景色
        5 => 'amount', // 工事金額
    ];

    /**
     * 顧客ランク（工事金額）マスタ情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_list(Request $param)
    {
        // 取得項目
        $query = MCustomerRankKoji::select(
            'customer_rank_koji_id',
            'name',
            'abbreviation',
            'order',
            'text_color',
            'background_color',
            'amount',
        );

        // ソート条件（order by）
        self::_set_order_by($query, $param->input('sort_by', 0), $param->input('highlow', 0));

        $result = $query->get();
        if ($result->count() == 0) {
            return $result;
        }

        // 取得結果整形
        return self::get_format_column($result);
    }


    /**
     * 顧客ランクマスタ情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_customer_rank_list(Request $param)
    {
        // 取得項目
        $query = MCustomerRankKoji::select(
            'customer_rank_koji_id',
            'name',
            'abbreviation',
            'order',
            'text_color',
            'background_color',
            'amount',
        );
        $query2 = MCustomerRankLastCompletion::select(
            'customer_rank_last_completions_id',
            'name',
            'abbreviation',
            'date',
        );
//        )->with(['rank_last' => function($q) {
//            $q->select('customer_rank_last_completions_id', 'name', 'abbreviation', 'date');
//        }]); // 顧客ランク（最終完工日）マスタ

        // ソート条件（order by）
        self::_set_order_by($query, $param->input('sort_by', 0), $param->input('highlow', 0));

        $result = $query->get();
        $result2 = $query2->get();
        if ($result->count() == 0) {
            return $result;
        }

        // 取得結果整形
        return self::get_format_customer_rank_column($result, $result2);
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
            // 未指定の場合、顧客ランク_工事金額マスタIDの昇順
            $query->orderBy(self::SORT_BY_COLUMN[0], ModelBase::SORT_KIND[0]);
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
     * @return Collection $results 整形後データ
     */
    private static function get_format_column($collection): Collection
    {
        $results = new Collection();
        foreach ($collection as $item) {
            $arr = $item->toArray();
            $data = [
                'customer_rank_koji_id' => $arr['customer_rank_koji_id'], // 顧客ランク_工事金額マスタID
                'name' => $arr['name'], // 顧客ランク名称
                'abbreviation' => $arr['abbreviation'], // 顧客ランク略称
                'text_color' => $arr['text_color'], // 文字色
                'background_color' => $arr['background_color'], // 背景色
                'amount' => $arr['amount'], // 工事金額
            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     * DB取得結果整形（顧客ランク一覧取得用）<br>
     * レスポンスの形に合わせ整形し、コレクションで返却
     *
     * @param $collection
     * @param $collection2
     * @return Collection $results 整形後データ
     */
    private static function get_format_customer_rank_column($collection, $collection2): Collection
    {
        $results = new Collection();
        $i = 0;
        foreach ($collection as $item) {
            foreach ($collection2 as $item2) {
                $arr = $item->toArray(); // 顧客ランク（工事金額）マスタ
                $arr2 = $item2->toArray(); // 顧客ランク（最終完工日）マスタ
                $data = [
                    'customer_rank_koji_id' => $i + 1, // 顧客ランク_工事金額マスタID
                    'customer_rank_last_completions_id' => $arr2['customer_rank_last_completions_id'], // 顧客ランク_最終完工日マスタID
                    'name' => $arr['name'] . $arr2['name'], // 顧客ランク名称（顧客ランク（工事金額）マスタの顧客ランク名称 + 顧客ランク（最終完工日）マスタの顧客ランク名称）
                    'abbreviation' => $arr['abbreviation'], // 顧客ランク略称（工事金額マスタ）
                    'order' => $arr['order'], // 顧客ランク順位
                    'abbreviation2' => $arr2['abbreviation'], // 顧客ランク略称（最終完工日マスタ）
                    'text_color' => $arr['text_color'], // 文字色（工事金額マスタ）
                    'background_color' => $arr['background_color'], // 背景色（工事金額マスタ）
                ];
                $results->push($data);
                $i++;
            }
        }

        return $results;
    }
}
