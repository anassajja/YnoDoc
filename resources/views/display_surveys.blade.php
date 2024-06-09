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
        <div class="row">
            @foreach ($responses as $response)
            <div class="col-md-4">
                <div class="card mt-4">
                    <div class="card-header">
                        <h2>Survey: {{ $response->survey->title }}</h2>
                    </div>
                    <div class="card-body">
                        <p><strong>Patient ID:</strong> {{ $response->patient->id }}</p>
                        <p><strong>Patient Name:</strong> {{ $response->patient->name }}</p>

                        @foreach ($response->survey->questions as $index => $question)
                        <p><strong>Question:</strong> {{ $question['text'] }}</p>
                        <p><strong>Answer:</strong> {{ json_decode($response->answers, true)[$index] }}</p>
                        @endforeach

                        <form action="{{ route('admin.surveys.archive', $response->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="response_id" value="{{ $response->id }}">
                            <button type="submit" class="btn btn-primary">Archive</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
<footer class="bg-primary text-white text-center p-3 mt-4">
    <p>&copy; 2024 Ynodoc</p>
</footer>

</html>