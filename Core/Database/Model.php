<?php

namespace Core\Database;

use Core\Database\Complement\Casts;
use Core\Database\Driver\MySQL;
use Core\Database\Driver\PostgresSQL;
use Core\Loaders\Config;
use Core\Support\Collection;

class Model
{
    use Casts;

    /**
     * Tabla en la base de datos
     */
    protected string $table = '';

    /**
     * Llave primaria de la tabla
     */
    protected string $primaryKey = 'id';

    /**
     * Columnas que se pueden editar y crear
     */
    protected array $fillable = [];

    /**
     * Columnas ocultas en las respuestas
     *
     * @var array
     */
    protected array $hidden = [];

    /**
     * Driver de conexión
     */
    protected PostgresSQL|MySQL $driver;

    /**
     * Constructor obligatorio
     */
    public function __construct(
        /**
         * Configuración de conexión a la base de datos,
         * almacenado en las configuraciones.
         */
        public ?string $connection = null
    ) {
        // Configuración automática de la tabla
        if ($this->table === '') {
            $table = explode('\\', get_called_class());
            $this->table = strtolower(array_pop($table)) . 's';
        }

        $this->connection = $this->connection ?? Config::get('database.default', 'pgsql');
        $connectionConfig = Config::get("database.connections.{$this->connection}");

        $this->driver = match ($connectionConfig['driver']) {
            'pgsql' => new PostgresSQL($connectionConfig, $this),
            'mysql' => new MySQL($connectionConfig, $this),
            'mariadb' => new MySQL($connectionConfig, $this),
            default => throw new \Exception('Driver not found', 500),
        };
    }

    /**
     * Obtener la tabla
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Obtener la llave primaria
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Obtener todos los registros de la tabla
     */
    public function all(array $columns = ['*']): Collection
    {
        return new Collection(
            $this->filterReturnColumns($this->driver->all($columns))
        );
    }

    /**
     * Obtener todos los registros de la tabla
     */
    public static function getAll(array $columns = ['*']): Collection
    {
        return (new static)->all($columns);
    }

    /**
     * Busca un registro especifico, devuelve solo 1 elemento
     */
    public function find(string|int $findByPrimaryKey): Collection
    {
        return new Collection(
            $this->filterReturnColumns($this->driver->find($findByPrimaryKey))[0] ?? []
        );
    }

    /**
     * Where sentence
     */
    public function where(string $column, mixed $sentence, mixed $three = ''): Model
    {
        $this->driver->where($column, $sentence, $three);

        return $this;
    }

    /**
     * Where sentence
     */
    public function limit(int $limit): Model
    {
        $this->driver->limit($limit);

        return $this;
    }

    /**
     * Obtiene los registros
     *
     * @return Collection
     */
    public function get(array $columns = ['*']): Collection
    {
        return new Collection(
            $this->filterReturnColumns($this->driver->get($columns))
        );
    }

    /**
     * Filtro a las columnas durante los get,
     * es usa para aplicar los hidden, los casts y los appends
     */
    public function filterReturnColumns(array $data): array
    {
        return array_map(function ($item) {
            // Hidden
            foreach ($this->hidden as $hidden) {
                unset($item[$hidden]);
            }

            // Casts
            foreach ($this->casts as $key => $value) {
                if (isset($item[$key])) {
                    $item[$key] = $this->getApplyCast($value, $item[$key]);
                }
            }

            return $item;
        }, $data);
    }

    /**
     * Ejecutar un query manualmente
     */
    public function query(string $query): array
    {
        return $this->driver->query($query);
    }

    /**
     * Crea un nuevo registro
     */
    public function create(array $data): static
    {
        $this->driver->create($data, $this->fillable, $this->casts);
        return $this;
    }

    /**
     * El insert sencillamente realiza un insert de los datos,
     * Sin embargo, no ejecuta los casts ni tiene en cuenta los fillable.
     * Esencialmente es un método potencialmente peligroso.
     * 
     * @return array de igual forma que el método "create", este devuelve los placeholders a insertar.
     */
    public function insert(array $data): static
    {
        $this->driver->insert($data);
        return $this;
    }

    /**
     * Obtiene el SQL generado
     */
    public function toSQL(): string
    {
        return $this->driver->toSQL();
    }
}
