# jwt-task

clone the project to your desktop

# git clone https://github.com/Ahmed213421/jwt-task

install the project seeders and migrations and do npm run build and copy .env.example to .env and npm install then npm run dev

# php artisan install:project MyApp

# got to http://127.0.0.1:8000/admin/login

email: spider@gmail.com
password:123

# put pusher account information to work in env file (real time notification when add a post)

-----
to work locally

📦 1. Clone the Project
# git clone https://github.com/your-username/your-project.git
# cd your-project

📁 2. Copy .env File
# cp .env.example .env

🔑 3. Generate App Key
# php artisan key:generate

📚 4. Install PHP Dependencies
# composer install

💾 5. Setup the Database
# DB_DATABASE=your_database
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

Then run:
# php artisan migrate --seed

📦 6. Install NPM Dependencies
# npm install

🧱 7. Build Frontend (for production)
# npm run build

OR

⚙️ Run Frontend Dev Server (for development)
# npm run dev

📡 8. Run Queue Worker (for notifications, emails, etc.)
# php artisan queue:work

Your Laravel project is now running locally! Visit:

# http://127.0.0.1:8000/admin/login
email: spider@gmail.com pass: 123

Start the app:
# php artisan serve

Using Laravel Echo + Pusher
Ensure your .env includes:

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=xxxx
PUSHER_APP_KEY=xxxx
PUSHER_APP_SECRET=xxxx
PUSHER_APP_CLUSTER=mt1

ensure your env
# QUEUE_CONNECTION=database






