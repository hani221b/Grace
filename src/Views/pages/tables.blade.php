@extends('Grace::index')

@section('content')
    <v-container>
        <v-card>
            <v-simple-table>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th>
                                Table
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tables as $table)
                            <tr>
                                <td>{{ $table->table_name }}</td>
                                <td>
                                    <a href="{{ route('add_relation', $table->id) }}">
                                        <v-btn color="green">
                                            Add Relation
                                        </v-btn>

                                    </a>
                                    <a href="{{ route('add_validation', $table->id) }}">
                                        <v-btn color="white" style="color:#1E1E1E">
                                            Add Validation
                                        </v-btn>

                                    </a>
                                    <a href="{{ route('delete_table', $table->id) }}">
                                        <v-btn color="error">
                                            Delete Table
                                        </v-btn>

                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </template>
            </v-simple-table>
        </v-card>
    </v-container>
@endsection
