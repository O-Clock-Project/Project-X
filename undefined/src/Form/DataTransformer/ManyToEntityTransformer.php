<?php

namespace App\Form\DataTransformer;

use App\Entity\Bookmark;

use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;


class ManyToEntityTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $class;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->class = "App\Entity\Bookmark";
    }

    /**
     * Transforms an object (use) to a string (id).
     *
     * @param  array           $array
     * @return ArrayCollection
     */
    public function transform($array)
    {
        $newArray = array();

        if (!($array instanceof PersistentCollection)) {
            return new ArrayCollection();
        }

        foreach ($array as $key => $value) {
            $newArray[] = $value;
        }

        return new ArrayCollection($newArray);
    }

    /**
     * Transforms a string (id) to an object (item).
     *
     * @param  string $id
     * @return PersistentCollection|ArrayCollection
     */
    public function reverseTransform($array)
    {
        $newArray = array();

        if (!$array) {
            return new ArrayCollection();
        }

        foreach ($array as $key => $value) {
            $item = $this->em
                ->getRepository($this->class)
                ->findOneBy(array('id' => $value))
            ;

            if (!is_null($item)) {
                $newArray[$key] = $item;
            }
        }
        dump($newArray);die;

        return new PersistentCollection($this->em, $this->class, new ArrayCollection($newArray));
    }
}