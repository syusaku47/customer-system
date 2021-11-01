<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use DB;
use Throwable;
use Auth;
use App\Traits\HasCompositePrimaryKey;

/**
 * Class MStore<br>
 * 店舗マスタ
 *
 * @package App\Models
 */
class MStore extends ModelBase
{
    use HasFactory;
    use HasCompositePrimaryKey;


    // テーブル名はクラスの複数形のスネークケース（m_Stores）
    // 複合キーで主キーのデフォルト名はid,company_id

    // プライマリキー設定
    protected $primaryKey = ['id', 'company_id'];
    // increment無効化
    public $incrementing = false;

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
        'id',
        'company_id',
        'name',
        'short_name',
        'furigana',
        'tel_no',
        'fax_no',
        'post_no',
        'prefecture',
        'city',
        'address',
        'building_name',
        'is_valid',
        'free_dial',
        'holder',

        'bank_name',
        'bank_account_no',
        'bank_store_name',
        'account',

        'bank_name2',
        'bank_account_no2',
        'bank_store_name2',
        'account2',

        'bank_name3',
        'bank_account_no3',
        'bank_store_name3',
        'account3',

        'bank_name4',
        'bank_account_no4',
        'bank_store_name4',
        'account4',

        'bank_name5',
        'bank_account_no5',
        'bank_store_name5',
        'account5',

        'bank_name6',
        'bank_account_no6',
        'bank_store_name6',
        'account6',

        'logo',
        'order',
    ];

    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [

        0 => 'order', // 操作（表示順）
        1 => 'name', // 店舗_名称
        2 => 'short_name', // 店舗_略称
        3 => 'furigana', // 店舗_フリガナ
        4 => 'tel_no', // 電話番号
        5 => 'fax_no', // FAX番号
        6 => 'post_no', // 郵便番号
        7 => 'prefecture', // 住所_都道府県
        8 => 'city', // 住所_市区町村
        9 => 'address', // 住所_地名・番地
        10 => 'building_name', // 住所_建築名等
        11 => 'is_valid', // 有効フラグ
        12 => 'free_dial', // フリーダイヤル
        13 => 'holder', // 口座名義
    ];

    /**
     * 店舗マスタ情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_list(Request $param)
    {

//      セッションからログインユーザーのcompany_idを取得
        $company_id = session()->get('company_id');

        // 取得項目
        $query = DB::table('m_stores as s')
            ->select(
            's.company_id',
            's.id',
            's.name',
            's.short_name',
            's.furigana',
            's.tel_no',
            's.fax_no',
            's.post_no',
            's.prefecture',
            's.city',
            's.address',
            's.building_name',
            's.is_valid',
            's.free_dial',
            's.logo',
            's.order',
            's.jisx0401_code',
            's.jisx0402_code',
            's.holder',
            's.bank_name',
            's.bank_store_name',
            's.account',
            's.bank_account_no',
            's.bank_name2',
            's.bank_store_name2',
            's.account2',
            's.bank_account_no2',
            's.bank_name3',
            's.bank_store_name3',
            's.account3',
            's.bank_account_no3',
            's.bank_name4',
            's.bank_store_name4',
            's.account4',
            's.bank_account_no4',
            's.bank_name5',
            's.bank_store_name5',
            's.account5',
            's.bank_account_no5',
            's.bank_name6',
            's.bank_store_name6',
            's.account6',
            's.bank_account_no6',
            'c.name as company_name',
        )->leftjoin('m_contract_companies as c', 's.company_id', '=', 'c.id')
            ->where('company_id', $company_id);

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
                'id' => $item->id, // 店舗マスタID
                'name' => $item->name, // 名称
                'short_name' => $item->short_name, // 略称
                'furigana' => $item->furigana, // フリガナ
                'tel_no' => $item->tel_no, // 電話番号
                'fax_no' => $item->fax_no, // FAX番号
                'post_no' => $item->post_no, // 郵便番号
                'prefecture' => ModelBase::PREFECTURE[$item->prefecture], // 都道府県
                'city' => $item->city, // 市区町村
                'address' => $item->address, // 地名・番地
                'building_name' => $item->building_name, // 建築名等
                'valid_flag' => $item->is_valid, // 有効フラグ
                'free_dial' => $item->free_dial, // フリーダイヤル
                'logo' => $item->logo, // ロゴ
                'order' => $item->order, // 表示順
                'jisx0401_code'  => $item->jisx0401_code ,//JISX0401コード
                'jisx0402_code'  => $item-> jisx0402_code,//JISX0402コード
                'holder'  => $item-> holder,//口座名義
                'bank_name'  => $item-> bank_name,//銀行名
                'bank_store_name'  => $item-> bank_store_name,//店舗名(銀行)
                'account'  => $item-> account,//口座
                'bank_account_no'  => $item-> bank_account_no,//口座番号
                'company_name'  => $item->company_name ,//会社名
            ];
//            2~6までの銀行情報
            for ($i=2; $i<7; $i++) {
                $rules['bank_name'.$i] =  $item->{'bank_name'.$i};//銀行名;
                $rules['bank_store_name'.$i] = $item->{'bank_store_name'.$i};//店舗名(銀行)
                $rules['account'.$i] =  $item->{'account'.$i};//口座
                $rules['bank_account_no'.$i] =  $item->{'bank_account_no'.$i};//口座番号
            }
            $results->push($data);
        }

        return $results;
    }
/*---------------------------------------------------------------------------------------------*/
    /**
     * 消費税マスタ情報保存（登録・更新）
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
            $tmp = MStore::where('company_id',$company_id)
                ->where("name", $arr['name'])->first();
            if (self::isRepeatName($tmp, $id)) {
                return ["code" => 'err_name'];
            }

//            トランザクション
            DB::beginTransaction();

            if ($id) {
                // 更新
                $instance = MStore::find(['id' => $id, 'company_id' => $company_id]);
                if (is_null($instance)) {
                    return ["code" => '404'];
                }

                // 更新処理
                $instance->fill($arr)->update();
            } else {

                // 登録
                $instance = new MStore();
                $instance->company_id = $company_id;

//                    idの最大値+1をそれぞれDBに格納
                $instance->id = MStore::max('id') + 1;

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

    /**
     * 重複チェック
     * @param $instance
     * @param int|null $id
     * @return boolean
     */
    private static function isRepeatName($instance, $id)
    {
//        同じIDでないのは重複とみなす
        if ($instance)
            if ($instance->id !== $id)
                return true;

        return false;
    }


    //    /**
