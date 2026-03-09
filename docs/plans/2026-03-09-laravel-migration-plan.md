# 4est.info Laravel Migration — Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Rewrite 4est.info from vanilla PHP to Laravel 12 with Docker Compose local dev, custom Blade auth, i18n (RO/EN), and admin panel.

**Architecture:** Laravel 12 MVC with Eloquent ORM, Blade templates, Tailwind CSS. Docker Compose for local dev (PHP-FPM + Nginx + MySQL + Mailpit). Translation table pattern for creation content, lang files for UI strings.

**Tech Stack:** Laravel 12, PHP 8.3, MySQL 8, Tailwind CSS, Vite, Docker Compose

---

## Task 1: Project Scaffolding & Docker Compose

**Files:**
- Create: `docker-compose.yml`
- Create: `docker/nginx/default.conf`
- Create: `docker/php/Dockerfile`
- Create: `Makefile`
- Create: `.env.example`

**Step 1: Create Laravel project**

```bash
cd /home/forest/forest/4est
composer create-project laravel/laravel src
```

**Step 2: Create Docker PHP Dockerfile**

Create `docker/php/Dockerfile`:
```dockerfile
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
```

**Step 3: Create Nginx config**

Create `docker/nginx/default.conf`:
```nginx
server {
    listen 80;
    index index.php index.html;
    root /var/www/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

**Step 4: Create docker-compose.yml**

```yaml
services:
  app:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    volumes:
      - ./src:/var/www
    depends_on:
      - mysql

  nginx:
    image: nginx:alpine
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app

  mysql:
    image: mysql:8.0
    ports:
      - "3306:3306"
    environment:
      MYSQL_DATABASE: 4est
      MYSQL_ROOT_PASSWORD: secret
      MYSQL_USER: 4est
      MYSQL_PASSWORD: secret
    volumes:
      - mysql_data:/var/lib/mysql

  mailpit:
    image: axllent/mailpit
    ports:
      - "8025:8025"
      - "1025:1025"

  node:
    image: node:20-alpine
    volumes:
      - ./src:/var/www
    working_dir: /var/www
    command: sh -c "npm install && npm run dev -- --host 0.0.0.0"
    ports:
      - "5173:5173"

volumes:
  mysql_data:
```

**Step 5: Create Makefile**

```makefile
up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build --no-cache

shell:
	docker compose exec app bash

migrate:
	docker compose exec app php artisan migrate

seed:
	docker compose exec app php artisan db:seed

fresh:
	docker compose exec app php artisan migrate:fresh --seed

test:
	docker compose exec app php artisan test

tinker:
	docker compose exec app php artisan tinker

artisan:
	docker compose exec app php artisan $(cmd)

npm-dev:
	docker compose exec node npm run dev

npm-build:
	docker compose exec node npm run build
```

**Step 6: Configure .env in src/**

Update `src/.env`:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=4est
DB_USERNAME=4est
DB_PASSWORD=secret

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS=contact@4est.info
MAIL_FROM_NAME="4est"

APP_LOCALE=ro
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=ro_RO
```

**Step 7: Boot and verify**

```bash
make build && make up
# Visit http://localhost:8080 — should see Laravel welcome page
```

**Step 8: Install Tailwind CSS**

```bash
docker compose exec node sh -c "cd /var/www && npm install -D tailwindcss @tailwindcss/vite"
```

Update `src/vite.config.js` to include Tailwind plugin.
Update `src/resources/css/app.css` with `@import "tailwindcss"`.

**Step 9: Commit**

```bash
git init && git add -A && git commit -m "feat: scaffold Laravel 12 project with Docker Compose"
```

---

## Task 2: Database Migrations

**Files:**
- Create: `src/database/migrations/xxxx_create_categories_table.php`
- Create: `src/database/migrations/xxxx_create_materials_table.php`
- Create: `src/database/migrations/xxxx_create_creations_table.php`
- Create: `src/database/migrations/xxxx_create_creation_translations_table.php`
- Create: `src/database/migrations/xxxx_create_gallery_images_table.php`
- Create: `src/database/migrations/xxxx_create_statistics_table.php`
- Modify: `src/database/migrations/xxxx_create_users_table.php` (default Laravel migration)

**Step 1: Modify users migration**

Add `role` column to the default users migration:
```php
$table->string('role')->default('customer'); // 'admin' or 'customer'
```

