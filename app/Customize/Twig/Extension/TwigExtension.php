<?php
/*
 *
 * Copyright (C) SPREAD WORKS Inc. All Rights Reserved.
 *
 */

namespace Customize\Twig\Extension;

use Doctrine\Common\Collections;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Master\ProductStatus;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Eccube\Repository\ProductRepository;

class TwigExtension extends \Twig_Extension
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * TwigExtension constructor.
     *
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EccubeConfig $eccubeConfig, 
        ProductRepository $productRepository
    ) {
        $this->entityManager = $entityManager;
        $this->eccubeConfig = $eccubeConfig;
        $this->productRepository = $productRepository;
    }
    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('CustomizeNewProduct', array($this, 'getCustomizeNewProduct')),
        );
    }

    /**
     * Name of this extension
     *
     * @return string
     */
    public function getName()
    {
        return 'CustomizeTwigExtension';
    }

    /**
     * 
     * �V�����i3���Ԃ�
     * 
     * @return Products|null
     */
    public function getCustomizeNewProduct()
    {
        try {
            // ������productRepository�𗘗p���A���i�����擾

            // ���������̐V�������`
            $searchData = array();
            $qb = $this->entityManager->createQueryBuilder();
            $query = $qb->select("plob")
                ->from("Eccube\\Entity\\Master\\ProductListOrderBy", "plob")
                ->where('plob.id = :id')
                ->setParameter('id', $this->eccubeConfig['eccube_product_order_newer'])
                ->getQuery();
            $searchData['orderby'] = $query->getOneOrNullResult();

            // �V�����̏��i���3���擾
            $qb = $this->productRepository->getQueryBuilderBySearchData($searchData);
            $query = $qb->setMaxResults(3)->getQuery();
            $products = $query->getResult();
            return $products;

        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}
