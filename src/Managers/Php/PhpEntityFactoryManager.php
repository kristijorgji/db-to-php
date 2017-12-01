<?php

namespace kristijorgji\DbToPhp\Managers\Php;

use kristijorgji\DbToPhp\AppInfo;
use kristijorgji\DbToPhp\Db\Adapters\DatabaseAdapterInterface;
use kristijorgji\DbToPhp\Db\Fields\FieldsCollection;
use kristijorgji\DbToPhp\FileSystem\FileSystemInterface;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpClassGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\Configs\PhpEntityFactoryGeneratorConfig;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryField;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryFieldsCollection;
use kristijorgji\DbToPhp\Generators\Php\PhpEntityFactoryGenerator;
use kristijorgji\DbToPhp\Managers\Exceptions\GenerateException;
use kristijorgji\DbToPhp\Managers\GenerateResponse;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use kristijorgji\DbToPhp\Support\StringCollection;
use kristijorgji\DbToPhp\Managers\Php\Resolvers\PhpEntityFactoryFieldFunctionResolver;

class PhpEntityFactoryManager extends AbstractPhpManager
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var PhpEntityManager
     */
    private $entityManager;

    /**
     * @param DatabaseAdapterInterface $databaseAdapter
     * @param PhpTypeMapperInterface $typeMapper
     * @param FileSystemInterface $fileSystem
     * @param bool $typeHint
     * @param array $config
     * @param PhpEntityManager $entityManager
     */
    public function __construct(
        DatabaseAdapterInterface $databaseAdapter,
        PhpTypeMapperInterface $typeMapper,
        FileSystemInterface $fileSystem,
        bool $typeHint,
        array $config,
        PhpEntityManager $entityManager
    )
    {
        parent::__construct($databaseAdapter, $typeMapper, $fileSystem, $typeHint);
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    /**
     * @return GenerateResponse
     * @throws GenerateException
     */
    public function generateFactories() : GenerateResponse
    {
        $response = new GenerateResponse();

        $tables = $this->filterTables(
            $this->databaseAdapter->getTables(),
            $this->config['includeTables']
        )->all();

        if (!$this->fileSystem->exists($this->config['outputDirectory'])) {
            $this->fileSystem->createDirectory($this->config['outputDirectory'], true);
        }

        $baseEntityFactory = $this->fileSystem->readFile(AppInfo::ABSTRACT_ENTITY_FACTORY_PATH);
        $baseEntityFactory = $this->changeNameSpaceOfBaseEntityFactory(
            $baseEntityFactory,
            $this->config['namespace']
        );
        $this->fileSystem->write(
            $this->config['outputDirectory'] . DIRECTORY_SEPARATOR . $this->getAbstractEntityFactoryFileName(),
            $baseEntityFactory
        );

        try {
            foreach ($tables as $table) {
                $response->addPath($this->generateFactory($table->getName()));
            }
        } catch (\Exception $e) {
            throw new GenerateException($e->getMessage(), $e, $response);
        }

        return $response;
    }

    /**
     * @param string $baseEntityFactory
     * @param string $namespace
     * @return string
     */
    private function changeNameSpaceOfBaseEntityFactory(
        string $baseEntityFactory,
        string $namespace
    ) : string
    {
        return preg_replace('#namespace.*#', sprintf('namespace %s;', $namespace), $baseEntityFactory);
    }

    /**
     * @param string $tableName
     * @return string
     */
    public function generateFactory(string $tableName) : string
    {
        $entityClassName = $this->entityManager->formClassName($tableName);
        $className = $this->formClassName($tableName, $entityClassName);
        $fields = $this->databaseAdapter->getFields($tableName);
        $fullyQualifiedEntityClassName = $this->entityManager->getEntitiesNamespace() . '\\' . $entityClassName;

        $entityFactoryGenerator = new PhpEntityFactoryGenerator(
            new PhpEntityFactoryGeneratorConfig(
                new  PhpClassGeneratorConfig(
                    $this->config['namespace'],
                    $className,
                    new StringCollection(... [
                        $fullyQualifiedEntityClassName
                    ]),
                    $this->getAbstractEntityFactoryClassName()
                ),
                $this->typeHint,
                $this->config['includeAnnotations']
            ),
            $this->formGeneratorFieldsDetails($fields),
            $entityClassName
        );

        $entityFactoryFileAsString = $entityFactoryGenerator->generate();
        $entityFactoryFileName = $className . '.php';

        $outputPath = $this->config['outputDirectory'] . '/' . $entityFactoryFileName;

        $this->fileSystem->write(
            $outputPath,
            $entityFactoryFileAsString
        );

        return $outputPath;
    }

    /**
     * @param string $tableName
     * @param string $entityClassName
     * @return string
     */
    public function formClassName(string $tableName, string $entityClassName) : string
    {
        if (!isset($this->config['tableToEntityFactoryClassName'][$tableName])) {
            return $entityClassName . 'Factory';
        }

        return $this->config['tableToEntityFactoryClassName'][$tableName];
    }

    /**
     * @return string
     */
    public function getAbstractEntityFactoryFileName() : string
    {
        return basename(AppInfo::ABSTRACT_ENTITY_FACTORY_PATH);
    }

    /**
     * @return string
     */
    public function getAbstractEntityFactoryClassName() : string
    {
        return str_replace('.php', '', $this->getAbstractEntityFactoryFileName());
    }

    /**
     * @param FieldsCollection $fields
     * @return PhpEntityFactoryFieldsCollection
     */
    public function formGeneratorFieldsDetails(FieldsCollection $fields) : PhpEntityFactoryFieldsCollection
    {
        $generatorFields = [];
        $fieldResolver = new PhpEntityFactoryFieldFunctionResolver();


        foreach ($fields->all() as $field) {
            $generatorFields[] = new PhpEntityFactoryField(
                $field->getName(),
                $fieldResolver->resolve($field)
            );
        }

        return new PhpEntityFactoryFieldsCollection(... $generatorFields);
    }
}
