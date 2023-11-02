<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" />
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
<script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vuetify/2.6.16/vuetify.min.js" integrity="sha512-17vpuR3wiDiOIvkkHmJ1m6u7htfIhu0qhQ1TKKFUzWdkyLJgid2NFfvymK8GjyArBFKRNlgbW0uDW0O7ScVH4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</html>

<script>
    new Vue({
        vuetify: new Vuetify(),
        el: "#success",

    });
</script>
