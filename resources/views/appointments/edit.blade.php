<div class="container">
    <h1>Edit Appointment</h1>
    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="patient_id">Patient ID</label>
            <input type="number" class="form-control" id="patient_id" name="patient_id" value="{{ $appointment->patient_id }}" required>
        </div>
        <div class="form-group">
            <label for="professional_id">Professional ID</label>
            <input type="number" class="form-control" id="professional_id" name="professional_id" value="{{ $appointment->professional_id }}" required>
        </div>
        <div class="form-group">
            <label for="appointment_date">Appointment Date</label>
            <input type="date" class="form-control" id="appointment_date" name="appointment_date" value="{{ $appointment->appointment_date }}" required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ $appointment->location }}" required>
        </div>
        <div class="form-group">
            <label for="specialization">Specialization</label>
            <input type="text" class="form-control" id="specialization" name="specialization" value="{{ $appointment->specialization }}" required>
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" value="{{ $appointment->city }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Appointment</button>
        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="mt-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Appointment</button>
        </form>
    </form>
</div>