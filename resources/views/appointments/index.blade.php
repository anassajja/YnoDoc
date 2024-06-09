<div class="container">
    <h1>Appointments</h1>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">Create New Appointment</a>
    <table class="table table-striped mt-2">
        <thead>
            <tr>
                <th>Patient ID</th>
                <th>Professional ID</th>
                <th>Appointment Date</th>
                <th>Location</th>
                <th>Specialization</th>
                <th>City</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
            <tr>
                <td>{{ $appointment->patient_id }}</td>
                <td>{{ $appointment->professional_id }}</td>
                <td>{{ $appointment->appointment_date }}</td>
                <td>{{ $appointment->location }}</td>
                <td>{{ $appointment->specialization }}</td>
                <td>{{ $appointment->city }}</td>
                <td>
                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>