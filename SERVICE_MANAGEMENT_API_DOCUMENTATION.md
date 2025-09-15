# Service Management API Documentation

## Overview
This document outlines the complete service management functionality for specialists, including adding, editing, deleting, and viewing services. The system follows the Repository pattern with Service layer for clean architecture.

## Service Management Features

### 1. Add Service (Specialist Only)
### 2. Edit Service (Specialist Only)  
### 3. Delete Service (Specialist Only)
### 4. View All Available Services (Public)
### 5. View Specialist's Services (Specialist Only)
### 6. Search Services (Public)
### 7. Filter Services by Type (Public)

## API Endpoints

### Public Service Endpoints (No Authentication Required)

#### Get All Available Services
```http
GET /api/services
```

**Query Parameters:**
- `per_page` (optional): Number of services per page (default: 15)

**Response:**
```json
{
    "message": "Services retrieved successfully",
    "services": {
        "data": [
            {
                "id": 1,
                "specialist_id": 1,
                "title": "Dental Cleaning",
                "price": 100.00,
                "duration": 60,
                "created_at": "2024-01-10T08:00:00.000000Z",
                "updated_at": "2024-01-10T08:00:00.000000Z",
                "specialist": {
                    "id": 1,
                    "name": "Dr. John Smith",
                    "type": "Dentist",
                    "is_active": true
                }
            }
        ],
        "current_page": 1,
        "per_page": 15,
        "total": 1
    }
}
```

#### Get Single Service
```http
GET /api/services/{id}
```

#### Search Services
```http
GET /api/services/search?q=dental&per_page=10
```

#### Get Services by Type
```http
GET /api/services/type/Dentist?per_page=10
```

### Specialist Service Management (Authentication Required)

#### Get Specialist's Services
```http
GET /api/specialist/services
Authorization: Bearer {specialist_token}
```

#### Create New Service
```http
POST /api/specialist/services
Authorization: Bearer {specialist_token}
Content-Type: application/json

{
    "title": "Dental Cleaning",
    "price": 100.00,
    "duration": 60
}
```

**Validation Rules:**
- `title`: Required, string, max 255 characters
- `price`: Required, numeric, minimum 0
- `duration`: Required, integer, 1-480 minutes (8 hours max)

**Response:**
```json
{
    "message": "Service created successfully",
    "service": {
        "id": 1,
        "specialist_id": 1,
        "title": "Dental Cleaning",
        "price": 100.00,
        "duration": 60,
        "created_at": "2024-01-10T08:00:00.000000Z",
        "updated_at": "2024-01-10T08:00:00.000000Z",
        "specialist": {
            "id": 1,
            "name": "Dr. John Smith",
            "type": "Dentist",
            "is_active": true
        }
    }
}
```

#### Update Service
```http
PUT /api/specialist/services/{id}
Authorization: Bearer {specialist_token}
Content-Type: application/json

{
    "title": "Advanced Dental Cleaning",
    "price": 150.00,
    "duration": 90
}
```

#### Delete Service
```http
DELETE /api/specialist/services/{id}
Authorization: Bearer {specialist_token}
```

**Response:**
```json
{
    "message": "Service deleted successfully"
}
```

#### Get Service Statistics
```http
GET /api/specialist/services-stats
Authorization: Bearer {specialist_token}
```

**Response:**
```json
{
    "message": "Statistics retrieved successfully",
    "stats": {
        "total_services": 5,
        "active_services": 5,
        "average_price": 125.50,
        "total_duration": 300
    }
}
```

## Error Responses

### Validation Errors (422)
```json
{
    "message": "Validation failed",
    "errors": {
        "title": ["Service title is required"],
        "price": ["Service price must be at least 0"],
        "duration": ["Service duration must be at least 1 minute"]
    }
}
```

### Authentication Errors (401)
```json
{
    "message": "Unauthenticated."
}
```

### Authorization Errors (403)
```json
{
    "message": "Unauthorized access to service"
}
```

### Not Found Errors (404)
```json
{
    "message": "Service not found"
}
```

## Usage Examples

### 1. Create a New Service
```bash
curl -X POST http://localhost:8000/api/specialist/services \
  -H "Authorization: Bearer YOUR_SPECIALIST_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Teeth Whitening",
    "price": 200.00,
    "duration": 120
  }'
```

### 2. Update a Service
```bash
curl -X PUT http://localhost:8000/api/specialist/services/1 \
  -H "Authorization: Bearer YOUR_SPECIALIST_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Premium Teeth Whitening",
    "price": 250.00,
    "duration": 150
  }'
```

### 3. Delete a Service
```bash
curl -X DELETE http://localhost:8000/api/specialist/services/1 \
  -H "Authorization: Bearer YOUR_SPECIALIST_TOKEN"
```

### 4. Search Services
```bash
curl -X GET "http://localhost:8000/api/services/search?q=dental&per_page=5"
```

### 5. Get Services by Type
```bash
curl -X GET "http://localhost:8000/api/services/type/Dentist"
```

### 6. Get Specialist's Services
```bash
curl -X GET http://localhost:8000/api/specialist/services \
  -H "Authorization: Bearer YOUR_SPECIALIST_TOKEN"
```

## Architecture

### Repository Pattern
- **ServiceContract**: Interface extending BaseContract
- **ServiceRepository**: SQL implementation with specialized methods
- **Methods**: CRUD operations + specialist-specific queries

### Service Layer
- **ServiceServiceInterface**: Business logic interface
- **ServiceService**: Implementation with validation and authorization
- **Features**: Data validation, authorization checks, error handling

### Controller Layer
- **ServiceController**: HTTP request handling
- **Features**: Route handling, dependency injection, response formatting

## Security Features

1. **Authentication Required**: Specialist endpoints require valid tokens
2. **Authorization**: Specialists can only manage their own services
3. **Input Validation**: Comprehensive validation for all inputs
4. **SQL Injection Protection**: Uses Eloquent ORM
5. **XSS Protection**: All output is properly escaped

## Business Rules

1. **Service Ownership**: Only the creating specialist can edit/delete their services
2. **Duration Limits**: Services can be 1-480 minutes (8 hours max)
3. **Price Validation**: Prices must be non-negative numbers
4. **Title Requirements**: Service titles are required and limited to 255 characters
5. **Active Specialists Only**: Only services from active specialists are shown publicly

## Database Schema

The services table includes:
- `id`: Primary key
- `specialist_id`: Foreign key to specialists table
- `title`: Service name
- `price`: Service price (decimal)
- `duration`: Service duration in minutes (integer)
- `created_at`: Timestamp
- `updated_at`: Timestamp

## Testing the Features

### 1. Test Service Creation
```bash
# First, get a specialist token by logging in
curl -X POST http://localhost:8000/api/specialist/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "specialist@example.com",
    "password": "password123"
  }'

# Then create a service
curl -X POST http://localhost:8000/api/specialist/services \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Service",
    "price": 50.00,
    "duration": 30
  }'
```

### 2. Test Public Service Listing
```bash
curl -X GET http://localhost:8000/api/services
```

The service management system is now fully implemented and ready for use!
