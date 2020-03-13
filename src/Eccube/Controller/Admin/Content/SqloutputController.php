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
use Eccube\Form\Type\Admin\NewsType;
// use Eccube\Repository\NewsRepository;
use Eccube\Repository\SqloutputRepository;
use Eccube\Util\CacheUtil;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SqloutputController extends AbstractController
{
    /**
     * @var SqloutputRepository
     */
    protected $sqloutputRepository;

    /**
     * SqloutputController constructor.
     *
     * @param SqloutputRepository $sqloutputRepository
     */
    public function __construct(SqloutputRepository $sqloutputRepository)
    {
        $this->sqloutputRepository = $sqloutputRepository;
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
     */
    public function index(Request $request)
    {
        $Outputs = $this->sqloutputRepository->getPageList();
        // $qb = $this->newsRepository->getQueryBuilderAll();

        $event = new EventArgs(
            [
                'Outputs' => $Outputs,
                // 'pagination' => $qb,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_SQLOUTPUT_INDEX_INITIALIZE, $event);
        // $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CONTENT_NEWS_INDEX_INITIALIZE, $event);

        return [
            'Outputs' => $Outputs,
            // 'pagination' => $qb,
        ];
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
