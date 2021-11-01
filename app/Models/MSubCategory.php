<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use DB;
use Throwable;
use Auth;

//use App\Traits\HasCompositePrimaryKey;

class MSubCategory extends ModelBase
{
    use HasFactory;

//    use HasCompositePrimaryKey;

    // テーブル名はクラスの複数形のスネークケース（m_subcategories）
    // 主キーはデフォルトではINT型のAuto Increment
    // company_id, internal_idはユニークキー

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
        'is_valid' => 1,
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
        'category_id',
        'name',
        'is_valid',
        'order',
    ];


    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'order', // 表示順
        1 => 'internal_id', // 表示ID
        2 => 'category_name', // 大分類名称
        3 => 'name', // 中分類名称
        4 => 'is_valid', // 有効フラグ
    ];

    /**
     * 中分類マスタ情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_list(Request $param)
    {
//      セッションからログインユーザーのcompany_idを取得
        $company_id = session()->get('company_id');

        // 取得項目
        $query = DB::table('m_sub_categories as s')
            ->select(
                's.id',
                's.internal_id',
                's.company_id',
                's.category_id',
                'c.name as category_name',
                's.name',
                's.order',
                's.is_valid',
            )->leftjoin('m_categories as c', 's.category_id', '=', 'c.id')
            ->where('s.company_id', $company_id);

//        $test = MSubCategory::select('m_sub_categories.category_id','d.id','de.id')
//            ->leftjoin('m_details as d', function ($q) {
//                $q->on('m_sub_categories.id', '=', 'd.tyubunrui_id')
//            ->groupBy('d.tyubunrui_id');
//            })
//            ->leftjoin('m_details as de', function ($q) {
//                $q->on('m_sub_categories.id', '=', 'de.tyubunrui_id')
//                    ->groupBy('de.tyubunrui_id');
//            })
//            ->get();
//
//                dd($test);

//        $ids = MSubCategory::selectRaw('MAX(id) AS id')
//          ->groupBy('category_id')
//          ->pluck('id');
//
//      $test = MSubCategory::whereIn('id', $ids)
//          ->orderBy('id', 'DESC')
//          ->get();

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
            $query = $query->where('s.is_valid', 1); // 有効情報のみ
        }
        $category_id = $param->input('category_id');
        if ($category_id) {
            $query = $query->where('category_id', $category_id);
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
            $data = [
                'id' => $item->id, // オートインクリメントID
                'company_id' => $item->company_id, // 会社ID
                'internal_id' => $item->internal_id, // 内部ID
                'category_id' => $item->category_id, // 大分類ID
                'category_name' => $item->category_name, // 大分類名称
                'name' => $item->name, // 中分類名称
                'order' => $item->order, // 表示順
                'valid_flag' => ($item->is_valid) ? true : false, // 有効フラグ

            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     * 中分類マスタ情報保存（登録・更新）
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
            $tmp = MSubCategory::where('company_id', $company_id)
                ->where("name", $arr['name'])->first();
            if (self::isRepeatName($tmp, $id)) {
                return ["code" => 'err_name'];
            }


//            トランザクション
            DB::beginTransaction();

            if ($id) {
                // 更新
                $instance = MSubCategory::find($id);
                if (is_null($instance)) {
                    return ["code" => '404'];
//                    ログインユーザーのcompany_idと一致しているか
                } elseif ($instance->company_id != $company_id) {
                    return ["code" => '403'];
                }

                // 更新処理
                $instance->fill($arr)->update();
            } else {

                // 登録
                $instance = new MSubCategory();
                $instance->company_id = $company_id;

//                内部ID(internal_id)の最大値取得
                $max_internal_id = MSubCategory::where('company_id', $company_id)->max('internal_id');

//                    internal_idの最大値+1をそれぞれDBに格納
                $instance->internal_id = $max_internal_id ? ($max_internal_id + 1) : 1;

                // 登録処理
                $instance->fill($arr)->save();
            }
            DB::commit();
            return ["code" => ""];

        } catch (Throwable $e) {
            DB::rollback();
//            トランザクションエラー
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
