<!DOCTYPE html>
<html lang="en">

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

    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Professional Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Profile details will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <h2 class="text-primary">Search Results</h2>

        <div class="row">
            @foreach($professionals as $professional)
            <div class="col-md-6">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="card-title text-primary">{{ $professional->titre }}{{ $professional->name }}</h3>
                                <p class="card-text">{{ $professional->specialization }}</p>
                                <p class="card-text">{{ $professional->address }}, {{ $professional->city }}</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" class="btn btn-primary view-profile" data-id="{{ $professional->id }}">
                                    View Profile
                                </button>
                            </div>
                        </div>
                        <div class="calendar-container" data-professional-id="{{ $professional->id }}" data-patient-id="{{ auth()->id() }}" data-location="{{ $professional->location }}" data-city="{{ $professional->city }}">
                            <div class="navigation d-flex justify-content-between align-items-center mt-3">
                                <button class="btn btn-outline-primary prev">Prev</button>
                                <h4 class="month-year m-0"></h4>
                                <button class="btn btn-outline-primary next">Next</button>
                            </div>
                            <div class="calendar border rounded p-3 mt-3" data-month-year="{{ date('F', mktime(0, 0, 0, $professional->calendar1['month'], 10)) }} {{ $professional->calendar1['year'] }}">
                                <table class="table table-bordered">
                                    {!! $professional->calendar1['calendar'] !!}
                                </table>
                            </div>
                            <div class="calendar border rounded p-3 mt-3" data-month-year="{{ date('F', mktime(0, 0, 0, $professional->calendar2['month'], 10)) }} {{ $professional->calendar2['year'] }}">
                                <table class="table table-bordered">
                                    {!! $professional->calendar2['calendar'] !!}
                                </table>
                            </div>
                            <div class="calendar border rounded p-3 mt-3" data-month-year="{{ date('F', mktime(0, 0, 0, $professional->calendar3['month'], 10)) }} {{ $professional->calendar3['year'] }}">
                                <table class="table table-bordered">
                                    {!! $professional->calendar3['calendar'] !!}
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        <div class="profile-details" style="display: none;">
                            <h3 class="card-title">Profile du {{ $professional->titre }}{{ $professional->name }}</h3>
                            <p class="card-text">{{ $professional->specialization }}</p>
                            <p class="card-text">Tel: {{ $professional->proPhone }}</p>
                            <p class="card-text">{{ $professional->location }}</p>
                            <p class="card-text">Description: {{ $professional->description }}</p>
                            <p class="card-text">Email: {{ $professional->email }}</p>
                            <p class="card-text">Adresse: {{ $professional->address }}</p>
                            <p class="card-text">Ville: {{ $professional->city }}</p>
                        </div>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        var calendarContainers = document.querySelectorAll('.calendar-container');

        calendarContainers.forEach(function(container) {
            var calendars = container.querySelectorAll('div.calendar');
            var prevButton = container.querySelector('.prev');
            var nextButton = container.querySelector('.next');
            var monthYearDiv = container.querySelector('.month-year');

            // Initially, only show the first calendar
            for (var i = 0; i < calendars.length; i++) {
                calendars[i].style.display = i === 0 ? 'block' : 'none';
            }
            prevButton.disabled = true;
            nextButton.disabled = calendars.length <= 1;

            // Initially, set the monthYearDiv text to the first calendar's month and year
            monthYearDiv.textContent = calendars[0].dataset.monthYear;

            prevButton.addEventListener('click', function() {
                for (var i = 0; i < calendars.length; i++) {
                    if (calendars[i].style.display !== 'none') {
                        calendars[i].style.display = 'none';
                        calendars[i - 1].style.display = 'block';
                        prevButton.disabled = i - 1 === 0;
                        nextButton.disabled = false;
                        monthYearDiv.textContent = calendars[i - 1].dataset.monthYear;
                        break;
                    }
                }
            });

            nextButton.addEventListener('click', function() {
                for (var i = 0; i < calendars.length; i++) {
                    if (calendars[i].style.display !== 'none') {
                        calendars[i].style.display = 'none';
                        calendars[i + 1].style.display = 'block';
                        nextButton.disabled = i + 1 === calendars.length - 1;
                        prevButton.disabled = false;
                        monthYearDiv.textContent = calendars[i + 1].dataset.monthYear;
                        break;
                    }
                }
            });

            var cells = document.querySelectorAll('.calendar td');

            var handleClick = function() {
                // Only proceed if the cell has the 'available' class
                if (!this.classList.contains('available')) {
                    return;
                }

                var date = this.dataset.date;
                var selected = this.classList.contains('selected');
                var container = this.closest('.calendar-container');
                var professionalId = container.getAttribute('data-professional-id');
                var patientId = container.getAttribute('data-patient-id');
                var location = container.getAttribute('data-location');
                var city = container.getAttribute('data-city');
                var cell = this; // Define the cell variable

                if (!selected) {
                    axios.post('/patient/bookAppointment', {
                        professional_id: professionalId,
                        patient_id: patientId,
                        date: date,
                        location: location,
                        city: city
                    }).then(function(response) {
                        console.log(response.data.status);
                        cell.classList.remove('available'); // Remove 'available' class
                        if (response.data.status === 'pending') {
                            cell.classList.add('pending'); // If the appointment is pending, add 'pending' class
                            cell.removeEventListener('click', handleClick); // Remove the click event listener
                        } else if (response.data.status === 'booked') {
                            cell.classList.add('booked'); // If the appointment is booked, add 'booked' class
                            cell.removeEventListener('click', handleClick); // Remove the click event listener
                        }
                    }).catch(function(error) {
                        console.error(error);
                        console.error(error.response.data);
                    });
                }
            };

            $(document).ready(function() {
                $('td.day').click(function() {
                    var date = $(this).data('date');
                    var professionalId = $(this).data('professional-id');
                    var location = $(this).data('location');
                    var city = $(this).data('city');
                    var patientId = $(this).data('patient-id');

                    if ($(this).hasClass('status-available')) {
                        $.ajax({
                            url: '/patient/bookAppointment',
                            method: 'POST',
                            data: {
                                professional_id: professionalId, // must be an integer
                                date: date, // must be a date
                                location: location, // must be a string
                                city: city, // must be a string
                                patient_id: patientId // must be an integer
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Laravel CSRF token
                            },
                            success: function(response) {
                                // handle success
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error(jqXHR.responseJSON);
                            }
                        });
                    }
                });
            });
        });
    });
    $(document).ready(function() {
        $('.view-profile').click(function() {
            var profileDetails = $(this).closest('.card').find('.profile-details').html();
            $('#profileModal .modal-body').html(profileDetails);
            $('#profileModal').modal('show');
        });
    });
</script>


</html>