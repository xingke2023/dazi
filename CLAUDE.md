# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WeChat groups social platform (同城交友搭子群) - a Laravel application with React frontend using Inertia.js for seamless SPA-like experience. The platform helps users find and join local WeChat groups for social activities, dating, and shared interests.

**Tech Stack:**
- **Backend**: Laravel 12 with PHP 8.2+
- **Frontend**: React 19 with TypeScript
- **Build Tool**: Vite with Laravel Vite Plugin
- **Styling**: Tailwind CSS v4
- **UI Components**: Radix UI primitives with custom components
- **Database**: SQLite (default)
- **Testing**: PHPUnit for backend, no frontend tests configured
- **WeChat Integration**: EasyWeChat library for WeChat APIs, Socialite for WeChat login

## Development Commands

### Backend Commands
```bash
# Start development server (includes frontend build, queue, logs)
composer run dev

# Start development server with SSR
composer run dev:ssr

# Run tests
composer run test
# or
php artisan test

# Run specific test
php artisan test --filter=TestName

# Code formatting (Laravel Pint)
vendor/bin/pint

# Database operations
php artisan migrate
php artisan migrate:fresh --seed

# Run specific seeders (may need --force in production)
php artisan db:seed --class=RegionSeeder --force
php artisan db:seed --class=WeChatGroupSeeder --force
php artisan db:seed --class=BeijingGroupSeeder --force
php artisan db:seed --class=ShenzhenGroupSeeder --force

# Database debugging
php artisan tinker
```

### Frontend Commands
```bash
# Build for production
npm run build

# Build with SSR
npm run build:ssr

# Development server (Vite only)
npm run dev

# Linting and formatting
npm run lint
npm run format
npm run format:check

# Type checking
npm run types
```

## Architecture Overview

### Backend Structure
- **Controllers**: Follow standard Laravel controller patterns with Inertia responses
- **Routes**: Organized in separate files (`web.php`, `auth.php`, `settings.php`, `wechat_pay.php`)
- **Models**: Key models include `WeChatGroup`, `Region`, `User`, `UserGroup`
- **Middleware**: Custom middleware for appearance handling and Inertia requests
- **API Routes**: RESTful APIs for provinces, cities, districts, and group data

### Frontend Structure
- **Entry Point**: `resources/js/app.tsx` - sets up Inertia app
- **Pages**: `resources/js/pages/` - Inertia page components
- **Components**: `resources/js/components/` - reusable UI components
- **Layouts**: `resources/js/layouts/` - page layout components
- **Hooks**: `resources/js/hooks/` - custom React hooks
- **UI Components**: `resources/js/components/ui/` - Radix UI-based components

### Key Patterns
- **Inertia.js**: Server-side routing with client-side navigation
- **SSR Ready**: Configured for server-side rendering via `resources/js/ssr.tsx`
- **Type Safety**: Full TypeScript setup with path aliases (`@/*` → `resources/js/*`)
- **Theme System**: Custom appearance/theme handling with local storage persistence

## Configuration Files

### Frontend Configuration
- `vite.config.ts`: Vite configuration with Laravel plugin, React, Tailwind
- `tsconfig.json`: TypeScript configuration with path aliases
- `eslint.config.js`: ESLint configuration for React and TypeScript
- `components.json`: Shadcn/ui component configuration

### Backend Configuration
- `config/inertia.php`: Inertia.js configuration with SSR enabled
- `phpunit.xml`: PHPUnit test configuration
- Database uses SQLite by default (`database/database.sqlite`)

## Testing

### Backend Tests
- Located in `tests/Feature/` and `tests/Unit/`
- Run with `php artisan test` or `composer run test`
- Uses SQLite in-memory database for testing

### Frontend
- No test framework currently configured
- Consider adding Jest/Vitest for component testing

## Business Logic & Data Model

### Geographic Hierarchy
- **Regions Table**: 3-level hierarchy (Province → City → District)
  - Level 1: Provinces (北京市, 广东省, etc.)
  - Level 2: Cities (深圳市, 广州市, etc.)
  - Level 3: Districts/Counties (南山区, 福田区, etc.)
- **Hot Cities**: Special flag `is_hot` for featured cities on homepage
- **Direct Municipalities**: Beijing, Shanghai, Tianjin, Chongqing have special handling

### WeChat Groups Structure
- **WeChatGroups Table**: Core business entity with geographic association
- **Pricing**: All groups currently priced at 9.9 yuan
- **Tags**: Comma-separated categories (交友, 脱单, 征婚, 美食搭子, etc.)
- **Capacity Management**: Tracks current_members vs capacity
- **QR Code System**: Uses demo QR codes with privacy mosaic overlay

### Key API Endpoints
- `GET /api/provinces/{id}/cities` - Get cities by province
- `GET /api/cities/{id}/districts` - Get districts by city  
- `GET /api/cities/{id}/city-districts` - Special endpoint for hot cities (always shows city-level option)
- `GET /search` - Search regions and groups
- `GET /groups/{province}/{city}` - Display groups with optional level/district filters

### Mobile-First Design
- Touch feedback and vibration effects on buttons
- Responsive grid layouts for group cards
- Horizontal scrolling for hot cities
- Mobile tab navigation at bottom

## Development Notes

- The project uses Laravel's built-in authentication with Inertia.js
- Theme/appearance system is implemented with React hooks and local storage
- UI components follow Radix UI patterns with Tailwind styling
- Database migrations include users, cache, jobs, regions, and wechat_groups tables
- SSR is configured but disabled by default (`config/inertia.php`)
- WeChat OAuth integration via Socialite and EasyWeChat
- QR code privacy protection with center-only mosaic overlay