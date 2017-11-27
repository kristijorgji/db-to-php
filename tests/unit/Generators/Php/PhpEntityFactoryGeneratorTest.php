<?php

namespace kristijorgji\UnitTests\Generators\Php;

use kristijorgji\DbToPhp\Db\FieldsCollection;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpClassGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityFactoryGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryGenerator;
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\Tests\Factories\Db\FieldsCollectionFactory;
use kristijorgji\Tests\Helpers\TestCase;

class PhpEntityFactoryGeneratorTest extends TestCase
{
    /**
     * @dataProvider generateProvider
     * @param PhpEntityFactoryGeneratorConfig $config
     * @param FieldsCollection $fields
     * @param string $entityClassName
     * @param string $expected
     */
    public function testGenerate(
        PhpEntityFactoryGeneratorConfig $config,
        FieldsCollection $fields,
        string $entityClassName,
        string $expected
    )
    {
        $entityGenerator = new PhpEntityFactoryGenerator(
            $config,
            $fields,
            $entityClassName
        );

        $actual = $entityGenerator->generate();

        $this->assertEquals($expected, $actual);
    }

    public function generateProvider()
    {
        $expected = self::getExpected(__DIR__ . '/expected/entity_factory_generator.txt');

        $phpClassGeneratorConfig = new PhpClassGeneratorConfig(
            'App\Factories\Entities',
            'TestEntity',
            new StringCollection(...['Entities\TestEntity']),
            null
        );

        return [
            'type_hinted' => [
                new PhpEntityFactoryGeneratorConfig(
                    $phpClassGeneratorConfig,
                    true
                ),
                FieldsCollectionFactory::make(),
                'SuperEntity',
                $expected['type_hinted']
            ]
        ];
    }
}
