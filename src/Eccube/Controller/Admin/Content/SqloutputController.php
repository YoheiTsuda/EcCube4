<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Controller\Admin\Content;

use Eccube\Controller\AbstractController;
// use Eccube\Entity\News;
use Eccube\Entity\Sqloutput;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
// use Eccube\Form\Type\Admin\NewsType;
// use Eccube\Repository\NewsRepository;
use Eccube\Repository\SqloutputRepository;
use Eccube\Util\CacheUtil;
// use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Eccube\Service\CsvExportService;


class SqloutputController extends AbstractController
{
    /**
     * @var SqloutputRepository
     */
    protected $sqloutputRepository;


    /**
     * @var CsvExportService
     */
    protected $csvExportService;

    /**
     * SqloutputController constructor.
     *
     * @param CsvExportService $csvExportService
     * @param SqloutputRepository $sqloutputRepository
     */
    public function __construct(
      SqloutputRepository $sqloutputRepository,
      CsvExportService $csvExportService
      )
    {
        $this->sqloutputRepository = $sqloutputRepository;
        $this->csvExportService = $csvExportService;
    }

    // /**
    //  * NewsController constructor.
    //  *
    //  * @param NewsRepository $newsRepository
    //  */
    // public function __construct(NewsRepository $newsRepository)
    // {
    //     $this->newsRepository = $newsRepository;
    // }

    /**
     * 新着情報一覧を表示する。
     *
     * @Route("/%eccube_admin_route%/content/sqloutput", name="admin_content_sqloutput")
     * @Template("@admin/Content/sqloutput.twig")
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        // ★getQueryBuilderAll　バージョン
        // $Outputs = $this->sqloutputRepository->getQueryBuilderAll();
        //
        // $event = new EventArgs(
        //     [
        //         'Outputs' => $Outputs,
        //     ],
        //     $request
        // );
        // $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_SQLOUTPUT_INDEX_INITIALIZE, $event);

        // return [ 'Outputs' => $Outputs ];

        // ★getQueryBuilderAll　バージョン2
        // $Outputs = $this->sqloutputRepository->getQueryBuilderAll();
        //
        // $event = new EventArgs(
        //     [
        //         'Outputs' => $Outputs,
        //     ],
        //     $request
        // );
        // // $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_SQLOUTPUT_INDEX_INITIALIZE, $event);
        //
        // return [
        //     'Outputs' => $Outputs,
        // ];

        // ★getPageList　バージョン
        // $qb = $this->sqloutputRepository->getPageList();
        // return [
        //   'Outputs' => $qb,
        // ];

        // ★生SQL実行
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT * FROM utb_csv_output;';
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->bindValue('status', 1);
        $statement->execute();
        $Outputs = $statement->fetchAll();
        return [ 'Outputs' => $Outputs];

    }

    /**
     * @param Request $request
     *
     * @return StreamedResponse
     * @Route("/%eccube_admin_route%/content/sqloutput/{id}", requirements={"id" = "\d+"}, name="admin_content_sqloutput_export")
     */
    public function exportCsv(Request $request)
    {
// ★　通常のCSV出力ボタン
        // タイムアウトを無効にする.
        set_time_limit(0);

        // sql loggerを無効にする.
        $em = $this->entityManager;
        $em->getConfiguration()->setSQLLogger(null);


        $ary = array(
          array("名前","年齢","血液型"),
          array("太郎","21","O")
        );
        $f = fopen( "test.csv",w);
        if ( $f ) {
          foreach($ary as $line){
            fputcsv($f , $line);

          }
        }
        fclose($f);



        // $response = new StreamedResponse();
        // $response->setCallback(function () use ($request) {
        //     // CSV種別を元に初期化.
        //     // $this->csvExportService->initCsvType($csvTypeId);
        //
        //     // ヘッダ行の出力.
        //     $this->csvExportService->exportHeader();
        //
        //     // 受注データ検索用のクエリビルダを取得.
        //     $qb = $this->csvExportService
        //         ->getOrderQueryBuilder($request);
        //
        //     // データ行の出力.
        //     $this->csvExportService->setExportQueryBuilder($qb);
        //     $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
        //         $Csvs = $csvService->getCsvs();
        //
        //         $Order = $entity;
        //         $OrderItems = $Order->getOrderItems();
        //
        //         foreach ($OrderItems as $OrderItem) {
        //             $ExportCsvRow = new ExportCsvRow();
        //
        //             // CSV出力項目と合致するデータを取得.
        //             foreach ($Csvs as $Csv) {
        //                 // 受注データを検索.
        //                 $ExportCsvRow->setData($csvService->getData($Csv, $Order));
        //                 if ($ExportCsvRow->isDataNull()) {
        //                     // 受注データにない場合は, 受注明細を検索.
        //                     $ExportCsvRow->setData($csvService->getData($Csv, $OrderItem));
        //                 }
        //                 if ($ExportCsvRow->isDataNull() && $Shipping = $OrderItem->getShipping()) {
        //                     // 受注明細データにない場合は, 出荷を検索.
        //                     $ExportCsvRow->setData($csvService->getData($Csv, $Shipping));
        //                 }
        //
        //                 $event = new EventArgs(
        //                     [
        //                         'csvService' => $csvService,
        //                         'Csv' => $Csv,
        //                         'OrderItem' => $OrderItem,
        //                         'ExportCsvRow' => $ExportCsvRow,
        //                     ],
        //                     $request
        //                 );
        //                 $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_ORDER_CSV_EXPORT_ORDER, $event);
        //
        //                 $ExportCsvRow->pushData();
        //             }
        //
        //             //$row[] = number_format(memory_get_usage(true));
        //             // 出力.
        //             $csvService->fputcsv($ExportCsvRow->getRow());
        //         }
        //     });
        // });
        //
        // $response->headers->set('Content-Type', 'application/octet-stream');
        // $response->headers->set('Content-Disposition', 'attachment; filename='.$fileName);
        // $response->send();
        //
        // return $response;
    }



