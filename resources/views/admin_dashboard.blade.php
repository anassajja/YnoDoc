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

    <div class="row">
        <div class="container">
            <div class="col-md-4">
                <div class="card" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s;">
                    <div class="card-body">
                        <h5 class="card-title">Total Users: {{ $totalUsers }}</h5>
                        <h5 class="card-title">Total Therapists: {{ $totalTherapists }}</h5>
                        <h5 class="card-title">Total Professionals: {{ $totalProfessionals }}</h5>
                        <h5 class="card-title">Total Patients: {{ $totalPatients }}</h5>
                        <h5 class="card-title">Total Packs: {{ $totalPacks }}</h5>
                        <h5 class="card-title">Total Surveys: {{ $totalSurveys }}</h5>
                        <div class="text-right">
                            <a href="{{ route('admin.createTherapist') }}" class="btn btn-primary">Create Therapist</a>
                            <a href="{{ route('packs.create') }}" class="btn btn-primary">Create Pack</a>
                            <a href="{{ route('surveys.create') }}" class="btn btn-primary">Create Survey</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <!-- Patients -->
                <div class="card" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s;">
                    <div class="card-body">
                        <h5 class="card-title">Patients</h5>
                        <ul>
                            @foreach ($patients as $patient)
                            <li>
                                {{ $patient->id }} - {{ $patient->name }} - {{ $patient->email }} - {{ $patient->confirmed }} <br>
                                <form method="POST" action="{{ route('admin.confirmPatient', ['id' => $patient->id]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Confirm</button>
                                </form>

                                <form method="POST" action="{{ route('admin.unconfirmPatient', ['id' => $patient->id]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Unconfirm</button>
                                </form>
                                <a href="{{ route('admin.editPatient', ['id' => $patient->id]) }}" class="btn btn-primary">Edit</a>
                                <a href="{{ route('admin.blockPatient', $patient->id) }}" class="btn btn-primary">Block/Archive</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Professionals -->
                <div class="card" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s;">
                    <div class="card-body">
                        <h5 class="card-title">Professionals</h5>
                        <ul>
                            @foreach ($professionals as $professional)
                            <li>
                                {{ $professional->id }} - {{ $professional->name }} - {{ $professional->email }} - {{ $professional->confirmed }} <br>
                                <a href="{{ route('admin.confirmUser', ['id' => $professional->id]) }}" class="btn btn-primary">Confirm</a>
                                <a href="{{ route('admin.unconfirmUser', ['id' => $professional->id]) }}" class="btn btn-primary">Unconfirm</a>
                                <a href="{{ route('admin.editProfessional', ['id' => $professional->id]) }}" class="btn btn-primary">Edit</a>
                                <a href="{{ route('admin.blockProfessional', $professional->id) }}" class="btn btn-primary">Block/Archive</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Therapists -->
                <div class="card" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s;">
                    <div class="card-body">
                        <h5 class="card-title">Therapists</h5>
                        <ul>
                            @foreach ($therapists as $therapist)
                            <li>
                                {{ $therapist->name }} - {{ $therapist->email }}
                                <form action="{{ route('admin.deleteTherapist', ['id' => $therapist->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary">Delete</button>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Packs -->
                <div class="card" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s;">
                    <div class="card-body">
                        <h5 class="card-title">Packs</h5>
                        <ul>
                            @foreach ($packs as $pack)
                            <li>
                                {{ $pack->name }} - {{ $pack->description }}
                                <form action="{{ route('packs.delete', ['id' => $pack->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary">Delete</button>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Surveys -->
                <div class="card" style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); transition: 0.3s;">
                    <div class="card-body">
                        <h5 class="card-title">Surveys <a href="{{ route('admin.surveys') }}" class="btn btn-primary">Voir les réponses</a></h5>
                        <ul>
                            @foreach ($surveys as $survey)
                            <li>
                                {{ $survey->title }} - {{ $survey->description }}
                                <form action="{{ route('admin.surveys.destroy', $survey->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary">Delete</button>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<footer class="bg-primary text-white text-center p-3 mt-4">
    <p>&copy; 2024 Ynodoc</p>
</footer>

</html>