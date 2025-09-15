# Repository Structure Documentation

## Overview
All repositories now follow a consistent structure with interfaces in the `Contracts` folder and implementations in the `Contracts/Sql` folder, following the same pattern as the existing PostRepository.

## Repository Structure

### Interface Location
```
app/Repositories/Contracts/
├── BaseContract.php
├── PostContract.php
├── BookingContract.php
└── ServiceContract.php
```

### Implementation Location
```
app/Repositories/Contracts/Sql/
├── PostRepository.php
├── BookingRepository.php
└── ServiceRepository.php
```

## Repository Pattern

### 1. BaseContract Interface
All repository contracts extend `BaseContract` which provides:
- `all()` - Get all records
- `create(array $attributes)` - Create new record
- `update(Model $model, array $attributes)` - Update existing record
- `remove(Model $model)` - Delete record
- `find(int $id, array $relations, array $filters)` - Find by ID
- `findOrFail(int $id, array $relations, array $filters)` - Find or fail

### 2. Specialized Contracts
Each contract adds specific methods for its domain:

#### PostContract
```php
interface PostContract extends BaseContract
{
    // Only extends BaseContract, no additional methods
}
```

#### ServiceContract
```php
interface ServiceContract extends BaseContract
{
    public function getServicesBySpecialist(int $specialistId, int $perPage = 15);
    public function getActiveServices(int $perPage = 15);
    public function getServicesByType(string $type, int $perPage = 15);
    public function searchServices(string $query, int $perPage = 15);
    public function getServiceStats(int $specialistId): array;
}
```

#### BookingContract
```php
interface BookingContract extends BaseContract
{
    public function getUserBookings(int $userId, int $perPage = 15);
    public function getSpecialistBookings(int $specialistId, int $perPage = 15);
    public function isSpecialistAvailable(int $specialistId, string $startTime, string $endTime, ?int $excludeBookingId = null): bool;
    public function getAvailableTimeSlots(int $specialistId, string $date): array;
    public function cancelBooking(int $id): bool;
    public function getUserBookingStats(int $userId): array;
    public function getSpecialistBookingStats(int $specialistId): array;
}
```

## Repository Implementations

### 1. PostRepository
**Location**: `app/Repositories/Contracts/Sql/PostRepository.php`
**Implements**: `PostContract`
**Features**: Basic CRUD operations with PostResource

### 2. ServiceRepository
**Location**: `app/Repositories/Contracts/Sql/ServiceRepository.php`
**Implements**: `ServiceContract`
**Features**: 
- CRUD operations
- Specialist-specific queries
- Search and filtering
- Statistics generation

### 3. BookingRepository
**Location**: `app/Repositories/Contracts/Sql/BookingRepository.php`
**Implements**: `BookingContract`
**Features**:
- CRUD operations
- User and specialist booking queries
- Availability checking
- Time slot generation
- Statistics generation

## Implementation Pattern

### Constructor
```php
public function __construct(Model $model)
{
    $this->model = $model;
}
```

### Base Methods Implementation
```php
public function all()
{
    return $this->model->with(['relations'])->get();
}

public function create(array $attributes = []): mixed
{
    return $this->model->create($attributes);
}

public function update(Model $model, array $attributes = []): mixed
{
    $model->update($attributes);
    return $model;
}

public function remove(Model $model): mixed
{
    return $this->model->destroy($model->id);
}

public function find(int $id, array $relations = [], array $filters = []): mixed
{
    return $this->model->with($relations)->find($id);
}

public function findOrFail(int $id, array $relations = [], array $filters = []): mixed
{
    return $this->model->with($relations)->findOrFail($id);
}
```

### Specialized Methods
Each repository implements domain-specific methods following these patterns:

#### Pagination Methods
```php
public function getEntityByUser(int $userId, int $perPage = 15)
{
    return $this->model
        ->with(['relations'])
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);
}
```

#### Search Methods
```php
public function searchEntities(string $query, int $perPage = 15)
{
    return $this->model
        ->with(['relations'])
        ->where('field', 'like', '%' . $query . '%')
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);
}
```

#### Statistics Methods
```php
public function getEntityStats(int $entityId): array
{
    return [
        'total' => $this->model->where('entity_id', $entityId)->count(),
        'active' => $this->model->where('entity_id', $entityId)->where('status', 'active')->count(),
        // ... more stats
    ];
}
```

## Service Provider Registration

All repositories are registered in `AppServiceProvider`:

```php
public function register(): void
{
    $this->app->bind(PostContract::class, PostRepository::class);
    $this->app->bind(BookingContract::class, BookingRepository::class);
    $this->app->bind(ServiceContract::class, ServiceRepository::class);
    $this->app->bind(BookingServiceInterface::class, BookingService::class);
    $this->app->bind(ServiceServiceInterface::class, ServiceService::class);
}
```

## Benefits of This Structure

### 1. **Consistency**
- All repositories follow the same pattern
- Easy to understand and maintain
- Predictable structure across the application

### 2. **Separation of Concerns**
- Interfaces define contracts
- Implementations handle SQL logic
- Clear separation between contract and implementation

### 3. **Testability**
- Easy to mock interfaces for testing
- Clear contracts make testing straightforward
- Dependency injection works seamlessly

### 4. **Maintainability**
- Changes to implementation don't affect contracts
- Easy to swap implementations
- Clear structure for new developers

### 5. **Scalability**
- Easy to add new repositories
- Consistent pattern for new features
- Clear organization of code

## Usage in Controllers

Controllers use repositories through service layer:

```php
class ServiceController extends BaseApiController
{
    protected $serviceService;

    public function __construct(ServiceServiceInterface $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index(Request $request)
    {
        $result = $this->serviceService->getAllServices($perPage);
        // Handle response...
    }
}
```

## Creating New Repositories

### 1. Create Interface
```php
// app/Repositories/Contracts/YourContract.php
interface YourContract extends BaseContract
{
    public function getYourSpecificMethod(int $id): array;
}
```

### 2. Create Implementation
```php
// app/Repositories/Contracts/Sql/YourRepository.php
class YourRepository implements YourContract
{
    public function __construct(YourModel $model)
    {
        $this->model = $model;
    }

    // Implement all methods...
}
```

### 3. Register in Service Provider
```php
$this->app->bind(YourContract::class, YourRepository::class);
```

The repository structure is now consistent and follows the established pattern throughout the application!
