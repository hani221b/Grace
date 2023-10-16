<v-container class="grey lighten-5 mb-6">
    <v-row>
        <v-col cols="12">
            <v-card class="pa-2">
                <v-main>
                    <form action="{{ route('makeModelAlive') }}" method="POST">
                        @csrf
                        <v-row>
                            <v-col cols="6">
                                <v-text-field name="table_name" label="Table Name" outlined>
                                </v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field value="{{ config('grace.model_namespace') }}" name="namespace"
                                    label="Namespace" outlined>
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <div v-for="(item, index) in fields" :key="item.id">
                            <v-row>
                                <v-col>
                                    <h3>Field #<span v-html="index + 1"></span></h3>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="8">
                                    <v-text-field name="field_names[]" label="Field Name" outlined>
                                    </v-text-field>
                                </v-col>
                                <v-col cols="2">
                                    <v-checkbox name="isFile[]" label="File?" value="1"></v-checkbox>
                                    <input style="display:none" id='testNameHidden' type='checkbox' value='0'
                                        name='isFile[]' checked>
                                </v-col>
                                <v-col cols="2">
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
