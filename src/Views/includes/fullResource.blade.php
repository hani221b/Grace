<v-container class="grey lighten-5 mb-6">
    <v-row>
        <v-col cols="12">
            <v-card class="pa-2">
                <v-main>
                    <form action="{{ route('makeFullResourceAlive') }}" method="POST">
                        @csrf
                        <v-row>
                            <v-col cols="6">
                                <v-text-field name="table_name" label="Table Name" outlined>
                                </v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-checkbox name="single_record_table" label="Single Record Table?" value="1">
                                </v-checkbox>
                            </v-col>
                        </v-row>
                        <div v-for="(item, index) in fields" :key="item.id">
                            <v-row>
                                <v-col>
                                    <h3>Field #<span v-html="index + 1"></span></h3>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="3">
                                    <v-text-field name="field_names[]" label="Field Name" outlined>
                                    </v-text-field>
                                </v-col>
                                <v-col cols="3">
                                    <v-autocomplete name="field_types[]" label="Field Type" small-chips
                                        v-on:change="fieldType(index, $event)" :items="columnTypes"></v-autocomplete>
                                </v-col>
                                <v-col cols="3">
                                    <v-autocomplete :disabled="item.isRelationType" name="input_types[]"
                                        label="Input Type" small-chips v-on:change="inputType(index, $event)"
                                        :items="inputTypes" ref="input_types" :value="item.input_type_value">
                                    </v-autocomplete>
                                </v-col>
                                <v-col cols="1">
                                    <v-btn color="error" @click="deleteField(index)">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                            width="30" height="30" preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M9 3v1H4v2h1v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6h1V4h-5V3H9m0 5h2v9H9V8m4 0h2v9h-2V8Z" />
                                        </svg>
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-row v-if="item.isSelect === true">
                                <v-col cols="6">
                                    <v-combobox clearable deletable-chips chips label="Options" name="select_options[]"
                                        multiple outlined dense>
                                    </v-combobox>
                                </v-col>
                            </v-row>
                        </div>
                        <v-row>
                            <v-col cols="12">
                                <v-btn color="success" @click="addField">Add Field</v-btn>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                <hr>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="3">
                                <h3>Namespaces</h3>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field value="{{ config('grace.model_namespace') }}" name="model_namespace"
                                    label="Model Namespace" outlined>
                                </v-text-field>
                            </v-col>
                            <v-col cols="6">
                                </v-combobox>
                                <v-text-field value="{{ config('grace.request_namespace') }}" name="request_namespace"
                                    label="Request Namespace" outlined>
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field value="{{ config('grace.migration_namespace') }}"
                                    name="migration_namespace" label="Migration Namespace" outlined>
                                </v-text-field>
                            </v-col>
                            <v-col cols="6">
                                </v-combobox>
                                <v-text-field value="{{ config('grace.resource_namespace') }}" name="resource_namespace"
                                    label="Resource Namespace" outlined>
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field value="{{ config('grace.controller_namespace') }}"
                                    name="controller_namespace" label="Controller Namespace" outlined>
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                <v-btn type="submit" color="primary">Make Alive</v-btn>
                            </v-col>
                        </v-row>
                    </form>
                </v-main>
            </v-card>
        </v-col>
    </v-row>
</v-container>
