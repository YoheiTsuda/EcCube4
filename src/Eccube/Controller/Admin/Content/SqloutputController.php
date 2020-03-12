<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Controller\Admin\Content;

use Eccube\Controller\AbstractController;
// use Eccube\Entity\Sqloutput;
use Eccube\Entity\News;
use Eccube\Event\EventArgs;
use Eccube\Event\EccubeEvents;
// use Eccube\Form\Type\Admin\SqloutputType;
// use Eccube\Repository\SqloutputRepository;
use Eccube\Repository\NewsRepository;
use Eccube\Util\CacheUtil;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;
use Goodby\CSV\Export\Standard\CsvFileObject;
use Goodby\CSV\Export\Standard\Collection\PdoCollection;


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



    /**
     * @Route("/%eccube_admin_route%/content/sqloutput", name="admin_content_sqloutput")
     * @Template("@admin/Content/sqloutput.twig")
     */
		 public function index(Request $request, Paginator $paginator)
     {
         $qb = $this->sqloutputRepository->getQueryBuilderAll();
         $pagination = $paginator->paginate(
             $qb,
             1,
             100
         );

	}
}
