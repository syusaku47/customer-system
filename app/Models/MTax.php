<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class MStore<br>
 * 消費税マスタ
 *
 * @package App\Models
 */
class MTax extends ModelBase
{
    use HasFactory;

    /**
     * モデルにタイムスタンプを付けるか
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
        'is_valid' => 1,
    ];

    /**
     * ホワイトリスト
     *
     * @var string[]
     */
    protected $fillable = [
        'start_date',
        'tax_rate',
        'is_valid',
    ];

    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'start_date', // 適用開始日
        1 => 'tax_rate', // 消費税率
        2 => 'is_valid', // 有効フラグ

    ];

    /**
     * 店舗マスタ情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_list(Request $param)
    {
        // 取得項目
        $query = MTax::select(
            'id',
            'start_date',
            'tax_rate',
            'is_valid',
        );

        // 検索条件（where）
        self::set_where($query, $param);
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
     * 検索条件設定
     *
     * @param $query
     * @param Request $param 検索パラメータ
     */
    public static function set_where(&$query, Request $param)
    {
        // マスタ管理用
        // 無効情報も含む
        if ($param->input('is_muko') == 0 || !$param->input('is_muko')) {
            $query = $query->where('is_valid', 1); // 有効情報のみ
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
            // 未指定の場合、名称の昇順
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
                'tax_id' => $arr['id'], // 消費税マスタID
                'id' => $arr['id'], // ID
                'start_date' => static::date_conversion($arr['start_date']), // 適用開始日
                'tax_rate' => $arr['tax_rate'], // 消費税率
                'valid_flag' => ($arr['is_valid']) ? true : false, // 有効フラグ

            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     * 日付をレスポンス用に変換
     * @param $date
     * @return array|string|string[]
     */
    public static function date_conversion($date)
    {
        return str_replace("-","/", $date);
    }

    /**
     * 消費税情報保存（登録・更新）
     *
     * @param Request $param
     * @param int|null $id
     * @return collection
     */
    public static function upsert(Request $param, int $id = null): Collection
    {
        $arr = $param->all();

        if ($id) {
            // 更新
            $tax = MTax::findOrFail($id);

            // 更新処理
            $tax->fill($arr)->update();
        } else {
            // 登録処理
            $tax = new MTax();
            $tax->fill($arr)->save();
        }

        return $tax;
    }
}
