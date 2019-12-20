<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * @internal
 */
class CreateEntityWebTestCase extends WebTestCase
{
    /** @var Client */
    protected $client;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var PropertyAccessor */
    protected $propertyAccessor;

    protected function setUp(): void
    {
        parent::setUp();

        if (null !== static::$kernel) {
            static::$kernel->shutdown();
        }

        $this->client = self::createClient();
        $this->client->disableReboot();
        $this->em = self::$container->get('doctrine.orm.entity_manager');
        $this->em->beginTransaction();
        $this->em->getConnection()->setAutoCommit(false);
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    protected function tearDown(): void
    {
        $this->em->rollback();
        $this->em->close();
        $this->em = null;
        $this->propertyAccessor = null;
        $this->client = null;

        parent::tearDown();
    }

    protected function createEntity(string $class, array $data, bool $flush = true)
    {
        $entity = new $class();

        foreach ($data as $field => $value) {
            $this->propertyAccessor->setValue($entity, $field, $value);
        }

        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }

        return $entity;
    }
}
