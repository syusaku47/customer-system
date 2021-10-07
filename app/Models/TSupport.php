<?php

namespace App\Models;

use App\Libraries\CommonUtility;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class TSupport<br>
 * 対応履歴データ
 *
 * @package App\Models
 */
class TSupport extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（t_supports）
    // 主キーのデフォルト名はid
    // 主キーはデフォルトではINT型のAuto Increment
    // デフォルトではタイムスタンプを自動更新（created_at、updated_atを生成）
    // デフォルトの接続データベースは .env の DB_CONNECTION の定義内容

    /**
     * ホワイトリスト
     *
     * @var string[]
     */
    protected $fillable = [
        'customer_id',
        'project_id',
//        'company_id', // TODO 後で追加
        'reception_date',
        'supported_kubun',
        'supported_content',
        'detail',
        'supported_date',
        'category',
        'supported_responsible_store',
        'supported_representative',
        'reception_time',
        'supported_history_name',
        'supported_person',
        'supported_complete_date',
        'is_fixed',
        'media',
        'image_name',
        'association',
        'last_updated_by',
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
     * 媒体に紐づく媒体マスタ取得（1対1）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function source_supported(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MSource::class, 'id', 'media');
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
        7 => 'ts.updated_at', // 更新日時
    ];

    /**
     * ソート用カラム定義（顧客・案件詳細画面タブ内の対応履歴情報絞り込み検索時用）
     *
     * @var string[]
     */
    protected const SORT_BY_DETAIL_COLUMN = [
        0 => 'ts.is_fixed', // 対応済フラグ
        1 => 'tp.name', // 案件名
        //2 => 'ts.reception_date, ts.supported_date', // 受付日、対応日（日付日時）
        2 => 'ts.reception_date, ts.supported_complete_date', // 受付日、対応日（日付日時）
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
     * @return mixed $query 取得クエリー
     * @throws Exception
     */
    private static function _search_list(Request $param)
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
            'ts.updated_at as ts_updated_at',
            'ts.image_name as image_name',
            'ts.association as association',
            // 顧客データ
            'tc.id as tc_id',
            'tc.name as tc_name',
            'tc.furigana as tc_furigana',
            // 案件データ
            'tp.id as tp_id',
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

        return $query;
    }

    /**
     * 対応履歴情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return Collection 取得データ
     * @throws Exception
     */
    public static function search_list(Request $param): Collection
    {
        $query = self::_search_list($param);

        $result_all = $query->get();
        if ($result_all->count() == 0) {
            return $result_all;
        }

        // ソート条件（order by）
        if ($param->filled('sort_by')) {
            self::_set_order_by($query, $param->input('sort_by', 1), $param->input('highlow', 0), 1);
        } else {
            self::_set_order_by($query, $param->input('filter_by', 2), $param->input('highlow', 0), 2);
        }
        if ($param->filled('limit')) {
            // オフセット条件（offset）
            $query->skip(($param->input('offset', 0) > 0) ? ($param->input('offset') * $param->input('limit')) : 0);
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
     * 対応履歴情報一覧検索（全件）
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     * @throws Exception
     */
    public static function search_list_count(Request $param)
    {
        $query = self::_search_list($param);

        return $query->get();
    }

    /**
     * 対応履歴情報1件検索
     *
     * @param Request $param 検索パラメータ
     * @param int $id 対応履歴ID
     * @return array|null 取得データ
     */
    public static function search_one(Request $param, int $id): ?array
    {
        // 取得項目
        $query = TSupport::select(
            'customer_id',
            'project_id',
            'id',
            'reception_time',
            'category',
            'media',
            'image_name',
            'supported_content',
            'supported_responsible_store',
            'supported_representative',
            'supported_date',
            'is_fixed',
            'reception_time',
            'supported_history_name',
            'supported_person',
            'supported_complete_date',
            'detail',
            'association',
        )->with(['customer' => function($q) {
            // With()で取得時、リレーション先のID項目の取得が必須
            $q->select('id', 'name', 'sales_shop', 'sales_contact')
            ->with(['employee' => function($q) {
                $q->select('id', 'name')->where('is_valid', 1);
            }]) // 社員マスタ（受付担当担当者）
            ->with(['store' => function($q) {
                $q->select('id', 'name')->where('is_valid', 1);
            }]); // 店舗マスタ（受付担当店舗）
        }]) // 顧客データ
        ->with(['project' => function($q) {
            // With()で取得時、リレーション先のID項目の取得が必須
            $q->select('id', 'name');
        }]) // 案件データ
        ->with(['employee_supported_rep' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 社員マスタ（対応担当担当者）
        ->with(['employee_supported' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 社員マスタ（対応者）
        ->with(['store_supported_rep' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]) // 店舗マスタ（対応担当店舗）
        ->with(['supported_history' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }])
        ->with(['source_supported' => function($q) {
            $q->select('name', 'id')->where('is_valid', 1);
        }]); // 対応履歴マスタ

        $result = $query->find($id);
        if (is_null($result)) {
            return null;
        }

        // 取得結果整形
        return self::get_format_column_one($result);
    }

    /**
     * 対応履歴情報保存（登録・更新）
     *
     * @param Request $param
     * @param int|null $id 対応履歴ID
     * @return string
     */
    public static function upsert(Request $param, int $id = null): string
    {
        $arr = $param->all();

        // DB登録・更新用にパラメータ変換
        // 受付日
        if ($param->filled('reception_time')) {
            $arr['reception_date'] = $param->input('reception_time');
            // 受付日時
            if ($param->filled('reception_hour') && $param->filled('reception_minutes')) {
                $arr['reception_time'] = new Carbon($arr['reception_date'] . ' ' .
                    $param->input('reception_hour') . ':' . $param->input('reception_minutes') . ':00');
            }
        }
        // 対応詳細
        if ($param->filled('supported_detail')) {
            $arr['detail'] = $param->input('supported_detail');
        }

        //対応日カラムと対応完了日の対応
        if ($param->filled('supported_date') && ! $param->filled('supported_complete_date')) {
            $arr['supported_complete_date'] = $param->input('supported_date');
        } else if (! $param->filled('supported_date') && $param->filled('supported_complete_date')) {
            $arr['supported_date'] = $param->input('supported_complete_date');
        } else if (! $param->filled('supported_date') && ! $param->filled('supported_complete_date')) {
            if (!isset($arr['supported_date'])) {
                $arr['supported_date'] = null;
            }
            if (!isset($arr['supported_complete_date'])) {
                $arr['supported_complete_date'] = null;
            }
        }

        //対応区分
        if ($param->filled('is_fixed')) {
            $arr['supported_kubun'] = $param->input('is_fixed');
        } else {
            $arr['supported_kubun'] = 0;
            $arr['is_fixed']        = 0;
        }

        // 最終更新者
        // TODO ログインユーザー名を登録
        $arr['last_updated_by'] = '管理者';

        if ($id) {
            // 更新
            $obj = TSupport::find($id);
            if (is_null($obj)) {
                return '404';
            }

            $file_name = null;
            if ($param->hasFile('image')) {
                $format =  strrpos($obj->image_name, '.') >= 0 ? substr($obj->image_name, strrpos($obj->image_name, '.')) : '';
                // 登録済みの画像を削除
                $file_path = config('filesystems.disks.s3.directory') . "/" . $obj->association . $format;
                Storage::disk('s3')->delete($file_path);

                //$association = str_random();
                $association = Str::uuid();
                // 画像をStorageに保存
                Storage::disk('s3')->putFileAs(
                    config('filesystems.disks.s3.directory'),
                    $param->file('image'),
                    $association . '.' . $param->file('image')->clientExtension(),
                    'public'
                );
                // ファイル名（拡張子を含む）
                $file_name = $param->file('image')->getClientOriginalName();
                // ファイル識別子
                $arr['association'] = $association;
            }
            // 画像名
            $arr['image_name'] = $file_name;

            // 更新処理
            $obj->fill($arr)->save();
        } else {
            // 対応区分
            // 登録時は未対応
            //$arr['supported_kubun'] = 0;

            $file_name = null;
            if ($param->hasFile('image')) {
                //$association = str_random();
                $association = Str::uuid();
                // 画像をStorageに保存
                Storage::disk('s3')->putFileAs(
                    config('filesystems.disks.s3.directory'),
                    $param->file('image'),
                    $association . '.' . $param->file('image')->clientExtension(),
                    'public'
                );
                // ファイル名（拡張子を含む）
                $file_name = $param->file('image')->getClientOriginalName();
                // ファイル識別子
                $arr['association'] = $association;
            }
            // 画像名
            $arr['image_name'] = $file_name;

            // 登録処理
            $customer = new TSupport();
            $customer->fill($arr)->save();
        }

        return 'ok';
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
        if ($param->filled('supported_kubun')) {
            if ($param->input('supported_kubun') == 0) {
                $query = $query->where('ts.is_fixed', 0);
            } else if ($param->input('supported_kubun') == 2) {
                $query = $query->where('ts.is_fixed', 1);
            }
        }
        // 文字列検索
        if ($param->filled('word')) {
            $query = $query->where(function ($q) use ($param) {
                $q->where('ts.supported_history_name', 'like', '%' . $param->input('word') . '%') // タイトル（対応履歴名）
                ->orWhere('ts.detail', 'like', '%' . $param->input('word') . '%') // 詳細内容
                ->orWhere('ts.supported_content', 'like', '%' . $param->input('word') . '%'); // 対応内容
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
            //if ($param->input('is_fixed') == 1) {
            //    $query = $query->where('ts.supported_kubun', 2); // 対応済
            //} else {
            //    $query = $query->where('ts.supported_kubun', 0); // 未対応
            //}
            $query = $query->where('ts.is_fixed', $param->input('is_fixed'));
        }

        // 顧客・案件詳細画面内タブでの絞込み用
        // 対応完了日
        if ($param->filled('supported_complete_date')) {
            $query = $query->whereDate('ts.supported_complete_date', $param->input('supported_complete_date'));
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
            $image_path = null;
            if ($arr['image_name'] && $arr['association']) {
                $format =  strrpos($arr['image_name'], '.') >= 0 ? substr($arr['image_name'], strrpos($arr['image_name'], '.')) : '';
                $filePath = config('filesystems.disks.s3.directory') . "/" . $arr['association'] . $format;
                if (Storage::disk('s3')->exists($filePath)) {
                    $image_path = config('filesystems.disks.s3.url') . "/"
                            . config('filesystems.disks.s3.bucket') . "/"
                            . $filePath;
                }
            }
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
                'image_name' => $arr['image_name'], // ファイル名
                'image_path' => $image_path, // s3上ファイル名
            ];
            $results->push($data);
        }

        return $results;
    }

    /**
     * DB取得結果整形（1件取得用）<br>
     * レスポンスの形に合わせ整形し、配列で返却
     *
     * @param $obj
     * @return array $data 整形後データ
     */
    private static function get_format_column_one($obj): ?array
    {
        // 画像ファイルのダウンロード
        $image = null;
        if ($obj->image_name && $obj->association) {
            $format =  strrpos($obj->image_name, '.') >= 0 ? substr($obj->image_name, strrpos($obj->image_name, '.')) : '';
            $filePath = config('filesystems.disks.s3.directory') . "/" . $obj->association . $format;
            if (Storage::disk('s3')->exists($filePath)) {
                $image = config('filesystems.disks.s3.url') . "/"
                        . config('filesystems.disks.s3.bucket') . "/"
                        . $filePath;
            }
        }

        $data[] = [
            'customer_id' => $obj->customer_id, // 顧客ID
            'project_id' => $obj->project_id, // 案件ID
            'id' => $obj->id, // 対応履歴ID
            'reception_date' => CommonUtility::is_exist_variable($obj->reception_time) ? CommonUtility::convert_timestamp($obj->reception_time, 'Y/m/d') : '', // 受付日
            'reception_hour' => CommonUtility::is_exist_variable($obj->reception_time) ? (int)CommonUtility::convert_timestamp($obj->reception_time, 'H') : '', // 受付時
            'reception_minutes' => CommonUtility::is_exist_variable($obj->reception_time) ? (int)CommonUtility::convert_timestamp($obj->reception_time, 'i') : '', // 受付分
            'customer_responsible_store' => CommonUtility::is_exist_variable($obj->customer) ? (CommonUtility::is_exist_variable($obj->customer->store) ? $obj->customer->store['id'] : '') : '', // 受付担当店舗
            'customer_responsible_store_name' => CommonUtility::is_exist_variable($obj->customer) ? (CommonUtility::is_exist_variable($obj->customer->store) ? $obj->customer->store['name'] : '') : '', // 受付担当店舗名称
            'customer_representative' => CommonUtility::is_exist_variable($obj->customer) ? (CommonUtility::is_exist_variable($obj->customer->employee) ? $obj->customer->employee['id'] : '') : '', // 受付担当担当者
            'customer_representative_name' => CommonUtility::is_exist_variable($obj->customer) ? (CommonUtility::is_exist_variable($obj->customer->employee) ? $obj->customer->employee['name'] : '') : '', // 受付担当担当者名称
            'category' => $obj->category, // カテゴリ
            'category_name' => CommonUtility::is_exist_variable($obj->supported_history) ? $obj->supported_history['name'] : '', // カテゴリ名称
            'media' => $obj->media, // 媒体
            'media_name' => CommonUtility::is_exist_variable($obj->source_supported) ? $obj->source_supported['name'] : '', // 媒体名称
            'customer_name' => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['name'] : '', // 顧客名
            'project_name' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['name'] : '', // 案件名
            'image' => $image, // 画像
            'image_name' => $obj->image_name, // 画像名
            'supported_content' => $obj->supported_content, // 対応内容
            'supported_responsible_store' => $obj->supported_responsible_store, // 対応担当店舗
            'supported_responsible_store_name' => CommonUtility::is_exist_variable($obj->store_supported_rep) ? $obj->store_supported_rep['name'] : '', // 対応担当店舗名称
            'supported_representative' => $obj->supported_representative, // 対応担当担当者
            'supported_representative_name' => CommonUtility::is_exist_variable($obj->employee_supported_rep) ? $obj->employee_supported_rep['name'] : '', // 対応担当担当者名称
            'supported_date' => $obj->supported_date, // 対応日
            'fixed_flag' => $obj->is_fixed, // 対応済みフラグ
            'reception_time' => $obj->reception_time, // 受付日時
            'supported_history_name' => $obj->supported_history_name, // 件名
            'supported_person' => $obj->supported_person, // 対応者
            'supported_person_name' => CommonUtility::is_exist_variable($obj->employee_supported) ? $obj->employee_supported['name'] : '', // 対応者名称
            'supported_complete_date' => $obj->supported_complete_date, // 対応完了日
            'supported_detail' => $obj->detail, // 対応詳細
        ];

        return $data;
    }

    /**
     * 対応履歴情報フリーワード検索
     *
     * @param Request $param 検索パラメータ
     * @return Collection 取得データ
     * @throws Exception
     */
    public static function search_list_freeword(Request $param): Collection
    {
        $query = self::_search_list($param);

        // 検索条件（or）
        if ($param->filled('keyword') || !is_null($param->input('keyword'))) {
            self::set_orwhere($query, $param->input('keyword'));
        }

        // ソート条件（order by）
        if ($param->filled('sort_by')) {
            self::_set_order_by($query, $param->input('sort_by', 1), $param->input('highlow', 0), 1);
        } else {
            self::_set_order_by($query, $param->input('filter_by', 2), $param->input('highlow', 0), 2);
        }
        if ($param->filled('limit')) {
            // オフセット条件（offset）
            $query->skip(($param->input('offset', 0) > 0) ? ($param->input('offset') * $param->input('limit')) : 0);
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

    public static function set_orwhere(&$query, String $keyword)
    {
        $query->where(function($_query) use ($keyword) {
            // 顧客名
            $_query->orWhere('tc.name', 'like', '%' . $keyword . '%');
            // 案件名
            $_query->orWhere('tp.name', 'like', '%' . $keyword . '%');
            // 対応履歴名
            $_query->orWhere('ts.supported_history_name', 'like', '%' . $keyword . '%');
            // 対応日
            $_query->orWhereDate('ts.supported_date', $keyword);
        });
        return;
    }
}
