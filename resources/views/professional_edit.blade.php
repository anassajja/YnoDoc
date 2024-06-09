<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Professional Dashboard</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<header class="bg-primary text-white text-center p-3">
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand mb-0 text-white" href="{{ route('professional.dashboard') }}">
            <h2 class="text-white">YnoDoc</h2>
        </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('professional.profile') }}">
                        <i class="fas fa-user-circle fa-lg"></i>
                        {{ Auth::user()->name }}
                    </a>
            </ul>
        </div>
    </nav>
</header>

<body>
    <div class="container">
        <h2 class="mt-4">Edit Professional Profile</h2>

        <form method="POST" action="{{ route('professional.updateProfile', ['id' => auth()->id()]) }}" class="mt-4">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="specialization">Specialization:</label>
                <input type="text" id="specialization" name="specialization" value="{{ $professional->specialization }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control" required>{{ $professional->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="proPhone">Professional Phone:</label>
                <input type="text" id="proPhone" name="proPhone" value="{{ $professional->proPhone }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="{{ $professional->address }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="{{ $professional->city }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <select id="location" name="location" class="form-control" required>
                    <option value="clinic" {{ $professional->location == 'clinic' ? 'selected' : '' }}>Clinic</option>
                    <option value="atHome" {{ $professional->location == 'atHome' ? 'selected' : '' }}>Home</option>
                    <option value="cabinet" {{ $professional->location == 'cabinet' ? 'selected' : '' }}>Cabinet</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>

<footer class="bg-primary text-white text-center p-3 mt-4">
    <p>&copy; 2024 Ynodoc</p>
</footer>

</html>