<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class TFamily<br>
 * 家族データ
 *
 * @package App\Models
 */
class TFamily extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（t_families）
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
        'name',
        'relationship',
        'mobile_phone',
        'birth_date',
    ];

    /**
     * ご家族情報一覧検索
     *
     * @param int $id 顧客ID
     * @return Collection
     */
    public static function search_list(int $id): Collection
    {
        // 取得項目
        $query = TFamily::select(
            'id',
            'customer_id',
            'name',
            'relationship',
            'mobile_phone',
            'birth_date',
        )->where('customer_id', $id);

        $result = $query->get();
        if ($result->count() == 0) {
            return $result;
        }

        // 取得結果整形
        return self::get_format_column($result);
    }

    /**
     * ご家族情報1件検索
     *
     * @param int $id 顧客ID
     * @param int $family_id 家族ID
     * @return array|null 取得データ
     */
    public static function search_one(int $id, int $family_id): ?array
    {
        // 取得項目
        $query = TFamily::select(
            'id',
            'customer_id',
            'name',
            'relationship',
            'mobile_phone',
            'birth_date',
        )->where('customer_id', $id);

        $result = $query->find($family_id);
        if (is_null($result)) {
            return null;
        }

        // 取得結果整形
        return self::get_format_column_one($result);
    }

    /**
     * ご家族情報保存（登録・更新）
     *
     * @param Request $param
     * @param int $id
     * @param int|null $family_id
     * @return string
     */
    public static function upsert(Request $param, int $id, int $family_id = null): string
    {

        $arr = $param->all();
        $arr['customer_id'] = $id;

        if ($family_id) {
            // 更新
            $obj = TFamily::select('*')->where('customer_id', $id)->find($family_id);
            if (is_null($obj)) {
                return '404';
            }

            // 更新処理
            $obj->fill($arr)->save();
        } else {
            // 登録処理
            $family = new TFamily();
            $family->fill($arr)->save();
        }

        return 'ok';
    }

    /**
     * ご家族情報削除
     *
     * @param int|null $family_id 家族ID
     */
    public static function remove(int $family_id)
    {
        // 削除処理
        TFamily::destroy($family_id);

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
                'id' => $arr['customer_id'], // 顧客ID
                'family_id' => $arr['id'], // ご家族ID
                'name' => $arr['name'], // 氏名
                'relationship' => $arr['relationship'], // 続柄
                'mobile_phone' => $arr['mobile_phone'], // 携帯電話
                'birth_date' => $arr['birth_date'], // 生年月日
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
        $data[] = [
            'id' => $obj->customer_id, // 顧客ID
            'family_id' => $obj->id, // ご家族ID
            'name' => $obj->name, // 氏名
            'relationship' => $obj->relationship, // 続柄
            'mobile_phone' => $obj->mobile_phone, // 携帯電話
            'birth_date' => $obj->birth_date, // 生年月日
        ];

        return $data;
    }
}
