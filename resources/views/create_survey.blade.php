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
        <h1>Create Survey</h1>

        <form method="POST" action="{{ route('surveys.store') }}">
            @csrf

            <!-- Survey Title -->
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <!-- Survey Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>

            <!-- Questions -->
            <div id="questions" class="form-group">
                <!-- Existing Questions will be inserted here by JavaScript -->
            </div>

            <!-- Add Question Button -->
            <button type="button" class="btn btn-primary" id="add-question">Add Question</button>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success">Create Survey</button>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
<footer class="bg-primary text-white text-center p-3 mt-4">
    <p>&copy; 2024 Ynodoc</p>
</footer>





<script>
    document.getElementById('add-question').addEventListener('click', function() {
        var questions = document.getElementById('questions');
        var questionCount = questions.getElementsByClassName('question').length;

        var question = document.createElement('div');
        question.className = 'question';

        question.innerHTML = `
        <label for="questions[${questionCount}][text]">Question</label>
        <input type="text" id="questions[${questionCount}][text]" name="questions[${questionCount}][text]" required>

        <label for="questions[${questionCount}][type]">Type</label>
        <select id="questions[${questionCount}][type]" name="questions[${questionCount}][type]" required>
            <option value="multiple_choice">Multiple Choice</option>
            <option value="true_false">True/False</option>
            <option value="simple_answer">Simple Answer</option>
        </select>

        <label for="questions[${questionCount}][choices]">Choices (comma separated)</label>
        <input type="text" id="questions[${questionCount}][choices]" name="questions[${questionCount}][choices]">
    `;

        questions.appendChild(question);
    });
</script>

</html>