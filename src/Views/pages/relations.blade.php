<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/vuetify/2.6.16/vuetify.css" integrity="sha512-P8e5EskR1yDRrv7VJ+EwmIlF1BxMl3wwmQhVo0kmUosUDVKG73CT2oKmVDxfmyttVXiTeuokHvdyX4nprgWi6g==" crossorigin="anonymous" referrerpolicy="no-referrer" />    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />
    <title>Grace - Add Relation</title>
    <style>
        /* Helper classes */
        .azzurri {
            background-color: #FFFBE6 !important;
        }

        .azzurri--text {
            color: #0080FF !important;
        }

        .reation-select-prefix {
            margin: 25px 0 0 30px
        }
    </style>
</head>

<body>
    <div id="relations">
        <template>
            <v-app>
                <v-card style="height: 100%">
                    <v-card-title class="text-center justify-center py-6">
                        <h3 class="font-weight-bold text-h2 azzurri--text">
                            GRACE
                        </h3>
                    </v-card-title>
                    <v-container>
                        <v-form>
                            <v-container>
                                <form method="POST" action="{{ route('submit_relations') }}">
                                    <div v-for="(relation, index) in relations">
                                        <v-card elevation="4" class="mb-3">
                                            <v-card-text>
                                                <v-row>
                                                    <v-col cols="2">
                                                        <h2 class="reation-select-prefix">Table {{ $table->table_name }}
                                                        </h2>
                                                    </v-col>
                                                    <input type="hidden" name="local_table"
                                                        value="{{ $table->table_name }}">
                                                    <v-col cols="2">
                                                        <v-select label="Relatoin Type" :items="relationTypes"
                                                            item-value="key" item-text="label" name="relation_type[]"
                                                            v-on:change="relationType(index, $event)">
                                                        </v-select>
                                                    </v-col>
                                                    <v-col cols="2">
                                                        <v-select label="Foreign Table" :items="dbTables"
                                                            v-on:change="ForeignTable(index, $event)"
                                                            name="foreign_table[]" ref="foreign_table">
                                                        </v-select>
                                                    </v-col>

                                                    <v-col cols="2">
                                                        <h2 class="reation-select-prefix">Foriegn Key</h2>
                                                    </v-col>
                                                    <v-col cols="4">
                                                        <v-select label="Foreign Key" :items="relation.foriegnKey"
                                                            name="foriegn_key[]" :disabled="relation.isBelongsToMany">
                                                        </v-select>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col cols="2">
                                                        <h2 class="reation-select-prefix">Store <span
                                                                v-if="relation.relationType === 'BelongsToMany' || relation.relationType === 'BelongsTo'">
                                                                <span v-html="relation.foreignTable"></span></span>
                                                            <span v-else>{{ $table->table_name }}</span>
                                                        </h2>
                                                    </v-col>
                                                    <v-col cols="4">
                                                        <v-select label="Local Key" :items="relation.storeKey"
                                                            item-value="key" item-text="label" name="local_key[]"
                                                            :disabled="relation.isBelongsToMany">
                                                        </v-select>
                                                    </v-col>
                                                    <v-col cols="">
                                                        <h2 class="reation-select-prefix">Display <span
                                                                v-html="relation.foreignTable"></span></h2>
                                                    </v-col>
                                                    <v-col cols="4">
                                                        <v-select label="Display" :items="relation.displayKey"
                                                            name="display_key[]" :disabled="relation.isBelongsToMany">
                                                        </v-select>
                                                    </v-col>
                                                </v-row>
                                                <v-row v-if="relation.relationType == 'BelongsToMany'">
                                                    <v-col cols="4">
                                                        <v-text-field label="Pivot Table" name="pivot_table[]">
                                                        </v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col cols="3">
                                                        <v-btn color="error" v-on:click="deleteRelation(index)">
                                                            Remove
                                                            Relation</v-btn>
                                                    </v-col>
                                                </v-row>
                                            </v-card-text>
                                        </v-card>

                                    </div>
                                    <v-row class="mt-3">
                                        <v-col cols="2">
                                            <v-btn color="success" v-on:click="addRelation">Add Relation
                                            </v-btn>
                                        </v-col>
                                        <v-col cols="2">
                                            <v-btn type="submit" color="primary">Make Relations Alive
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </form>
                            </v-container>
                        </v-form>
                    </v-container>
                </v-card>
            </v-app>
        </template>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vuetify/2.6.16/vuetify.min.js" integrity="sha512-17vpuR3wiDiOIvkkHmJ1m6u7htfIhu0qhQ1TKKFUzWdkyLJgid2NFfvymK8GjyArBFKRNlgbW0uDW0O7ScVH4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</html>

<script>
    new Vue({
        vuetify: new Vuetify(),
        el: "#relations",
        data() {
            return {
                relations: [{
                    foriegnKey: [],
                    displayKey: [],
                    storeKey: [],
                    time: Date.now(),
                    relationType: '',
                    foreignTable: '',
                    isBelongsToMany: false
                }],
                id: 1,
                relationTypes: [{
                    label: 'Has One',
                    key: 'HasOne'
                }, {
                    label: 'Has Many',
                    key: 'HasMany'
                }, {
                    label: 'Belongs To',
                    key: 'BelongsTo'
                }, {
                    label: 'Belongs To Many',
                    key: 'BelongsToMany'
                }],
                localFields: [],
                dbTables: [],
                localKey: [],
                dbFields: [],

            }
        },
        methods: {
            ForeignTable(index, event) {
                console.log('event', event);
                this.relations[index].foreignTable = event;
                let relationType = this.relations[index].relationType
                if (relationType === 'BelongsTo' || relationType === 'BelongsToMany') {
                    this.relations[index].storeKey = Object.values(this.dbFields[this.relations[index]
                        .foreignTable]);
                    this.relations[index].foriegnKey = Object.values(this.localFields);
                } else {
                    this.relations[index].foriegnKey = Object.values(this.dbFields[event]);
                    this.relations[index].storeKey = Object.values(this.localFields);
                }
                this.relations[index].displayKey = Object.values(this.dbFields[event])
            },
            relationType(index, event) {
                this.relations[index].relationType = event;
                if (event === 'BelongsToMany') {
                    this.relations[index].isBelongsToMany = true
                } else {
                    this.relations[index].isBelongsToMany = false
                }

                if (event === 'BelongsTo' || event === 'BelongsToMany') {
                    if (this.relations[index].foreignTable !== undefined) {
                        this.relations[index].foriegnKey = Object.values(this.dbFields[this.relations[index]
                            .foreignTable]);
                    }
                    this.relations[index].foriegnKey = Object.values(this.localFields);
                } else {
                    if (this.relations[index].foreignTable !== undefined) {
                        this.relations[index].storeKey = Object.values(this.localFields);
                        this.relations[index].foriegnKey = Object.values(this.dbFields[this.relations[index]
                            .foreignTable]);
                    } else {
                        this.relations[index].storeKey = []
                        this.relations[index].foriegnKey = []
                    }
                }
            },
            addRelation() {
                this.id += 1;
                this.relations.push({
                    foriegnKey: [],
                    time: Date.now(),
                    relationType: ''
                });
            },
            deleteRelation(relationIndex) {
                this.relations.splice(relationIndex, 1)
            },
        },
        created() {
            this.dbTables = <?php echo json_encode($db_tables, JSON_HEX_TAG); ?>;
            this.localFields = <?php echo json_encode($fields, JSON_HEX_TAG); ?>;
            this.dbFields = <?php echo json_encode($db_fields, JSON_HEX_TAG); ?>;
        },
    });
</script>
