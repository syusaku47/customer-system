<?php

namespace App\Models;

use App\Libraries\CommonUtility;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use DB;
use Throwable;


class MContractCompany extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（m_contract_companies）
    // 主キーのデフォルト名はid
    // 主キーはデフォルトではINT型のAuto Increment

    /**
     * モデルの属性のデフォルト値
     *
     * @var array
     */
    protected $attributes = [
        'is_valid' => 1,
    ];


    /**
     * 契約マスタに紐づく社員マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function employee(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MEmployee::class, 'id', 'employee_id');
    }

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
        'id',
        'name',
        'employee_id',
        'accunts_m',
        'prefix',
        'accounts',
        'operation_year',
        'operation_month',
        'datetime_ins',
        'capacity',
        'tel_no',
        'post_no',
        'prefecture',
        'city',
        'address',
        'building_name',
        'status',
        'authority1',
        'authority2',
        'authority3',
        'authority4',
        'authority5',
        'is_valid',
    ];

    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'id', // ID
        1 => 'name', // 会社_名称
        2 => 'mail_address', // 会社_メールアドレス（アドミン用）
        3 => 'tel_no', // 会社_メールアドレス（アドミン用）
        4 => 'post_no', // 住所_郵便番号
        5 => 'prefecture', // 住所_都道府県
        6 => 'city', // 住所_市区町村
        7 => 'address', // 住所_地名・番地
        8 => 'building_name', // 住所_建築名等
        9 => 'status', // ステータス（有償／無償）
        10 => 'accounts', // アカウント数
        11 => 'capacity', // ファイル容量（単位GB）
        12 => 'employee_status', // 状態（有効／無効or招待中）
        13 => 'is_valid', // 有効フラグ
    ];

    /**
     * 契約会社情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed $query 取得クエリー
     * @throws Exception
     */
    public static function search_list(Request $param)
    {
        // 取得項目
        $query = DB::table('m_contract_companies as c')
            ->select(
                'c.id',
                'c.employee_id',
                'e.mail_address',
                'c.name',
                'c.accunts_m',
                'c.prefix',
                'c.accounts',
                'c.operation_year',
                'c.operation_month',
                'c.datetime_ins',
                'c.capacity',
                'c.tel_no',
                'c.post_no',
                'c.prefecture',
                'c.city',
                'c.address',
                'c.building_name',
                'c.status',
                'e.status as employee_status',
                'c.authority1',
                'c.authority2',
                'c.authority3',
                'c.authority4',
                'c.authority5',
                'c.is_valid',
            )->leftjoin('m_employees as e', 'c.employee_id', '=', 'e.id');



//        // 検索条件（where）
        self::set_where($query, $param);
//        // ソート条件（order by）
        self::set_order_by($query, $param->input('sort_by', 0), $param->input('highlow', 0));
        $result = $query->get();

        if ($result->count() == 0) {
            return $result;
        }

        // 取得結果整形
        return self::get_format_column($result);
    }


    private static function set_order_by(&$query, int $order_column_id = null, int $sort_id = null)
    {
        if (is_null($order_column_id) and is_null($sort_id)) {
            // 未指定の場合、顧客IDの昇順
            $query->orderBy(self::SORT_BY_COLUMN[1], ModelBase::SORT_KIND[0]);
        } else {
            $query->orderBy(self::SORT_BY_COLUMN[$order_column_id], ModelBase::SORT_KIND[$sort_id]);
//            $query->sortBy(self::SORT_BY_COLUMN[$order_column_id])->values();
        }
    }

    /**
     * 検索条件設定
     *
     * @param $query
     * @param Request $param 検索パラメータ
     */
    private static function set_where(&$query, Request $param)
    {
        // マスタ管理用
        // 無効情報も含む
        if ($param->input('is_muko') == 0 || !$param->input('is_muko')) {
            $query = $query->where('c.is_valid', 1); // 有効情報のみ
        }

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
                'id' => $item->id, // 社員マスタID
                'name' => $item->name, //
                'mail_address' => $item->mail_address, //メールアドレス(管理者)
                'tel_no' => $item->tel_no, //電話番号
                'post_no' => $item->post_no, //郵便番号
                'prefecture' => ModelBase::PREFECTURE[$item->prefecture], //都道府県
                'city' => $item->city, //市区町村
                'address' => $item->address, //地名・番地
                'building_name' => $item->building_name, //建築名等
                'status' => $item->status, //ステータス（有償／無償）
                'employee_status' => $item->employee_status, //ステータス（招待状態）
                'accounts' => $item->accounts, //アカウント数
                'capacity' => $item->capacity, //容量
                'authority1' => $item->authority1, // 権限
                'authority2' => $item->authority2, // 権限2
                'authority3' => $item->authority3, // 権限3
                'authority4' => $item->authority4, // 権限4
                'authority5' => $item->authority5, // 権限5
                'valid_flag' => $item->is_valid, // 有効フラグ
            ];
            $results->push($data);
        }

        return $results;
    }


    /**
     * 契約会社情報保存（登録・更新）
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

            //            重複チェック
            $tmp = MContractCompany::where("name", $arr['name'])->first();
            if (self::isRepeatName($tmp, $id)) {
                return ["code" => 'err_name'];
            }

//            トランザクション
            DB::beginTransaction();
                if ($id) {

                    // 更新
                    $instance = MContractCompany::find($id);
                    if (is_null($instance)) {
                        return ["code" => '404'];
                    }

                    // 更新処理
                    $instance->fill($arr)->update();
                } else {
                    // 登録
                    $instance = new MContractCompany();

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
