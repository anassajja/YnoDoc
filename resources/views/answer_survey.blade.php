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
        <div class="card">
            <div class="card-header text-center">
                <h1>{{ $survey->title }}</h1>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $survey->description }}</p>

                <form method="POST" action="/surveys/{{ $survey->id }}/submit">
                    @csrf

                    @foreach ($survey->questions as $index => $question)
                    <div class="mb-3">
                        <label class="form-label">{{ $question['text'] }}</label>

                        @if ($question['type'] === 'multiple_choice')
                        @foreach (explode(',', $question['choices']) as $choice)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="answers[{{ $index }}][]" name="answers[{{ $index }}][]" value="{{ $choice }}">
                            <label class="form-check-label" for="answers[{{ $index }}][]">{{ $choice }}</label>
                        </div>
                        @endforeach
                        @elseif ($question['type'] === 'true_false')
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="answers[{{ $index }}]" name="answers[{{ $index }}]" value="true">
                            <label class="form-check-label" for="answers[{{ $index }}]">True</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="answers[{{ $index }}]" name="answers[{{ $index }}]" value="false">
                            <label class="form-check-label" for="answers[{{ $index }}]">False</label>
                        </div>
                        @else
                        <input type="text" class="form-control" id="answers[{{ $index }}]" name="answers[{{ $index }}]">
                        @endif
                    </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>


<footer class="bg-primary text-white text-center p-3 mt-4">
    <p>&copy; 2024 Ynodoc</p>
</footer>

</html>