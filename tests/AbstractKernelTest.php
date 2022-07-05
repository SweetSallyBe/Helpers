<?php

namespace SweetSallyBe\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractKernelTest extends KernelTestCase
{
    protected static function loadFixtures(array $fixtures)
    {
        $container = self::getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');
        foreach ($fixtures as $fixture) {
            $realFixtures = $container->get($fixture);
            $realFixtures->load($entityManager);
        }
    }

    protected static function truncateEntities(array $entities)
    {
        $container = self::getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $connection = $entityManager->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');
        }
        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $entityManager->getClassMetadata($entity)->getTableName()
            );
            $connection->executeQuery($query);
        }
        if ($databasePlatform->supportsForeignKeyConstraints()) {
            $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}