# Booking Validation System Documentation

## Overview
The booking system includes comprehensive validation to ensure data integrity, prevent conflicts, and maintain business rules. The system validates specialist availability, time constraints, and service-specialist relationships.

## Validation Requirements

### 1. **Conflict Prevention**
- ✅ **Same Specialist Cannot Be Booked at Same Time**
- ✅ **Comprehensive Overlap Detection**
- ✅ **Detailed Conflict Information**

### 2. **Time Validation**
- ✅ **Booking Time ≥ Current Time**
- ✅ **Future Booking Limits (6 months max)**
- ✅ **Business Hours (9 AM - 11 PM)**
- ✅ **Duration Limits (30 minutes - 8 hours)**

### 3. **Service-Specialist Validation**
- ✅ **Service Must Belong to Specialist**
- ✅ **Specialist Must Be Active**
- ✅ **Service Must Exist**

## Implementation Details

### 1. BookingService Validation

#### **validateBookingData() Method**
```php
public function validateBookingData(array $data): array
{
    // 1. Basic validation rules
    $validator = Validator::make($data, [
        'specialist_id' => 'required|exists:specialists,id',
        'service_id' => 'required|exists:services,id',
        'start_time' => 'required|date|after:now',
        'end_time' => 'required|date|after:start_time',
    ]);

    // 2. Specialist validation
    $specialist = Specialist::find($data['specialist_id']);
    if (!$specialist || !$specialist->is_active) {
        return ['valid' => false, 'errors' => ['specialist_id' => ['Specialist not available']]];
    }

    // 3. Service-specialist relationship validation
    $service = Service::where('id', $data['service_id'])
        ->where('specialist_id', $data['specialist_id'])
        ->first();
    
    if (!$service) {
        return ['valid' => false, 'errors' => ['service_id' => ['Service does not belong to specialist']]];
    }

    // 4. Time validation
    $startTime = Carbon::parse($data['start_time']);
    $endTime = Carbon::parse($data['end_time']);
    $now = Carbon::now();
    
    // Future time check
    if ($startTime->lt($now)) {
        return ['valid' => false, 'errors' => ['start_time' => ['Must be in future']]];
    }

    // 6 months advance limit
    if ($startTime->gt($now->copy()->addMonths(6))) {
        return ['valid' => false, 'errors' => ['start_time' => ['Max 6 months advance']]];
    }

    // Duration validation
    $duration = $endTime->diffInHours($startTime);
    if ($duration > 8) {
        return ['valid' => false, 'errors' => ['end_time' => ['Max 8 hours duration']]];
    }
    if ($duration < 0.5) {
        return ['valid' => false, 'errors' => ['end_time' => ['Min 30 minutes duration']]];
    }

    // Business hours validation
    $startHour = $startTime->hour;
    $endHour = $endTime->hour;
    if ($startHour < 9 || $endHour > 23) {
        return ['valid' => false, 'errors' => ['start_time' => ['Business hours: 9 AM - 11 PM']]];
    }

    return ['valid' => true, 'errors' => []];
}
```

### 2. Conflict Detection System

#### **isSpecialistAvailable() Method**
```php
public function isSpecialistAvailable(int $specialistId, string $startTime, string $endTime, ?int $excludeBookingId = null): bool
{
    $startTime = Carbon::parse($startTime);
    $endTime = Carbon::parse($endTime);

    $query = $this->model
        ->where('specialist_id', $specialistId)
        ->where('status', 'confirmed')
        ->where(function ($q) use ($startTime, $endTime) {
            // Check for overlapping bookings
            $q->where(function ($q2) use ($startTime, $endTime) {
                // New booking starts during existing booking
                $q2->where('start_time', '<=', $startTime)
                   ->where('end_time', '>', $startTime);
            })->orWhere(function ($q3) use ($startTime, $endTime) {
                // New booking ends during existing booking
                $q3->where('start_time', '<', $endTime)
                   ->where('end_time', '>=', $endTime);
            })->orWhere(function ($q4) use ($startTime, $endTime) {
                // New booking completely contains existing booking
                $q4->where('start_time', '>=', $startTime)
                   ->where('end_time', '<=', $endTime);
            })->orWhere(function ($q5) use ($startTime, $endTime) {
                // Existing booking completely contains new booking
                $q5->where('start_time', '<=', $startTime)
                   ->where('end_time', '>=', $endTime);
            });
        });

    if ($excludeBookingId) {
        $query->where('id', '!=', $excludeBookingId);
    }

    return $query->count() === 0;
}
```

#### **getConflictingBookings() Method**
```php
public function getConflictingBookings(int $specialistId, string $startTime, string $endTime, ?int $excludeBookingId = null): Collection
{
    // Same logic as isSpecialistAvailable but returns actual conflicting bookings
    // with user and service relationships loaded
}
```

### 3. Enhanced Error Responses

