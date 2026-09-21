# Task 4 — Database Normalization

## Unnormalized form (UNF)

One bad starting table might be `HospitalVisit_UNF`:

```text
VisitID, PatientID, PatientName, PatientPhone, DoctorID, DoctorName,
DepartmentName, AppointmentDate, RoomNumber, Medicines{MedicineName, Dosage},
TreatmentDetails, BillTotal, PaymentStatus
```

The repeating `Medicines{...}` group is not atomic, and patient/doctor/department facts are duplicated on every visit.

## First normal form (1NF)

Make each value atomic and create one row per prescribed medicine:

```text
VisitMedicine_1NF(VisitID, PatientID, PatientName, PatientPhone, DoctorID,
DoctorName, DepartmentName, AppointmentDate, RoomNumber, MedicineName,
Dosage, TreatmentDetails, BillTotal, PaymentStatus)
```

Possible composite key: `(VisitID, MedicineName)`. Repeating groups are removed, but patient and doctor details still depend only on part of the key.

## Second normal form (2NF)

Remove partial dependencies from the composite key:

```text
Patient(PatientID, PatientName, PatientPhone)
Doctor(DoctorID, DoctorName, DepartmentName)
Visit(VisitID, PatientID, DoctorID, AppointmentDate, RoomNumber, TreatmentDetails, BillTotal, PaymentStatus)
VisitMedicine(VisitID, MedicineName, Dosage)
```

Now patient data depends on `PatientID`, doctor data depends on `DoctorID`, and medicine dosage depends on the whole `VisitID + MedicineName` key.

## Third normal form (3NF)

Remove transitive dependencies such as `DoctorID → DepartmentName` and separate independent hospital concepts:

```text
Department(DepartmentID, DepartmentName, Location, ContactNumber, HeadOfDepartment)
Doctor(DoctorID, DepartmentID, DoctorName, Specialization, Phone, Email, ...)
Patient(PatientID, PatientName, Gender, DOB, BloodGroup, Address, Phone, ...)
Appointment(AppointmentID, PatientID, DoctorID, Date, Time, Purpose, Status, Notes)
Room(RoomID, RoomNumber, RoomType, Capacity, DailyCharge, AvailabilityStatus)
Admission(AdmissionID, PatientID, RoomID, AdmissionDate, DischargeDate, Reason, Status)
Treatment(TreatmentID, PatientID, DoctorID, AdmissionID, Diagnosis, Medicines, Date, FollowUp)
Bill(BillID, PatientID, BillDate, TotalCharge, Discount, Tax, FinalAmount, Method, Status)
```

This is the implemented design. Each non-key attribute depends on the key, the whole key, and nothing but the key; it reduces update, insert, and delete anomalies.
