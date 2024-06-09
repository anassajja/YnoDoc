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
    <div class="container">
        <h2 class="mt-4">Modifier Profil</h2>

        <form action="{{ route('patient.update', $patient->id) }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nom Complet:</label>
                <input type="text" id="name" name="name" value="{{ $patient->name }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="city">Ville:</label>
                <input type="text" id="city" name="city" value="{{ $patient->city }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="phone">Tel:</label>
                <input type="text" id="phone" name="phone" value="{{ $patient->phone }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control">{{ $patient->description }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Modifer</button>
        </form>
    </div>
</body>

<footer class="bg-primary text-white text-center p-3 mt-4">
    <p>&copy; 2024 Ynodoc</p>
</footer>


</html>