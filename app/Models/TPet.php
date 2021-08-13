<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class Pet<br>
 * ペットデータ
 *
 * @package App\Models
 */
class TPet extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（t_pets）
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
        'type',
        'sex',
        'age',
    ];

    /**
     * ペット情報一覧検索
     *
     * @param int $id 顧客ID
     * @return Collection
     */
    public static function search_list(int $id): Collection
    {
        // 取得項目
        $query = TPet::select(
            'id',
            'customer_id',
            'name',
            'type',
            'sex',
            'age',
        )->where('customer_id', $id);

        $result = $query->get();
        if ($result->count() == 0) {
            return $result;
        }

        // 取得結果整形
        return self::get_format_column($result);
    }

    /**
     * ペット情報1件検索
     *
     * @param int $id 顧客ID
     * @param int $pet_id ペットID
     * @return array|null 取得データ
     */
    public static function search_one(int $id, int $pet_id): ?array
    {
        // 取得項目
        $query = TPet::select(
            'id',
            'customer_id',
            'name',
            'type',
            'sex',
            'age',
        )->where('customer_id', $id);

        $result = $query->find($pet_id);
        if (is_null($result)) {
            return null;
        }

        // 取得結果整形
        return self::get_format_column_one($result);
    }

    /**
     * ペット情報保存（登録・更新）
     *
     * @param Request $param
     * @param int $id
     * @param int|null $pet_id
     * @return string
     */
    public static function upsert(Request $param, int $id, int $pet_id = null): string
    {

        $arr = $param->all();
        $arr['customer_id'] = $id;

        if ($pet_id) {
            // 更新
            $obj = TPet::select('*')->where('customer_id', $id)->find($pet_id);
            if (is_null($obj)) {
                return '404';
            }

            // 更新処理
            $obj->fill($arr)->save();
        } else {
            // 登録処理
            $pet = new TPet();
            $pet->fill($arr)->save();
        }

        return 'ok';
    }

    /**
     * ペット情報削除
     *
     * @param int|null $pet_id ペットID
     */
    public static function remove(int $pet_id)
    {
        // 削除処理
        TPet::destroy($pet_id);

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
                'pet_id' => $arr['id'], // ペットID
                'name' => $arr['name'], // 名前
                'type' => $arr['type'], // 種別
                'sex' => ModelBase::SEX[$arr['sex']], // 性別
                'age' => $arr['age'], // 才
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
            'pet_id' => $obj->id, // ペットID
            'name' => $obj->name, // 名前
            'type' => $obj->type, // 種別
            'sex' => $obj->sex, // 性別
            'age' => $obj->age, // 才
        ];

        return $data;
    }
}
