<?php

namespace Eccube\Repository;

use Eccube\Repository\AbstractRepository;
use Eccube\Entity\Sqloutput;
use Symfony\Bridge\Doctrine\RegistryInterface;

// use Doctrine\Common\Collections\ArrayCollection;
// use Doctrine\Common\Collections\Criteria;
// use Doctrine\DBAL\Exception\DriverException;
// use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;


/**
 * SqloutputRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SqloutputRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sqloutput::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderAll()
    {
        $qb = $this->createQueryBuilder('s');
        // $qb->orderBy('s.id', 'DESC');

        return $qb;
    }

//     /**
//      * @return \Doctrine\ORM\QueryBuilder
     // */
public function getPageList()
{
  $qb = $this->createQueryBuilder('s');

  // $qb->getQuery();
  // ->getResult();

  return $qb;

}


    //
    // /**
    //  * @return Sqloutput[]|ArrayCollection
    //  */
    // public function getList()
    // {
    //     // second level cacheを効かせるためfindByで取得
    //     $Results = $this->findBy(['visible' => true], ['publish_date' => 'DESC', 'id' => 'DESC']);
    //
    //     // 公開日時前のNewsをフィルター
    //     $criteria = Criteria::create();
    //     $criteria->where(Criteria::expr()->lte('publish_date', new \DateTime()));
    //
    //     $Sqloutput = new ArrayCollection($Results);
    //
    //     return $Sqloutput->matching($criteria);
    // }
}
