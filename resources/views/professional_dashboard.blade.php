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
    <div class="container">

        <div class="calendar mt-4">
            <div class="d-flex justify-content-center mb-2">

                <button class="prev btn btn-secondary ml-2">&#10094;</button>
                <h3 id="month1">{{ date('F Y', strtotime($calendar1['month'] . '/1/' . $calendar1['year'])) }}</h3>
                <button class="next btn btn-secondary ml-2">&#10095;</button>
            </div>
            <table class="table">
                {!! $calendar1['calendar'] !!}
            </table>
        </div>
        <div class="calendar mt-4">
            <div class="d-flex justify-content-center mb-2">

                <button class="prev btn btn-secondary ml-2">&#10094;</button>
                <h3 id="month2">{{ date('F Y', strtotime($calendar2['month'] . '/1/' . $calendar2['year'])) }}</h3>
                <button class="next btn btn-secondary ml-2">&#10095;</button>
            </div>
            <table class="table">
                {!! $calendar2['calendar'] !!}
            </table>
        </div>
        <div class="calendar mt-4">
            <div class="d-flex justify-content-center mb-2">

                <button class="prev btn btn-secondary ml-2">&#10094;</button>
                <h3 id="month3">{{ date('F Y', strtotime($calendar3['month'] . '/1/' . $calendar3['year'])) }}</h3>
                <button class="next btn btn-secondary ml-2">&#10095;</button>
            </div>
            <table class="table">
                {!! $calendar3['calendar'] !!}
            </table>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-6 text-center">
                <div class="card mt-4">
                    <div class="card-body">
                        <h3>Ouvrir un rendez-vous</h3>

                        <form method="POST" action="{{ route('appointment.store') }}" class="form-inline justify-content-center">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="date" class="sr-only">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <input type="hidden" id="status" name="status" value="available">
                            <button type="submit" class="btn btn-primary mb-2">Create Available Appointment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center">Rendez-vous ouvert</h3>

                        @foreach($appointments as $appointment)
                        <div class="card mt-2">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <p>{{ $appointment->date }}</p>
                                <p>{{ $appointment->status }}</p>
                                <form method="POST" action="{{ route('appointment.destroy', $appointment) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center">Réservation des patients</h3>
                        <div class="row">
                            @foreach($schedules as $schedule)
                            <div class="col-md-4">
                                <div class="schedule card {{ $schedule->status == 'accepted' ? 'accepted' : ($schedule->status == 'declined' ? 'declined' : ($schedule->status == 'pending' ? 'pending' : '')) }} mt-2" id="schedule-{{ $schedule->id }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <p>{{ $schedule->date ? \Carbon\Carbon::parse($schedule->date)->format('Y-m-d') : '' }}</p>
                                            </div>
                                            <div class="col-12 text-center">
                                                <p>
                                                    @if($schedule->status == 'pending')
                                                    Pending
                                                    @else
                                                    {{ \Carbon\Carbon::parse($schedule->appointment_time)->format('H:i') }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-12 text-center">
                                                <p>Location: {{ $schedule->location }}</p>
                                            </div>
                                            <div class="col-12 text-center">
                                                <p>City: {{ $schedule->city }}</p>
                                            </div>
                                            <div class="col-12 text-center">
                                                <p>Patient: {{ $schedule->patient->name }}</p>
                                            </div>
                                        </div>
                                        @if($schedule->status == 'pending')
                                        <div class="d-flex flex-column align-items-center">
                                            <form class="accept-form" method="POST" action="{{ route('schedule.accept', $schedule) }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="appointment_time">Appointment Time:</label>
                                                    <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                                                </div>
                                                <button type="submit" class="btn btn-success w-100">Accept</button>
                                            </form>
                                            <form class="decline-form" method="POST" action="{{ route('schedule.decline', $schedule) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-danger w-100">Decline</button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

<footer class="bg-primary text-white text-center p-3 mt-4">
    <p>&copy; 2024 Ynodoc</p>
</footer>


<script>
    document.querySelectorAll('.accept-form').forEach(function(form) {
        form.addEventListener('submit', handleFormSubmit('accepted'));
    });

    document.querySelectorAll('.decline-form').forEach(function(form) {
        form.addEventListener('submit', handleFormSubmit('declined'));
    });

    function handleFormSubmit(status) {
        return function(e) {
            e.preventDefault();

            var scheduleDiv = this.closest('.schedule');

            fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data); // Log the response data for debugging
                    if (data.status === status) {
                        scheduleDiv.classList.remove('pending');
                        scheduleDiv.classList.add(status);
                        scheduleDiv.querySelector('.accept-form').style.display = 'none';
                        scheduleDiv.querySelector('.decline-form').style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        };
    }


    var calendars = document.querySelectorAll('.calendar');
    var prevButtons = document.querySelectorAll('.prev');
    var nextButtons = document.querySelectorAll('.next');

    // Initially, only show the current month
    for (var i = 0; i < calendars.length; i++) {
        calendars[i].style.display = i === 0 ? 'block' : 'none';
        prevButtons[i].disabled = i === 0;
        nextButtons[i].disabled = i === calendars.length - 1;
    }

    prevButtons.forEach(function(button, index) {
        button.addEventListener('click', function() {
            // Hide the current month and show the previous month
            calendars[index].style.display = 'none';
            calendars[index - 1].style.display = 'block';

            // Update the button's disabled state
            prevButtons[index - 1].disabled = index - 1 === 0;
            nextButtons[index - 1].disabled = false;
        });
    });

    nextButtons.forEach(function(button, index) {
        button.addEventListener('click', function() {
            // Hide the current month and show the next month
            calendars[index].style.display = 'none';
            calendars[index + 1].style.display = 'block';

            // Update the button's disabled state
            nextButtons[index + 1].disabled = index + 1 === calendars.length - 1;
            prevButtons[index + 1].disabled = false;
        });
    });
</script>


</html>