@extends('Grace::index')

@section('content')
    <v-card>
        <v-tabs v-model="tab" background-color="transparent" grow>
            <v-tab>
                Create Full Resource
            </v-tab>
            <v-tab>
                Create Migration
            </v-tab>
            <v-tab>
                Create Model
            </v-tab>
            <v-tab>
                Create Controller
            </v-tab>
            <v-tab>
                Create Reauest Class
            </v-tab>
            <v-tab>
                Create Resource Class
            </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
            <v-tab-item>
                <v-card color="azure" flat>
                    @include('Grace::includes.fullResource')
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card color="azure" flat>
                    @include('Grace::includes.migration')
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card color="azure" flat>
                    @include('Grace::includes.model')
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card color="azure" flat>
                    @include('Grace::includes.controller')
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card color="azure" flat>
                    @include('Grace::includes.request')
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card color="azure" flat>
                    @include('Grace::includes.resource')
                </v-card>
            </v-tab-item>
        </v-tabs-items>
    </v-card>
@endsection
