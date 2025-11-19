# Mini Wallet - Laravel 12 + Vue 3 Application

A real-time wallet application built with Laravel 12, Vue 3, Inertia.js, and Pusher for live updates.

## Features

- User Authentication with Sanctum
- Real-time Wallet Balance Updates (Pusher)
- Money Transfer System
- Transaction History
- Multi-tab Synchronization
- Commission Calculation (1.5%)
- RESTful API
- Session-based Web Routes
- Token-based API Routes

## Tech Stack

**Backend:**
- Laravel 12
- Laravel Sanctum (API Authentication)
- Laravel Broadcasting (Pusher)
- MySQL/SQLite

**Frontend:**
- Vue 3 (Composition API)
- TypeScript
- Inertia.js (Server-Side Rendering)
- Tailwind CSS
- Axios

**Real-time:**
- Pusher (WebSocket Broadcasting)
- Laravel Echo

## Prerequisites

- PHP 8.2+
- Node.js 18+
- npm or yarn
- MySQL/SQLite
- Composer
- Pusher Account (for real-time features)

## Installation

### 1. Clone Repository

```bash
git clone https://github.com/umarm6/mini-wallet.git
cd mini-wallet
```

### 2. Install Dependencies

```bash
# Backend
composer install

# Frontend
npm install
```

### 3. Environment Configuration

```bash
# Copy .env file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure .env

Update these values in `.env`:

```env
# App
APP_NAME="Mini Wallet"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mini_wallet
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_DRIVER=file
SESSION_SECURE_COOKIES=false
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:8000,127.0.0.1:8000
SANCTUM_TOKEN_PREFIX=

# Pusher (Get from https://pusher.com)
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1

# Vite
VITE_PUSHER_APP_KEY=your_app_key
VITE_PUSHER_APP_CLUSTER=mt1
```

### 5. Database Setup

```bash
# Run migrations
php artisan migrate:fresh

# Seed demo data
php artisan db:seed
```

### 6. Start Development Servers

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Frontend Build:**
```bash
npm run dev
```

Visit: `http://localhost:8000`


### How Authentication Works

1. **Web Login (Form-based)**
    - User fills login form
    - POST to `/login` (web route)
    - `WebLoginController@store` called
    - `Auth::attempt()` creates session
    - Session ID stored in `LARAVEL_SESSION` cookie
    - Sanctum token generated for API
    - User redirected to dashboard

2. **API Authentication (Token-based)**
    - Client sends token in Authorization header
    - Sanctum validates token
    - Protected API routes accessible

3. **Session Persistence**
    - Session stored in `storage/framework/sessions/`
    - Valid for 120 minutes
    - Destroyed on logout

## API Endpoints

### Authentication

- `POST /api/login` - Login (returns token)
- `POST /api/register` - Register new user
- `GET /api/me` - Get current user
- `POST /api/logout` - Logout

### Transactions

- `GET /api/transactions` - Get transaction history with balance
- `POST /api/transactions` - Send money (transfer)

### Web Routes

- `GET /` - Home page
- `GET /login` - Login form
- `POST /login` - Submit login (creates session)
- `GET /dashboard` - Dashboard (protected)
- `POST /logout` - Logout

## Transfer System

### Flow

1. User fills receiver ID and amount
2. Commission calculated (1.5% of amount)
3. Form submitted
4. API validates and processes transfer
5. Balances updated in database
6. Event broadcasted to Pusher
7. Real-time update sent to all connected clients
8. Balance updates on all tabs instantly

### Validation

- Receiver must exist
- Sender must have sufficient balance
- Amount must be > 0
- Receiver cannot be sender

## Real-Time Updates (Pusher)

### How It Works

1. **Transaction Completed Event**
    - Backend: `TransactionCompleted` event dispatched
    - Broadcasts to: `user.{user_id}` channel
    - Event name: `transactionCompleted`

2. **Frontend Listener**
    - Wallet component subscribes to Pusher
    - Listens for `transactionCompleted` event
    - Updates balance in real-time
    - Refreshes transaction history

3. **Multi-tab Sync**
    - All tabs listening to same channel
    - All tabs receive same broadcast
    - All tabs update simultaneously

### Broadcasting Channels

```
Private channels:
- transaction_user.{user_id}  (Transaction updates)

Events:
- transactionCompleted  (Broadcast when transfer succeeds)
```
### Demo Accounts & Credentials

| Account | Email | Password | 
|---------|-------|----------|
| **User 1** | john@example.com | password123 |
| **User 2** | jane@example.com | password123 |  
| **User 3** | bob@example.com | password123 |  
| **User 4** | alice@example.com | password123 | 
| **User 5** | eve@example.com | password123 | 

### Quick Start

**Recommended Test Flow:**

1. **First Login:**
    - Email: `john@example.com`
    - Password: `password123`
    - Balance: $1000.00

2. **Send Money:**
    - Receiver ID: `2` (jane@example.com)
    - Amount: `$50.00`
    - Watch balance update to $949.50 (with 1.5% commission)

3. **Open Multiple Tabs:**
    - Tab 1: Logged in as john@example.com
    - Tab 2: Logged in as jane@example.com
    - Send money in Tab 1
    - Watch Tab 2 balance update in real-time

4. **Switch Accounts:**
    - Logout from current account
    - Login with different email
    - Verify transaction appears in history

### Demo Account Features

- All accounts pre-created with verified emails
- Pre-loaded with different balances
- Ready for immediate testing
- Can send/receive unlimited transfers
- Transaction history included
- Real-time updates enabled

### How to Get Demo Accounts

**After running migrations:**

```bash
# Seed demo data
php artisan db:seed
```

**Database Seeder creates:**
- 5 demo users
- Each with different balance
- Email verification completed
- Personal access tokens ready

### Manual Testing

```bash
# 1. Login
# 2. Open wallet in multiple tabs
# 3. Send money from Tab 1
# 4. Watch Tab 2 balance update in real-time
# 5. Check transaction history appears
```

### API Testing with cURL

```bash
# Get transactions
curl -X GET http://localhost:8000/api/transactions \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Send money
curl -X POST http://localhost:8000/api/transactions \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"receiver_id": 2, "amount": 50}'
```

##  Troubleshooting

### Issue: Real-time updates not working

**Problem:** Pusher not configured or not broadcasting

**Solution:**
1. Get Pusher credentials from https://pusher.com
2. Add to `.env`:
   ```
   PUSHER_APP_ID=your_id
   PUSHER_APP_KEY=your_key
   PUSHER_APP_SECRET=your_secret
   ```
3. Verify frontend subscribes to channel
4. Check browser console for Pusher errors

## Security

- CSRF protection on all forms
- Password hashing with Bcrypt
- Sanctum token validation on API
- Session timeout after 120 minutes
- HTTPS recommended for production
- Environment variables for sensitive data

##  Deployment

### Production Checklist

- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`
- [ ] Configure HTTPS/SSL
- [ ] Update `.env` with production URLs
- [ ] Use production database (MySQL)
- [ ] Configure Pusher for production
- [ ] Run migrations: `php artisan migrate`
- [ ] Clear cache: `php artisan optimize`
- [ ] Build assets: `npm run build`

## Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Vue 3 Documentation](https://vuejs.org/)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Pusher Documentation](https://pusher.com/docs)

## Development

### Code Style

- PSR-12 for PHP
- Vue style guide for Vue components
- Prettier for formatting
- ESLint for linting

## License

MIT License


**Happy Building!**

 
