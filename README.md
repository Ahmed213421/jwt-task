ns), service management, and advanced booking validation with conflict prevention.

## Features

- 🔐 **Multi-Authentication** - Users, Specialists, and Admins with Laravel Sanctum
- 📅 **Advanced Booking System** - Conflict prevention and availability checking
- 🛠️ **Service Management** - CRUD operations for services
- 👨‍⚕️ **Specialist Management** - Profile and service management
- ✅ **Comprehensive Validation** - Time constraints, business rules, and conflict detection
- 🏗️ **Clean Architecture** - Repository pattern with service layer
- 📊 **Statistics & Analytics** - Booking and service statistics
- 🔍 **Search & Filtering** - Advanced search capabilities



## Installation

### Prerequisites

- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js & NPM (for frontend assets)

### Setup Instructions

1. **Clone the repository**
```bash
git clone <repository-url>
cd jwt
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database configuration**
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=booking_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run migrations and seeders**
```bash
php artisan migrate:fresh --seed
```

6. **Start the development server**
```bash
php artisan serve
```

The application will be available at `127.0.0.1`

## Database Seeders

The project includes comprehensive seeders for testing:

### Users Seeder
- Creates sample users for testing
- Default password: `password`

### Specialists Seeder
- Creates sample specialists with different types
- Includes active and inactive specialists
- Default password: `password`

### Services Seeder
- Creates sample services for each specialist
- Various service types and pricing

### Bookings Seeder
- Creates sample bookings with different statuses
- Past, present, and future bookings
- Different time slots and durations

## API Endpoints

### Authentication Endpoints

#### User Authentication
```http
POST /api/user/register
POST /api/user/login
GET  /api/user/profile
POST /api/user/logout
POST /api/user/logout-all
GET  /api/user/tokens
POST /api/user/revoke-token
```

#### Specialist Authentication
```http
POST /api/specialist/register
POST /api/specialist/login
GET  /api/specialist/profile
POST /api/specialist/logout
POST /api/specialist/logout-all
GET  /api/specialist/tokens
POST /api/specialist/revoke-token
```


### Public Endpoints

#### Services
```http
GET /api/services                    # Get all services
GET /api/services/{id}               # Get specific service
GET /api/services/search?q={query}   # Search services
GET /api/services/type/{type}        # Get services by type
```

#### Specialists
```http
GET /api/specialists                 # Get all specialists
GET /api/specialists/{id}            # Get specific specialist
GET /api/specialists/search?q={query} # Search specialists
GET /api/specialists/type/{type}     # Get specialists by type
```

#### Available Slots
```http
GET /api/specialists/{id}/available-slots?date={date}&service_id={id}
```

### Protected User Endpoints

#### User Bookings
```http
GET    /api/user/bookings            # Get user's bookings
GET    /api/user/bookings/{id}       # Get specific booking
POST   /api/user/bookings            # Create new booking
PUT    /api/user/bookings/{id}       # Update booking
POST   /api/user/bookings/{id}/cancel # Cancel booking
GET    /api/user/bookings-stats      # Get user statistics
```

### Protected Specialist Endpoints

#### Specialist Services
```http
GET    /api/specialist/services      # Get specialist's services
POST   /api/specialist/services      # Create new service
PUT    /api/specialist/services/{id} # Update service
DELETE /api/specialist/services/{id} # Delete service
GET    /api/specialist/services-stats # Get service statistics
```

#### Specialist Bookings
```http
GET    /api/specialist/bookings      # Get specialist's bookings
GET    /api/specialist/bookings/{id} # Get specific booking
PUT    /api/specialist/bookings/{id} # Update booking
POST   /api/specialist/bookings/{id}/cancel # Cancel booking
GET    /api/specialist/bookings-stats # Get booking statistics
```

## Request/Response Examples

### Successful Booking Creation
```json
{
  "status": "success",
  "message": "Booking created successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "specialist_id": 1,
    "service_id": 1,
    "start_time": "2024-01-15 10:00:00",
    "end_time": "2024-01-15 11:00:00",
    "status": "confirmed",
    "specialist": {
      "id": 1,
      "name": "Dr. Smith",
      "type": "beauty"
    },
    "service": {
      "id": 1,
      "name": "Haircut",
      "price": 50.00
    }
  }
}
```

### Conflict Error Response
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

### Validation Error Response
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

## Database Schema

### Users Table
- `id`, `name`, `email`, `password`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`

### Specialists Table
- `id`, `name`, `email`, `password`, `email_verified_at`, `mobile`, `type`, `bio`, `is_active`, `remember_token`, `created_at`, `updated_at`

### Services Table
- `id`, `specialist_id`, `name`, `description`, `price`, `duration`, `type`, `is_active`, `created_at`, `updated_at`

### Bookings Table
- `id`, `user_id`, `specialist_id`, `service_id`, `start_time`, `end_time`, `status`, `created_at`, `updated_at`



### Code Structure
```
app/
├── Http/
│   ├── Controllers/Api/V1/
│   │   ├── AuthController.php
│   │   ├── UserAuthController.php
│   │   ├── SpecialistAuthController.php
│   │   ├── BookingController.php
│   │   ├── ServiceController.php
│   │   └── SpecialistController.php
│   └── Requests/
├── Models/
│   ├── User.php
│   ├── Specialist.php
│   ├── Service.php
│   └── Booking.php
├── Repositories/
│   └── Contracts/
│       ├── Sql/
│       │   ├── BookingRepository.php
│       │   └── ServiceRepository.php
│       └── BookingContract.php
├── Services/
│   ├── BookingService.php
│   └── ServiceService.php
└── Providers/
    └── AppServiceProvider.php
```
