# LinkedIn AI Agent

A full-javascript LinkedIn AI Agent application built with Laravel backend and React frontend.

## Project Structure

This project has been restructured to separate frontend and backend concerns:

```
├── backend/                 # Laravel API Backend
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── routes/
│   ├── storage/
│   ├── public/              # Public assets & Laravel entry point
│   └── ...
├── frontend/                # React Frontend Application
│   ├── resources/
│   │   ├── js/
│   │   └── css/
│   ├── package.json
│   ├── tailwind.config.js
│   └── vite.config.js
├── package.json             # Root package.json for managing both projects
└── README.md                # This file
```

## Quick Start

### Prerequisites

- Node.js (v18+)
- PHP (v8.1+)
- Composer
- MySQL/PostgreSQL

### Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd linkedin-ai-agent
   ```

2. **Install root dependencies:**
   ```bash
   npm install
   ```

3. **Install all project dependencies:**
   ```bash
   npm run install:all
   ```

### Development Setup

1. **Environment Setup:**
   ```bash
   cp backend/.env.example backend/.env
   ```
   Edit `backend/.env` with your数据库 credentials and other configuration.

2. **Database Setup:**
   ```bash
   cd backend
   php artisan key:generate
   php artisan migrate
   php artisan db:seed (optional)
   cd ..
   ```

3. **Start Development Servers:**

   **Option 1: Run both frontend and backend together:**
   ```bash
   npm run dev
   ```
   This starts both Laravel server on `:8000` and Vite dev server.

   **Option 2: Run them separately:**
   ```bash
   # Terminal 1 - Backend
   npm run dev:backend
   
   # Terminal 2 - Frontend  
   npm run dev:frontend
   ```

## Available Scripts

From the root directory:

- `npm run dev` - Start both frontend and backend development servers
- `npm run dev:backend` - Start only Laravel backend server
- `npm run dev:frontend` - Start only React frontend development server
- `npm run build` - Build frontend for production
- `npm run install:backend` - Install PHP dependencies
- `npm run install:frontend` - Install Node.js dependencies
- `npm run install:all` - Install both backend and frontend dependencies
- `npm run test:backend` - Run backend tests
- `npm run lint:frontend` - Lint frontend code

## Development Notes

### Frontend (React + Vite)
- Located in `/frontend` directory
- Built with React 18, Vite, Tailwind CSS
- Uses modern React patterns (hooks, context, etc.)
- Hot module replacement enabled in development

### Backend (Laravel)
- Located in `/backend` directory  
- Laravel 10 with Sanctum for API authentication
- RESTful API endpoints in `/routes/api.php`
- Database migrations in `/database/migrations`

### Integration
- Frontend builds into `backend/public/build` directory
- Laravel serves the built assets
- API endpoints prefixed with `/api/`
- CORS configured for frontend-backend communication

## Technologies Used

### Backend
- Laravel 10
- PHP 8.1+
- MySQL/PostgreSQL
- Laravel Sanctum
- Laravel Cashier (Stripe integration)

### Frontend
- React 18
- TypeScript
- Vite
- Tailwind CSS
- Radix UI Components
- React Hook Form
- React Query
- Axios

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License.