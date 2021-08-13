<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class ModelBase<br>
 * モデルベース
 *
 * @package App\Models
 */
class ModelBase extends Model
{
    /**
     * ソート方法
     *
     * @var string[]
     */
    protected const SORT_KIND = [
        0 => 'ASC', // 昇順
        1 => 'DESC', // 降順
    ];

    /**
     * 都道府県
     *
     * @var string[]
     */
    public const PREFECTURE = [
        1 => '北海道',
        2 => '青森県',
        3 => '岩手県',
        4 => '宮城県',
        5 => '秋田県',
        6 => '山形県',
        7 => '福島県',
        8 => '茨城県',
        9 => '栃木県',
        10 => '群馬県',
        11 => '埼玉県',
        12 => '千葉県',
        13 => '東京都',
        14 => '神奈川県',
        15 => '新潟県',
        16 => '富山県',
        17 => '石川県',
        18 => '福井県',
        19 => '山梨県',
        20 => '長野県',
        21 => '岐阜県',
        22 => '静岡県',
        23 => '愛知県',
        24 => '三重県',
        25 => '滋賀県',
        26 => '京都府',
        27 => '大阪府',
        28 => '兵庫県',
        29 => '奈良県',
        30 => '和歌山県',
        31 => '鳥取県',
        32 => '島根県',
        33 => '岡山県',
        34 => '広島県',
        35 => '山口県',
        36 => '徳島県',
        37 => '香川県',
        38 => '愛媛県',
        39 => '高知県',
        40 => '福岡県',
        41 => '佐賀県',
        42 => '長崎県',
        43 => '熊本県',
        44 => '大分県',
        45 => '宮崎県',
        46 => '鹿児島県',
        47 => '沖縄県',
        0 => 'その他',
    ];

    /**
     * 性別
     *
     * @var string[]
     */
    public const SEX = [
        1 => '指定なし',
        2 => 'オス',
        3 => 'メス',
    ];

    //CSVダウンロード
    public static function download_csv($results){

        $response = new StreamedResponse(function () use ($results) {
            $stream = fopen('php://output','w');
            // 文字化け回避
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');

            if (empty($results[0])) {
                fputcsv($stream, [
                    'データが存在しませんでした。',
                ]);
            } else {
                $columns = TCustomer::get_columns();

        //        必要なカラムをdataに詰める
                $data = array_values($columns);
                fputcsv($stream, $data);

//                    foreach ($results as $row) {
//                        $data2 = [];
//            //        必要なカラムをdataに詰める
//                        foreach($columns as $key => $column){
//                            $data2[] = $row->{$key};
//                        }
//                        fputcsv($stream, $data2);
//                    }
            }
            fclose($stream);
        });

        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('content-disposition', 'attachment; filename=Member_Import_Template.csv');
        return $response;
    }


    private const LOCAL_STORAGE_PATH = 1;
    /**
     * Storageへのパス
     */
    public const STORAGE_PATH = [
        self::LOCAL_STORAGE_PATH => 'public/upload/',
    ];

    /**
     * 商品区分
     *
     * @var string[]
     */
    public const SHOHIN_KUBUN = [
        1 => '資材',
        2 => '工事',
        3 => '設備',
        4 => '材公共',
        5 => 'その他',
    ];
}
