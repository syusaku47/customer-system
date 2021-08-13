<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

/**
 * Class TOrder<br>
 * 受注データ
 *
 * @package App\Models
 */
class TOrder extends ModelBase
{
    use HasFactory;

    // テーブル名はクラスの複数形のスネークケース（t_orders）
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
        'project_id',
        'quote_id',
//        'company_id', // TODO 後で追加
        'contract_date',
        'construction_start_date',
        'completion_end_date',
        'groundbreaking_ceremony',
        'completion_based',
        'contract_money',
        'contract_billing_date',
        'contract_expected_date',
        'start_construction_money',
        'start_construction_billing_date',
        'start_construction_expected_date',
        'intermediate_gold1',
        'intermediate1_billing_date',
        'intermediate1_expected_date',
        'intermediate_gold2',
        'intermediate2_billing_date',
        'intermediate2_expected_date',
        'completion_money',
        'completion_billing_date',
        'completion_expected_date',
        'unallocated_money',
        'remarks',
        'last_updated_by',
    ];

    /**
     * 受注情報1件検索
     *
     * @param Request $param 検索パラメータ
     * @param int $project_id 案件ID
     * @return array|null 取得データ
     */
    public static function search_one(Request $param, int $project_id): ?array
    {
        // 取得項目
        $result = TOrder::select(
            'project_id',
            'quote_id',
            'contract_money',
            'contract_billing_date',
            'contract_expected_date',
            'start_construction_money',
            'start_construction_billing_date',
            'start_construction_expected_date',
            'intermediate_gold1',
            'intermediate1_billing_date',
            'intermediate1_expected_date',
            'intermediate_gold2',
            'intermediate2_billing_date',
            'intermediate2_expected_date',
            'completion_money',
            'completion_billing_date',
            'completion_expected_date',
            'unallocated_money',
            'remarks',
        )->where('project_id', $project_id)->first();

        if (is_null($result)) {
            return null;
        }

        // 取得結果整形
        return self::get_format_column_one($result);
    }

    /**
     * 受注情報保存（登録・更新）
     *
     * @param Request $param
     * @param int|null $id 受注ID
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

        // 最終更新者
        // TODO ログインユーザー名を登録
        $arr['last_updated_by'] = '管理者';

        if ($id) {
            // 更新
            $obj = TOrder::find($id);
            if (is_null($obj)) {
                return '404';
            }

            // 更新処理
            $obj->fill($arr)->save();
        } else {
            // 登録処理
            $customer = new TOrder();
            $customer->fill($arr)->save();
        }

        return 'ok';
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
            'project_id' => $obj->project_id, // 案件ID
            'quote_id' => $obj->quote_id, // 見積ID
            'contract_money' => floatval($obj->contract_money), // 契約金
            'contract_billing_date' => $obj->contract_billing_date, // 契約金_請求日
            'contract_expected_date' => $obj->contract_expected_date, // 契約金_入金予定日
            'start_construction_money' => floatval($obj->start_construction_money), // 着工金
            'start_construction_billing_date' => $obj->start_construction_billing_date, // 着工金_請求日
            'start_construction_expected_date' => $obj->start_construction_expected_date, // 着工金_入金予定日
            'intermediate_gold1' => floatval($obj->intermediate_gold1), // 中間金1
            'intermediate1_billing_date' => $obj->intermediate1_billing_date, // 中間金1_請求日
            'intermediate1_expected_date' => $obj->intermediate1_expected_date, // 中間金1_入金予定日
            'intermediate_gold2' => floatval($obj->intermediate_gold2), // 中間金2
            'intermediate2_billing_date' => $obj->intermediate2_billing_date, // 中間金2_請求日
            'intermediate2_expected_date' => $obj->intermediate2_expected_date, // 中間金2_入金予定日
            'completion_money' => floatval($obj->completion_money), // 完工金
            'completion_billing_date' => $obj->completion_billing_date, // 完工金_請求日
            'completion_expected_date' => $obj->completion_expected_date, // 完工金_入金予定日
            'unallocated_money' => floatval($obj->unallocated_money), // 未割当金
            'remarks' => $obj->remarks, // 備考
        ];

        return $data;
    }
}
