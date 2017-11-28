<?php

namespace kristijorgji\UnitTests\Generators\Php;


use kristijorgji\DbToPhp\Generators\Php\Configs\PhpClassGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityFactoryGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryField;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryFieldsCollection;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryGenerator;
use kristijorgji\DbToPhp\Rules\Php\PhpType;
use kristijorgji\DbToPhp\Rules\Php\PhpTypes;
use kristijorgji\DbToPhp\Support\StringCollection;
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
            $entityClassName
        );

        $actual = $entityGenerator->generate();

        $this->assertEquals($expected, $actual);
    }

    public function generateProvider()
    {
        $expected = self::getExpected(__DIR__ . '/expected/entity_factory_generator.txt');

        $entityClassName = 'TestEntity';
        $qualifiedEntityClassName = 'Entities\TestEntity';

        $phpClassGeneratorConfig = new PhpClassGeneratorConfig(
            'App\Factories\Entities',
            'TestEntityFactory',
            new StringCollection(...[$qualifiedEntityClassName]),
            'BaseEntityFactory'
        );

        $generatorFields = new PhpEntityFactoryFieldsCollection(... [
           new PhpEntityFactoryField(
               'status',
               'self::randomInt32()'

           ),
            new PhpEntityFactoryField(
                'credit_value',
                'self::randomInt16()'
            )
        ]);

        return [
            'type_hinted_annotations' => [
                new PhpEntityFactoryGeneratorConfig(
                    $phpClassGeneratorConfig,
                    true,
                    true
                ),
                $generatorFields,
                $entityClassName,
                $expected['type_hinted_annotations']
            ],
            'type_hinted_no_annotations' => [
                new PhpEntityFactoryGeneratorConfig(
                    $phpClassGeneratorConfig,
                    true,
                    false
                ),
                $generatorFields,
                $entityClassName,
                $expected['type_hinted_no_annotations']
            ],
            'not_type_hinted_annotations' => [
                new PhpEntityFactoryGeneratorConfig(
                    $phpClassGeneratorConfig,
                    false,
                    true
                ),
                $generatorFields,
                $entityClassName,
                $expected['not_type_hinted_annotations']
            ],
            'not_type_hinted_no_annotations' => [
                new PhpEntityFactoryGeneratorConfig(
                    $phpClassGeneratorConfig,
                    false,
                    false
                ),
                $generatorFields,
                $entityClassName,
                $expected['not_type_hinted_no_annotations']
            ]
        ];
    }
}
