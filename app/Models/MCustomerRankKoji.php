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

/**
 * Class MCustomerRankKoji<br>
 * 顧客ランク（工事金額）マスタ
 *
 * @package App\Models
 */
class MCustomerRankKoji extends ModelBase
{
    use HasFactory;

//    use HasCompositePrimaryKey;


    // テーブル名はクラスの複数形のスネークケース（m_customer_rank_kojis）
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
     * ホワイトリスト
     *
     * @var string[]
     */
    protected $fillable = [
        'company_id',
        'internal_id',
        'name',
        'abbreviation',
        'text_color',
        'background_color',
        'amount',
        'is_valid',
        'order',
    ];

    protected $attributes = [
        'is_valid' => 1,
        'order' => 999,
    ];

    /**
     * 顧客ランク（工事金額）マスタに紐づく顧客ランク（最終完工日）マスタ取得（1対多）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rank_last(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MCustomerRankLastCompletion::class, 'customer_rank_last_completions_id', 'id');
    }

    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'order', // 表示順
        1 => 'internal_id', // 表示ID
        2 => 'name', // 顧客ランク名称
        3 => 'abbreviation', // 顧客ランク略称
        4 => 'amount', // 工事金額
        5 => 'background_color', // 背景色
        6 => 'text_color', // 文字色
        7 => 'is_valid', // 有効フラグ
    ];

    /**
     * 顧客ランク（工事金額）マスタ情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_list(Request $param)
    {
//      セッションからログインユーザーのcompany_idを取得
        $company_id = session()->get('company_id');

        // 取得項目
        $query = MCustomerRankKoji::select(
            'id',
            'internal_id',
            'company_id',
            'name',
            'abbreviation',
            'order',
            'text_color',
            'background_color',
            'amount',
            'is_valid',
        )->where('company_id', $company_id);

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
     * 顧客ランクマスタ情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_customer_rank_list(Request $param, $mode = null)
    {
        //      セッションからログインユーザーのcompany_idを取得
        $company_id = session()->get('company_id');

        // 取得項目
        $query = MCustomerRankKoji::select(
            'id',
            'name',
            'abbreviation',
            'order',
            'amount',
        )->where('company_id', $company_id);

        $query2 = MCustomerRankLastCompletion::select(
            'id',
            'name',
            'abbreviation',
            'order',
            'date',
        )->where('company_id', $company_id);

//        )->with(['rank_last' => function($q) {
//            $q->select('customer_rank_last_completions_id', 'name', 'abbreviation', 'date');
//        }]); // 顧客ランク（最終完工日）マスタ


        // 検索条件（where）
        self::set_where($query, $param);
        self::set_where($query2, $param);

        // ソート条件（order by）
        self::_set_order_by($query, $param->input('sort_by', 0), $param->input('highlow', 0));
        self::_set_order_by($query2, $param->input('sort_by', 0), $param->input('highlow', 0));

        $result = $query->get();
        $result2 = $query2->get();
//        dd($result2);

//        if ($result->count() == 0) {
//            return $result;
//        }
//        顧客ランク自動更新用
        if ($mode == 'autorank') {
            $data['kojis'] = $result;
            $data['lasts'] = $result2;

            return $data;
        }

        // 取得結果整形
        return self::get_format_customer_rank_column($result, $result2);
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
            // 未指定の場合、顧客ランク_工事金額マスタIDの昇順
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
                'name' => $item->name, // 顧客ランク名称
                'abbreviation' => $item->abbreviation, // 顧客ランク略称
                'text_color' => $item->text_color, // 文字色
                'background_color' => $item->background_color, // 背景色
                'amount' => $item->amount, // 工事金額
                'order' => $item->order, // 顧客見込みランク順位
                'valid_flag' => ($item->is_valid) ? true : false, // 有効フラグ
            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     * DB取得結果整形（顧客ランク一覧取得用）<br>
     * レスポンスの形に合わせ整形し、コレクションで返却
     *
     * @param $collection
     * @param $collection2
     * @return Collection $results 整形後データ
     */
    private static function get_format_customer_rank_column($collection, $collection2): Collection
    {
        $results = new Collection();
        $i = 0;
        foreach ($collection as $item) {
            foreach ($collection2 as $item2) {
                $data = [
                    'koji_id' => $item->id, // 顧客ランク_工事金額マスタID
                    'last_completions_id' => $item2->id, // 顧客ランク_最終完工日マスタID
                    'name' => $item->name . $item2->name, // 顧客ランク名称（顧客ランク（工事金額）マスタの顧客ランク名称 + 顧客ランク（最終完工日）マスタの顧客ランク名称）
                    'abbreviation' => $item->abbreviation, // 顧客ランク略称（工事金額マスタ）
                    'abbreviation2' => $item2->abbreviation, // 顧客ランク略称（最終完工日マスタ）
                    'koji_order' => $item->order, // 顧客ランク_工事金額順位
                    'last_completions_order' => $item2->order, // 顧客ランク_最終完工日順位
//                    'text_color' => $item->text_color, // 文字色（工事金額マスタ）
//                    'background_color' => $item->background_color, // 背景色（工事金額マスタ）
                ];
                $results->push($data);
                $i++;
            }
        }

        return $results;
    }

