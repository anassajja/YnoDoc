<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Your Website</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css" rel="stylesheet">
</head>

<header class="bg-primary text-white text-center p-3">
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand mb-0 text-white" href="{{ route('patient.dashboard') }}">
            <h2 class="text-white">YnoDoc</h2>
        </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('patient.profile') }}">
                        <i class="fas fa-user-circle fa-lg"></i>
                        {{ Auth::user()->name }}
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2 class="text-center">Patient Profile</h2>
                <ul class="list-group">
                    <li class="list-group-item">Name: {{ $patient->name }}</li>
                    <li class="list-group-item">Email: {{ $patient->email }}</li>
                    <li class="list-group-item">City: {{ $patient->city }}</li>
                    <li class="list-group-item">Phone: {{ $patient->phone }}</li>
                    <li class="list-group-item">Description: {{ $patient->description }}</li>
                    <li class="list-group-item">Age: {{ $patient->age }}</li>
                </ul>
                <a href="{{ route('patient.edit', $patient->id) }}" class="btn btn-primary mt-3">Edit</a>
            </div>
        </div>
    </div>
</body>

<footer class="bg-primary text-white text-center p-3 mt-4">
    <p>&copy; 2024 Ynodoc</p>
</footer>

</html>