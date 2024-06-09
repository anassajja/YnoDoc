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
        <a class="navbar-brand mb-0 text-white" href="{{ route('admin.dashboard') }}">
            <h2 class="text-white">YnoDoc</h2>
        </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white">
                        <i class="fas fa-user-circle fa-lg"></i>
                        {{ Auth::user()->name }}
                    </a>
            </ul>
        </div>
    </nav>
</header>

<body>
    <div class="container">
        <h2>Edit Patient</h2>

        <form method="POST" action="{{ route('admin.updatePatient', ['id' => $patient->id]) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="{{ $patient->name }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ $patient->email }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="{{ $patient->city }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="{{ $patient->phone }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="{{ $patient->age }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control" required>{{ $patient->description }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
<footer class="bg-primary text-white text-center p-3 mt-4">
    <p>&copy; 2024 Ynodoc</p>
</footer>

</html>