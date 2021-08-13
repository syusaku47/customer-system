<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class MCustomerEstimatedRank<br>
 * 顧客見込みランクマスタ
 *
 * @package App\Models
 */
class MCustomerEstimatedRank extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（m_customer_estimated_ranks）
    // 主キーのデフォルト名はid
    // 主キーはデフォルトではINT型のAuto Increment
    // デフォルトではタイムスタンプを自動更新（created_at、updated_atを生成）
    // デフォルトの接続データベースは .env の DB_CONNECTION の定義内容

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
    ];


    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'id', // 顧客見込みランクマスタID
        1 => 'name', // 顧客見込みランク名称
        2 => 'abbreviation', // 顧客見込みランク略称
        3 => 'text_color', // 文字色
        4 => 'background_color', // 背景色
    ];

    /**
     * 顧客見込みランクマスタ情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_list(Request $param)
    {
        // 取得項目
        $query = MCustomerEstimatedRank::select(
            'id',
            'name',
            'abbreviation',
            'order',
            'text_color',
            'background_color',
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
            // 未指定の場合、顧客見込みランクマスタIDの昇順
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
                'customer_estimated_rank_id' => $arr['id'], // 顧客見込みランクマスタID
                'name' => $arr['name'], // 顧客見込みランク名称
                'abbreviation' => $arr['abbreviation'], // 顧客見込みランク略称
                'order' => $arr['order'], // 顧客見込みランク順位
                'text_color' => $arr['text_color'], // 文字色
                'background_color' => $arr['background_color'], // 背景色
            ];
            $results->push($data);
        }

        return $results;
    }
}