    /**
     * 顧客見込みランク情報保存（登録・更新）
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

            //            ランク名、略式表示の重複チェック
            $tmp = MCustomerRankKoji::where('company_id', $company_id)
                ->where(function ($q) use ($arr) {
                    $q->where("name", $arr['name'])
                        ->orWhere("abbreviation", $arr['abbreviation']);
                })
                ->first();
            if (self::isRepeatName($tmp, $id)) {
                return ["code" => 'err_name'];
            }

//            トランザクション
            DB::beginTransaction();

            if ($id) {
                // 更新
                $instance = MCustomerRankKoji::find($id);
                if (is_null($instance)) {
                    return ["code" => '404'];
//                    ログインユーザーのcompany_idと一致しているか
                } elseif ($instance->company_id != $company_id) {
                    return ["code" => '403'];
                }

                // 更新処理
                $instance->fill($arr)->update();

//                順位変更
                self::rankOrderObj('amount');

            } else {


                // 登録
                $instance = new MCustomerRankKoji();
                $instance->company_id = $company_id;

//                    idの最大値+1をそれぞれDBに格納
//                内部ID(internal_id)の最大値取得
                $max_internal_id = MCustomerRankKoji::where('company_id', $company_id)->max('internal_id');
//                internal_idの最大値+1をそれぞれDBに格納
                $instance->internal_id = $max_internal_id ? ($max_internal_id + 1) : 1;

                // 登録処理
                $instance->fill($arr)->save();

//                順位変更
                self::rankOrderObj('amount');

            }
            DB::commit();
            return ["code" => ""];

        } catch (Throwable $e) {
            DB::rollback();
            \Log::debug($e);
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

    /*
     * オブジェクト順位並び替えupdate
     */
    private static function rankOrderObj($order_column)
    {
//          セッションからログインユーザーのcompany_idを取得
        $company_id = session()->get('company_id');

        $ranks = MCustomerRankKoji::where('company_id', $company_id)
            ->orderBy($order_column, 'ASC')
            ->get();

        foreach ($ranks as $key => $rank) {
            $rank->order = $key + 1;
            $rank->update();
        }
    }


