<?php

namespace Hani221b\Grace\Controllers\Relations\Utilities;
use Hani221b\Grace\Controllers\Relations\RelationController;
use Illuminate\Support\Str;
use Hani221b\Grace\Support\Str as GraceStr;
use Hani221b\Grace\Support\Stub;
use Illuminate\Support\Facades\Artisan;

class RelationTemplate extends RelationController
{
    private static $_local_table;

    public function __construct()
    {
        self::$_local_table = $this->local_table;
    }

    public static function hasOne(array $relation_info): string
    {   
        $stub_variables = [
            "LOCAL_TABLE" => self::$_local_table,
            "SINGLE_FOREIGN_TABLE_NAME" => Str::singular($relation_info["foreign_table"]),
            "FOREIGN_MODEL" => GraceStr::singularClass($relation_info["foreign_table"]),
            "FOREIGN_KEY" => $relation_info["foreign_key"],
            "LOCAL_KEY" => $relation_info["local_key"]

        ];
        return Stub::getStubContents(__DIR__ . "/../../../Stubs/relations/has-one.stub", $stub_variables);
    }

    public static function haMany(array $relation_info): string
    {
        $pivot_table = $relation_info["pivot_table"];
        if($pivot_table == null){
            $foreign_model = GraceStr::singularClass($relation_info["foreign_table"])."::class";
        } else {
            $foreign_model = "'$pivot_table'";
        }
        $stub_variables = [
            "LOCAL_TABLE" => self::$_local_table,
            "FOREIGN_TABLE_NAME" => $relation_info["foreign_table"],
            "FOREIGN_MODEL" => $foreign_model,
            "FOREIGN_KEY" => $relation_info["foreign_key"],
            "LOCAL_KEY" => $relation_info["local_key"]
        ];
        return Stub::getStubContents(__DIR__ . "/../../../Stubs/relations/has-many.stub", $stub_variables);
    }

    public static function belongsTo(array $relation_info): string
    {
        $stub_variables = [
            "LOCAL_TABLE" => self::$_local_table,
            "SINGLE_FOREIGN_TABLE_NAME" => Str::singular($relation_info["foreign_table"]),
            "FOREIGN_MODEL" => GraceStr::singularClass($relation_info["foreign_table"]),
            "FOREIGN_KEY" => $relation_info["foreign_key"],
            "LOCAL_KEY" => $relation_info["local_key"]
        ];
        return Stub::getStubContents(__DIR__ . "/../../../Stubs/relations/belongs-to.stub", $stub_variables);
    }

    public static function belongsToMany(array $relation_info): string
    {
        $pivot_table = $relation_info["pivot_table"];
        if($pivot_table == null){
            $foreign_model = GraceStr::singularClass($relation_info["foreign_table"])."::class";
        } else {
            $foreign_model = GraceStr::singularClass($relation_info["foreign_table"])."::class, '$pivot_table'";
        }
        $foreign_id = Str::singular($relation_info["foreign_table"])."_id";
        $local_id = Str::singular(self::$_local_table)."_id";

        $stub_variables = [
            "LOCAL_TABLE" => self::$_local_table,
            "SINGLE_FOREIGN_TABLE_NAME" => Str::singular($relation_info["foreign_table"]),
            "FOREIGN_MODEL" => $foreign_model,
            "FOREIGN_ID" => $foreign_id,
            "LOCAL_ID" => $local_id
        ];

        $pivotStubsVariables = [
            'field_names'=>[$foreign_id, $local_id],
            'field_types'=>['integer','integer'],
        ];

        self::createPivotTable($pivotStubsVariables);
        return Stub::getStubContents(__DIR__ . "/../../../Stubs/relations/belongs-to-many.stub", $stub_variables);
    }

    public static function createPivotTable(array $pivotStubsVariables): void
    {
        $content = Stub::getMigrationStubContents(__DIR__ . "/../../Stubs/migration.pivot.stub", $pivotStubsVariables);
        $foreign_table = Str::plural(str_replace('_id', '', $pivotStubsVariables['field_names'][0]));
        $local_table = Str::plural(str_replace('_id', '', $pivotStubsVariables['field_names'][1]));
        $table_name = $foreign_table."_".$local_table;
        $file_name =  date("Y_m_d") . "_" . $_SERVER['REQUEST_TIME']
            . "_create_".$table_name."_table" . '.php';
        $content = str_replace("{{ table_name }}", $table_name, $content);
        file_put_contents( base_path() . '/database/migrations/' .$file_name, $content);
        Artisan::call('migrate', ['--path'=>  '/database/migrations/'.$file_name]);
    }
}