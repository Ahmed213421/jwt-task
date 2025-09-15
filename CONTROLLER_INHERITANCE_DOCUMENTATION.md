# Controller Inheritance with BaseApiController

## Overview
All API controllers now inherit from `BaseApiController` and use consistent response formatting through the `BaseApiResponseTrait`. This provides a standardized API response structure across all endpoints.

## BaseApiController Structure

### Inheritance Chain
```
BaseApiController extends Controller
├── Uses BaseApiResponseTrait
├── Has repository property
├── Has modelResource property
└── Provides response methods
```

### Available Response Methods

#### 1. `respondWithSuccess($message, $data = [])`
Returns a successful response with status 200.

**Example:**
```php
return $this->respondWithSuccess('Data retrieved successfully', $data);
```

**Response:**
```json
{
    "status": 200,
    "message": "Data retrieved successfully",
    "data": { ... }
}
```

#### 2. `respondWithError($message, $statusCode = 500)`
Returns an error response with specified status code.

**Example:**
```php
return $this->respondWithError('Resource not found', 404);
```

**Response:**
```json
{
    "status": 404,
    "errors": {
        "message": ["Resource not found"]
    }
}
```

#### 3. `setStatusCode($statusCode)`
Sets the status code for the next response.

**Example:**
```php
return $this->setStatusCode(201)->respondWithSuccess('Created successfully', $data);
```

## Updated Controllers

### 1. ServiceController
**Inherits from:** `BaseApiController`
**Uses:** ServiceService for business logic

**Methods:**
- `index()` - Get all services
- `show($id)` - Get single service
- `store()` - Create service (specialist only)
- `update($id)` - Update service (specialist only)
- `destroy($id)` - Delete service (specialist only)
- `specialistServices()` - Get specialist's services
- `search()` - Search services
- `byType($type)` - Get services by type
- `stats()` - Get service statistics

**Example Response:**
```json
{
    "status": 200,
    "message": "Services retrieved successfully",
    "data": {
        "services": {
            "data": [...],
            "current_page": 1,
            "per_page": 15,
            "total": 10
        }
    }
}
```

### 2. BookingController
**Inherits from:** `BaseApiController`
**Uses:** BookingService for business logic

**Methods:**
- `index()` - Get user's bookings
- `specialistBookings()` - Get specialist's bookings
- `store()` - Create booking
- `show($id)` - Get single booking
- `update($id)` - Update booking
- `cancel($id)` - Cancel booking
- `availableSlots()` - Get available time slots
- `specialistStats()` - Get specialist statistics
- `userStats()` - Get user statistics

**Example Response:**
```json
{
    "status": 201,
    "message": "Booking created successfully",
    "data": {
        "booking": {
            "id": 1,
            "user_id": 1,
            "specialist_id": 1,
            "service_id": 1,
            "start_time": "2024-01-15 10:00:00",
            "end_time": "2024-01-15 11:00:00",
            "status": "confirmed"
        }
    }
}
```

### 3. SpecialistController
**Inherits from:** `BaseApiController`
**Uses:** Direct model queries (no service layer)

**Methods:**
- `index()` - Get all active specialists
- `show($id)` - Get single specialist
- `byType($type)` - Get specialists by type
- `search()` - Search specialists

**Example Response:**
```json
{
    "status": 200,
    "message": "Specialists retrieved successfully",
    "data": {
        "data": [
            {
                "id": 1,
                "name": "Dr. John Smith",
                "type": "Dentist",
                "bio": "Experienced dentist...",
                "is_active": true,
                "services": [...]
            }
        ],
        "current_page": 1,
        "per_page": 15,
        "total": 5
    }
}
```

## Response Pattern

### Success Responses
All success responses follow this pattern:
```json
{
    "status": 200,
    "message": "Descriptive success message",
    "data": { ... }
}
```

### Error Responses
All error responses follow this pattern:
```json
{
    "status": 400,
    "errors": {
        "message": ["Error description"]
    }
}
```

### Status Codes Used
- `200` - Success (GET, PUT, DELETE)
- `201` - Created (POST)
- `400` - Bad Request (validation errors)
- `401` - Unauthorized (authentication required)
- `403` - Forbidden (authorization failed)
- `404` - Not Found (resource doesn't exist)
- `422` - Unprocessable Entity (validation failed)
- `500` - Internal Server Error

## Controller Implementation Pattern

### 1. Service-Based Controllers (ServiceController, BookingController)
```php
public function index(Request $request)
{
    $result = $this->service->getData($request->all());
    
    if ($result->getStatusCode() === 200) {
        $data = $result->getData(true);
        return $this->respondWithSuccess('Data retrieved successfully', $data);
    }
    
    return $result; // Pass through service errors
}
```

### 2. Direct Model Controllers (SpecialistController)
```php
public function index(Request $request)
{
    $data = Model::with(['relations'])
        ->where('condition', true)
        ->paginate($perPage);

    return $this->respondWithSuccess('Data retrieved successfully', $data);
}
```

## Benefits of This Approach

### 1. **Consistent Response Format**
- All API responses follow the same structure
- Easy for frontend developers to handle
- Predictable error handling

### 2. **Centralized Response Logic**
- Response formatting logic is in one place
- Easy to modify response structure globally
- Reduces code duplication

### 3. **Better Error Handling**
- Standardized error responses
- Consistent status codes
- Clear error messages

### 4. **Maintainability**
- Changes to response format only need to be made in BaseApiController
- Easy to add new response methods
- Clear separation of concerns

### 5. **Testing**
- Easy to test response formats
- Consistent test expectations
- Mock response methods easily

## Usage Examples

### Creating a New Controller
```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\Contracts\YourServiceInterface;
use Illuminate\Http\Request;

class YourController extends BaseApiController
{
    protected $yourService;

    public function __construct(YourServiceInterface $yourService)
    {
        $this->yourService = $yourService;
    }

    public function index(Request $request)
    {
        $result = $this->yourService->getData();
        
        if ($result->getStatusCode() === 200) {
            $data = $result->getData(true);
            return $this->respondWithSuccess('Data retrieved successfully', $data);
        }
        
        return $result;
    }

    public function store(Request $request)
    {
        $result = $this->yourService->createData($request->all());
        
        if ($result->getStatusCode() === 201) {
            $data = $result->getData(true);
            return $this->setStatusCode(201)->respondWithSuccess('Data created successfully', $data);
        }
        
        return $result;
    }
}
```

The controller inheritance pattern is now fully implemented across all API controllers, providing consistent and maintainable response formatting throughout the application!