#### **Conflict Error Response**
```json
{
    "message": "The specialist is not available at the requested time",
    "conflicting_bookings": [
        {
            "id": 1,
            "start_time": "2024-01-15 10:00:00",
            "end_time": "2024-01-15 11:00:00",
            "service": "Haircut",
            "user": "John Doe"
        }
    ]
}
```

#### **Validation Error Response**
```json
{
    "message": "Validation failed",
    "errors": {
        "start_time": ["Booking start time must be in the future."],
        "service_id": ["The selected service does not belong to this specialist."]
    }
}
```

## Validation Flow

### 1. **Create Booking Flow**
```
1. Validate input data (validateBookingData)
   ├── Check required fields
   ├── Validate specialist exists and active
   ├── Validate service belongs to specialist
   ├── Validate time constraints
   └── Validate business rules

2. Check specialist availability (isSpecialistAvailable)
   ├── Query existing confirmed bookings
   ├── Check for time overlaps
   └── Return availability status

3. If conflicts found
   ├── Get conflicting bookings (getConflictingBookings)
   └── Return detailed error with conflicts

4. If no conflicts
   ├── Create booking
   └── Return success response
```

### 2. **Update Booking Flow**
```
1. Find existing booking
2. Check authorization
3. Validate booking can be updated (not past, not cancelled)
4. Validate new data (validateBookingData)
5. Check availability (excluding current booking)
6. Update booking if valid
```

## Business Rules

### 1. **Time Constraints**
- **Minimum Duration**: 30 minutes
- **Maximum Duration**: 8 hours
- **Advance Booking**: Maximum 6 months
- **Business Hours**: 9:00 AM - 11:00 PM
- **Future Only**: Cannot book in the past

### 2. **Conflict Prevention**
- **Same Specialist**: Cannot have overlapping bookings
- **Status Check**: Only confirmed bookings cause conflicts
- **Time Overlap**: Any time overlap is considered a conflict

### 3. **Service Validation**
- **Specialist Link**: Service must belong to the specialist
- **Active Status**: Specialist must be active
- **Existence**: Service must exist in database

## API Endpoints

### 1. **Create Booking**
```
POST /api/user/bookings
{
    "specialist_id": 1,
    "service_id": 1,
    "start_time": "2024-01-15 10:00:00",
    "end_time": "2024-01-15 11:00:00"
}
```

### 2. **Update Booking**
```
PUT /api/user/bookings/{id}
{
    "specialist_id": 1,
    "service_id": 1,
    "start_time": "2024-01-15 14:00:00",
    "end_time": "2024-01-15 15:00:00"
}
```

### 3. **Check Available Slots**
```
GET /api/specialists/{id}/available-slots?date=2024-01-15&service_id=1
```

## Error Handling

### 1. **Validation Errors (422)**
- Invalid input data
- Business rule violations
- Time constraint violations

### 2. **Conflict Errors (422)**
- Specialist not available
- Time slot conflicts
- Detailed conflict information

### 3. **Authorization Errors (403)**
- Unauthorized access to booking
- Invalid user permissions

### 4. **Not Found Errors (404)**
- Booking not found
- Specialist not found
- Service not found

## Testing Scenarios

### 1. **Valid Booking**
```json
{
    "specialist_id": 1,
    "service_id": 1,
    "start_time": "2024-01-15 10:00:00",
    "end_time": "2024-01-15 11:00:00"
}
```
**Expected**: Success (201)

### 2. **Past Time Booking**
```json
{
    "specialist_id": 1,
    "service_id": 1,
    "start_time": "2023-01-15 10:00:00",
    "end_time": "2023-01-15 11:00:00"
}
```
**Expected**: Validation error (422)

### 3. **Conflicting Booking**
```json
{
    "specialist_id": 1,
    "service_id": 1,
    "start_time": "2024-01-15 10:30:00",
    "end_time": "2024-01-15 11:30:00"
}
```
**Expected**: Conflict error with details (422)

### 4. **Wrong Service-Specialist**
```json
{
    "specialist_id": 1,
    "service_id": 2, // Service belongs to different specialist
    "start_time": "2024-01-15 10:00:00",
    "end_time": "2024-01-15 11:00:00"
}
```
**Expected**: Validation error (422)

## Performance Considerations

### 1. **Database Indexes**
- `specialist_id` on bookings table
- `start_time` and `end_time` on bookings table
- `status` on bookings table

### 2. **Query Optimization**
- Use specific date ranges for availability checks
- Limit conflict queries to relevant time periods
- Use eager loading for related data

### 3. **Caching**
- Cache specialist availability for popular time slots
- Cache service-specialist relationships
- Use Redis for frequently accessed data

## Security Considerations

### 1. **Authorization**
- Users can only access their own bookings
- Specialists can only access their own bookings
- Proper middleware protection

### 2. **Input Validation**
- Sanitize all input data
- Validate data types and formats
- Prevent SQL injection

### 3. **Rate Limiting**
- Limit booking creation attempts
- Prevent spam booking attempts
- Implement proper throttling

The booking validation system ensures data integrity, prevents conflicts, and maintains business rules while providing clear error messages and detailed conflict information.
