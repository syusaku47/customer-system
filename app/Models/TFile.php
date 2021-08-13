<?php

namespace App\Models;

use App\Libraries\CommonUtility;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Class TFile<br>
 * ファイルデータ
 *
 * @package App\Models
 */
class TFile extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（t_files）
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
        'file_name',
        'format',
        'size',
        'comment',
        'association',
        'last_updated_by',
    ];

    /**
     * ファイルと紐づく顧客データ取得（1対多（逆））
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TCustomer::class);
    }

    /**
     * ファイルと紐づく案件データ取得（1対多（逆））
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TProject::class);
    }

    /**
     * ソート用カラム定義
     *
     * @var string[]
     */
    protected const SORT_BY_COLUMN = [
        0 => 'tf.id', // No.
        1 => 'tf.file_name', // ファイル名
        2 => 'tf.format', // 形式
        3 => 'tf.size', // サイズ
        4 => 'tf.updated_at', // アップロード日時
        5 => 'tf.last_updated_by', // 更新者
        6 => 'tc.name', // 顧客名
        7 => 'tp.name', // 案件名
    ];

    /**
     * ソート用カラム定義（顧客・案件詳細画面タブ内のファイル情報絞り込み検索時用）
     *
     * @var string[]
     */
    protected const SORT_BY_DETAIL_COLUMN = [
        0 => 'tf.id', // No.
        1 => 'tf.file_name', // ファイル名
        2 => 'tf.format', // 形式
        3 => 'tf.size', // サイズ
        4 => 'tf.updated_at', // アップロード日時
        5 => 'tf.last_updated_by', // 更新者
        6 => 'tf.comment', // コメント
        7 => 'tc.id', // 顧客ID
        8 => 'tp.id', // 案件ID
    ];

    /**
     * ファイル情報一覧検索
     *
     * @param Request $param 検索パラメータ
     * @return mixed 取得データ
     * @throws Exception
     */
    public static function search_list(Request $param)
    {
        // 取得項目
        $query = TFile::select(
            // ファイルデータ
            'tf.customer_id as tf_customer_id',
            'tf.project_id as tf_project_id',
            'tf.id as tf_id',
            'tf.file_name as tf_file_name',
            'tf.format as tf_format',
            'tf.size as tf_size',
            'tf.updated_at as tf_updated_at',
            'tf.comment as tf_comment',
            'tf.association as tf_association',
            'tf.last_updated_by as tf_last_updated_by',
            //　顧客データ
            'tc.name as tc_name',
            'tc.furigana as tc_furigana',
            //　案件データ
            'tp.name as tp_name',
        )->distinct()->from('t_files as tf')
            ->join('t_customers as tc', 'tf.customer_id', '=', 'tc.id') // 顧客データ
            ->leftjoin('t_projects as tp', 'tf.project_id', '=', 'tp.id'); // 案件データ

        // 検索条件（where）
        self::set_where_join($query, $param); // ファイル
        self::set_where_customer_join($query, $param); // 顧客
        self::set_where_project_join($query, $param); // 案件
        // ソート条件（order by）
        if ($param->filled('sort_by')) {
            self::_set_order_by($query, $param->input('sort_by', 0), $param->input('highlow', 0), 1);
        } else {
            self::_set_order_by($query, $param->input('filter_by', 0), $param->input('highlow', 0), 2);
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
     * ファイル情報1件検索
     *
     * @param Request $param 検索パラメータ
     * @param int $id ファイルID
     * @return array|null 取得データ
     * @throws BindingResolutionException
     */
    public static function search_one(Request $param, int $id): ?array
    {
        // 取得項目
        $query = TFile::select(
            'customer_id',
            'project_id',
            'id',
            'file_name',
            'format',
            'association',
            'updated_at',
            'last_updated_by',
            'comment',
        )->with(['customer' => function($q) {
            // With()で取得時、リレーション先のID項目の取得が必須
            $q->select('id', 'name', 'furigana');
        }]) // 顧客データ
        ->with(['project' => function($q) {
            // With()で取得時、リレーション先のID項目の取得が必須
            $q->select('id', 'name');
        }]); // 案件データ

        $result = $query->find($id);
        if (is_null($result)) {
            return null;
        }

        // 取得結果整形
        return self::get_format_column_one($result);
    }

    /**
     * ファイル情報保存（登録・更新）
     *
     * @param Request $param
     * @param int|null $id ファイルID
     * @return string
     */
    public static function upsert(Request $param, int $id = null): string
    {
        $arr = $param->all();

        // DB登録・更新用にパラメータ変換
        // 最終更新者
        // TODO ログインユーザー名を登録
        $arr['last_updated_by'] = '管理者';

        if ($id) {
            // 更新
            if ($param->filled('project_id')) {
                $obj = TFile::select('*')
                    ->where('customer_id', $param->input('customer_id'))
                    ->where('project_id', $param->input('project_id'))->find($id);
            } else {
                $obj = TFile::select('*')->where('customer_id', $param->input('customer_id'))->find($id);
            }
            if (is_null($obj)) {
                return '404';
            }

            if ($param->hasFile('file')) {
                $format =  strrpos($obj->file_name, '.') >= 0 ? substr($obj->file_name, strrpos($obj->file_name, '.')) : '';
                // 登録済みのファイルを削除
                $file_path = ModelBase::STORAGE_PATH[1] . $obj->association . $format;
                Storage::delete($file_path);

                $file_name = str_random();
                // ファイルをStorageに保存
                Storage::putFileAs(ModelBase::STORAGE_PATH[1], $param->file('file')
                    , $file_name . '.' . $param->file('file')->clientExtension());
                // サイズ（KB換算）
                $filesize = filesize($param->file('file')->getRealPath()) / 1024;
                $arr['size'] = $filesize;
                // ファイル識別子
                $arr['association'] = $file_name;
            }

            // 更新処理
            $obj->fill($arr)->save();
        } else {
            if ($param->hasFile('file')) {
                $file_name = str_random();
                // ファイルをStorageに保存
                Storage::putFileAs(ModelBase::STORAGE_PATH[1], $param->file('file')
                    , $file_name . '.' . $param->file('file')->clientExtension());
                // サイズ（KB換算）
                $filesize = filesize($param->file('file')->getRealPath()) / 1024;
                $arr['size'] = $filesize;
                // ファイル識別子
                $arr['association'] = $file_name;
            }

            // 登録処理
            $customer = new TFile();
            $customer->fill($arr)->save();
        }

        return 'ok';
    }

    /**
     * ファイル情報削除
     *
     * @param int $id ファイルID
     */
    public static function remove(int $id)
    {
        $obj = TFile::find($id);
        if (!is_null($obj)) {
            $format =  strrpos($obj->file_name, '.') >= 0 ? substr($obj->file_name, strrpos($obj->file_name, '.')) : '';
            // ファイル削除処理
            $file_path = ModelBase::STORAGE_PATH[1] . $obj->association . $format;
            Storage::delete($file_path);
            // データ削除処理
            TFile::destroy($id);
        }

        return;
    }

    /**
     * ファイルダウンロード
     *
     * @param int $id ファイルID
     */
    public static function download(int $id)
    {
        $obj = TFile::find($id);

        if (!is_null($obj) && $obj->file_name && $obj->format && $obj->association) {
            $format = strrpos($obj->file_name, '.') >= 0 ? substr($obj->file_name, strrpos($obj->file_name, '.')) : '';
            $filePath = ModelBase::STORAGE_PATH[1] . $obj->association . $format;
            if (Storage::exists($filePath)) {
                // ファイルダウンロード
                $mimeType = Storage::mimeType($filePath);
                $headers = ['Content-Type' => $mimeType];

                return Storage::download($filePath, $obj->file_name, $headers);
            }
        }

        return;
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
        // ファイル名
        if ($param->filled('file_name')) {
            $query = $query->where('tf.file_name', 'like', '%' . $param->input('file_name') . '%');
        }
        // アップロード日_期間開始～終了
        if ($param->filled('upload_date_start') && $param->filled('upload_date_end')) {
            $query = $query->where(function ($q) use ($param) {
                // アップロード日_期間開始、終了の期間
                $q->where('tf.updated_at', '>=', new \DateTime($param->input('upload_date_start')))
                    ->where('tf.updated_at', '<=', new \DateTime($param->input('upload_date_end')));
            });
        } else if ($param->filled('upload_date_start') && is_null($param->input('upload_date_end'))) {
            // アップロード日_期間開始以降
            $query = $query->where('tf.updated_at', '>=', new \DateTime($param->input('upload_date_start')));
        } else if (is_null($param->input('tm.upload_date_start')) && $param->filled('upload_date_end')) {
            // アップロード日_期間終了以前
            $query = $query->where('tf.updated_at', '<=', new \DateTime($param->input('upload_date_end')));
        }

        // SP版用
        // キーワード検索
        if ($param->filled('word')) {
            $query = $query->where(function ($q) use ($param) {
                $q->where('tf.file_name', 'like', '%' . $param->input('word') . '%'); // ファイル名
                $q->orWhereDate('tf.updated_at', $param->input('word')); // 更新日
            });
        }

        // 顧客・案件詳細画面内タブでの絞込み用
        // No.
        if ($param->filled('no')) {
            $query = $query->where('tf.id', 'like', '%' . $param->input('no') . '%');
        }
        // 形式
        if ($param->filled('format')) {
            $query = $query->where('tf.format', 'like', '%' . $param->input('format') . '%');
        }
        // サイズ
        if ($param->filled('size')) {
            $query = $query->where('tf.size', 'like', '%' . $param->input('size') . '%');
        }
        // アップロード日時
        if ($param->filled('upload_date')) {
            $query = $query->whereDate('tf.updated_at', $param->input('upload_date'));
        }
        // 更新者
        if ($param->filled('updater')) {
            $query = $query->where('tf.last_updated_by', 'like', '%' . $param->input('updater') . '%');
        }
        // コメント
        if ($param->filled('comment')) {
            $query = $query->where('tf.comment', 'like', '%' . $param->input('comment') . '%');
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
        // 顧客ID
        if ($param->filled('customer_id')) {
            $query = $query->where('tc.id', $param->input('customer_id'));
        }
        // 顧客名
        if ($param->filled('customer_name')) {
            $query = $query->where('tc.name', 'like', '%' . $param->input('customer_name') . '%');
        }

        // SP版用
        // キーワード検索
        if ($param->filled('word')) {
            $query = $query->where(function ($q) use ($param) {
                $q->where('tc.name', 'like', '%' . $param->input('word') . '%'); // 顧客名
            });
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
        // 案件ID
        if ($param->filled('project_id')) {
            $query = $query->where('tp.id', $param->input('project_id'));
        }
        // 案件名
        if ($param->filled('project_name')) {
            $query = $query->where('tp.name', 'like', '%' . $param->input('project_name') . '%');
        }

        // SP版用
        // キーワード検索
        if ($param->filled('word')) {
            $query = $query->where(function ($q) use ($param) {
                $q->where('tp.name', 'like', '%' . $param->input('word') . '%'); // 顧客名
            });
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
                // 未指定の場合、No.の昇順
                $query->orderBy(self::SORT_BY_COLUMN[0], ModelBase::SORT_KIND[0]);
            } else {
                // 未指定の場合、No.の昇順
                $query->orderBy(self::SORT_BY_DETAIL_COLUMN[0], ModelBase::SORT_KIND[0]);
            }
        } else {
            if ($filter == 1) {
                $query->orderBy(self::SORT_BY_COLUMN[$order_column_id], ModelBase::SORT_KIND[$sort_id]);
            } else {
                $query->orderBy(self::SORT_BY_DETAIL_COLUMN[$order_column_id], ModelBase::SORT_KIND[$sort_id]);
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

            // アイコン_サムネイルのダウンロード
            $icon_thumbnail = null;
            if ($arr['tf_file_name'] && $arr['tf_format'] && $arr['tf_association']) {
                $format = strrpos($arr['tf_file_name'], '.') >= 0 ? substr($arr['tf_file_name'], strrpos($arr['tf_file_name'], '.')) : '';
                $filePath = ModelBase::STORAGE_PATH[1] . $arr['tf_association'] . $format;
                if (Storage::exists($filePath)) {
                    $mimeType = Storage::mimeType($filePath);
                    $headers = ['Content-Type' => $mimeType];

                    $icon_thumbnail = base64_encode(Storage::download($filePath, $arr['tf_file_name'], $headers));
                }
            }

            $data = [
                'customer_id' => $arr['tf_customer_id'], // 顧客ID
                'project_id' => $arr['tf_project_id'], // 案件ID
                'id' => $arr['tf_id'], // ファイルID
                'no' => $arr['tf_id'], // No.
                'file_name' => $arr['tf_file_name'], // ファイル名
                'format' => $arr['tf_format'], // 形式
                'size' => $arr['tf_size'], // サイズ
                'upload_date' => $arr['tf_updated_at'], // アップロード日時
                'updater' => $arr['tf_last_updated_by'], // 更新者
                'customer_name' => $arr['tc_name'], // 顧客名
                'furigana' => $arr['tc_furigana'], // 顧客名フリガナ
                'project_name' => $arr['tp_name'], // 案件名
                'icon_thumbnail' => $icon_thumbnail, // アイコン_サムネイル
                'comment' => $arr['tf_comment'], // コメント
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
        // ファイルダウンロード
        $file = null;
        if ($obj->file_name && $obj->format && $obj->association) {
            $format = strrpos($obj->file_name, '.') >= 0 ? substr($obj->file_name, strrpos($obj->file_name, '.')) : '';
            $filePath = ModelBase::STORAGE_PATH[1] . $obj->association . $format;
            if (Storage::exists($filePath)) {
                $mimeType = Storage::mimeType($filePath);
                $headers = ['Content-Type' => $mimeType];

                $file = base64_encode(Storage::download($filePath, $obj->file_name, $headers));
            }
        }

        $data[] = [
            'customer_id' => $obj->customer_id, // 顧客ID
            'project_id' => $obj->project_id, // 案件ID
            'id' => $obj->id, // ファイルID
            'customer_name' => CommonUtility::is_exist_variable($obj->customer) ? $obj->customer['name'] : '', // 顧客名
            'project_name' => CommonUtility::is_exist_variable($obj->project) ? $obj->project['name'] : '', // 案件名
            'file_name' => $obj->file_name, // ファイル名
            'format' => $obj->format, // 形式
            'file' => $file, //ファイル
            'comment' => $obj->comment, // コメント
            'upload_date' => CommonUtility::convert_timestamp($obj->updated_at, 'Y/m/d H:i:s'), // アップロード日時
            'updater' => $obj->last_updated_by, // 更新者
        ];

        return $data;
    }
}
