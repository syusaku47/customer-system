<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class MStore<br>
 * 店舗マスタ
 *
 * @package App\Models
 */
class MStore extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（m_Stores）
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
        'bank_name',
        'bank_account_no',
        'holder',
        'bank_account',
        'logo',
    ];

    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'name', // 店舗_名称
        1 => 'short_name', // 店舗_略称
        2 => 'furigana', // 店舗_フリガナ
        3 => 'tel_no', // 電話番号
        4 => 'fax_no', // FAX番号
        5 => 'post_no', // 郵便番号
        6 => 'prefecture', // 住所_都道府県
        7 => 'city', // 住所_市区町村
        8 => 'address', // 住所_地名・番地
        9 => 'building_name', // 住所_建築名等
        10 => 'is_valid', // 有効フラグ
        11 => 'free_dial', // フリーダイヤル
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
        $query = MStore::select(
            'id',
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
            'logo',
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
     * 店舗マスタ情報1件取得
     * @param int $id //店舗マスタID
     * @return array|null
     */
    public static function search_one(int $id): ?array
    {
        // 取得項目
        $query = MStore::select(
            'id',
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
            'logo',
        )->where('id', $id);
        $result = $query->firstOrFail();
        if (is_null($result)) {
            return null;
        }

        // 取得結果整形
        return self::get_format_column_one($result);
    }

    /**
     * @param $obj
     * @return array|null
     */
    private static function get_format_column_one($obj): ?array
    {
        $data[] = [
            'id' => $obj->id, // 店舗マスタID
            'name' => $obj->name, // 名称
            'furigana'=> $obj->furigana,
            'short_name' => $obj->short_name,
            'tel_no' => $obj->tel_no,
            'fax_no' => $obj->fax_no,
            'post_no' => $obj->post_no,
            'prefecture' => $obj->prefecture,
            'city' => $obj->city,
            'address' => $obj->address,
            'building_name' => $obj->building_name,
            'valid_flag' => $obj->is_valid,
            'free_dial' => $obj->free_dial,
            'logo' => $obj->logo,
        ];

        return $data;
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
                'id' => $arr['id'], // 店舗マスタID
                'name' => $arr['name'], // 名称
                'short_name' => $arr['short_name'], // 略称
                'furigana' => $arr['furigana'], // フリガナ
                'tel_no' => $arr['tel_no'], // 電話番号
                'fax_no' => $arr['fax_no'], // FAX番号
                'post_no' => $arr['post_no'], // 郵便番号
                'prefecture' => $arr['prefecture'], // 都道府県
                'city' => $arr['city'], // 市区町村
                'address' => $arr['address'], // 地名・番地
                'building_name' => $arr['building_name'], // 建築名等
                'valid_flag' => $arr['is_valid'], // 有効フラグ
                'free_dial' => $arr['free_dial'], // フリーダイヤル
                'logo' => $arr['logo'], // ロゴ
            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     * @param $request
     * @return mixed
     */
    public static function create_data($request)
    {
        $store = new MStore();
        $store->fill($request->all())->save();

        return $store;
    }

    /**
     * @param $request
     * @param $id
     * @return mixed
     */
    public static function update_data($request, $id)
    {
        $store = MStore::findOrFail($id);
        $store->update($request->all());

        return $store;
    }



}
