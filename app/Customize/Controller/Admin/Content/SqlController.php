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

namespace Customize\Controller\Admin\Content;

/* use Customize\Entity\Sql; */
/* use Customize\Repository\SqlRepository; */

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\Paginator;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;
use Goodby\CSV\Export\Standard\CsvFileObject;
use Goodby\CSV\Export\Standard\Collection\PdoCollection;
use Eccube\Util\CacheUtil;

/* use Customize\Form\Type\Admin\SqlType; */

class SqlController extends AbstractController
{
    /**
     * @Route("/%eccube_admin_route%/content/sqloutput", name="admin_content_sqloutput")
     * @Template("@admin/Content/sqloutput.twig")
     */
	public function index(Request $request)
	{

	}
}