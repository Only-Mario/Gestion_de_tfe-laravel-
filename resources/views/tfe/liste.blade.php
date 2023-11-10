<!-- resources/views/report_list.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>List of Reports</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Theme</th>
                    <th>Auteurs</th>
                    <th>Année de réalisation</th>
                    <!-- Add other relevant columns as needed -->
                </tr>
            </thead>
            <tbody>
                @foreach ($tfes as $tfe)
                    <tr>
                        <td>{{ $tfe->id }}</td>
                        <td>{{ $tfe->theme }}</td>
                        <td>{{ $tfe->auteurs }}</td>
                        <td>{{ $tfe->annee_de_realisation }}</td>
                        <td>{{ $tfe->groupe_pedagogique }}</td>
                        <td>{{ $tfe->resume }}</td>
                        <!-- Add other relevant columns as needed -->
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tfes->links() }} <!-- Renders pagination links -->
    </div>
@endsection
