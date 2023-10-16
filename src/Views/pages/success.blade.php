<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('grace/assets/css/dist/vuetify.css') }}">
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
    <div id="success">
        <v-container>
            <template>
                <v-app>
                    <v-card class="mx-auto">
                        <v-card-title class="text--primary">

                            <div>Done Successfully</div>
                        </v-card-title>

                        <v-card-actions>
                            <v-btn color="blue" link href="{{ route('factory') }}">
                                Back to Factory
                            </v-btn>

                            <v-btn color="green" link href="{{ route('grace_tables') }}">
                                Back to Tables
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-app>
            </template>
        </v-container>
    </div>
</body>
<script src="{{ asset('grace/assets/js/dist/vue.js') }}"></script>
<script src="{{ asset('grace/assets/js/dist/vuetify.js') }}"></script>

</html>

<script>
    new Vue({
        vuetify: new Vuetify(),
        el: "#success",

    });
</script>
