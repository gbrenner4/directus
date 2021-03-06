<?php

namespace Directus\Db\Schemas;


abstract class AbstractSchema implements SchemaInterface
{
    /**
     * Database connection adapter
     * @var \Zend\DB\Adapter\Adapter
     */
    protected $adapter;

    /**
     * AbstractSchema constructor.
     * @param $adapter
     */
    public function __construct($adapter)
    {
        $this->adapter = $adapter;
    }

    public function getColumnsNames($tableName)
    {
        $columns = $this->getColumns($tableName);

        $columnNames = [];
        foreach($columns as $column) {
            $columnNames[] = $column['column_name'];
        }

        return $columnNames;
    }

    public function parseRecordValuesByType($records, $nonAliasSchemaColumns)
    {
        // hotfix: records sometimes are no set as an array of rows.
        $items = !is_numeric_keys_array($records) ? [&$records] : $records;

        foreach ($nonAliasSchemaColumns as $column) {
            foreach($items as &$record) {
                $col = $column['id'];
                if (array_key_exists($col, $record)) {
                    $record[$col] = $this->parseType($record[$col], $column['type']);
                }
            }
        }

        return $records;
    }
}
