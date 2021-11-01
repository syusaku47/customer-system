<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use DB;
use Throwable;
use Auth;

/**
 * Class TCustomerRankLog<br>
 * 顧客ランク自動更新ログデータ
 *
 * @package App\Models
 */
class TCustomerRankLog extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（t_customer_rank_logs）
    // 主キーのデフォルト名はid
    // 主キーはデフォルトではINT型のAuto Increment
    // company_id, internal_idはユニークキー

    /* モデルにタイムスタンプを付けるか
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
        'internal_id',
        'company_id',
        'customer_id',
        'customer_name',
        'sales_contact',
        'customer_rank_before_change',
        'customer_rank_after_change',
        'total_work_price',
        'total_work_times',
        'last_completion_date',
        'updated_date',
    ];

    /**
     * 対応履歴と紐づく顧客データ取得（1対多（逆））
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TCustomer::class);
    }

    /**
     * 対応履歴と紐づく案件データ取得（1対多（逆））
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TProject::class);
    }

    /**
     * 対応担当店舗（対応履歴）に紐づく店舗マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function store_supported_rep(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MStore::class, 'id', 'supported_responsible_store');
    }

    /**
     * 対応担当担当者（対応履歴）に紐づく社員マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function employee_supported_rep(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MEmployee::class, 'id', 'supported_representative');
    }

    /**
     * 対応者に紐づく社員マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function employee_supported(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MEmployee::class, 'id', 'supported_person');
    }

    /**
     * 対応履歴に紐づく対応履歴マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function supported_history(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MCategory::class, 'id', 'category');
    }

    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'ts.is_fixed', // 対応済フラグ
        1 => 'ts.reception_time', // 受付日時
        2 => 'ts.category', // カテゴリ
        3 => 'tc.name', // 顧客名
        4 => 'tp.project_representative', // 案件担当者
        5 => 'ts.supported_person', // 対応者
        6 => 'ts.supported_complete_date', // 対応完了日
    ];

    /**
     * ソート用カラム定義（顧客・案件詳細画面タブ内の対応履歴情報絞り込み検索時用）
     *
     * @var string[]
     */
    protected const SORT_BY_DETAIL_COLUMN = [
        0 => 'ts.is_fixed', // 対応済フラグ
        1 => 'tp.name', // 案件名
        2 => 'ts.reception_date, ts.supported_date', // 受付日、対応日（日付日時）
        3 => 'ts.category', // カテゴリ
        4 => 'ts.supported_history_name', // 件名（対応履歴名）
        5 => 'ts.supported_person', // 対応者
        6 => 'ts.supported_complete_date', // 対応完了日
        7 => 'tc.id', // 顧客ID
        8 => 'tp.id', // 案件ID
    ];

    /**
     * 対応履歴情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     * @throws Exception
     */
    public static function search_list(Request $param)
    {
        // 取得項目
        $query = TSupport::select(
        // 対応履歴データ
            'ts.customer_id as ts_customer_id',
            'ts.project_id as ts_project_id',
            'ts.id as ts_id',
            'ts.is_fixed as ts_is_fixed',
            'ts.reception_time as ts_reception_time',
            'ts.category as ts_category',
            'ts.supported_person as ts_supported_person',
            'ts.supported_complete_date as ts_supported_complete_date',
            'ts.supported_history_name as ts_supported_history_name',
            'ts.reception_date as ts_reception_date',
            'ts.supported_date as ts_supported_date',
            // 顧客データ
            'tc.name as tc_name',
            'tc.furigana as tc_furigana',
            // 案件データ
            'tp.name as tp_name',
            'tp.project_representative as tp_project_representative',
            // 対応履歴マスタ
            'ms.supported as ms_supported',
            // 社員マスタ
            'me.name as project_representative_name',
            'me2.name as supported_person_name',
        )->distinct()->from('t_supports as ts')
            ->join('t_customers as tc', 'ts.customer_id', '=', 'tc.id') // 顧客データ
            ->leftjoin('t_projects as tp', 'ts.project_id', '=', 'tp.id') // 案件データ
            ->leftjoin('m_supports as ms', 'ts.category', '=', 'ms.id') // 対応履歴マスタ
            ->leftjoin('m_employees as me', 'tp.project_representative', '=', 'me.id') // 案件担当者
            ->leftjoin('m_employees as me2', 'ts.supported_person', '=', 'me2.id'); // 対応者

        // 検索条件（where）
        self::set_where_join($query, $param); // 対応履歴
        self::set_where_customer_join($query, $param); // 顧客
        self::set_where_project_join($query, $param); // 案件
        // ソート条件（order by）
        if ($param->filled('sort_by')) {
            self::_set_order_by($query, $param->input('sort_by', 1), $param->input('highlow', 0), 1);
        } else {
            self::_set_order_by($query, $param->input('filter_by', 2), $param->input('highlow', 0), 2);
        }
        if ($param->filled('limit')) {
            // オフセット条件（offset）
            $query->skip($param->input('offset', 0));
            // リミット条件（limit）
            $query->take($param->input('limit'));
        }

        $result = $query->get();
        if ($result->count() == 0) {
            return $result;
        }

        // 取得結果整形
        return self::get_format_column($result);
    }

    /**
     * 検索条件設定（JOIN時用）
     *
     * @param $query
     * @param Request $param 検索パラメータ
     * @throws Exception
     */
    public static function set_where_join(&$query, Request $param)
    {
        // 簡易検索用
        // 受付日
        if ($param->filled('reception_date')) {
            $query = $query->whereDate('ts.reception_date', $param->input('reception_date'));
        }
        // カテゴリ
        if ($param->filled('category')) {
            $query = $query->where('ts.category', $param->input('category'));
        }
        // 対応区分
        if ($param->filled('supported_kubun') && $param->input('supported_kubun') != 1) {
            $query = $query->where('ts.supported_kubun', $param->input('supported_kubun'));
        }
        // 文字列検索
        if ($param->filled('word')) {
            $query = $query->where(function ($q) use ($param) {
                $q->where('ts.supported_history_name', 'like', '%' . $param->input('supported_history_name') . '%') // タイトル（対応履歴名）
                ->orWhere('ts.detail', 'like', '%' . $param->input('detail') . '%') // 詳細内容
                ->orWhere('ts.supported_content', 'like', '%' . $param->input('supported_content') . '%'); // 対応内容
            });
        }
        // キーワード検索
        if ($param->filled('sp_word')) {
            $query = $query->where(function ($q) use ($param) {
                $q->where('ts.supported_history_name', 'like', '%' . $param->input('sp_word') . '%'); // 対応履歴名
                $q->orWhereDate('ts.supported_date', $param->input('sp_word')); // 対応日時
            });
        }

        // 詳細検索用
        // 対応日
        if ($param->filled('supported_date')) {
            $query = $query->whereDate('ts.supported_date', $param->input('supported_date'));
        }
        // 対応担当店舗
        if ($param->filled('supported_responsible_store')) {
            $query = $query->where('ts.supported_responsible_store', $param->input('supported_responsible_store'));
        }
        // 対応担当担当者
        if ($param->filled('supported_representative')) {
            $query = $query->where('ts.supported_representative', $param->input('supported_representative'));
        }
        // 日付日時開始～終了
        $sy = $param->filled('date_time_start_year') ? $param->input('date_time_start_year') : date('Y');
        $sm = $param->filled('date_time_start_month') ? $param->input('date_time_start_month') : date('m');
        $sd = $param->filled('date_time_start_date') ? $param->input('date_time_start_date') : date('d');
        $s_ymd = $sy . '/' . $sm . '/' . $sd;
        $ey = $param->filled('date_time_end_year') ? $param->input('date_time_end_year') : date('Y');
        $em = $param->filled('date_time_end_month') ? $param->input('date_time_end_month') : date('m');
        $ed = $param->filled('date_time_end_date') ? $param->input('date_time_end_date') : date('d');
        $e_ymd = $ey . '/' . $em . '/' . $ed;

        if ($param->filled('date_time_start_year')
            && $param->filled('date_time_start_month')
            && $param->filled('date_time_start_date')
            && $param->filled('date_time_end_year')
            && $param->filled('date_time_end_month')
            && $param->filled('date_time_end_date')) {
            $query = $query->where(function ($q) use ($s_ymd, $e_ymd) {
                // 受付日開始、終了の期間
                $q->where('ts.reception_date', '>=', new \DateTime($s_ymd))
                    ->where('ts.reception_date', '<=', new \DateTime($e_ymd));
            })->orWhere(function ($q) use ($s_ymd, $e_ymd) {
                // 対応日開始、終了の期間
                $q->where('ts.supported_date', '>=', new \DateTime($s_ymd))
                    ->where('ts.supported_date', '<=', new \DateTime($e_ymd));
            });
        } else if (($param->filled('date_time_start_year')
                && $param->filled('date_time_start_month')
                && $param->filled('date_time_start_date'))
            && (is_null($param->input('date_time_end_year'))
                || is_null($param->input('date_time_end_month'))
                || is_null($param->input('date_time_end_date')))) {
            // 受付日_開始以降、対応日_開始以降
            $query = $query->where('ts.reception_date', '>=', new \DateTime($s_ymd))
                ->orWhere('ts.supported_date', '>=', new \DateTime($s_ymd));
        } else if ((is_null($param->input('date_time_start_year'))
                || is_null($param->input('date_time_start_month'))
                || is_null($param->input('date_time_start_date')))
            && $param->filled('date_time_end_year')
            && $param->filled('date_time_end_month')
            && $param->filled('date_time_end_date')) {
            // 受付日時_終了以前、対応日時_終了以前
            $query = $query->where('ts.reception_date', '<=', new \DateTime($e_ymd))
                ->orWhere('ts.supported_date', '<=', new \DateTime($e_ymd));
        }
        // 件名
        if ($param->filled('subject')) {
            $query = $query->where('ts.supported_history_name', 'like', '%' . $param->input('subject') . '%');
        }
        // 対応者
        if ($param->filled('supported_person')) {
            $query = $query->where('ts.supported_person', $param->input('supported_person'));
        }
        // 対応完了日開始～終了
        $ssy = $param->filled('supported_complete_start_year') ? $param->input('supported_complete_start_year') : date('Y');
        $ssm = $param->filled('supported_complete_start_month') ? $param->input('supported_complete_start_month') : date('m');
        $ssd = $param->filled('supported_complete_start_date') ? $param->input('supported_complete_start_date') : date('d');
        $ss_ymd = $ssy . '/' . $ssm . '/' . $ssd;
        $sey = $param->filled('supported_complete_end_year') ? $param->input('supported_complete_end_year') : date('Y');
        $sem = $param->filled('supported_complete_end_month') ? $param->input('supported_complete_end_month') : date('m');
        $sed = $param->filled('supported_complete_end_date') ? $param->input('supported_complete_end_date') : date('d');
        $se_ymd = $sey . '/' . $sem . '/' . $sed;

        if ($param->filled('supported_complete_start_year')
            && $param->filled('supported_complete_start_month')
            && $param->filled('supported_complete_start_date')
            && $param->filled('supported_complete_end_year')
            && $param->filled('supported_complete_end_month')
            && $param->filled('supported_complete_end_date')) {
            $query = $query->where(function ($q) use ($ss_ymd, $se_ymd) {
                // 対応完了日開始、終了の期間
                $q->where('ts.supported_complete_date', '>=', new \DateTime($ss_ymd))
                    ->where('ts.supported_complete_date', '<=', new \DateTime($se_ymd));
            });
        } else if (($param->filled('supported_complete_start_year')
                && $param->filled('supported_complete_start_month')
                && $param->filled('supported_complete_start_date'))
            && (is_null($param->input('supported_complete_end_year'))
                || is_null($param->input('supported_complete_end_month'))
                || is_null($param->input('supported_complete_end_date')))) {
            // 対応完了日_開始以降、対応完了日_開始以降
            $query = $query->where('ts.supported_complete_date', '>=', new \DateTime($ss_ymd));
        } else if ((is_null($param->input('supported_complete_start_year'))
                || is_null($param->input('supported_complete_start_month'))
                || is_null($param->input('supported_complete_start_date')))
            && $param->filled('supported_complete_end_year')
            && $param->filled('supported_complete_end_month')
            && $param->filled('supported_complete_end_date')) {
            // 対応完了日_終了以前、対応完了日_終了以前
            $query = $query->where('ts.supported_complete_date', '<=', new \DateTime($se_ymd));
        }
        // 対応済みフラグ
        if ($param->filled('is_fixed')) {
            if ($param->input('is_fixed') == 1) {
                $query = $query->where('ts.supported_kubun', 2); // 対応済
            } else {
                $query = $query->where('ts.supported_kubun', 0); // 未対応
            }
        }

        return;
    }

    /**
     * 検索条件設定（顧客情報（JOIN時用））
     *
     * @param $query
     * @param Request $param 検索パラメータ
     */
    public static function set_where_customer_join(&$query, Request $param)
    {
        // 簡易検索
        // キーワード検索
        if ($param->filled('sp_word')) {
            $query = $query->where(function ($q) use ($param) {
                $q->orWhere('tc.name', 'like', '%' . $param->input('sp_word') . '%'); // 顧客名
            });
        }

        // 詳細検索用
        // 顧客名
        if ($param->filled('customer_name')) {
            $query = $query->where('tc.name', 'like', '%' . $param->input('customer_name') . '%');
        }
        // 顧客担当店舗
        if ($param->filled('customer_responsible_store')) {
            $query = $query->where('tc.sales_shop', $param->input('customer_responsible_store'));
        }
        // 顧客担当担当者
        if ($param->filled('customer_representative')) {
            $query = $query->where('tc.sales_contact', $param->input('customer_representative'));
        }

        // 顧客詳細画面内タブ用
        // 顧客ID
        if ($param->filled('customer_id')) {
            $query = $query->where('tc.id', $param->input('customer_id'));
        }

        return;
    }

    /**
     * 検索条件設定（案件情報（JOIN時用））
     *
     * @param $query
     * @param Request $param 検索パラメータ
     * @throws Exception
     */
    public static function set_where_project_join(&$query, Request $param)
    {
        // 簡易検索
        // キーワード検索
        if ($param->filled('sp_word')) {
            $query = $query->where(function ($q) use ($param) {
                $q->orWhere('tp.name', 'like', '%' . $param->input('sp_word') . '%'); // 案件名
            });
        }

        // 詳細検索用
        // 案件名
        if ($param->filled('project_name')) {
            $query = $query->where('tp.name', 'like', '%' . $param->input('project_name') . '%');
        }

        // 案件詳細画面内タブ用
        // 案件ID
        if ($param->filled('project_id')) {
            $query = $query->where('tp.id', $param->input('project_id'));
        }

        return;
    }

    /**
     * ソート条件設定
     *
     * @param $query
     * @param int $order_column_id 並替基準列
     * @param int $sort_id 並替方法
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
     * @param int $filter 対象の並替基準列一覧の選定
     */
    private static function _set_order_by(&$query, int $order_column_id = null, int $sort_id = null, int $filter = 1)
    {
        if (is_null($order_column_id) || is_null($sort_id)) {
            if ($filter == 1) {
                // 未指定の場合、受付日時の昇順
                $query->orderBy(self::SORT_BY_COLUMN[1], ModelBase::SORT_KIND[0]);
            } else {
                // 未指定の場合、受付日、対応日（日付日時）の昇順
                $query->orderBy(self::SORT_BY_DETAIL_COLUMN[2], ModelBase::SORT_KIND[0]);
            }
        } else {
            if ($filter == 1) {
                $query->orderBy(self::SORT_BY_COLUMN[$order_column_id], ModelBase::SORT_KIND[$sort_id]);
            } else {
                if (strpos(self::SORT_BY_DETAIL_COLUMN[$order_column_id], ',') !== false) {
                    $arr = explode(',', self::SORT_BY_DETAIL_COLUMN[$order_column_id]);
                    foreach ($arr as $item) {
                        $query->orderBy(trim($item), ModelBase::SORT_KIND[$sort_id]);
                    }
                } else {
                    $query->orderBy(self::SORT_BY_DETAIL_COLUMN[$order_column_id], ModelBase::SORT_KIND[$sort_id]);
                }
            }
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
                'customer_id' => $arr['ts_customer_id'], // 顧客ID
                'project_id' => $arr['ts_project_id'], // 案件ID
                'id' => $arr['ts_id'], // 対応履歴ID
                'fixed_flag' => $arr['ts_is_fixed'], // 対応済みフラグ
                'reception_time' => $arr['ts_reception_time'], // 受付日時
                'category' => $arr['ms_supported'], // カテゴリ名称
                'customer_name' => $arr['tc_name'], // 顧客名
                'furigana' => $arr['tc_furigana'], // 顧客名フリガナ
                'project_name' => $arr['tp_name'], // 案件名
                'project_representative' => $arr['project_representative_name'], // 案件担当者
                'supported_person' => $arr['supported_person_name'], // 対応者
                'supported_complete_date' => $arr['ts_supported_complete_date'], // 対応完了日
                'supported_history_name' => $arr['ts_supported_history_name'], // 対応履歴名
            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     *  顧客ランクログデータ新規登録
     *
     * @access public
     * @param collection $instance 連想配列
     */
    public static function addRankLog($l_param)
    {
        try {

            DB::beginTransaction();

//            新カラムを詰める
            $columns = array(
                'customer_id',
                'customer_name',
                'sales_contact',
                'customer_rank_before_change',
                'customer_rank_after_change',
                'total_work_price',
                'total_work_times',
                'last_completion_date',
                'updated_date',
            );

            $rank_log = new TCustomerRankLog();

//          セッションからログインユーザーのcompany_idを取得
            $company_id = session()->get('company_id');

            $rank_log->company_id = $company_id;

            //                内部ID(internal_id)の最大値取得
            $max_internal_id = TCustomerRankLog::where('company_id', $company_id)->max('internal_id');

//                    internal_idの最大値+1をそれぞれDBに格納
            $rank_log->internal_id = $max_internal_id ? ($max_internal_id + 1) : 1;

            foreach($columns as $column) {
                if($l_param[$column] != ''){
                    $rank_log->{$column} = $l_param[$column];
                }
            }

            $rank_log->save();
            DB::commit();
            return ["code" => ""];
        } catch (Throwable $e) {
            DB::rollback();
            \Log::debug($e);
//            トランザクションエラー

            return ["code" => 'fail'];
        }

    }
}
