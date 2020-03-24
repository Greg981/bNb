<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Pagination {
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;       
    }

    public function getPages()
    {
        // How many entry on the table
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        // make division; convert to next integer 3.4 => 4; and return Data
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    public function getData()
    {
        // Offset calculation
        $offset = $this->currentPage * $this->limit - $this->limit;
        // Ask Repository to find elements
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);
        // Send resquested elements
        return $data;
    }

    public function setPage($page)
    {
        $this->currentPage = $page;

        return $this;
    }

    public function getPage()
    {
        return $this->currentPage;
    }


    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setEntityClass($entityClass)    
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }
}