**Step 2: Create categories migration**

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name', 50);
    $table->string('slug', 50)->unique();
    $table->timestamps();
});
```

**Step 3: Create materials migration**

```php
Schema::create('materials', function (Blueprint $table) {
    $table->id();
    $table->string('name', 50);
    $table->timestamps();
});
```

**Step 4: Create creations migration**

```php
Schema::create('creations', function (Blueprint $table) {
    $table->id();
    $table->string('size', 50);
    $table->decimal('price', 8, 2)->default(0);
    $table->string('download', 255)->nullable();
    $table->foreignId('category_id')->constrained();
    $table->foreignId('material_id')->constrained();
    $table->timestamps();
});
```

**Step 5: Create creation_translations migration**

```php
Schema::create('creation_translations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('creation_id')->constrained()->cascadeOnDelete();
    $table->string('locale', 5); // 'ro', 'en'
    $table->string('name', 255);
    $table->string('slug', 255);
    $table->text('description');
    $table->timestamps();

    $table->unique(['creation_id', 'locale']);
    $table->index('slug');
});
```

**Step 6: Create gallery_images migration**

```php
Schema::create('gallery_images', function (Blueprint $table) {
    $table->id();
    $table->foreignId('creation_id')->constrained()->cascadeOnDelete();
    $table->string('filename', 255);
    $table->tinyInteger('ranking')->default(0);
    $table->timestamps();
});
```

**Step 7: Create statistics migration**

```php
Schema::create('statistics', function (Blueprint $table) {
    $table->id();
    $table->string('ip', 45);
    $table->string('city', 100)->nullable();
    $table->string('state', 100)->nullable();
    $table->string('country', 100)->nullable();
    $table->string('continent', 100)->nullable();
    $table->string('page', 100)->nullable();
    $table->timestamps();
});
```

**Step 8: Run migrations**

```bash
make migrate
```
Expected: All tables created successfully.

**Step 9: Commit**

```bash
git add -A && git commit -m "feat: add database migrations for all tables"
```

---

## Task 3: Eloquent Models

**Files:**
- Modify: `src/app/Models/User.php`
- Create: `src/app/Models/Category.php`
- Create: `src/app/Models/Material.php`
- Create: `src/app/Models/Creation.php`
- Create: `src/app/Models/CreationTranslation.php`
- Create: `src/app/Models/GalleryImage.php`
- Create: `src/app/Models/Statistic.php`

**Step 1: Write tests for model relationships**

Create `src/tests/Feature/Models/CreationTest.php`:
```php
public function test_creation_belongs_to_category(): void
{
    $category = Category::factory()->create();
    $creation = Creation::factory()->create(['category_id' => $category->id]);
    $this->assertInstanceOf(Category::class, $creation->category);
}

public function test_creation_has_many_translations(): void
{
    $creation = Creation::factory()->create();
    CreationTranslation::factory()->create(['creation_id' => $creation->id, 'locale' => 'ro']);
    CreationTranslation::factory()->create(['creation_id' => $creation->id, 'locale' => 'en']);
    $this->assertCount(2, $creation->translations);
}

public function test_creation_has_many_gallery_images(): void
{
    $creation = Creation::factory()->create();
    GalleryImage::factory()->count(3)->create(['creation_id' => $creation->id]);
    $this->assertCount(3, $creation->galleryImages);
}
```

**Step 2: Run tests — expect FAIL**

```bash
make test
```

**Step 3: Create models**

`User.php` — add:
```php
protected $casts = [
    'password' => 'hashed',
];

public function isAdmin(): bool
{
    return $this->role === 'admin';
}
```

`Category.php`:
```php
class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    public function creations(): HasMany
    {
        return $this->hasMany(Creation::class);
    }
}
```

`Material.php`:
```php
class Material extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function creations(): HasMany
    {
        return $this->hasMany(Creation::class);
    }
}
```

`Creation.php`:
```php
class Creation extends Model
{
    use HasFactory;
    protected $fillable = ['size', 'price', 'download', 'category_id', 'material_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CreationTranslation::class);
    }

    public function translation(?string $locale = null): HasOne
    {
        return $this->hasOne(CreationTranslation::class)
            ->where('locale', $locale ?? app()->getLocale());
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class)->orderBy('ranking');
    }

    public function coverImage(): HasOne
    {
        return $this->hasOne(GalleryImage::class)->orderBy('ranking');
    }
}
```

`CreationTranslation.php`:
```php
class CreationTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['creation_id', 'locale', 'name', 'slug', 'description'];

    public function creation(): BelongsTo
    {
        return $this->belongsTo(Creation::class);
    }
}
```

`GalleryImage.php`:
```php
class GalleryImage extends Model
{
    use HasFactory;
    protected $fillable = ['creation_id', 'filename', 'ranking'];

