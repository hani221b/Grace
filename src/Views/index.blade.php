<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/vuetify/2.6.16/vuetify.css" integrity="sha512-P8e5EskR1yDRrv7VJ+EwmIlF1BxMl3wwmQhVo0kmUosUDVKG73CT2oKmVDxfmyttVXiTeuokHvdyX4nprgWi6g==" crossorigin="anonymous" referrerpolicy="no-referrer" />    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />
    <style>
        /* Helper classes */
        .azzurri {
            background-color: #FFFBE6 !important;
        }

        .azzurri--text {
            color: #0080FF !important;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div id="app">
        <template>
            <v-app>
                <v-container fluid>
                    <v-row>
                        <v-col cols="2">
                            <v-navigation-drawer permanent>
                                <v-list-item>
                                    <v-list-item-content>
                                        <v-list-item-title class="text-h6">
                                            <span class="font-weight-bold text-h4 azzurri--text">
                                                GRACE
                                            </span>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-divider></v-divider>
                                <v-list dense nav>
                                    <v-list-item href="{{ route('factory') }}" link>
                                        <v-list-item-icon>
                                            <span class="mdi mdi-factory"></span>
                                        </v-list-item-icon>

                                        <v-list-item-content>
                                            <v-list-item-title>Factory</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item href="{{ route('grace_tables') }}" link>
                                        <v-list-item-icon>
                                            <span class="mdi mdi-table-large"></span>
                                        </v-list-item-icon>

                                        <v-list-item-content>
                                            <v-list-item-title>Tables</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </v-navigation-drawer>
                        </v-col>
                        <v-col cols="10">
                            @yield('content')
                        </v-col>
                    </v-row>
                </v-container>
            </v-app>
        </template>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vuetify/2.6.16/vuetify.min.js" integrity="sha512-17vpuR3wiDiOIvkkHmJ1m6u7htfIhu0qhQ1TKKFUzWdkyLJgid2NFfvymK8GjyArBFKRNlgbW0uDW0O7ScVH4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        new Vue({
            el: "#app",
            vuetify: new Vuetify(),
            data: () => ({
                columnTypes: ['bigIncrements', 'bigInteger', 'binary', 'boolean', 'char', 'dateTimeTz',
                    'dateTime', 'date', 'decimal', 'double', 'enum', 'float', 'foreignId',
                    'foreignIdFor', 'foreignUuid', 'geometryCollection', 'geometry', 'id', 'increments',
                    'integer', 'ipAddress', 'json', 'jsonb', 'lineString', 'longText ', 'macAddress',
                    'mediumIncrements', 'mediumInteger', 'mediumText', 'morphs', 'morphs', 'multiPoint',
                    'multiPolygon', 'nullableTimestamps', 'nullableMorphs', 'nullableUuidMorphs',
                    'point', 'polygon', 'rememberToken', 'set', 'smallIncrements', 'smallInteger',
                    'softDeletesTz', 'softDeletes', 'string', 'text', 'timeTz', 'time', 'timestampTz',
                    'timestamp', 'timestampsTz', 'timestamps', 'tinyIncrements', 'tinyInteger',
                    'tinyText', 'unsignedBigInteger', 'unsignedDecimal', 'unsignedInteger',
                    'unsignedMediumInteger', 'unsignedSmallInteger', 'unsignedTinyInteger',
                    'uuidMorphs', 'uuid', 'year'
                ],
                inputTypes: ['text', 'select', 'textarea', 'file'],
                relationsFields: ['foreignId', 'foreignIdFor', 'foreignUuid'],
                tab: null,
                id: 1,
                fields: [{
                    name: "",
                    time: Date.now(),
                    isSelect: false,
                    isRelationType: false
                }],
            }),
            methods: {
                addField() {
                    this.id += 1;
                    this.fields.push({
                        name: "",
                        time: Date.now(),
                        isSelect: false,
                        isRelationType: false,
                        input_type_value: ''
                    });
                },
                deleteField(fieldIndex) {
                    this.fields.splice(fieldIndex, 1)
                },
                inputType(index, event) {
                    if (event == 'select') {
                        this.fields[index].isSelect = true;
                    } else {
                        this.fields[index].isSelect = false;
                    }
                },
                fieldType(index, event) {
                    if (this.relationsFields.includes(event)) {
                        this.fields[index].input_type_value = 'relation'
                        this.fields[index].isRelationType = true
                    } else {
                        this.fields[index].isRelationType = false
                    }
                }
            },
        });
    </script>
</body>

</html>
