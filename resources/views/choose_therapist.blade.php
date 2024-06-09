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
        <h1>Choisi ton therapeute</h1>

        <div class="row">
            <!-- Display therapists -->
            @foreach ($therapists as $therapist)
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body" onclick="showForm({{ $therapist->id }})">
                        <h2 class="card-title">{{ $therapist->name }}</h2>
                        <p class="card-text">Ville: {{ $therapist->city }}</p>
                        <p class="card-text">Spécialité: {{ $therapist->specialization }}</p>
                        <p class="card-text">Description: {{ $therapist->description }}</p>
                    </div>

                    <form id="form{{ $therapist->id }}" style="display: none;" action="{{ route('psychotherapy.store') }}" method="POST" class="p-3">
                        @csrf
                        <input type="hidden" name="therapist_id" value="{{ $therapist->id }}">
                        <input type="hidden" name="patient_id" value="{{ auth('patient')->user()->id }}">

                        <!-- Patient information -->
                        <div class="mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone" required>
                        </div>

                        <!-- Pack selection -->
                        @foreach ($packs as $pack)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="pack{{ $pack->id }}" name="pack_id" value="{{ $pack->id }}" required>
                            <label class="form-check-label" for="pack{{ $pack->id }}">
                                <h4>{{ $pack->title }}</h4>
                                <div>Description: {{ $pack->description }}</div>
                                <div>Price: {{ $pack->price }} USD</div>
                            </label>
                        </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>


<footer class="bg-primary text-white text-center p-3 mt-4">
    <p>&copy; 2024 Ynodoc</p>
</footer>
<script>
    function showForm(therapistId) {
        // Hide all forms
        var forms = document.getElementsByTagName('form');
        for (var i = 0; i < forms.length; i++) {
            forms[i].style.display = 'none';
        }

        // Show the form for the selected therapist
        document.getElementById('form' + therapistId).style.display = 'block';
    }
</script>