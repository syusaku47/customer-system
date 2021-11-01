<?php

namespace App\Models;

use App\Libraries\CommonUtility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use DB;
use Throwable;
use Auth;
//use App\Traits\HasCompositePrimaryKey;

class MDetail extends ModelBase
{
    use HasFactory;
//    use HasCompositePrimaryKey;

    // テーブル名はクラスの複数形のスネークケース（m_details）
    // 複合キーで主キーのデフォルト名はid,company_id

//    // プライマリキー設定
//    protected $primaryKey = ['id', 'company_id'];
//    // increment無効化
//    public $incrementing = false;
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
        'valid_flag' => 1,
        'order' => 999,

    ];

    /**
     * ホワイトリスト
     *
     * @var string[]
     */
    protected $fillable = [
        'company_id',
        'internal_id',
        'shohin_cd',
        'shohin_kubun',
        'daibunrui_id',
        'tyubunrui_id',
        'name',
        'kikaku',
        'suryou',
        'tani_id',
        'genka',
        'shikiri_kakaku',
        'valid_flag',
        'order',
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
        0 => 'order', // 表示順
        1 => 'internal_id', // 表示ID
        2 => 'shohin_kubun', // 商品区分
        3 => 'daibunrui_name', // 大分類名称
        4 => 'tyubunrui_name', // 中分類名称
        5 => 'name', // 名称
        6 => 'kikaku', // 規格
        7 => 'suryou', // 数量
        8 => 'tani_name', // 単位名称
        9 => 'shikiri_kakaku', // 見積単価
        10 => 'genka', // 原価
        11 => 'valid_flag', // 有効フラグ
    ];

    /**
     * 明細マスタ情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_list(Request $param)
    {
        //      セッションからログインユーザーのcompany_idを取得
        $company_id = session()->get('company_id');
        // 取得項目
        $query = DB::table('m_details as d')
        ->select(
            'd.id',
            'd.internal_id',
            'd.company_id',
//          'd.shohin_cd',
            'd.shohin_kubun',
            'c.name as daibunrui_name',
            's.name as tyubunrui_name',
            'd.name',
            'd.kikaku',
            'd.suryou',
            'cr.name as tani_name',
            'd.genka',
            'd.shikiri_kakaku',
            'd.valid_flag',
            'd.order',
            )->leftjoin('m_categories as c', 'd.daibunrui_id', '=', 'c.id')
            ->leftjoin('m_sub_categories as s', 'd.tyubunrui_id', '=', 's.id')
            ->leftjoin('m_credits as cr','d.tani_id', '=', 'cr.id')
            ->where('d.company_id', $company_id);




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
        self::id_where($query,"shohin_kubun",$shohin_kubun); //商品区分絞り込み

        $category_id = $param->input('category_id');
        self::id_where($query,"daibunrui_id",$category_id); //大分類絞り込み

        $subcategory_id = $param->input('subcategory_id');
        self::id_where($query,"tyubunrui_id",$subcategory_id); //中分類絞り込み

        $word = $param->input('word');
        self::like_orWhere($query,$word); //名称、規格絞り込み


    }

    public static function id_where($query, $column, $id){
        if ($id != null) {
            $query->where($column, "=", $id);
        }
    }

    public static function like_orWhere($query, $keyword){
        if ($keyword != null) {
            $query->where(function($query) use ($keyword){ //orの優先順位のため無名関数使用
               $query->orWhere("d.name", 'like', "%". $keyword ."%")
                   ->orWhere("d.kikaku", 'like', "%". $keyword ."%");
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
            $data = [
                'id' => $item->id, // オートインクリメントID
                'company_id' => $item->company_id, // 会社ID
                'internal_id' => $item->internal_id, // 内部ID
                'product_kubun' => array_key_exists($item->shohin_kubun, ModelBase::SHOHIN_KUBUN) ? ModelBase::SHOHIN_KUBUN[$item->shohin_kubun] : '', // 商品区分
                'category_name' => $item->daibunrui_name, // 大分類名称
                'subcategory_name' => $item->tyubunrui_name, // 中分類名称
                'name' => $item->name, // 名称
                'standard' => $item->kikaku, // 規格
                'quantity' => $item->suryou, // 数量
                'credit_name' => $item->tani_name, // 単位名称
                'quote_unit_price' => $item->shikiri_kakaku, // 見積単価
                'prime_cost' => $item->genka, // 原価
                'valid_flag' => ($item->valid_flag) ? true : false, // 有効フラグ

            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     * 明細マスタ情報保存（登録・更新）
     *
     * @param Request $param
     * @param int|null $id
     * @return collection
     */
    public static function upsert(Request $param, int $id = null)
    {


        try {
//        全パラメータ取得
            $arr = $param->all();

//          セッションからログインユーザーのcompany_idを取得
            $company_id = session()->get('company_id');
            //            重複チェック
            $tmp = MDetail::where('company_id',$company_id)
                ->where("name", $arr['name'])->first();
            if (self::isRepeatName($tmp, $id)) {
                return ["code" => 'err_name'];
            }

//            トランザクション
            DB::beginTransaction();

            if ($id) {
                // 更新
                $instance = MDetail::find($id);
                if (is_null($instance)) {
                    return ["code" => '404'];
//                    ログインユーザーのcompany_idと一致しているか
                }elseif ($instance->company_id != $company_id){
                    return ["code" => '403'];
                }

                $instance->shohin_kubun = $param->product_kubun;
                $instance->daibunrui_id = $param->category_id;
                $instance->tyubunrui_id = $param->subcategory_id;
//                $instance->name = $param->name;
                $instance->kikaku = $param->standard;
                $instance->suryou = $param->quantity;
                $instance->tani_id = $param->credit_id;
                $instance->shikiri_kakaku = $param->quote_unit_price;
                $instance->genka = $param->prime_cost;
//                $instance->valid_flag = $param->valid_flag;

                // 更新処理
                $instance->fill($arr)->update();
            } else {

                // 登録
                $instance = new MDetail();
                $instance->company_id = $company_id;

                //                内部ID(internal_id)の最大値取得
                $max_internal_id = MDetail::where('company_id', $company_id)->max('internal_id');

//                    internal_idの最大値+1をそれぞれDBに格納
                $instance->internal_id = $max_internal_id ? ($max_internal_id + 1):  1;
                $instance->shohin_cd = $max_internal_id ? ($max_internal_id + 1):  1;

                $instance->shohin_kubun = $param->product_kubun;
                $instance->daibunrui_id = $param->category_id;
                $instance->tyubunrui_id = $param->subcategory_id;
//                $instance->name = $param->name;
                $instance->kikaku = $param->standard;
                $instance->suryou = $param->quantity;
                $instance->tani_id = $param->credit_id;
                $instance->shikiri_kakaku = $param->quote_unit_price;
                $instance->genka = $param->prime_cost;
//                $instance->valid_flag = $param->valid_flag;

                // 登録処理
                $instance->fill($arr)->save();
            }
            DB::commit();
            return ["code" => ""];

        } catch (Throwable $e) {
            DB::rollback();
//            トランザクションエラー
            \Log::debug($e);
            return ["code" => 'fail'];
        }
    }

    /*
     * 重複チェック
     */
    private static function isRepeatName($instance, $id)
    {
//        同じIDでないのは重複とみなす
        if ($instance)
            if ($instance->id !== $id)
                return true;

        return false;
    }


}
