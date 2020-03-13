<?php

namespace Eccube\Entity;

use Doctrine\ORM\Mapping as ORM;

    /**
     * Sqloutput
     *
     * @ORM\Table(name="utb_csv_output")
     * @ORM\Entity(repositoryClass="Eccube\Repository\SqloutputRepository")
     */
    // class Sqloutput extends \Eccube\Entity\AbstractEntity
    class Sqloutput
    {
        /**
         * @var int
         *
         * @ORM\Column(name="id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var text
         *
         * @ORM\Column(name="name", type="text")
         */
        private $name;

        /**
         * @var text
         *
         * @ORM\Column(name="title", type="text")
         */
        private $title;

        /**
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @return string
         */
        public function getTitle()
        {
            return $this->title;
        }


        /**
         * @param int $id
         *
         * @return $this;
         */
        public function setId($id)
        {
            $this->id = $id;

            return $this;
        }

        /**
         * @param string $title
         *
         * @return $this;
         */
        public function setTitle($title)
        {
            $this->title = $title;

            return $this;
        }

        /**
         * @param string $name
         *
         * @return $this;
         */
        public function setName($name)
        {
            $this->name = $name;

            return $this;
        }

        //
        // /**
        //  * @var \DateTime|null
        //  *
        //  * @ORM\Column(name="publish_date", type="datetimetz", nullable=true)
        //  */
        // private $publish_date;
        //
        // /**
        //  * @var string
        //  *
        //  * @ORM\Column(name="title", type="string", length=255)
        //  */
        // private $title;
        //
        // /**
        //  * @var string|null
        //  *
        //  * @ORM\Column(name="description", type="text", nullable=true)
        //  */
        // private $description;
        //
        // /**
        //  * @var string|null
        //  *
        //  * @ORM\Column(name="url", type="string", length=4000, nullable=true)
        //  */
        // private $url;
        //
        // /**
        //  * @var boolean
        //  *
        //  * @ORM\Column(name="link_method", type="boolean", options={"default":false})
        //  */
        // private $link_method = false;
        //
        // /**
        //  * @var \DateTime
        //  *
        //  * @ORM\Column(name="create_date", type="datetimetz")
        //  */
        // private $create_date;
        //
        // /**
        //  * @var \DateTime
        //  *
        //  * @ORM\Column(name="update_date", type="datetimetz")
        //  */
        // private $update_date;
        //
        // /**
        //  * @var boolean
        //  *
        //  * @ORM\Column(name="visible", type="boolean", options={"default":true})
        //  */
        // private $visible;
        //
        // /**
        //  * @var \Eccube\Entity\Member
        //  *
        //  * @ORM\ManyToOne(targetEntity="Eccube\Entity\Member")
        //  * @ORM\JoinColumns({
        //  *   @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
        //  * })
        //  */
        // private $Creator;
        //
        // /**
        //  * Get id.
        //  *
        //  * @return int
        //  */
        // public function getId()
        // {
        //     return $this->id;
        // }
        //
        // /**
        //  * Set publishDate.
        //  *
        //  * @param \DateTime|null $publishDate
        //  *
        //  * @return $this
        //  */
        // public function setPublishDate($publishDate = null)
        // {
        //     $this->publish_date = $publishDate;
        //
        //     return $this;
        // }
        //
        // /**
        //  * Get publishDate.
        //  *
        //  * @return \DateTime|null
        //  */
        // public function getPublishDate()
        // {
        //     return $this->publish_date;
        // }
        //
        // /**
        //  * Set title.
        //  *
        //  * @param string $title
        //  *
        //  * @return $this
        //  */
        // public function setTitle($title)
        // {
        //     $this->title = $title;
        //
        //     return $this;
        // }
        //
        // /**
        //  * Get title.
        //  *
        //  * @return string
        //  */
        // public function getTitle()
        // {
        //     return $this->title;
        // }
        //
        // /**
        //  * Set description.
        //  *
        //  * @param string|null $description
        //  *
        //  * @return $this
        //  */
        // public function setDescription($description = null)
        // {
        //     $this->description = $description;
        //
        //     return $this;
        // }
        //
        // /**
        //  * Get description.
        //  *
        //  * @return string|null
        //  */
        // public function getDescription()
        // {
        //     return $this->description;
        // }
        //
        // /**
        //  * Set url.
        //  *
        //  * @param string|null $url
        //  *
        //  * @return $this
        //  */
        // public function setUrl($url = null)
        // {
        //     $this->url = $url;
        //
        //     return $this;
        // }
        //
        // /**
        //  * Get url.
        //  *
        //  * @return string|null
        //  */
        // public function getUrl()
        // {
        //     return $this->url;
        // }
        //
        // /**
        //  * Set linkMethod.
        //  *
        //  * @param boolean $linkMethod
        //  *
        //  * @return $this
        //  */
        // public function setLinkMethod($linkMethod)
        // {
        //     $this->link_method = $linkMethod;
        //
        //     return $this;
        // }
        //
        // /**
        //  * Get linkMethod.
        //  *
        //  * @return boolean
        //  */
        // public function isLinkMethod()
        // {
        //     return $this->link_method;
        // }
        //
        // /**
        //  * Set createDate.
        //  *
        //  * @param \DateTime $createDate
        //  *
        //  * @return $this
        //  */
        // public function setCreateDate($createDate)
        // {
        //     $this->create_date = $createDate;
        //
        //     return $this;
        // }
        //
        // /**
        //  * Get createDate.
        //  *
        //  * @return \DateTime
        //  */
        // public function getCreateDate()
        // {
        //     return $this->create_date;
        // }
        //
        // /**
        //  * Set updateDate.
        //  *
        //  * @param \DateTime $updateDate
        //  *
        //  * @return $this
        //  */
        // public function setUpdateDate($updateDate)
        // {
        //     $this->update_date = $updateDate;
        //
        //     return $this;
        // }
        //
        // /**
        //  * Get updateDate.
        //  *
        //  * @return \DateTime
        //  */
        // public function getUpdateDate()
        // {
        //     return $this->update_date;
        // }
        //
        // /**
        //  * @return integer
        //  */
        // public function isVisible()
        // {
        //     return $this->visible;
        // }
        //
        // /**
        //  * @param boolean $visible
        //  *
        //  * @return $this
        //  */
        // public function setVisible($visible)
        // {
        //     $this->visible = $visible;
        //
        //     return $this;
        // }
        //
        // /**
        //  * Set creator.
        //  *
        //  * @param \Eccube\Entity\Member|null $creator
        //  *
        //  * @return $this
        //  */
        // public function setCreator(\Eccube\Entity\Member $creator = null)
        // {
        //     $this->Creator = $creator;
        //
        //     return $this;
        // }
        //
        // /**
        //  * Get creator.
        //  *
        //  * @return \Eccube\Entity\Member|null
        //  */
        // public function getCreator()
        // {
        //     return $this->Creator;
        // }
    }
