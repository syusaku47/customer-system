<?php

use Illuminate\Support\Facades\Route;
use App\Models\MEmployee;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('auth/me', MeController::class);
});

//Route::prefix('api')->middleware(["middleware" => "api"])->group(function () {
//
//    // PDF生成
    Route::post('pdf', [App\Http\Controllers\Api\PdfController::class, 'index'])->name('pdf.index');
//
//});



//Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['middleware' => 'api', 'namespace' => 'Api'], function(){
        // 共通パスワード変更
        Route::group(['prefix' => 'common'], function() {
            Route::post("/chgpassword", 'ChangePasswordController@changePassword');
        });

        // 顧客管理
        Route::group(['prefix' => 'customer'], function() {
            // 顧客情報
            // 顧客ID発行
            Route::get('id', 'CustomersController@get_id');
            // 編集中顧客情報削除
            Route::delete('id/{id}', 'CustomersController@destroy_edit_data');
            // 更新
            Route::match('post', '{id}', 'CustomersController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする

            // ご家族情報
            Route::group(['prefix' => '{id}'], function() {
                // 一覧取得
                Route::get('family', 'FamiliesController@index');
                // 取得
                Route::get('family/{family_id}', 'FamiliesController@show');
                // 登録
                Route::post('family', 'FamiliesController@store');
                // 更新
                Route::match('post', 'family/{family_id}', 'FamiliesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                // 削除
                Route::delete('family/{family_id}', 'FamiliesController@destroy');
            });

            // ペット情報
            Route::group(['prefix' => '{id}'], function() {
                // 一覧取得
                Route::get('pet', 'PetsController@index');
                // 取得
                Route::get('pet/{pet_id}', 'PetsController@show');
                // 登録
                Route::post('pet', 'PetsController@store');
                // 更新
                Route::match('post', 'pet/{pet_id}', 'PetsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                // 削除
                Route::delete('pet/{pet_id}', 'PetsController@destroy');
            });
        });
        // 顧客情報
        // 取得・登録
        Route::apiResource('customer', 'CustomersController');

        // 案件管理
        Route::group(['prefix' => 'project'], function() {
            // 案件情報
            // 案件ID発行
            Route::get('id/{customer_id}', 'ProjectsController@get_id');
            // 編集中案件情報削除
            Route::delete('id/{id}', 'ProjectsController@destroy_edit_data');
            // 更新
            Route::match('post', '{id}', 'ProjectsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
        });
        // 案件情報
        // 取得・登録
        Route::apiResource('project', 'ProjectsController');
        // メンテナンス管理
        // 更新
        Route::match('post', 'maintenance/{id}', 'MaintenancesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
        //  取得・登録
        Route::apiResource('maintenance', 'MaintenancesController');
        // 対応履歴管理
        // 更新
        Route::match('post', 'supported/{id}', 'SupportsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
        // 取得・登録
        Route::apiResource('supported', 'SupportsController');
        // 受注管理
        // 取得
        Route::get('project/{project_id}/order', 'OrdersController@show');
        // 更新
        Route::match('post', 'order/{id}', 'OrdersController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
        // 登録
        Route::apiResource('order', 'OrdersController');
        // ファイル管理
        // 更新
        Route::match('post', 'file/{id}', 'FilesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
        // 取得・登録・削除
        Route::apiResource('file', 'FilesController');
        // ダウンロード
        Route::get('file/{id}/download', 'FilesController@download');

        // マスタ管理
        Route::group(['prefix' => 'master'], function(){
            Route::group(['prefix' => 'company'], function(){
                // 店舗マスタ
                // 取得・登録・更新
                Route::match('post', 'store/{id}', 'MStoresController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('store', 'MStoresController');
                // 社員マスタ
                // 取得・登録・更新
                Route::match('post', 'employee/{id}', 'MEmployeesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('employee', 'MEmployeesController');
                // 消費税マスタ
                // 取得・登録・更新
                Route::match('post', 'tax/{id}', 'MTaxesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('tax', 'MTaxesController');
            });
            Route::group(['prefix' => 'sizaikoji'], function(){
                // 大分類マスタ
                // 取得・登録・更新
                Route::match('post', 'category/{id}', 'MCategoriesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('category', 'MCategoriesController');
                // 中分類マスタ
                // 取得・登録・更新
                Route::match('post', 'subcategory/{id}', 'MSubCategoriesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('subcategory', 'MSubCategoriesController');
                // 明細マスタ
                // 取得・登録・更新
                Route::match('post', 'detail/{id}', 'MDetailsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('detail', 'MDetailsController');
            });
            Route::group(['prefix' => 'kubun'], function(){
                // エリアマスタ
                // 取得・登録・更新
                Route::match('post', 'area/{id}', 'MAreasController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('area', 'MAreasController');
                // 建物分類マスタ
                // 取得・登録・更新
                Route::match('post', 'building/{id}', 'MBuildingsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('building', 'MBuildingsController');
                // 間取りマスタ
                // 取得・登録・更新
                Route::match('post', 'madori/{id}', 'MMadorisController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('madori', 'MMadorisController');
                // 発生源マスタ
                // 取得・登録・更新
                Route::match('post', 'source/{id}', 'MSourcesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('source', 'MSourcesController');
                // 失注理由マスタ
                // 取得・登録・更新
                Route::match('post', 'lostorder/{id}', 'MLostordersController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('lostorder', 'MLostordersController');
                // 単位マスタ
                // 取得・登録・更新
                Route::match('post', 'credit/{id}', 'MCreditsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('credit', 'MCreditsController');
                // 対応履歴マスタ
                // 取得・登録・更新
                Route::match('post', 'supported/{id}', 'MSupportsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('supported', 'MSupportsController');
                // 問合せマスタ
                // 取得・登録・更新
                Route::match('post', 'inquiry/{id}', 'MInquiriesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('inquiry', 'MInquiriesController');
            });
            Route::group(['prefix' => 'relatedtag'], function(){
                // 関連タグマスタ
                // 取得・登録・更新
                Route::match('post', 'relationtag/{id}', 'MTagsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('relationtag', 'MTagsController');
                // 部位マスタ
                // 取得・登録・更新
                Route::match('post', 'part/{id}', 'MPartsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('part', 'MPartsController');
                // マイカー種別マスタ
                // 取得・登録・更新
                Route::match('post', 'mycartype/{id}', 'MMyCarTypesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('mycartype', 'MMyCarTypesController');
            });
            Route::group(['prefix' => 'rank'], function(){
                // 顧客見込みランクマスタ
                // 取得・登録・更新
                Route::match('post', 'customerestimated/{id}', 'MCustomerEstimatedRanksController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('customerestimated', 'MCustomerEstimatedRanksController');
                // 案件ランクマスタ
                // 取得・登録・更新
                Route::match('post', 'project/{id}', 'MProjectRanksController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('project', 'MProjectRanksController');
                // 顧客ランクマスタ
                // 取得・登録・更新
                Route::match('post', 'customer/{id}', 'MCustomerRanksController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('customer', 'MCustomerRanksController');
            });
            Route::group(['prefix' => 'maintenance'], function(){
                // メンテナンスマスタ
                // 取得・登録・更新
                Route::match('post', 'aftermaintenance/{id}', 'MAfterMaintenancesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('aftermaintenance', 'MAfterMaintenancesController');
            });
            Route::group(['prefix' => 'fixed'], function(){
                // メンテナンスマスタ
                // 取得・登録・更新
                Route::match('post', 'quotefixed/{id}', 'MQuotefixsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('quotefixed', 'MQuotefixsController');
                // 現場準備確認項目マスタ
                // 取得・登録・更新
                Route::match('post', 'fieldconfirmitem/{id}', 'MFieldConfirmItemsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('fieldconfirmitem', 'MFieldConfirmItemsController');
                // 工事確認項目マスタ
                // 取得・登録・更新
                Route::match('post', 'kojiconfirmitem/{id}', 'MKojiConfirmItemsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('kojiconfirmitem', 'MKojiConfirmItemsController');
                // 署名マスタ
                // 取得・登録・更新
                Route::match('post', 'signature/{id}', 'MSignaturesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                Route::apiResource('signature', 'MSignaturesController');
            });
            Route::group(['prefix' => 'customer'], function(){
                // CSVインポート
                Route::post('upload/csv', 'CustomersController@import_csv');

                // CSVダウンロード
                Route::get('template/download', 'CustomersController@download_templete_csv');



                // CSVダウンロード
                Route::get('template/download', 'CustomersController@download_templete_csv');

    //            本番顧客データ移行用
                Route::post('data/migration', 'CustomersController@import_csv_prod');


            });
        });

        // 見積管理
        Route::group(['prefix' => 'quote'], function (){
            Route::get('bat', 'QuoteDetailsController@xml_import');
            // 見積情報
            // 見積ID発行
            Route::get('id/{project_id}', 'QuotesController@get_id');
            // 編集中見積情報削除
            Route::delete('id/{id}', 'QuotesController@destroy_edit_data');
            // 更新
            Route::match('post', '{id}', 'QuotesController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする


            // 見積明細
            Route::group(['prefix' => '{id}'], function (){
                // 明細ツリー一覧取得
                Route::get('detailtree', 'QuoteDetailsController@detail_tree');
                // 明細一覧取得
                Route::get('detail', 'QuoteDetailsController@index');
                // 明細取得
                Route::get('detail/{detail_id}', 'QuoteDetailsController@show');
                // 明細一括登録
                Route::post('listdetail', 'QuoteDetailsController@store_list');
                // 明細登録
                Route::post('detail', 'QuoteDetailsController@store');
                // 明細更新
                Route::match('post', 'detail/{detail_id}', 'QuoteDetailsController@update'); // POSTでも末尾のルートパラメータを受け取れるようにする
                // 明細削除
                Route::delete('detail/{detail_id}', 'QuoteDetailsController@destroy');
                // 印刷名称変更
                Route::post('detail/{detail_id}/updname', 'QuoteDetailsController@update_name');
            });
        });
        // 見積情報
        // 取得・登録
        Route::apiResource('quote', 'QuotesController');

        // CSV出力管理
        Route::group(['prefix' => 'csv'], function () {
            // 一覧取得
            // 顧客情報
            Route::get('customer', 'CsvOutputsController@index_customer');
            // 誕生日リスト
            Route::get('birthday', 'CsvOutputsController@index_birthday');
            // 結婚記念日リスト
            Route::get('weddinganniversary', 'CsvOutputsController@index_wedding');
            // 案件情報
            Route::get('project', 'CsvOutputsController@index_project');
            // 受注情報
            Route::get('order', 'CsvOutputsController@index_order');
            // 未受注案件
            Route::get('notorder', 'CsvOutputsController@index_notorder');
            // 失注情報
            Route::get('lostorder', 'CsvOutputsController@index_lostorder');
            // メンテナンス情報
            Route::get('maintenance', 'CsvOutputsController@index_maintenance');
            // 顧客ランク更新ログ
            Route::get( 'custrankupdlog', 'CsvOutputsController@index_custrankupdlog');
            // 対応履歴
            Route::get('supported', 'CsvOutputsController@index_supported');
        });

        // 検索
        Route::get("/{page}/search/freeword", 'SearchController@searchFreeword');
});
//});

Route::post("/mail/reset_password", 'Api\SendMailController@reset_password');
Route::post("/check_expiry_token", 'Api\ChangePasswordController@checkExpiryToken');
Route::post("chgpassword", 'Api\ChangePasswordController@changePasswordFromMail');
