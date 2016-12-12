<?php
/**
 * Created by IntelliJ IDEA.
 * User: evgesha
 * Date: 11.12.16
 * Time: 12:51
 */

namespace Evgit\Bundle\WidgetBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;

class QueryBuilder
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @param RegistryInterface $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function setOptions(){

    }

}