    public function creation(): BelongsTo
    {
        return $this->belongsTo(Creation::class);
    }
}
```

`Statistic.php`:
```php
class Statistic extends Model
{
    use HasFactory;
    protected $fillable = ['ip', 'city', 'state', 'country', 'continent', 'page'];
}
```

**Step 4: Create factories for all models**

Create factories in `src/database/factories/` for each model with appropriate fake data.

**Step 5: Run tests — expect PASS**

```bash
make test
```

**Step 6: Commit**

```bash
git add -A && git commit -m "feat: add Eloquent models with relationships and factories"
```

---

## Task 4: Database Seeders (Import Old Data)

**Files:**
- Create: `src/database/seeders/CategorySeeder.php`
- Create: `src/database/seeders/MaterialSeeder.php`
- Create: `src/database/seeders/CreationSeeder.php`
- Create: `src/database/seeders/AdminUserSeeder.php`
- Modify: `src/database/seeders/DatabaseSeeder.php`

**Step 1: Create CategorySeeder**

Seed from existing data:
```php
$categories = [
    ['id' => 1, 'name' => 'Laser', 'slug' => 'laser'],
    ['id' => 2, 'name' => '3D Print', 'slug' => '3d-print'],
    ['id' => 3, 'name' => 'Electronics', 'slug' => 'electronics'],
    ['id' => 4, 'name' => 'Plans', 'slug' => 'plans'],
];
```

**Step 2: Create MaterialSeeder**

```php
$materials = [
    ['id' => 1, 'name' => '4mm Plywood'],
    ['id' => 2, 'name' => '6mm Plywood'],
    ['id' => 3, 'name' => '8mm Plywood'],
    ['id' => 4, 'name' => 'PLA'],
    ['id' => 5, 'name' => 'Arduino'],
];
```

**Step 3: Create CreationSeeder**

This seeder must:
1. Read the old SQL dump from `old/estinfo_4est.sql`
2. Parse creation data (id, name, size, description as hex blob, price, download, category_id, material_id)
3. Convert hex blob descriptions to UTF-8 text
4. Create `Creation` records
5. Create `CreationTranslation` records with locale `en` (original content is in English)
6. Generate slugs from names using `Str::slug()`
7. Parse gallery data and create `GalleryImage` records

**Important:** The `description` field in the old DB is stored as a hex blob. Decode with `hex2bin()` to get the HTML content.

**Step 4: Create AdminUserSeeder**

```php
User::create([
    'name' => 'Forest',
    'email' => 'admin@4est.info',
    'password' => bcrypt('CHANGE_ME_IMMEDIATELY'),
    'role' => 'admin',
]);
```
Note: Do NOT use the old MD5 password. Set a new bcrypt password.

**Step 5: Wire up DatabaseSeeder**

```php
public function run(): void
{
    $this->call([
        AdminUserSeeder::class,
        CategorySeeder::class,
        MaterialSeeder::class,
        CreationSeeder::class,
    ]);
}
```

**Step 6: Run seeders**

```bash
make fresh
```
Expected: All data imported, verify with tinker:
```bash
docker compose exec app php artisan tinker
> Creation::count() // should be 16
> GalleryImage::count() // should be ~90+
> Category::count() // should be 4
```

**Step 7: Copy static assets**

```bash
# Copy images from old site to Laravel storage
cp -r old/public_html/images/* src/storage/app/public/creations/
# Copy downloads
cp -r old/public_html/downloads/* src/storage/app/public/downloads/
# Copy resources (logo, fonts, etc.)
cp -r old/public_html/res/* src/storage/app/public/res/
# Create storage symlink
docker compose exec app php artisan storage:link
```

**Step 8: Commit**

```bash
git add -A && git commit -m "feat: add seeders with data migration from old site"
```

---

## Task 5: Internationalization (i18n) Setup

**Files:**
- Create: `src/app/Http/Middleware/SetLocale.php`
- Create: `src/lang/ro.json`
- Create: `src/lang/en.json`
- Modify: `src/bootstrap/app.php` (register middleware)
- Modify: `src/routes/web.php`

**Step 1: Create SetLocale middleware**

```php
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1);

        if (in_array($locale, ['ro', 'en'])) {
            app()->setLocale($locale);
        } else {
            return redirect('/' . app()->getLocale() . $request->getRequestUri());
        }

        return $next($request);
    }
}
```

**Step 2: Create lang files**

`lang/ro.json`:
```json
{
    "Home": "Acasă",
    "Creations": "Creații",
    "About": "Despre",
    "Contact": "Contact",
    "Login": "Autentificare",
    "Register": "Înregistrare",
    "Logout": "Deconectare",
    "Search": "Caută",
    "All categories": "Toate categoriile",
    "Related creations": "Creații similare",
    "Send message": "Trimite mesaj",
    "Name": "Nume",
    "Email": "Email",
    "Message": "Mesaj",
    "Subject": "Subiect",
    "Size": "Dimensiune",
    "Material": "Material",
    "Category": "Categorie",
    "Price": "Preț",
    "Download": "Descarcă",
    "Previous": "Anterior",
    "Next": "Următor"
}
```

`lang/en.json`:
```json
{
    "Home": "Home",
    "Creations": "Creations",
    "About": "About",
    "Contact": "Contact",
    "Login": "Login",
    "Register": "Register",
    "Logout": "Logout",
    "Search": "Search",
    "All categories": "All categories",
    "Related creations": "Related creations",
    "Send message": "Send message",
    "Name": "Name",
    "Email": "Email",
    "Message": "Message",
    "Subject": "Subject",
    "Size": "Size",
    "Material": "Material",
    "Category": "Category",
    "Price": "Price",
    "Download": "Download",
    "Previous": "Previous",
    "Next": "Next"
}
```

**Step 3: Add locale helper for route generation**

Create `src/app/Helpers/locale.php` or add to a service provider:
```php
// Helper to generate localized URLs
function locale_route(string $name, array $params = []): string
{
    return route($name, array_merge(['locale' => app()->getLocale()], $params));
}
```

**Step 4: Set up routes with locale prefix**

In `routes/web.php`:
```php
// Redirect root to default locale
Route::get('/', fn () => redirect('/' . config('app.locale')));

// All localized routes
Route::prefix('{locale}')
    ->where(['locale' => 'ro|en'])
    ->middleware('set.locale')
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/creations', [CreationController::class, 'index'])->name('creations.index');
        Route::get('/creations/{slug}', [CreationController::class, 'show'])->name('creations.show');
        Route::get('/about', [PageController::class, 'about'])->name('about');
        Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
        Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
    });
```

**Step 5: Register middleware**

In `src/bootstrap/app.php`, register the `set.locale` middleware alias.

**Step 6: Test locale switching**

Visit `http://localhost:8080/ro` and `http://localhost:8080/en` — both should resolve.
Visit `http://localhost:8080/` — should redirect to `/ro`.

**Step 7: Commit**

```bash
git add -A && git commit -m "feat: add i18n with locale prefix routing and translation files"
```

---

## Task 6: Blade Layouts & Public Pages

**Files:**
- Create: `src/resources/views/layouts/app.blade.php`
- Create: `src/resources/views/layouts/admin.blade.php`
- Create: `src/resources/views/components/navbar.blade.php`
- Create: `src/resources/views/components/footer.blade.php`
- Create: `src/resources/views/components/creation-card.blade.php`
- Create: `src/resources/views/home.blade.php`
- Create: `src/resources/views/creations/index.blade.php`
- Create: `src/resources/views/creations/show.blade.php`
- Create: `src/resources/views/about.blade.php`
- Create: `src/resources/views/contact.blade.php`
- Create: `src/app/Http/Controllers/HomeController.php`
- Create: `src/app/Http/Controllers/CreationController.php`
- Create: `src/app/Http/Controllers/PageController.php`
- Create: `src/app/Http/Controllers/ContactController.php`

**Step 1: Create main layout `layouts/app.blade.php`**

Based on old `title.php` + `header.php` + `footer.php`:
- HTML5 doctype, meta tags, Tailwind via `@vite`
- Navbar component with logo + nav links (Home, Creations, About, Contact)
- Language switcher (RO/EN toggle)
- Footer
- `@yield('content')` section

Preserve old site's color scheme. Reference `old/public_html/includes/title.php` for meta tags and `old/public_html/includes/header.php` for navbar structure.

**Step 2: Create navbar component**

Based on old `header.php` — Bootstrap navbar converted to Tailwind:
- Logo (4est)
- Navigation links using `{{ __('Home') }}` for translations
- Language switcher: links to same page in other locale
- Mobile responsive hamburger menu

**Step 3: Create creation-card component**

Based on old `creation_card.php`:
```blade
@props(['creation'])
<a href="{{ locale_route('creations.show', ['slug' => $creation->translation->slug]) }}">
    <div class="...">
        <img src="{{ asset('storage/creations/' . str_pad($creation->id, 8, '0', STR_PAD_LEFT) . '/' . $creation->coverImage?->filename) }}" />
        <h3>{{ $creation->translation->name }}</h3>
        <span>{{ $creation->category->name }}</span>
    </div>
</a>
```

**Step 4: Create HomeController**

```php
public function index(string $locale)
{
    $featured = Creation::with(['translation', 'coverImage', 'category'])
        ->latest()
        ->take(4)
        ->get();

    return view('home', compact('featured'));
}
```

**Step 5: Create home.blade.php**

Based on old `home.php`:
- Hero section / carousel (images from `res/`)
- Featured creations grid (4 latest)
- Brief about section
- Etsy promo section

**Step 6: Create CreationController**

```php
public function index(string $locale, Request $request)
{
    $query = Creation::with(['translation', 'coverImage', 'category']);

    if ($request->has('category')) {
        $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
    }

    $creations = $query->latest()->paginate(12);
    $categories = Category::all();

    return view('creations.index', compact('creations', 'categories'));
}

public function show(string $locale, string $slug)
{
    $creation = Creation::whereHas('translations', fn ($q) =>
        $q->where('slug', $slug)->where('locale', $locale)
    )->with(['translations', 'galleryImages', 'category', 'material'])->firstOrFail();

    $related = Creation::where('category_id', $creation->category_id)
        ->where('id', '!=', $creation->id)
        ->with(['translation', 'coverImage'])
        ->take(4)
        ->get();

    return view('creations.show', compact('creation', 'related'));
}
```

**Step 7: Create creations/index.blade.php**

Based on old `creations.php`:
- Category filter buttons
- Grid of creation cards (4 columns desktop, 2 mobile)
- Pagination

**Step 8: Create creations/show.blade.php**

Based on old `creation.php`:
- Image gallery (main image + thumbnails, click to switch)
- Creation name, description (HTML), size, material, category, price
- Download button (if available)
- Previous/Next navigation
- Related creations section

**Step 9: Create about.blade.php**

Based on old `about.php`:
- Biography text
- Images

**Step 10: Create ContactController and contact.blade.php**

Based on old `contact.php` + `send_email.php`:
- Form: name, email, subject, message
- reCAPTCHA v2 validation
- Laravel Mail to send email
- Flash success/error messages

**Step 11: Test all public pages**

Visit:
- `http://localhost:8080/ro` — home page
- `http://localhost:8080/ro/creations` — grid
- `http://localhost:8080/ro/creations/{slug}` — detail
- `http://localhost:8080/en/creations` — same in English
- `http://localhost:8080/ro/about`
- `http://localhost:8080/ro/contact`

**Step 12: Commit**

```bash
git add -A && git commit -m "feat: add public pages with Blade layouts, controllers, and i18n"
```

---

## Task 7: Custom Authentication

**Files:**
- Create: `src/app/Http/Controllers/Auth/LoginController.php`
- Create: `src/app/Http/Controllers/Auth/RegisterController.php`
- Create: `src/app/Http/Controllers/Auth/ForgotPasswordController.php`
- Create: `src/app/Http/Middleware/AdminOnly.php`
- Create: `src/resources/views/auth/login.blade.php`
- Create: `src/resources/views/auth/register.blade.php`
- Create: `src/resources/views/auth/forgot-password.blade.php`
- Modify: `src/routes/web.php`

**Step 1: Write tests for auth**

Create `src/tests/Feature/Auth/LoginTest.php`:
```php
public function test_login_page_renders(): void
{
    $response = $this->get('/login');
    $response->assertStatus(200);
}

public function test_user_can_login_with_valid_credentials(): void
{
    $user = User::factory()->create(['password' => 'password123']);
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);
    $response->assertRedirect('/ro');
    $this->assertAuthenticated();
}

public function test_user_cannot_login_with_invalid_credentials(): void
{
    $user = User::factory()->create();
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong',
    ]);
    $this->assertGuest();
}
```

**Step 2: Run tests — expect FAIL**

**Step 3: Create LoginController**

```php
class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/' . app()->getLocale());
        }

        return back()->withErrors(['email' => __('Invalid credentials.')]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
```

**Step 4: Create RegisterController**

Similar pattern: show form, validate (name, email, password, password_confirmation), create user with `role=customer`, login, redirect.

**Step 5: Create ForgotPasswordController**

Use Laravel's built-in password reset functionality (`Password::sendResetLink`).

**Step 6: Create Blade views for auth**

Simple Tailwind forms matching the site's design. Login: email + password + remember me. Register: name + email + password + confirm.

**Step 7: Create AdminOnly middleware**

```php
class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->isAdmin()) {
            abort(403);
        }
        return $next($request);
    }
}
```

**Step 8: Add auth routes**

```php
// Auth routes (no locale prefix)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
```

**Step 9: Run tests — expect PASS**

**Step 10: Commit**

```bash
git add -A && git commit -m "feat: add custom authentication with login, register, and admin middleware"
```

---

## Task 8: Admin Panel

**Files:**
- Create: `src/app/Http/Controllers/Admin/DashboardController.php`
- Create: `src/app/Http/Controllers/Admin/AdminCreationController.php`
- Create: `src/app/Http/Controllers/Admin/AdminUserController.php`
- Create: `src/resources/views/layouts/admin.blade.php`
- Create: `src/resources/views/admin/dashboard.blade.php`
- Create: `src/resources/views/admin/creations/index.blade.php`
- Create: `src/resources/views/admin/creations/create.blade.php`
- Create: `src/resources/views/admin/creations/edit.blade.php`
- Create: `src/resources/views/admin/users/index.blade.php`
- Create: `src/resources/views/admin/users/create.blade.php`
- Create: `src/resources/views/admin/users/edit.blade.php`
- Modify: `src/routes/web.php`

**Step 1: Add admin routes**

```php
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('creations', AdminCreationController::class);
        Route::resource('users', AdminUserController::class);
    });
```

**Step 2: Create admin layout**

Based on old `me_admin/includes/header.php` + `menu.php`:
- Sidebar navigation: Dashboard, Creations, Users, Logout
- Main content area
- Different layout from public site

**Step 3: Create DashboardController**

Based on old `me_admin/home.php`:
```php
public function index()
{
    $recentVisits = Statistic::latest()->take(100)->get();
    $totalVisits = Statistic::count();
    $totalCreations = Creation::count();
    $totalUsers = User::count();

    return view('admin.dashboard', compact('recentVisits', 'totalVisits', 'totalCreations', 'totalUsers'));
}
```

**Step 4: Create AdminCreationController**

Full CRUD based on old `me_admin/creations.php` + `sql_operations.php`:

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'size' => 'required|string|max:50',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'material_id' => 'required|exists:materials,id',
        'download' => 'nullable|file',
        'translations' => 'required|array',
        'translations.*.name' => 'required|string|max:255',
        'translations.*.description' => 'required|string',
        'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
    ]);

    $creation = Creation::create($validated);

    // Save translations for each locale
    foreach ($request->translations as $locale => $data) {
        $creation->translations()->create([
            'locale' => $locale,
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'],
        ]);
    }

    // Handle image uploads via ImageService
    // Handle download file upload

    return redirect()->route('admin.creations.index')->with('success', 'Creation added.');
}
```

**Step 5: Create admin creation views**

- `index.blade.php`: Table with all creations (ID, name, category, actions)
- `create.blade.php`: Form with tabs for RO/EN translations, file uploads, image gallery uploads with ranking
- `edit.blade.php`: Same form pre-filled, existing images shown with delete option

**Step 6: Create AdminUserController**

CRUD for user management. Admin can create/edit/delete users and assign roles.

**Step 7: Create admin user views**

- `index.blade.php`: Table with all users
- `create.blade.php`: Form (name, email, password, role)
- `edit.blade.php`: Form pre-filled

**Step 8: Test admin panel**

1. Login as admin
2. Visit `/admin` — dashboard with stats
3. Create a new creation with images
4. Edit/delete creation
5. Manage users

**Step 9: Commit**

```bash
git add -A && git commit -m "feat: add admin panel with creation and user CRUD"
```

---

## Task 9: ImageService (Upload, Resize, Watermark)

**Files:**
- Create: `src/app/Services/ImageService.php`
- Create: `src/tests/Feature/Services/ImageServiceTest.php`
- Copy: `old/public_html/res/awery.smallcaps.ttf` → `src/storage/app/fonts/awery.smallcaps.ttf`

**Step 1: Write tests**

```php
public function test_image_is_resized_to_hd(): void
{
    Storage::fake('public');
    $image = UploadedFile::fake()->image('test.jpg', 3000, 2000);
    $service = new ImageService();
    $path = $service->processAndStore($image, 1);

    // Verify image exists
    Storage::disk('public')->assertExists($path);

    // Verify dimensions (1920x1080)
    $stored = Storage::disk('public')->path($path);
    [$width, $height] = getimagesize($stored);
    $this->assertEquals(1920, $width);
    $this->assertEquals(1080, $height);
}
```

**Step 2: Run test — expect FAIL**

**Step 3: Create ImageService**

Port logic from old `file_operations.php`:
```php
class ImageService
{
    private int $targetWidth = 1920;
    private int $targetHeight = 1080;

    public function processAndStore(UploadedFile $file, int $creationId): string
    {
        $directory = 'creations/' . str_pad($creationId, 8, '0', STR_PAD_LEFT);
        $filename = $file->getClientOriginalName();
        $path = $directory . '/' . $filename;

        // Store original temporarily
        $file->storeAs($directory, $filename, 'public');

        // Process: resize + crop + watermark
        $fullPath = Storage::disk('public')->path($path);
        $this->resizeAndWatermark($fullPath);

        return $path;
    }

    private function resizeAndWatermark(string $path): void
    {
        $image = imagecreatefromjpeg($path);
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        // Calculate resize dimensions (same logic as old file_operations.php)
        $originalAspect = $originalWidth / $originalHeight;
        $targetAspect = $this->targetWidth / $this->targetHeight;

        if ($originalAspect >= $targetAspect) {
            $newHeight = $this->targetHeight;
            $newWidth = (int)($originalWidth / ($originalHeight / $this->targetHeight));
        } else {
            $newWidth = $this->targetWidth;
            $newHeight = (int)($originalHeight / ($originalWidth / $this->targetWidth));
        }

        // Resize
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        // Crop to target
        $cropped = imagecreatetruecolor($this->targetWidth, $this->targetHeight);
        $cropX = (int)(($newWidth - $this->targetWidth) / 2);
        $cropY = (int)(($newHeight - $this->targetHeight) / 2);
        imagecopy($cropped, $resized, 0, 0, $cropX, $cropY, $this->targetWidth, $this->targetHeight);

        // Watermark
        $fontFile = storage_path('app/fonts/awery.smallcaps.ttf');
        if (file_exists($fontFile)) {
            $textColor = imagecolorallocatealpha($cropped, 255, 255, 255, 99);
            imagettftext($cropped, 170, 0, 150, 270, $textColor, $fontFile, '4est');
            imagettftext($cropped, 170, 0, 1350, 950, $textColor, $fontFile, '4est');
        }

        // Save at 80% quality
        imagejpeg($cropped, $path, 80);

        imagedestroy($image);
        imagedestroy($resized);
        imagedestroy($cropped);
    }

    public function delete(int $creationId): void
    {
        $directory = 'creations/' . str_pad($creationId, 8, '0', STR_PAD_LEFT);
        Storage::disk('public')->deleteDirectory($directory);
    }
}
```

**Step 4: Run test — expect PASS**

**Step 5: Integrate ImageService into AdminCreationController**

Inject `ImageService` and use it in `store()` and `update()` methods.

**Step 6: Commit**

```bash
git add -A && git commit -m "feat: add ImageService with resize, crop, and watermark"
```

---

## Task 10: Statistics Tracking Middleware

**Files:**
- Create: `src/app/Http/Middleware/TrackVisit.php`
- Create: `src/app/Services/GeoLocationService.php`
- Modify: `src/bootstrap/app.php`

**Step 1: Create GeoLocationService**

Based on old `statistics.php`:
```php
class GeoLocationService
{
    public function lookup(string $ip): array
    {
        try {
            $response = Http::timeout(2)->get("http://www.geoplugin.net/json.gp?ip={$ip}");
            $data = $response->json();

            return [
                'city' => $data['geoplugin_city'] ?? '',
                'state' => $data['geoplugin_region'] ?? '',
                'country' => $data['geoplugin_countryName'] ?? '',
                'continent' => $data['geoplugin_continentName'] ?? '',
            ];
        } catch (\Exception $e) {
            return ['city' => '', 'state' => '', 'country' => '', 'continent' => ''];
        }
    }
}
```

**Step 2: Create TrackVisit middleware**

```php
class TrackVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Track after response (non-blocking)
        $ip = $request->ip();
        $geo = app(GeoLocationService::class)->lookup($ip);

        Statistic::create([
            'ip' => $ip,
            'city' => $geo['city'],
            'state' => $geo['state'],
            'country' => $geo['country'],
            'continent' => $geo['continent'],
            'page' => $request->path(),
        ]);

        return $response;
    }
}
```

**Step 3: Register middleware on public routes**

Apply `TrackVisit` middleware to the localized route group.

**Step 4: Test**

Visit any page, then check admin dashboard — the visit should be logged.

**Step 5: Commit**

```bash
git add -A && git commit -m "feat: add visitor tracking middleware with geolocation"
```

---

## Task 11: Contact Form with Email

**Files:**
- Create: `src/app/Mail/ContactFormMail.php`
- Create: `src/resources/views/emails/contact.blade.php`
- Modify: `src/app/Http/Controllers/ContactController.php`

**Step 1: Create Mailable**

```php
class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $subject,
        public string $body,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact');
    }
}
```

**Step 2: Create email template**

Simple Blade template showing sender info and message.

**Step 3: Update ContactController**

```php
public function send(string $locale, Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|max:5000',
        'g-recaptcha-response' => 'required',
    ]);

    // Verify reCAPTCHA
    // ...

    Mail::to(config('mail.from.address'))->send(new ContactFormMail(
        senderName: $validated['name'],
        senderEmail: $validated['email'],
        subject: $validated['subject'],
        body: $validated['message'],
    ));

    return back()->with('success', __('Message sent successfully.'));
}
```

**Step 4: Test with Mailpit**

Send a message via the contact form, then check `http://localhost:8025` — the email should appear.

**Step 5: Commit**

```bash
git add -A && git commit -m "feat: add contact form with email via Laravel Mail"
```

---

## Task 12: IP Blocking

**Files:**
- Create: `src/database/migrations/xxxx_create_blocked_ips_table.php`
- Create: `src/app/Models/BlockedIp.php`
- Create: `src/app/Http/Middleware/BlockIp.php`

**Step 1: Create migration**

```php
Schema::create('blocked_ips', function (Blueprint $table) {
    $table->id();
    $table->string('ip', 45);
    $table->string('reason', 255)->nullable();
    $table->timestamps();
});
```

**Step 2: Create model and middleware**

```php
class BlockIp
{
    public function handle(Request $request, Closure $next): Response
    {
        if (BlockedIp::where('ip', $request->ip())->exists()) {
            abort(403);
        }
        return $next($request);
    }
}
```

**Step 3: Add admin UI for managing blocked IPs** (optional, can be done via admin dashboard)

**Step 4: Commit**

```bash
git add -A && git commit -m "feat: add IP blocking middleware with database-driven blocklist"
```

---

## Task 13: Final Polish & Testing

**Step 1: Write feature tests for all public routes**

Test each page returns 200, correct view, correct data.

**Step 2: Write feature tests for admin CRUD**

Test authenticated admin can create/edit/delete creations and users.

**Step 3: Test i18n**

Verify `/ro/creations/{slug}` shows Romanian content and `/en/creations/{slug}` shows English.

**Step 4: Cross-check with old site**

Compare `http://localhost:8080/ro` with `https://4est.info` — verify all creations, images, and content match.

**Step 5: Run full test suite**

```bash
make test
```
Expected: All tests pass.

**Step 6: Final commit**

```bash
git add -A && git commit -m "feat: add feature tests and final polish"
```

---

## Execution Summary

| Task | Description | Dependencies |
|------|-------------|--------------|
| 1 | Project scaffolding + Docker Compose | None |
| 2 | Database migrations | Task 1 |
| 3 | Eloquent models + factories | Task 2 |
| 4 | Seeders (import old data) | Task 3 |
| 5 | i18n setup | Task 1 |
| 6 | Blade layouts + public pages | Tasks 3, 4, 5 |
| 7 | Custom authentication | Task 6 |
| 8 | Admin panel | Task 7 |
| 9 | ImageService | Task 3 |
| 10 | Statistics tracking | Task 3 |
| 11 | Contact form with email | Task 6 |
| 12 | IP blocking | Task 2 |
| 13 | Final polish + testing | All |

**Parallelizable**: Tasks 5, 9, 10, 12 can be developed independently after Task 3.
