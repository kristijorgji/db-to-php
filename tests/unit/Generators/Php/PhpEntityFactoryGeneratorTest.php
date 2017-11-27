<?php

namespace kristijorgji\UnitTests\Generators\Php;


use kristijorgji\DbToPhp\Generators\Php\Configs\PhpClassGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityFactoryGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryField;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryFieldsCollection;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryGenerator;
use kristijorgji\DbToPhp\Generators\Resolvers\PhpEntityFactoryFieldFunctionResolver;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\Tests\Factories\Generators\PhpEntityFactoryFieldsCollectionFactory;
use kristijorgji\Tests\Helpers\TestCase;

class PhpEntityFactoryGeneratorTest extends TestCase
{
    /**
     * @dataProvider generateProvider
     * @param PhpEntityFactoryGeneratorConfig $config
     * @param PhpEntityFactoryFieldsCollection $fields
     * @param string $entityClassName
     * @param string $expected
     */
    public function testGenerate(
        PhpEntityFactoryGeneratorConfig $config,
        PhpEntityFactoryFieldsCollection $fields,
        string $entityClassName,
        string $expected
    )
    {
        $entityGenerator = new PhpEntityFactoryGenerator(
            $config,
            $fields,
            new PhpEntityFactoryFieldFunctionResolver(),
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
            'BaseEntityFactory'
        );

        $generatorFields = new PhpEntityFactoryFieldsCollection(... [
           new PhpEntityFactoryField(
               'status',
               'status',
               new PhpType(new PhpTypes(PhpTypes::INTEGER), true),
               24,
               false
           ),
            new PhpEntityFactoryField(
                'credit_value',
                'creditValue',
                new PhpType(new PhpTypes(PhpTypes::INTEGER), true),
                8,
                true
            ),
            new PhpEntityFactoryField(
                'name',
                'name',
                new PhpType(new PhpTypes(PhpTypes::STRING), true),
                20,
                true
            ),
        ]);

        return [
            'type_hinted' => [
                new PhpEntityFactoryGeneratorConfig(
                    $phpClassGeneratorConfig,
                    true,
                    true
                ),
                $generatorFields,
                'SuperEntity',
                $expected['type_hinted']
            ]
        ];
    }
}
