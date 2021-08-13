<?php

namespace App\Libraries;

use Illuminate\Support\Carbon;

/**
 * Class CommonUtility<br>
 * 共通ユーティリティクラス
 *
 * @package App\Libraries
 */
class CommonUtility
{
    /**
     * 変数存在チェック
     *
     * @param mixed $val チェック対象の変数
     * @return bool
     */
    public static function is_exist_variable($val): bool
    {
        return !empty($val) && strlen(strval($val)) != 0;
    }

    /**
     * 変数存在チェック（配列）
     *
     * @param mixed $array チェック対象の配列
     * @return bool
     */
    public static function is_exist_variable_array($array): bool
    {
        return is_array($array) && !empty($array);
    }

    /**
     * timestamp型の日付をフォーマット指定して変換
     *
     * @param mixed $date 変換対象の日付
     * @param string $format 日付フォーマット
     * @return mixed
     */
    public static function convert_timestamp($date, string $format)
    {
        $date = new Carbon($date);
        return $date->format($format);
    }
}