//     * 店舗マスタ情報1件取得
//     * @param int $id //店舗マスタID
//     * @return array|null
//     */
//    public static function search_one(int $id): ?array
//    {
//        // 取得項目
//        $query = MStore::select(
//            'id',
//            'name',
//            'short_name',
//            'furigana',
//            'tel_no',
//            'fax_no',
//            'post_no',
//            'prefecture',
//            'city',
//            'address',
//            'building_name',
//            'is_valid',
//            'free_dial',
//            'logo',
//        )->where('id', $id);
//        $result = $query->firstOrFail();
//        if (is_null($result)) {
//            return null;
//        }
//
//        // 取得結果整形
//        return self::get_format_column_one($result);
//    }

//    /**
//     * @param $obj
//     * @return array|null
//     */
//    private static function get_format_column_one($obj): ?array
//    {
//        $data[] = [
//            'id' => $obj->id, // 店舗マスタID
//            'name' => $obj->name, // 名称
//            'furigana'=> $obj->furigana,
//            'short_name' => $obj->short_name,
//            'tel_no' => $obj->tel_no,
//            'fax_no' => $obj->fax_no,
//            'post_no' => $obj->post_no,
//            'prefecture' => $obj->prefecture,
//            'city' => $obj->city,
//            'address' => $obj->address,
//            'building_name' => $obj->building_name,
//            'valid_flag' => $obj->is_valid,
//            'free_dial' => $obj->free_dial,
//            'logo' => $obj->logo,
//        ];
//
//        return $data;
//    }

}
