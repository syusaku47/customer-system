<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Contracts\Auth\User as Authenticatable;

/**
 * Class MEmployee<br>
 * 社員マスタ
 *
 * @package App\Models
 */
class MEmployee extends Authenticatable
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（m_employees）
    // 主キーのデフォルト名はid
    // 主キーはデフォルトではINT型のAuto Increment
    // デフォルトではタイムスタンプを自動更新（created_at、updated_atを生成）
    // デフォルトの接続データベースは .env の DB_CONNECTION の定義内容


    /**
     * 社員マスタに紐づく店舗マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function store(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MStore::class, 'id', 'store_id');
    }

    /**
     * モデルにタイムスタンプを付けるか
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


/**
     * モデルの属性のデフォルト値
     *
     * @var array
     */
    protected $attributes = [
        'is_valid' => 1,
        'authority1' => 0,
        'authority2' => 0,
        'authority3' => 0,
        'authority4' => 0,
    ];

    protected static $_is_valid = [
      "false" => 0,
      "true" => 1
    ];

    public static function get_is_valid(){
        return static::$_is_valid;
    }

    /**
     * ホワイトリスト
     *
     * @var string[]
     */
    protected $fillable = [
        'employee_cd',
        'password',
        'store_id',
        'name',
        'short_name',
        'furigana',
        'job_title',
        'mail_address',
        'sales_target',
        'is_valid',
        'authority1',
        'authority2',
        'authority3',
        'authority4',
    ];

//    /**
//     * 社員マスタに紐づく店舗マスタ取得（1対多）
//     *
//     * @return \Illuminate\Database\Eloquent\Relations\HasOne
//     */
//    public function store(): \Illuminate\Database\Eloquent\Relations\HasOne
//    {
//        return $this->belongsTo(App\Models\Store::class, 'id', 'item_kubun');
//    }

    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'id', // 社員マスタID
        1 => 'employee_cd', // 社員CD
        2 => 'store_id', // 店舗ID
        3 => 'name', // 名称
        4 => 'short_name', // 略称
        5 => 'furigana', // フリガナ
        6 => 'job_title', // 役職名
        7 => 'sales_target', // 売上目標
        8 => 'valid_flag', // 有効フラグ
        9 => 'authority1', // 権限
        10 => 'authority2', // 権限2
        11 => 'authority3', // 権限3
        12 => 'authority4', // 権限4
    ];

    /**
     * 社員マスタ情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_list(Request $param)
    {
        // 取得項目
        $query = MEmployee::select(
            'id',
            'employee_cd',
            'store_id',
            'name',
            'short_name',
            'furigana',
            'job_title',
            'sales_target',
            'is_valid',
            'authority1',
            'authority2',
            'authority3',
            'authority4',
            )->with(['store' => function($q) {
                $q->select('name', 'id')->where('is_valid', 1);
            }]); // マスタ

        // 検索条件（where）
        self::set_where($query, $param);
        // ソート条件（order by）
        self::_set_order_by($query, $param->input('sort_by', 3), $param->input('highlow', 0));

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
            $query->orderBy(self::SORT_BY_COLUMN[3], ModelBase::SORT_KIND[0]);
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
                'id' => $arr['id'], // 社員マスタID
                'employee_cd' => $arr['employee_cd'], // 社員CD
                'store_name' => $arr['store']['name'], // 店舗名
                'name' => $arr['name'], // 名称
                'short_name' => $arr['short_name'], // 略称
                'furigana' => $arr['furigana'], // フリガナ
                'job_title' => $arr['job_title'], // 役職名
                'sales_target' => $arr['sales_target'], // 売上目標
                'valid_flag' => $arr['is_valid'], // 有効フラグ
                'authority1' => $arr['authority1'], // 権限
                'authority2' => $arr['authority2'], // 権限2
                'authority3' => $arr['authority3'], // 権限3
                'authority4' => $arr['authority4'], // 権限4
            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     * @param $request
     * @return collection $employee
     */
    public static function create_data($request){
        $employee = new MEmployee();
        $employee = static::check_passwd_and_is_valid($request,$employee);
        $employee->save();
        return $employee;
    }


    /**
     * @param $request
     * @param $id
     * @return mixed
     */
    public static function update_data($request, $id){
        $employee = MEmployee::findOrfail($id);
        $employee = static::check_passwd_and_is_valid($request,$employee);
        $employee->update();
        return $employee;
    }

    /**
     * @param $request
     * @param $instance
     * @return mixed
     */
    public static function check_passwd_and_is_valid($request, $instance){
        $instance->fill($request->all());
        if($request->new_password == $request->confirm_password){
            $instance->password = isset($request->new_password)?$request->new_password:null;
        }
        $is_valid =  static::get_is_valid();
        $instance->is_valid = ($request->is_delete) ? $is_valid["false"] : $is_valid["true"] ;
        return $instance;
    }

}
