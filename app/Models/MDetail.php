<?php

namespace App\Models;

use App\Libraries\CommonUtility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MDetail extends ModelBase
{
    use HasFactory;
    // テーブル名はクラスの複数形のスネークケース（m_details）
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
        'product_kubun',
        'category_name',
        'subcategory_name',
        'name',
        'standard',
        'quantity',
        'credit_name',
        'quote_unit_price',
        'prime_cost',
        'is_valid',

    ];

    /**
     * 明細に紐づく大分類マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MCategory::class, 'id', 'category_name');
    }

    /**
     * 明細に紐づく中分類マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sub_category(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MSubCategory::class, 'id', 'subcategory_name');
    }

    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'product_kubun', // 商品区分
        1 => 'category_name', // 大分類名称
        2 => 'subcategory_name', // 中分類名称
        3 => 'name', // 名称
        4 => 'standard', // 規格
        5 => 'quantity', // 数量
        6 => 'credit_name', // 単位名称
        7 => 'quote_unit_price', // 見積単価
        8 => 'prime_cost', // 見積単価
        9 => 'is_valid', // 有効フラグ
    ];

    /**
     * 明細マスタ情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_list(Request $param)
    {
        // 取得項目
        $query = MDetail::select(
            'id',
            'product_kubun',
            'category_name',
            'subcategory_name',
            'name',
            'standard',
            'quantity',
            'credit_name',
            'quote_unit_price',
            'prime_cost',
            'is_valid',
        )->with(['category' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 大分類マスタ
        ->with(['sub_category' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]); // 中分類マスタ

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

        $shohin_kubun = $param->input('shohin_kubun');
        self::like_where($query,"product_kubun",$shohin_kubun); //商品区分絞り込み

        $category_name = $param->input('category_name');
        self::like_where($query,"category_name",$category_name); //大分類絞り込み

        $subcategory_name = $param->input('subcategory_name');
        self::like_where($query,"subcategory_name",$subcategory_name); //中分類絞り込み

        $word = $param->input('word');
        self::like_orWhere($query,$word); //名称、規格絞り込み
    }

    public static function like_where($query, $column, $keyword){
        if ($keyword != null) {
            $query->where($column, 'like', "%". $keyword ."%");
        }
    }

    public static function like_orWhere($query, $keyword){
        if ($keyword != null) {
            $query->where(function($query) use ($keyword){ //orの優先順位のため無名関数使用
               $query->orWhere("name", 'like', "%". $keyword ."%")
                   ->orWhere("standard", 'like', "%". $keyword ."%");
            });
        }
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
                'detail_id' => $arr['id'], // 明細マスタID
                'id' => $arr['id'], // ID
                'product_kubun' => array_key_exists($arr['product_kubun'], ModelBase::SHOHIN_KUBUN) ? ModelBase::SHOHIN_KUBUN[$arr['product_kubun']] : '', // 商品区分
                'category_name' => CommonUtility::is_exist_variable_array($arr['category']) ? $arr['category']['name'] : '', // 大分類名称
                'subcategory_name' => CommonUtility::is_exist_variable_array($arr['sub_category']) ? $arr['sub_category']['name'] : '', // 中分類名称
                'name' => $arr['name'], // 名称
                'standard' => $arr['standard'], // 規格
                'quantity' => $arr['quantity'], // 数量
                'credit_name' => $arr['credit_name'], // 単位名称
                'quote_unit_price' => $arr['quote_unit_price'], // 見積単価
                'prime_cost' => $arr['prime_cost'], // 原価
                'valid_flag' => ($arr['is_valid']) ? true : false, // 有効フラグ

            ];
            $results->push($data);
        }

        return $results;
    }

}
