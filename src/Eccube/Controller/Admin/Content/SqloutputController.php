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
     * @param $csvTypeId
     * @param string $fileName
     *
     * @return StreamedResponse
     * @Route("/%eccube_admin_route%/content/sqloutput/{id}", requirements={"id" = "\d+"}, name="admin_content_sqloutput_export", methods={"POST", "GET"})
     */

    protected function exportCsv(Request $request, $csvTypeId, $fileName)
    {
        // タイムアウトを無効にする.
        set_time_limit(0);

        // sql loggerを無効にする.
        $em = $this->entityManager;
        $em->getConfiguration()->setSQLLogger(null);

        $response = new StreamedResponse();
        $response->setCallback(function () use ($request, $csvTypeId) {
            // CSV種別を元に初期化.
            $this->csvExportService->initCsvType($csvTypeId);

            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            // 受注データ検索用のクエリビルダを取得.
            $qb = $this->csvExportService
                ->getOrderQueryBuilder($request);

            // データ行の出力.
            $this->csvExportService->setExportQueryBuilder($qb);
            $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
                $Csvs = $csvService->getCsvs();

                $Order = $entity;
                $OrderItems = $Order->getOrderItems();

                foreach ($OrderItems as $OrderItem) {
                    $ExportCsvRow = new ExportCsvRow();

                    // CSV出力項目と合致するデータを取得.
                    foreach ($Csvs as $Csv) {
                        // 受注データを検索.
                        $ExportCsvRow->setData($csvService->getData($Csv, $Order));
                        if ($ExportCsvRow->isDataNull()) {
                            // 受注データにない場合は, 受注明細を検索.
                            $ExportCsvRow->setData($csvService->getData($Csv, $OrderItem));
                        }
                        if ($ExportCsvRow->isDataNull() && $Shipping = $OrderItem->getShipping()) {
                            // 受注明細データにない場合は, 出荷を検索.
                            $ExportCsvRow->setData($csvService->getData($Csv, $Shipping));
                        }

                        $event = new EventArgs(
                            [
                                'csvService' => $csvService,
                                'Csv' => $Csv,
                                'OrderItem' => $OrderItem,
                                'ExportCsvRow' => $ExportCsvRow,
                            ],
                            $request
                        );
                        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_ORDER_CSV_EXPORT_ORDER, $event);

                        $ExportCsvRow->pushData();
                    }

                    //$row[] = number_format(memory_get_usage(true));
                    // 出力.
                    $csvService->fputcsv($ExportCsvRow->getRow());
                }
            });
        });

        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$fileName);
        $response->send();

        return $response;
    }



    /**
     * @Route("/%eccube_admin_route%/content/sqloutput/{id}", requirements={"id" = "\d+"}, name="admin_content_sqloutput_export", methods={"POST", "GET"})
     */
    public function export(Request $request)
    {
        $Csv = $this->sqloutputRepository->findBy(['id' => $request->get('id')]);

        if (empty($Csv[0])) {
            // error
            $this->addDanger('データがありません。', 'admin');
            return $this->redirectToRoute('admin_content_sqloutput');
        }

        try {
            $conn = $this->get('database_connection');
            $stmt = $conn->prepare($Csv[0]->getName());
            $stmt->execute();
            // header
            $result = $stmt->fetch();

            foreach($result as $key => $value)
            {
                $meta[] = $key;
            }

            $config = new ExporterConfig();
            $config
                //->setDelimiter("\t") // Customize delimiter. Default value is comma(,)
                //->setEnclosure("'")  // Customize enclosure. Default value is double quotation(")
                //->setEscape("\\")    // Customize escape character. Default value is backslash(\)
                ->setToCharset('SJIS-win') // Customize file encoding. Default value is null, no converting.
                ->setFromCharset('UTF-8') // Customize source encoding. Default value is null.
                ->setFileMode(CsvFileObject::FILE_MODE_WRITE) // Customize file mode and choose either write or append. Default value is write ('w'). See fopen() php docs
                ->setColumnHeaders($meta)
            ;

            $response = new StreamedResponse();
            $response->setStatusCode(200);
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename=export.csv');
            $response->setCallback(function() use($stmt, $config) {
                $exporter = new Exporter($config);

                $exporter->export('php://output', new PdoCollection($stmt->getIterator()));
            });
            $response->send();
            return $response;

        } catch (\Exception $e) {
            $this->addDanger($e->getMessage(), 'admin');
            return $this->redirectToRoute('admin_content_sqloutput');
        }
    }



    // {
    //     $qb = $this->sqloutputRepository->getPageList();
    //
    //     $event = new EventArgs(
    //         [
    //             'qb' => $qb,
    //         ],
    //         $request
    //     );
    //     $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_SQLOUTPUT_INDEX_INITIALIZE, $event);
    //
    //
    //     return [
    //         'pagination' => $pagination,
    //     ];
    // }

    // /**
    //  * 新着情報を登録・編集する。
    //  *
    //  * @Route("/%eccube_admin_route%/content/sqloutput/new", name="admin_content_sqloutput_new")
    //  * @Route("/%eccube_admin_route%/content/sqloutput/{id}/edit", requirements={"id" = "\d+"}, name="admin_content_sqloutput_edit")
    //  * @Template("@admin/Content/sqloutput_edit.twig")
    //  *
    //  * @param Request $request
    //  * @param null $id
    //  *
    //  * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
    //  */
    // public function edit(Request $request, $id = null, CacheUtil $cacheUtil)
    // {
    //     if ($id) {
    //         $Sqloutput = $this->sqloutputRepository->find($id);
    //         if (!$Sqloutput) {
    //             throw new NotFoundHttpException();
    //         }
    //     } else {
    //         $Sqloutput = new \Eccube\Entity\Sqloutput();
    //         $Sqloutput->setPublishDate(new \DateTime());
    //     }
    //
    //     $builder = $this->formFactory
    //         ->createBuilder(SqloutputType::class, $Sqloutput);
    //
    //     $event = new EventArgs(
    //         [
    //             'builder' => $builder,
    //             'Sqloutput' => $Sqloutput,
    //         ],
    //         $request
    //     );
    //     $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_SQLOUTPUT_EDIT_INITIALIZE, $event);
    //
    //     $form = $builder->getForm();
    //     $form->handleRequest($request);
    //
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         if (!$Sqloutput->getUrl()) {
    //             $Sqloutput->setLinkMethod(false);
    //         }
    //         $this->sqloutputRepository->save($Sqloutput);
    //
    //         $event = new EventArgs(
    //             [
    //                 'form' => $form,
    //                 'Sqloutput' => $Sqloutput,
    //             ],
    //             $request
    //         );
    //         $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_SQLOUTPUT_EDIT_COMPLETE, $event);
    //
    //         $this->addSuccess('admin.common.save_complete', 'admin');
    //
    //         // キャッシュの削除
    //         $cacheUtil->clearDoctrineCache();
    //
    //         return $this->redirectToRoute('admin_content_sqloutput_edit', ['id' => $Sqloutput->getId()]);
    //     }
    //
    //     return [
    //         'form' => $form->createView(),
    //         'Sqloutput' => $Sqloutput,
    //     ];
    // }

    // /**
    //  * 指定した新着情報を削除する。
    //  *
    //  * @Route("/%eccube_admin_route%/content/sqloutput/{id}/delete", requirements={"id" = "\d+"}, name="admin_content_sqloutput_delete", methods={"DELETE"})
    //  *
    //  * @param Request $request
    //  * @param Sqloutput $Sqloutput
    //  *
    //  * @return \Symfony\Component\HttpFoundation\RedirectResponse
    //  */
    // public function delete(Request $request, Sqloutput $Sqloutput, CacheUtil $cacheUtil)
    // {
    //     $this->isTokenValid();
    //
    //     log_info('新着情報削除開始', [$Sqloutput->getId()]);
    //
    //     try {
    //         $this->sqloutputRepository->delete($Sqloutput);
    //
    //         $event = new EventArgs(['Sqloutput' => $Sqloutput], $request);
    //         $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_SQLOUTPUT_DELETE_COMPLETE, $event);
    //
    //         $this->addSuccess('admin.common.delete_complete', 'admin');
    //
    //         log_info('新着情報削除完了', [$Sqloutput->getId()]);
    //
    //         // キャッシュの削除
    //         $cacheUtil->clearDoctrineCache();
    //     } catch (\Exception $e) {
    //         $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $Sqloutput->getTitle()]);
    //         $this->addError($message, 'admin');
    //
    //         log_error('新着情報削除エラー', [$Sqloutput->getId(), $e]);
    //     }
    //
    //     return $this->redirectToRoute('admin_content_sqloutput');
    // }
}