    public static function auto_update(Request $param)
    {

        $mode = 'autorank';
        $data = self::search_customer_rank_list($param, $mode);


        $kojis = $data['kojis'];
        $lasts = $data['lasts'];
        if (is_null($data)) {
            return ["code" => '400'];
        }

        $ranklist = [];
        $kojiIds = [];
        $lastIds = [];
        foreach ($kojis as $koji) {
            foreach ($lasts as $last) {
                $kojiIds[$koji->name] = $koji->id; //顧客ランク（工事金額）の名称にIDを代入
                $lastIds[$last->name] = $last->id; //顧客ランク（最終完工日）の名称にIDを代入
            }
        }

//       kojiIds => [key=name value=id]格納
        $ranklist['kojiIds'] = $kojiIds;
        $ranklist['lastIds'] = $lastIds;
        $data['kojis'] = $kojis;
        $data['lasts'] = $lasts;


        $count = DB::table('t_customers')->count();

        if ($count == 0) {
            return ['code' => ""];
        }

        $perPage = 10;
        $offset = 0;
        $loop = 0;
        $index = 0;

//        while (1) {
//            unset($customer);
            $customer = TCustomer::getCsvSearchResultList($perPage, $offset);

////            自動ランク付け失敗しました。
//
//            //無くなったら終わり
//            if(!is_array($customer)) {
//                dd($customer);
//                break;
//            }

            foreach ($customer as $key => $val) {
//                //ランク設定
                $rank_x = null;
                $rank_y = null;

//            完工日が空なら飛ばし
                if ($val->completion_date == "") {

                    continue;
                }

                //工事金額ランク更新
                foreach ($kojis as $koji) {
                    $rank_y = ($val->jyutyu_kin_all >= $koji->amount) ? $koji->name : null;
                    if (isset($rank_y)) {
                        break;
                    }
                }

//                //現在
                $nowYear = date('Y');
                $nowMonth = date('m');
                $nowDay = date('d');


//           最終完工日ランク更新
                foreach ($lasts as $last) {
//                何日前か？
                    $ago = date("Y-m-d", mktime(0, 0, 0, $nowMonth, $nowDay - $last->date, $nowYear));
                    $rank_x = ($val->completion_date != "" && $val->completion_date > $ago) ? $last->name : null;
                    if (isset($rank_x)) {
                        break;
                    }
                }


//            DBに格納されているIDと異なるか
                if ($val->rank_koji != $ranklist['kojiIds'][$rank_y] or $val->rank_last != $ranklist['lastIds'][$rank_x]) {
//                    unset($r_param);
                    $r_param = array(
                        'id' => $val->id,
                        'rank_koji' => $ranklist['kojiIds'][$rank_y],
                        'rank_last' => $ranklist['lastIds'][$rank_x],
                    );

                    $res = TCustomer::updateRank($r_param);
                    if ($res['code'] == 'fail') {
                        return ["code" => 'fail'];
                    }


//            ランク更新

//            必要情報取得
                    $rank_before = $val->koji_name . $val->rank_last;
                    $rank = $rank_y . $rank_x;
                    $rank_after = $rank;
                    $datetime_upd = date('Y-m-d');

//           新カラムに詰める
                    $l_param = array(
                        'customer_id' => $val->id,
                        'customer_name' => $val->name,
                        'sales_contact' => $val->employee_name,
                        'customer_rank_before_change' => $rank_before,
                        'customer_rank_after_change' => $rank_after,
                        'total_work_price' => floor($val->jyutyu_kin_all),
//                'total_work_price' => floor($val->kouji_kin),
                        'total_work_times' => 10,
//                'total_work_times' => $val->kouji_count,
                        'last_completion_date' => $val->completion_date ? $val->completion_date : NULL,
                        'updated_date' => $datetime_upd,
                    );

                    $res = TCustomerRankLog::addRankLog($l_param);

                    if ($res['code'] == 'fail') {
                        return ["code" => 'fail'];
                    }
                }

                $offset = $perPage * (++$loop);
            }


////*/
/////*
//                    $log[$index]['customer_id'] = $val['id'];
//                    $log[$index]['customer_name'] = $val['name'];
//                    $log[$index]['employee_name'] = $val['employee_name'];
//                    $log[$index]['rank_before'] = $val['customerrank_cd'];
//                    $log[$index]['rank_after'] = $rank;
//                    $log[$index]['kouji_kin'] = floor($val['kouji_kin']);
//                    $log[$index]['kouji_count'] = $val['kouji_count'];
//                    $log[$index]['saisyu_kankou_dt'] = $val['saisyu_kankou_dt'] ? $val['saisyu_kankou_dt'] : NULL;
//                    $log[$index]['datetime_upd'] = $datetime_upd;
//                    $index++;
//                }
//            }
//
//        }
        return ['code' => ""];

//        //次へ
//        //$offset++;
//        $offset = $perPage * (++$loop);

    }

}
