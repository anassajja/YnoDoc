<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patient Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

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
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt fa-lg"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </nav>
</header>

<body>


    <div class="container mt-5">
        <div class="card mb-4">
            <div class="card-header">
                <h3>Search for Appointments</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('patient.search') }}">
                    <div class="form-group">
                        <label>Location:</label>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="location" value="clinic">
                                <i class="fas fa-hospital"></i> Clinic
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="location" value="atHome">
                                <i class="fas fa-home"></i> At Home
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="location" value="cabinet">
                                <i class="fas fa-briefcase"></i> Cabinet
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="specialization">Specialization:</label>
                        <select id="specialization" name="specialization" required class="form-control">
                            @foreach($specialities as $speciality)
                            <option value="{{ $speciality->specialization }}">{{ $speciality->specialization }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="city">City:</label>
                        <select id="city" name="city" required class="form-control">
                            @foreach($cities as $city)
                            <option value="{{ $city->city }}">{{ $city->city }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Upcoming Appointments</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Professional</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($schedule->date)->format('Y-m-d') }}</td>
                            <td>
                                @if($schedule->status == 'pending')
                                Pending
                                @else
                                {{ \Carbon\Carbon::parse($schedule->appointment_time)->format('H:i') }}
                                @endif
                            </td>
                            <td>{{ $schedule->professional->name }}</td>
                            <td>{{ $schedule->status }}</td>
                            <td>
                                @if($schedule->status == 'pending')
                                <form method="POST" action="{{ route('schedule.destroy', $schedule->id) }}" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <a href="{{ route('psychotherapy.create') }}" class="btn btn-primary">Je choisis mon thérapeute</a>
            <a href="/patient/surveys" class="btn btn-primary">Answer Survey</a>
        </div>
    </div>
</body>
<footer class="bg-primary text-white text-center p-3 mt-4">
    <p>&copy; 2024 Ynodoc</p>
</footer>


<script>
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            var scheduleRow = this.closest('tr');
            var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            if (!csrfToken) {
                console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
                return;
            }

            fetch(this.action, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: new FormData(this)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Fetch request successful', data);
                    if (data.status === 'deleted') {
                        scheduleRow.remove();
                    }
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        });
    });
</script>


</html>