<?php declare(strict_types = 1);

namespace kristijorgji\DbToPhp\Managers\Php;

use kristijorgji\DbToPhp\Data\AbstractEntityFactory;
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
use kristijorgji\DbToPhp\Managers\Php\Resolvers\PhpEntityFactoryFieldFunctionResolver;
use kristijorgji\DbToPhp\Mappers\Types\Php\PhpTypeMapperInterface;
use kristijorgji\DbToPhp\Support\StringCollection;
use Throwable;

class PhpEntityFactoryManager extends AbstractPhpManager
{
    public function __construct(
        DatabaseAdapterInterface $databaseAdapter,
        PhpTypeMapperInterface $typeMapper,
        FileSystemInterface $fileSystem,
        bool $typeHint,
        private array $config,
        private readonly PhpEntityManager $entityManager,
    ) {
        parent::__construct($databaseAdapter, $typeMapper, $fileSystem, $typeHint);
    }

    /**
     * @throws GenerateException
     */
    public function generateFactories() : GenerateResponse
    {
        $response = new GenerateResponse;

        $tables = $this->filterTables(
            $this->databaseAdapter->getTables(),
            $this->config['includeTables'],
        )->all();

        try {
            foreach ($tables as $table) {
                $response->addPath($this->generateFactory($table->getName()));
            }
        } catch (Throwable $e) {
            throw new GenerateException($e->getMessage(), $e, $response);
        }

        return $response;
    }

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
                        AbstractEntityFactory::class,
                        $fullyQualifiedEntityClassName,
                    ]),
                    $this->stripClassName(AbstractEntityFactory::class),
                ),
                $this->typeHint,
                $this->config['includeAnnotations'],
            ),
            $this->formGeneratorFieldsDetails($fields),
            $entityClassName,
        );

        $entityFactoryFileAsString = $entityFactoryGenerator->generate();
        $entityFactoryFileName = $className . '.php';

        if (!$this->fileSystem->exists($this->config['outputDirectory'])) {
            $this->fileSystem->createDirectory($this->config['outputDirectory'], true);
        }

        $outputPath = $this->config['outputDirectory'] . '/' . $entityFactoryFileName;

        $this->fileSystem->write(
            $outputPath,
            $entityFactoryFileAsString,
        );

        return $outputPath;
    }

    public function formClassName(string $tableName, string $entityClassName) : string
    {
        if (!isset($this->config['tableToEntityFactoryClassName'][$tableName])) {
            return $entityClassName . 'Factory';
        }

        return $this->config['tableToEntityFactoryClassName'][$tableName];
    }

    public function formGeneratorFieldsDetails(FieldsCollection $fields) : PhpEntityFactoryFieldsCollection
    {
        $generatorFields = [];
        $fieldResolver = new PhpEntityFactoryFieldFunctionResolver;

        foreach ($fields->all() as $field) {
            $generatorFields[] = new PhpEntityFactoryField(
                $field->getName(),
                $fieldResolver->resolve($field),
            );
        }

        return new PhpEntityFactoryFieldsCollection(... $generatorFields);
    }
}
