<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet" />
    <title>Grace - Add Validation</title>
    <style>
        /* Helper classes */
        .azzurri {
            background-color: #FFFBE6 !important;
        }

        .azzurri--text {
            color: #0080FF !important;
        }
    </style>
</head>

<body>
    <div id="validation">
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
                                <form method="POST" action="{{ route('submit_validation') }}">
                                    @foreach ($fields as $index => $field)
                                        <input type="hidden" name="table_id" value="{{ $id }}">
                                        <v-card class="mt-3">
                                            <v-container>
                                                <v-card-title>
                                                    {{ $field }}
                                                </v-card-title>
                                                <v-card-text>
                                                    <v-row v-for="(rule, index) in rules" :key="rule.id">
                                                        <v-row v-if="rule.field === {{ json_encode($field) }}">
                                                            <input type="hidden"
                                                                name="validation[{{ $index }}][field]"
                                                                value="{{ $field }}">
                                                            <v-col cols="5">
                                                                <v-autocomplete :items="rulesList" outlined
                                                                    label="Rules"
                                                                    name="validation[{{ $index }}][rules][]"
                                                                    v-on:change="addOptions(index, $event)">
                                                                </v-autocomplete>
                                                            </v-col>
                                                            <v-col cols="5">
                                                                <v-text-field v-show="rule.hasOptions === true" outlined
                                                                    label="Options" :value="null"
                                                                    name="validation[{{ $index }}][options][]">
                                                                </v-text-field>
                                                            </v-col>
                                                            <v-col cols="">
                                                                <v-btn color="error" v-on:click="deleteRule(index)">
                                                                    Remove
                                                                    Rule</v-btn>
                                                            </v-col>
                                                        </v-row>
                                                    </v-row>
                                                </v-card-text>
                                                <v-row>
                                                    <v-col cols="3">
                                                        <v-btn color="success"
                                                            v-on:click="addRule({{ json_encode($field) }})">Add Rule
                                                        </v-btn>
                                                    </v-col>
                                                </v-row>
                                            </v-container>
                                        </v-card>
                                    @endforeach
                                    <v-row>
                                        <v-col cols="12">
                                            <hr>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-btn type="submit" color="primary">Make Validations Alive</v-btn>
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
<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>

</html>

<script>
    new Vue({
        vuetify: new Vuetify(),
        el: "#validation",
        data: () => ({
            rulesList: [
                'accepted',
                'accepted_if',
                'active_url', 'after', 'after_or_equal', 'alpha', 'alpha_dash', 'alpha_num',
                'array', 'bail', 'before', 'before_or_equal',
                'between', 'boolean', 'confirmed', 'current_password', 'date', 'date_equals',
                'date_format', 'declined', 'declined_if',
                'different', 'digits', 'digits_between', 'dimensions', 'distinct', 'email',
                'ends_with', 'enum', 'exclude', 'exclude_if',
                'exclude_unless', 'exclude_with', 'exclude_without', 'exists', 'file', 'filled',
                'gt', 'gte', 'image', 'in', 'in_array',
                'integer', 'ip', 'ipv4', 'ipv6', 'json', 'lt', 'lte', 'mac_address', 'max',
                'mimetypes', 'mimes', 'min', 'multiple_of', 'not_in',
                'not_regex', 'nullable', 'numeric', 'password', 'present', 'prohibited',
                'prohibited_if', 'prohibited_unless', 'prohibits',
                'regex', 'required', 'required_if', 'required_unless', 'required_with',
                'required_with_all', 'required_without', 'required_without_all',
                'required_array_keys', 'same', 'size', 'starts_with', 'string', 'timezone',
                'unique', 'url', 'uuid',
            ],
            rules: [],
        }),
        methods: {
            addRule(field) {
                this.rules.push({
                    field: field,
                    time: Date.now(),
                    hasOptions: false
                });
            },
            deleteRule(fieldIndex) {
                this.rules.splice(fieldIndex, 1)
            },
            addOptions(index, event) {
                let rulesWithOptions = [
                    'accepted_if', 'after', 'after_or_equal', 'before', 'before_or_equal', 'between',
                    'date_equals', 'date_format',
                    'declined_if', 'different', 'digits', 'digits_between', 'ends_with', 'exclude_if',
                    'exclude_unless', 'exclude_with',
                    'exclude_without', 'exists', 'gt', 'gte', 'in', 'in_array', 'lt', 'lte', 'max',
                    'mimetypes', 'mimes', 'min', 'multiple_of',
                    'not_in', 'prohibited_if', 'prohibited_unless', 'prohibits', 'regex', 'required_if',
                    'required_unless', 'required_with',
                    'required_with_all', 'required_without', 'required_without_all', 'required_array_keys',
                    'same', 'size', 'starts_with',
                    'unique',
                ];
                if (rulesWithOptions.includes(event)) {
                    this.rules[index].hasOptions = true;
                } else {
                    this.rules[index].hasOptions = false;
                }
            }
        },
        created() {
            let fields = <?php echo json_encode($fields, JSON_HEX_TAG); ?>;
            for (const [key, value] of Object.entries(fields)) {
                this.rules.push({
                    field: value,
                    time: Date.now(),
                    hasOptions: false
                });
            }
        }
    });
</script>