    // /**
    //  * @Route("/%eccube_admin_route%/content/sqloutput/{id}", requirements={"id" = "\d+"}, name="admin_content_sqloutput_export", methods={"POST", "GET"})
    //  */
    // public function export(Request $request)
    // {
    //   // ★　CSV4のプラグインでのCSV出力ボタン、Goodbyを使ってる
    //     $Csv = $this->sqloutputRepository->findBy(['id' => $request->get('id')]);
    //
    //     if (empty($Csv[0])) {
    //         // error
    //         $this->addDanger('データがありません。', 'admin');
    //         return $this->redirectToRoute('admin_content_sqloutput');
    //     }
    //
    //     try {
    //         $conn = $this->get('database_connection');
    //         $stmt = $conn->prepare($Csv[0]->getName());
    //         $stmt->execute();
    //         // header
    //         $result = $stmt->fetch();
    //
    //         foreach($result as $key => $value)
    //         {
    //             $meta[] = $key;
    //         }
    //
    //         $config = new ExporterConfig();
    //         $config
    //             //->setDelimiter("\t") // Customize delimiter. Default value is comma(,)
    //             //->setEnclosure("'")  // Customize enclosure. Default value is double quotation(")
    //             //->setEscape("\\")    // Customize escape character. Default value is backslash(\)
    //             ->setToCharset('SJIS-win') // Customize file encoding. Default value is null, no converting.
    //             ->setFromCharset('UTF-8') // Customize source encoding. Default value is null.
    //             ->setFileMode(CsvFileObject::FILE_MODE_WRITE) // Customize file mode and choose either write or append. Default value is write ('w'). See fopen() php docs
    //             ->setColumnHeaders($meta)
    //         ;
    //
    //         $response = new StreamedResponse();
    //         $response->setStatusCode(200);
    //         $response->headers->set('Content-Type', 'text/csv');
    //         $response->headers->set('Content-Disposition', 'attachment; filename=export.csv');
    //         $response->setCallback(function() use($stmt, $config) {
    //             $exporter = new Exporter($config);
    //
    //             $exporter->export('php://output', new PdoCollection($stmt->getIterator()));
    //         });
    //         $response->send();
    //         return $response;
    //
    //     } catch (\Exception $e) {
    //         $this->addDanger($e->getMessage(), 'admin');
    //         return $this->redirectToRoute('admin_content_sqloutput');
    //     }
    // }

}
