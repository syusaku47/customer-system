<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Contracts\Auth\User as Authenticatable;
use DB;
use Throwable;
use Auth;

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

    /**
     * 社員マスタに紐づく店舗マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function store(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(MStore::class, 'store_id', 'id');
    }

    /**
     * 社員マスタに紐づく店舗マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function company(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(MContractCompany::class, 'company_id', 'id');
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
        'order' => 999,
        'role' => 0,
        'status' => 1,
    ];

    protected static $_is_valid = [
        "false" => 0,
        "true" => 1
    ];

    public static function get_is_valid()
    {
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
        'company_id',
        'is_valid',
        'authority1',
        'authority2',
        'authority3',
        'authority4',
        'role',
        'status',
        'expiration',
        'token',
        'order',
    ];


    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'order', // 表示順
        1 => 'employee_cd', // 社員CD
        2 => 'store_name', // 店舗名(s.name)
        3 => 'name', // 社員名称
        4 => 'short_name', //社員略称
        5 => 'furigana', // 社員フリガナ
        6 => 'job_title', // 役職名
        7 => 'sales_target', // 売上目標
        8 => 'valid_flag', // 有効フラグ
        9 => 'authority1', // 権限1
        10 => 'authority4', // 権限2
        11 => 'status', // 0:無効 1:招待中 2:有効
    ];

    /**
     * 社員マスタ情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_list(Request $param)
    {
        // セッションからログインユーザーのcompany_idを取得
        $company_id = session()->get('company_id');
        // 取得項目
        $query = DB::table('m_employees as e')
            ->select(
                'e.order',
                'e.employee_cd',
                'e.company_id',
                'e.store_id',
                's.name as store_name',
                'e.name',
                'e.short_name',
                'e.furigana',
                'e.job_title',
                'e.sales_target',
                'e.is_valid',
                'e.authority1',
                'e.authority4',
                'e.status',
            )->leftjoin('m_stores as s','e.store_id', '=', 's.id')
            ->where('e.company_id', $company_id);



        // 検索条件（where）
        self::set_where($query, $param);
        // ソート条件（order by）
        self::_set_order_by($query, $param->input('sort_by', 0), $param->input('highlow', 0));

        $result = $query->get();
//        dd($result);
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
        // セッションからログインユーザーのcompany_idを取得
//        $company_id = session()->get('company_id');
//
//        $query = $query->where('company_id', $company_id); // 社員のcompany_idのみ

        // 無効情報も含む
        if ($param->input('is_muko') == 0 || !$param->input('is_muko')) {
            $query = $query->where('e.is_valid', 1); // 有効情報のみ
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
                'company_id' => $item->company_id, // 表示順
                'order' => $item->order, // 表示順
                'employee_cd' => $item->employee_cd, // 社員CD
                'store_name' => $item->store_name, // 店舗名
                'name' => $item->name, // 名称
                'short_name' => $item->short_name, // 略称
                'furigana' => $item->furigana, // フリガナ
                'job_title' => $item->job_title, // 役職名
                'sales_target' => $item->sales_target, // 売上目標
                'valid_flag' => ($item->is_valid) ? "true" : "false", // 有効フラグ
                'authority1' => $item->authority1, // 担当外情報操作権限(権限1)
                'authority4' => $item->authority4, // マスタ管理操作権限(権限2)
                'status' => $item->status, // マスタ管理操作権限(権限2)
            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     *
     * @param Request $param ユーザー情報
     * @return Collection $results 整形後データ
     */
    public static function get_user_format_column(Request $param): Collection
    {
        $user = $param->user();

        $results = new Collection();
            $data = [
                'company_id' => $user->company_id, // 表示順
                'id' => $user->id, // 社員ID
                'name' => $user->name, // 名称
                'store_id' => $user->store_id, // 表示順
                'employee_cd' => $user->employee_cd, // 社員CD
                'short_name' => $user->short_name, // 略称
                'furigana' => $user->furigana, // フリガナ
                'job_title' => $user->job_title, // 役職名
                'sales_target' => $user->sales_target, // 売上目標
                'mail_address' => $user->mail_address, // メールアドレス
                'authority1' => $user->authority1, // 担当外情報操作権限(権限1)
                'authority4' => $user->authority4, // マスタ管理操作権限(権限2)
                'store_name' => $user->store->name, // 店舗名
                'company_name' => $user->company->name, // 会社名
                'valid_flag' => ($user->is_valid) ? "true" : "false", // 有効フラグ
//                'order' => $user->order, // 表示順
//                'status' => $user->status, // マスタ管理操作権限(権限2)
            ];
            $results->push($data);

        return $results;
    }

    /**
     * 社員マスタ情報保存（登録・更新）
     *
     * @param Request $param
     * @param int|null $id
     * @return collection
     */
    public static function upsert(Request $param, int $id = null)
    {
        try {

//            $sql  = <<< SQL
//            SELECT
//                   COUNT(*)
//             FROM ( m_employees LEFT JOIN m_stores ON m_employees.store_id = m_stores.id )
//             LEFT JOIN m_contract_companies ON m_employees.company_id = m_contract_companies.id
//        SQL;
//
//            $ids = DB::select($sql);

//        全パラメータ取得
            $arr = $param->all();

//        セッションからログインユーザーのcompany_idを取得
            $company_id = session()->get('company_id');

            //            重複チェック
            $tmp = MEmployee::where('company_id',$company_id)
                ->where("name", $arr['name'])->first();
            if (self::isRepeatName($tmp, $id)) {
                return ["code" => 'err_name'];
            }

//            トランザクション
            DB::beginTransaction();

                if ($id) {
                    // 更新
                    $instance = MEmployee::where('id', $id)->where( 'company_id' , $company_id)->first();
                    if (is_null($instance)) {
                        return ["code" => '404'];
                    }

                    //                    アカウント数チェック
                    $results = MEmployee::where('company_id', $company_id)->where('is_valid', 1);
                    $count = $results->count();
//                    dd($instance);
                    $company = MContractCompany::where('id',$company_id)->first();

                    if($company->accounts <= $count && $arr['is_valid'] && !$instance->is_valid){
                        return ["code" => 'account_over'];
                    }

                    if ($arr['new_password'] == $arr['confirm_password']) {
                        $instance->password = isset($arr['new_password']) ? $arr['new_password'] : null;
                    }
                    // 更新処理
                    $instance->fill($arr)->update();

                } else {

//                    アカウント数チェック
                    $results = MEmployee::where('company_id', $company_id)->where('is_valid', 1);
                    $count = $results->count();
                    $company = MContractCompany::where('id',$company_id)->first();

                    if($company->accounts <= $count && $arr['is_valid']){
                        return ["code" => 'account_over'];
                    }

                    // 登録
                    $instance = new MEmployee();
                    $instance->company_id = $company_id;

//                    idの最大値+1をそれぞれDBに格納
                    $instance->id = MEmployee::max('id') + 1;

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

    private static function isRepeatName($instance, $id)
    {

//        同じIDでないのは重複とみなす
        if ($instance)
            if ($instance->id !== $id)
                return true;

        return false;
    }




//    /**
//     * @param $request
//     * @return collection $employee
//     */
//    public static function create_data($request)
//    {
//        $employee = new MEmployee();
//        $employee = static::check_passwd_and_is_valid($request, $employee);
//        $employee->save();
//        return $employee;
//    }
//
//
//    /**
//     * @param $request
//     * @param $id
//     * @return mixed
//     */
//    public static function update_data($request, $id)
//    {
//        $employee = MEmployee::findOrfail($id);
//        $employee = static::check_passwd_and_is_valid($request, $employee);
//        $employee->update();
//        return $employee;
//    }
//
//    /**
//     * @param $request
//     * @param $instance
//     * @return mixed
//     */
//    public static function check_passwd_and_is_valid($request, $instance)
//    {
//        $instance->fill($request->all());
//        if ($request->new_password == $request->confirm_password) {
//            $instance->password = isset($request->new_password) ? $request->new_password : null;
//        }
//        $is_valid = static::get_is_valid();
//        $instance->is_valid = ($request->is_delete) ? $is_valid["false"] : $is_valid["true"];
//        return $instance;
//    }

}
