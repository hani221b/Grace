<?php

namespace Hani221b\Grace\Controllers\Relations;

use Hani221b\Grace\Models\Table;
use Hani221b\Grace\Models\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Hani221b\Grace\Support\Str as GraceStr;
use Illuminate\Http\RedirectResponse;
use Hani221b\Grace\Controllers\Relations\Utilities\RelationTemplate;

class RelationController
{
    protected $relation_type;
    protected $local_table;
    protected $foreign_table;
    protected $local_key;
    protected $foriegn_key;
    protected $display_key;
    protected $pivot_table;

    public function __construct(Request $request)
    {
        $this->relation_type = $request->relation_type;
        $this->local_table = $request->local_table;
        $this->foreign_table = $request->foreign_table;
        $this->local_key = $request->local_key;
        $this->foriegn_key = $request->foriegn_key;
        $this->display_key = $request->display_key;
        $this->pivot_table = $request->pivot_table;
    }

    public function addRelationToModel(): RedirectResponse
    {
        $relations_array = array();
        $template = array();
        $relation_template = '';
        $local_table = Table::where('table_name', $this->local_table)->first();
        foreach ($this->relation_type as $type) {
            array_push($relations_array, "rt__" . $type . "__rt");
        }
        foreach ($this->foreign_table as $index => $foreign_table) {
            $relations_array[$index] = $relations_array[$index] . "__ft__" . $foreign_table . "__ft";
        }
        foreach ($this->foriegn_key as $index => $foriegn_key) {
            $relations_array[$index] = $relations_array[$index] . "__fk__" . $foriegn_key . "__fk";
        }
        foreach ($this->local_key as $index => $local_key) {
            $relations_array[$index] = $relations_array[$index] . "__lk__" . $local_key . "__lk";
        }
        if($this->pivot_table)
        {
            foreach ($this->pivot_table as $index => $pivot_table) {
                $relations_array[$index] = $relations_array[$index] . "__pt__" . $pivot_table . "__pt";
            }
        }

        foreach ($relations_array as $arr) {
            $single_relation = [
                'relation_type' => GraceStr::getBetween($arr, "rt__", "__rt"),
                'foreign_table' => GraceStr::getBetween($arr, "ft__", "__ft"),
                'foreign_key' => GraceStr::getBetween($arr, "fk__", "__fk"),
                'local_key' => GraceStr::getBetween($arr, "lk__", "__lk"),
                'pivot_table' => GraceStr::getBetween($arr, "pt__", "__pt"),
            ];
            switch ($single_relation['relation_type']) {
                case 'HasOne':
                    $relation_template = RelationTemplate::hasOne($single_relation);
                    break;

                case 'HasMany':
                    $relation_template = RelationTemplate::haMany($single_relation);
                    break;

                case 'BelongsTo':
                    $relation_template = RelationTemplate::belongsTo($single_relation);
                    break;

                case 'BelongsToMany':
                    $relation_template = RelationTemplate::belongsToMany($single_relation);
                    break;
            }
            array_push($template, $relation_template);
        }

        $string_relation_template = '';
        foreach ($template as $index => $tem) {
            $string_relation_template .= $template[$index] . "\n";
        }

        $model_path = base_path() . "/" . $local_table->model . ".php";
        $model_content = file_get_contents($model_path);
        $start_relation_field_marker = "/*<relations>*/";
        $end_relation_field_marker = "/*</relations>*/";
        $relations_field_in_model = GraceStr::getBetween($model_content, $start_relation_field_marker, $end_relation_field_marker);
        $new_model = str_replace(
            $relations_field_in_model,
            $relations_field_in_model .= $string_relation_template,
            $model_content
        );
        file_put_contents($model_path, $new_model);

        //append create fields
        $this->appendCreateFields();
        //append edit fields
        $this->appendEditFields("<!--<$this->local_table-form>-->", "<!--</$this->local_table-form>-->", 0);
          //append translations fields
        $this->appendEditFields("<!--<$this->local_table-translations-form>-->", "<!--</$this->local_table-translations-form>-->", "{{ \$index }}");
        //append index field
        $this->appendIndexFields();

        return redirect()->route('success');
    }

