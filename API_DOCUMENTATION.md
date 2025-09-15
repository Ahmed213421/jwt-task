# API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {your_token_here}
```

## Response Format
All API responses follow a consistent format:

### Success Response
```json
{
    "status": "success",
    "message": "Operation completed successfully",
    "data": {
        // Response data here
    }
}
```

### Error Response
```json
{
    "status": "error",
    "message": "Error description",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

## Authentication Endpoints

### User Registration
```http
POST /api/user/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

### User Login
```http
POST /api/user/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "token": "1|abc123..."
    }
}
```

### User Profile
```http
GET /api/user/profile
Authorization: Bearer {token}
```

### User Logout
```http
POST /api/user/logout
Authorization: Bearer {token}
```

### Specialist Registration
```http
POST /api/specialist/register
Content-Type: application/json

{
    "name": "Dr. Sarah Johnson",
    "email": "sarah@clinic.com",
    "password": "password",
    "password_confirmation": "password",
    "mobile": "+1234567890",
    "type": "beauty",
    "bio": "Professional beauty specialist"
}
```

### Specialist Login
```http
POST /api/specialist/login
Content-Type: application/json

{
    "email": "sarah@clinic.com",
    "password": "password"
}
```

## Public Endpoints

### Get All Services
```http
GET /api/services
```

**Query Parameters:**
- `per_page` (optional): Number of items per page (default: 15)
- `page` (optional): Page number

### Get Service by ID
```http
GET /api/services/{id}
```

### Search Services
```http
GET /api/services/search?q={query}
```

### Get Services by Type
```http
GET /api/services/type/{type}
```

### Get All Specialists
```http
GET /api/specialists
```

### Get Specialist by ID
```http
GET /api/specialists/{id}
```

### Search Specialists
```http
GET /api/specialists/search?q={query}
```

### Get Specialists by Type
```http
GET /api/specialists/type/{type}
```

### Get Available Slots
```http
GET /api/specialists/{id}/available-slots?date={date}&service_id={service_id}
```

**Example:**
```http
GET /api/specialists/1/available-slots?date=2024-01-15&service_id=1
```

**Response:**
```json
{
    "status": "success",
    "message": "Available slots retrieved successfully",
    "data": {
        "specialist": "Dr. Sarah Johnson",
        "service": "Haircut & Styling",
        "date": "2024-01-15",
        "available_slots": [
            {
                "start_time": "2024-01-15 09:00:00",
                "end_time": "2024-01-15 09:30:00",
                "formatted_time": "09:00 - 09:30"
            },
            {
                "start_time": "2024-01-15 09:30:00",
                "end_time": "2024-01-15 10:00:00",
                "formatted_time": "09:30 - 10:00"
            }
        ]
    }
}
```

## User Protected Endpoints

### Get User Bookings
```http
GET /api/user/bookings
Authorization: Bearer {token}
```

### Get Specific Booking
```http
GET /api/user/bookings/{id}
Authorization: Bearer {token}
```

### Create Booking
```http
POST /api/user/bookings
Authorization: Bearer {token}
Content-Type: application/json

{
    "specialist_id": 1,
    "service_id": 1,
    "start_time": "2024-01-15 10:00:00",
    "end_time": "2024-01-15 11:00:00"
}
```

**Validation Rules:**
- `specialist_id`: Required, must exist in specialists table
- `service_id`: Required, must exist in services table
- `start_time`: Required, must be a valid date, must be in the future
- `end_time`: Required, must be a valid date, must be after start_time

**Business Rules:**
- Booking time must be ≥ current time
- Service must belong to the selected specialist
- No overlapping bookings for the same specialist
- Booking duration: 30 minutes to 8 hours
- Business hours: 9:00 AM - 11:00 PM
- Maximum advance booking: 6 months

### Update Booking
```http
PUT /api/user/bookings/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "specialist_id": 1,
    "service_id": 1,
    "start_time": "2024-01-15 14:00:00",
    "end_time": "2024-01-15 15:00:00"
}
```

### Cancel Booking
```http
POST /api/user/bookings/{id}/cancel
Authorization: Bearer {token}
```

### Get User Statistics
```http
GET /api/user/bookings-stats
Authorization: Bearer {token}
```

**Response:**
```json
{
    "status": "success",
    "message": "Statistics retrieved successfully",
    "data": {
        "total_bookings": 10,
        "confirmed_bookings": 8,
        "cancelled_bookings": 2,
        "upcoming_bookings": 3,
        "past_bookings": 5
    }
}
```

## Specialist Protected Endpoints

### Get Specialist Services
```http
GET /api/specialist/services
Authorization: Bearer {specialist_token}
```

### Create Service
```http
POST /api/specialist/services
Authorization: Bearer {specialist_token}
Content-Type: application/json

{
    "name": "Haircut & Styling",
    "description": "Professional haircut and styling service",
    "price": 50.00,
    "duration": 60,
    "type": "beauty"
}
```

### Update Service
```http
PUT /api/specialist/services/{id}
Authorization: Bearer {specialist_token}
Content-Type: application/json

{
    "name": "Updated Service Name",
    "description": "Updated description",
    "price": 60.00,
    "duration": 90,
    "type": "beauty"
}
```

### Delete Service
```http
DELETE /api/specialist/services/{id}
Authorization: Bearer {specialist_token}
```

### Get Service Statistics
```http
GET /api/specialist/services-stats
Authorization: Bearer {specialist_token}
```

### Get Specialist Bookings
```http
GET /api/specialist/bookings
Authorization: Bearer {specialist_token}
```

### Get Specialist Booking Statistics
```http
GET /api/specialist/bookings-stats
Authorization: Bearer {specialist_token}
```

## Error Responses

### Validation Error (422)
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "start_time": ["Booking start time must be in the future."],
        "service_id": ["The selected service does not belong to this specialist."]
    }
}
```

### Conflict Error (422)
```json
{
    "status": "error",
    "message": "The specialist is not available at the requested time",
    "conflicting_bookings": [
        {
            "id": 2,
            "start_time": "2024-01-15 10:30:00",
            "end_time": "2024-01-15 11:30:00",
            "service": "Haircut",
            "user": "Jane Doe"
        }
    ]
}
```

### Unauthorized Error (401)
```json
{
    "status": "error",
    "message": "Unauthenticated."
}
```

### Forbidden Error (403)
```json
{
    "status": "error",
    "message": "Unauthorized access to booking"
}
```

### Not Found Error (404)
```json
{
    "status": "error",
    "message": "Booking not found"
}
```

## Testing with cURL

### 1. Register a User
```bash
curl -X POST http://localhost:8000/api/user/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password",
    "password_confirmation": "password"
  }'
```

### 2. Login
```bash
curl -X POST http://localhost:8000/api/user/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password"
  }'
```

### 3. Create a Booking
```bash
curl -X POST http://localhost:8000/api/user/bookings \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "specialist_id": 1,
    "service_id": 1,
    "start_time": "2024-01-15 10:00:00",
    "end_time": "2024-01-15 11:00:00"
  }'
```

### 4. Get Available Slots
```bash
curl -X GET "http://localhost:8000/api/specialists/1/available-slots?date=2024-01-15&service_id=1"
```

## Rate Limiting
- Authentication endpoints: 5 attempts per minute
- Other endpoints: 60 requests per minute

## Pagination
List endpoints support pagination with the following query parameters:
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15, max: 100)

**Pagination Response:**
```json
{
    "data": [...],
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 45,
    "from": 1,
    "to": 15
}
```

## Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error
