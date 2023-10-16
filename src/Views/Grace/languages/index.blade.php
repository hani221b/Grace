@extends('Grace::Grace.grace_layout')

@section('content')
    <div class="content-body">

        <div class="row page-titles mx-0">
            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Data Table</h4>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Status</th>
                                            <th>Direction</th>
                                            <th>Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($languages as $language)
                                            <tr>
                                                <td>{{ $language->name }}</td>
                                                <td>{{ $language->abbr }}</td>
                                                <td>{{ $language->getStatus() }}</td>
                                                <td>{{ $language->direction }}</td>
                                                <td>
                                                    <a
                                                        href="{{ asset('dashboard/languages/change_status/' . $language->id) }}">
                                                        <button type="button"
                                                            class="btn mb-1 btn-warning">{{ $language->status === 0 ? 'Activate' : 'Deactivate' }}</button>
                                                    </a>
                                                    <a
                                                        href="{{ $language->default === 1 ? 'javascript:void(0)' :  asset('dashboard/languages/set_to_default/' . $language->id) }}">
                                                        <button type="button"
                                                            class="btn mb-1 {{ $language->default === 0 ? 'btn-warning' : 'btn-primary disabled' }} ">{{ $language->default === 0 ? 'Set as default' : 'Default language' }}</button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Status</th>
                                            <th>Operation</th>
                                        </tr>

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
