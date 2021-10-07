<?php
namespace App\Http\Controllers\Api;

// ************************* TCPDFとFPDIはの設置場所は迷ったけど、app配下にvendorディレクトリを作成し、手動で設置してます
require_once(app_path().'/vendor/TCPDF/tcpdf.php');
require_once(app_path().'/vendor/FPDI/src/autoload.php');

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF;

class PdfController extends Controller
{
    /**
     * PDF生成
     * @param Request $request
     * @return
     */
    public function index (Request $request)
    {
		try {
            // ************************* バリデーション処理（Requestクラスを使用しない理由は、PDF出力でエラー検知した場合は404（Not Found）エラーにしたかったからです） *************************
            // ************************* 最低限のチェックを書いてます（SVGデータの整合性チェックまでしてません）
			//SVGファイルの作成
			if (empty($request->svg_file) || empty($request->svg_file[0])) {
				throw new \Exception("PDF情報がありません");
			}

			//SVGファイルの作成
			if (empty($request->preset_type) || ($request->preset_type < 1 || $request->preset_type > 2 )) {
				throw new \Exception("用紙形式が設定されていません");
			}

			//SVGファイルの作成
			if (empty($request->pdf_list) || ($request->pdf_list < 1 || $request->pdf_list > 2 )) {
				throw new \Exception("出力PDF種別が設定されていません");
			}

            // ************************* Config定義（Configにファイル作成してそちらに定義がよいかと） *************************
			//SVGファイルからPDFファイルに変換するための初期値
			$config = [
				'pdf_size' => [
					//A4
					1 => [
						'dpi'      => 72,         // PDFの解像度 [dpi]
						'w'        => 595,       // PDFの横幅 [px]
						'h'        => 842,       // PDFの縦幅 [px]
						'w_mm'     => 210,
						'h_mm'     => 297,
						'direction'=> 'P',
						'filename' => 'template_vertical.pdf',
					],
					//A4横向き
					2 => [
						'dpi'  => 72,         // PDFの解像度 [dpi]
						'w'    => 842,       // PDFの横幅 [px]
						'h'    => 595,       // PDFの縦幅 [px]
						'w_mm' => 297,
						'h_mm' => 210,
						'direction'=> 'L',
						'filename' => 'template_horizontal.pdf',
					],
				],
				//PDF出力するファイル名
				'pdf_list' => [
					1  => 'vertical',
					2  => 'horizontal',
				],
			];

            // ************************* 一時ファイル生成場所の確保 *************************
        	// zipディレクトリ作成
        	if (!file_exists($path = storage_path('app/public/zip_tmp/'))) {
        	    mkdir($path, 0777, true);
        	    chmod($path, 0777);
        	}

            // ************************* PDFファイル情報の設定 *************************
			//PDFのサイズの処理分け
			$pdf_config        = $config['pdf_size'][$request->preset_type];
			$pdf_list          = $config['pdf_list'];
			$pdf_template_file = resource_path('pdf/').$pdf_config['filename'];
			$pdf_filename      = $pdf_list[$request->pdf_list] . '_' . date('YmdHis') . '.pdf';


            // ************************* SVGファイル生成 *************************
			$cnt = count($request->svg_file);
			for ($i = 1; $i <= $cnt; $i++)
			{
				file_put_contents($path.'/createsvg-'.$i.'.svg', base64_decode($request->svg_file[$i-1]));
			}

            // ************************* ここからSVGtoPDF *************************
			$pdf = new FPDI($pdf_config['direction'], 'mm', 'A4'); //用紙サイズがA4以外もある場合は、第3パラメータも動的に設定するように

			$pdf->setPrintHeader( false );
			$pdf->setPrintFooter( false );

			$pdf->setSourceFile($pdf_template_file);

			$pdf->SetMargins(0, 0, 0);

			$pdf->SetAutoPageBreak(false, 0);

			// ---------------------------------------------------------
			// set font
			$font = new \TCPDF_FONTS();
			$fontfile = resource_path('pdf/').'ipag.ttf'; //IPAゴシックフォントファイル指定
			$myFont = $font->addTTFfont($fontfile);
			$pdf->SetFont($myFont, '', 10);

			$tplIdx = $pdf->importPage(1);
			for ($i = 1; $i <= $cnt; $i++)
			{
				// add a page
				$pdf->AddPage();
				$pdf->useTemplate($tplIdx);
                // SVG to PDF
				$pdf->ImageSVG($file=$path.'/createsvg-'.$i.'.svg', $x=0, $y=0, $w=$pdf_config['w_mm'], $h=$pdf_config['h_mm'], $link='', $align='', $palign='', $border=0, $fitonpage=false);
                // 変換したSVGファイルを削除
				unlink($path.'/createsvg-'.$i.'.svg');
			}
            // PDFファイル出力
			$pdf->Output($path.'/'.$pdf_filename, 'F');

        	chmod(storage_path('app/public/zip_tmp/' . $pdf_filename), 0666);

        	ob_end_clean();

            // PDFファイル出力（一時ファイル（PDF）も削除
        	$headers = ['Content-Type' => 'application/pdf'];
        	return response()->download($path.'/'.$pdf_filename, $pdf_filename, $headers)->deleteFileAfterSend(true);
		}
		catch (\Exception $e) {
            // ************************* Exception処理（エラー検知した場合は、404エラーとしています） *************************
			Log::error($e->getMessage());
			http_response_code( 404 ) ;
			exit;
		}
    }
}