    public function appendCreateFields(): void
    {
        $foreign_tables_keys = array_combine($this->foreign_table, $this->foriegn_key);
        foreach ($foreign_tables_keys as $foriegn_table => $foriegn_key) {
            //store record in db
            Relation::create([
                'local_table'=>$this->local_table,
                'foreign_table'=>$foriegn_table
                ]);
            //append relation field in create.blade.pho file
            $blade_create_file = base_path()."/resources/views/grace/$this->local_table/create.blade.php";
            if(file_exists($blade_create_file)){
                $create_file_content = file_get_contents($blade_create_file);
                $create_form = GraceStr::getBetween( $create_file_content, "<!--<$this->local_table-form>-->","<!--</$this->local_table-form>-->");
                $new_create_form = $create_form . $this->createFieldTemplate($foriegn_table, $foriegn_key);
                $new_create_form = preg_replace('/\\\\/', '', $new_create_form);
                $create_file_content = str_replace($create_form, $new_create_form, $create_file_content);
                file_put_contents($blade_create_file ,$create_file_content);
            }
        }
    }

    public function appendEditFields(string $start_marker, string $end_marker, string $index): void
    {
        $foreign_tables_keys = array_combine($this->foreign_table, $this->foriegn_key);
        foreach ($foreign_tables_keys as $foriegn_table => $foriegn_key) {
            //append relation field in create.blade.pho file
            $blade_edit_file = base_path()."/resources/views/grace/$this->local_table/edit.blade.php";
            if(file_exists($blade_edit_file)){
                $edit_file_content = file_get_contents($blade_edit_file);
                $edit_form = GraceStr::getBetween($edit_file_content, $start_marker, $end_marker);
                $new_edit_form = $edit_form . $this->editFieldTemplate($foriegn_table, $foriegn_key, $index);
                $new_edit_form = preg_replace('/\\\\/', '', $new_edit_form);
                $edit_file_content = str_replace($edit_form, $new_edit_form, $edit_file_content);
                file_put_contents($blade_edit_file ,$edit_file_content);
            }
        }
    }

    public function appendIndexFields(): void
    {
        $foriegn_data = [];
        $index_blade_file = base_path() . "/resources/views/grace/$this->local_table/index.blade.php";
        if(file_exists($index_blade_file)){
            $index_file = file_get_contents($index_blade_file);
            foreach ($this->foreign_table as $table) {
                foreach ($this->display_key as  $key) {
                    array_push($foriegn_data, $table."_|_".$key);
                }
            }
            $foriegn_data = array_values(array_unique($foriegn_data));
            $foriegn_data_foriegn_key = array_combine($foriegn_data, $this->foriegn_key);
            foreach($foriegn_data_foriegn_key as $data => $key){
                $relation_name = Str::singular(strtok($data, '_|_'));
                $local_key = Str::singular($this->local_table);
                $relation_key = substr($data, strpos($data, '_|_') + 3);
                $index_file = str_replace("$$local_key->$key", "$$local_key->$relation_name->$relation_key", $index_file);
                $index_file = str_replace("<td>".Str::title($key)."</td>", "<td>".Str::title($relation_name)."</td>", $index_file);
                $index_file = str_replace("<th>".ucfirst($key)."</th>", "<th>".ucfirst($relation_name)."</th>", $index_file);
                file_put_contents(base_path() . $index_blade_file, $index_file);
            }
        }
    }

    public function createFieldTemplate(string $foreign_table, string $field): string
    {
        $capital_foreign_table = Str::title($foreign_table);
        $singular_foreign_table = Str::singular($foreign_table);
        return "
        <div class='form-group'>
        <label>$capital_foreign_table</label>
        <select class='form-control' name='$this->local_table\[{{ \$index }}][$field]'>
        @foreach($$foreign_table as $$singular_foreign_table)
            <option value='{{ $$singular_foreign_table->\id }}'>{{ $$singular_foreign_table->\\name }}</option>
        @endforeach
        </select>
    </div>
        ";
    }

    public function editFieldTemplate(string $foreign_table, string $field, string $index): string
    {
        $capital_foreign_table = Str::title($foreign_table);
        $singular_foreign_table = Str::singular($foreign_table);
        $singular_local_table = Str::singular($this->local_table);
        return "
        <div class='form-group'>
        <label>$capital_foreign_table</label>
        <select class='form-control' name='$this->local_table\[$index][$field]'>
        @foreach($$foreign_table as $$singular_foreign_table)
            <option value='{{ $$singular_foreign_table->\id }}' @if($$singular_foreign_table->\id === $$singular_local_table->$field) selected @endif>{{ $$singular_foreign_table->\\name }}</option>
        @endforeach
        </select>
    </div>
        ";
    }

}
