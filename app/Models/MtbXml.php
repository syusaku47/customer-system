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
 * Class MCustomerRankKojisController<br>
 * 見積定型,署名
 *
 * @package App\Http\Controllers\Api\Master
 */
class MtbXml extends ModelBase
{
    use HasFactory;

//    use HasCompositePrimaryKey;

    // テーブル名はクラスの複数形のスネークケース（mtb_xmls）
    // 複合キーで主キーのデフォルト名はid,company_id

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
        'xml_format',
        'is_valid',
    ];

    protected $attributes = [
        'is_valid' => 1,
    ];

    /**
     * 見積定型,署名一件取得
     *
     * @param Int $id 検索パラメータ
     * @return mixed 取得データ
     */
    public static function search_one($id)
    {
//      セッションからログインユーザーのcompany_idを取得
        $company_id = session()->get('company_id');

        $instance = MtbXml::where('company_id',$company_id)
            ->where('internal_id', $id)->first();

//        合致するデータがなかったら
        if (is_null($instance)) {
            return ["code" => '404'];
        }

        // 取得結果整形
        return self::get_format_column($instance);
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
            $data = [
                'id' => $collection->id, // 見積定型,署名ID
                'company_id' => $collection->company_id, // 会社ID
                'internal_id' => $collection->internal_id, // 内部ID
                'xml_format' => $collection->xml_format, // フォーマットデータ
            ];
            $results->push($data);

        return $results;
    }

    /**
     * 見積定型,署名情報保存（更新）
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

//            トランザクション
            DB::beginTransaction();

            // 更新
            $instance = MtbXml::where('company_id',$company_id)
                ->where('internal_id', $id)
                ->first();

//        合致するデータがなかったら
            if (is_null($instance)) {
                return ["code" => '404'];
            }

//            // 更新処理
            $instance->fill($arr)->update();

            DB::commit();
            return ["code" => ""];

        } catch (Throwable $e) {
            DB::rollback();
//            トランザクションエラー
            \Log::debug($e);
            return ["code" => 'fail'];
        }
    }

}
