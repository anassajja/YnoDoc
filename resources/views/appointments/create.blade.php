<div class="container">
    <h1>Create New Appointment</h1>
    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="patient_id">Patient ID</label>
            <input type="number" class="form-control" id="patient_id" name="patient_id" required>
        </div>
        <div class="form-group">
            <label for="professional_id">Professional ID</label>
            <input type="number" class="form-control" id="professional_id" name="professional_id" required>
        </div>
        <div class="form-group">
            <label for="appointment_date">Appointment Date</label>
            <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" class="form-control" id="location" name="location" required>
        </div>
        <div class="form-group">
            <label for="specialization">Specialization</label>
            <input type="text" class="form-control" id="specialization" name="specialization" required>
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Appointment</button>
    </form>
</div>