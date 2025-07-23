# PROJECT.md - Technische Projektdokumentation

## 🎯 Projektübersicht

**KI-Coding.de** ist eine professionelle Laravel-basierte Wiki-Plattform für die KI-Programmierung-Community. Das System kombiniert moderne Web-Technologien mit robusten Sicherheitsfeatures und einem umfassenden Content-Management-System.

### Technische Basis
- **Framework**: Laravel 12.21.0 (neueste Version)
- **PHP**: 8.4+
- **Datenbank**: MySQL/PostgreSQL
- **Frontend**: Tailwind CSS + Vite + Alpine.js
- **Containerisierung**: Docker
- **Suchengine**: Meilisearch
- **Cache/Sessions**: Redis
- **API**: RESTful API v1 mit Laravel Sanctum
- **Notifications**: Real-time Push-System

---

## 🏗️ Architektur-Übersicht

### MVC-Struktur

#### Models
- **User**: Erweiterte Benutzerprofile mit Spatie Permissions Integration
- **Article**: Wiki-Artikel mit Revision-System, Soft Deletes, Like/Bookmark-Funktionalität
- **Category**: Hierarchische Kategorien für Content-Organisation
- **Comment**: Moderiertes Kommentar-System mit Like-Funktionalität
- **Tag**: Flexible Content-Kategorisierung
- **Revision**: Vollständige Versionshistorie für Artikel
- **Notification**: Laravel Notification System für Real-time Benachrichtigungen
- **PersonalAccessToken**: API-Token Management mit Laravel Sanctum

#### Controllers
**Wiki-Bereich:**
- `ArticleController`: CRUD-Operationen, Publishing, Featured Content
- `CategoryController`: Kategorie-Management mit Hierarchie
- `CommentController`: Kommentar-Moderation und Like-System
- `TagController`: Tag-Verwaltung mit Popularitäts-Tracking
- `SearchController`: Volltext-Suche mit Auto-Complete

**Admin-Bereich:**
- `UserManagementController`: Benutzer-Verwaltung und Rollen-Zuweisung
- `RoleController`: Spatie Permission Management
- `ModerationController`: Content- und Community-Moderation

**Core-Bereich:**
- `ProfileController`: Erweiterte Profil-Features
- `DashboardController`: Rollen-basierte Dashboards
- `ContactController`: reCAPTCHA-geschütztes Kontaktformular
- `NotificationController`: Push-Notifications Management
- `ApiTokenController`: API-Token Verwaltung

**API-Bereich:**
- `Api\V1\ArticleController`: RESTful API für Artikel-Management
- `Api\UserController`: Authentifizierte User-Informationen

#### Views
**Layout-System:**
- `layouts/app.blade.php`: Haupt-Layout mit Navigation
- Responsive Design mit Rollen-basierter Navigation
- Component-basierte UI-Elemente

**Wiki-Views:**
- Artikel-Management (CRUD, Revision-Historie)
- Kategorie-Navigation (hierarchisch)
- Such-Interface mit Filtern
- Moderation-Dashboard

---

## 🔐 Authentifizierung & Autorisierung

### Rollen-Hierarchie (Spatie Permissions)

1. **User** (Basis-Rolle)
   - Artikel lesen und kommentieren
   - Eigenes Profil bearbeiten
   - Kommentare erstellen/bearbeiten

2. **Contributor** (Content Creator)
   - Artikel erstellen, bearbeiten, löschen (eigene)
   - Tags erstellen und verwalten
   - Draft-Artikel speichern
   - API-Zugang für Artikel-Management

3. **Editor** (Content Manager)
   - Alle Artikel bearbeiten und publizieren
   - Featured Articles verwalten
   - Kategorien administrieren
   - Comment-Moderation

4. **Moderator** (Community Manager)
   - User-Management (Bans, Warnings)
   - Vollständige Content-Moderation
   - Report-System verwalten
   - Admin-Panel Zugang

5. **Admin** (System Administrator)
   - Alle System-Permissions
   - Rollen-Management
   - User-Löschung
   - System-Konfiguration

### Permission-System
**Ausschließlich rollenbasierte Permissions** - keine direkten User-Permissions für maximale Sicherheit.
Über 80 granulare Permissions für präzise Zugriffskontrolle:
- `view articles`, `edit articles`, `delete articles`, `create articles`
- `publish articles`, `feature articles`
- `manage categories`, `manage tags`
- `moderate comments`, `ban users`, `unban users`
- `manage roles`, `assign roles`, `access admin panel`
- `use api` - API-Zugang für externe Integrationen
- `view users`, `delete users` - Admin-Funktionen

### Sicherheits-Middleware

**WikiSecurity Middleware:**
- Security Headers (CSP, X-Frame-Options, XSS-Protection)
- XSS-Pattern Detection und Logging
- Malicious Input Blocking
- Content Security Policy für Wiki-Bereiche

**Weitere Middleware:**
- `BanCheck`: Überprüft aktive User-Bans
- `RateLimitWiki`: Wiki-spezifische Rate Limits
- `SecurityHeaders`: Globale Security Headers
- `auth:sanctum`: API-Authentifizierung mit Laravel Sanctum

---

## 🗄️ Datenbank-Schema

### Haupt-Tabellen

**users**
```sql
- id, name, email, username, email_verified_at
- bio (TEXT), avatar, website, location
- job_title, company, birthday
- privacy_settings (JSON)
- reputation, articles_count, comments_count
- banned_until, last_activity_at
- social_media (JSON)
- invitation_token, invited_by
```

**articles**
```sql
- id, title, slug, content, excerpt
- user_id, category_id
- status (draft/published), featured
- published_at, reading_time
- views_count, likes_count, comments_count
- deletion_requested_at, deletion_requested_by
- deleted_at (Soft Deletes)
- meta_title, meta_description (SEO)
```

**categories**
```sql
- id, name, slug, description
- parent_id (hierarchisch)
- articles_count, deleted_at
```

**comments**
```sql
- id, content, user_id, article_id
- status (pending/approved/rejected)
- likes_count, reported_at
- deleted_at
```

**article_revisions**
```sql
- id, article_id, user_id
- title, content, excerpt
- created_at (Versionshistorie)
```

### Permission-Tabellen (Spatie)
- `roles`, `permissions`
- `model_has_roles`, `model_has_permissions`
- `role_has_permissions`

### Notification-System
- `notifications`: Laravel Notification System
  - `id` (UUID), `type`, `notifiable_type`, `notifiable_id`
  - `data` (JSON), `read_at`, `created_at`, `updated_at`

### API-System
- `personal_access_tokens`: Laravel Sanctum Tokens
  - `id`, `tokenable_type`, `tokenable_id`, `name`
  - `token`, `abilities` (JSON), `last_used_at`
  - `expires_at`, `created_at`, `updated_at`

### Bookmark-System
- `article_bookmarks`: Benutzer-Bookmarks für Artikel
  - `id`, `user_id`, `article_id`, `created_at`

### Moderation-Tabellen
- `article_reports`, `user_reports`, `comment_reports`
- `user_invitations`

---

## 🎨 Frontend-Architektur

### Tailwind CSS Konfiguration

**Logo-basierte Farbpalette:**
- **Primary**: #4CAF50 (Grün) - 50-950 Abstufungen
- **Secondary**: #E67E22 (Orange) - 50-950 Abstufungen
- **Accent**: #3498DB (Blau) - 50-950 Abstufungen
- **Extended**: Purple, Teal, Pink Familien

**Design-System:**
- Moderne Gradienten (sunset, ocean, forest, dawn)
- Glassmorphism-Effekte mit backdrop-blur
- Typography: Inter (sans), Poppins (display)
- Responsive Design (Mobile-First)

### Build-System
- **Vite**: Moderne Asset-Bundling
- **PostCSS**: Tailwind-Processing
- **Auto-Purge**: Production CSS-Optimierung

### UI-Components
- Hero-Header mit konfigurierbaren Gradienten
- Modal-System für User-Interaktionen
- Form-Components mit Validation-Display
- Responsive Navigation mit Dropdown-Menüs
- **Notification Bell**: Alpine.js-basierte Real-time Notifications
- **API Token Management**: Dashboard-Integration für Token-Verwaltung

---

## 📝 Wiki-Features

### Content-Management
- **Markdown-Editor**: WYSIWYG mit Live-Preview
- **Revision-System**: Vollständige Versionshistorie
- **Deletion-Workflow**: Request → Review → Approve
- **Featured Articles**: Redaktionelle Empfehlungen
- **Draft-System**: Unveröffentlichte Artikel speichern

### Search-Integration
- **Meilisearch**: Externe Suchengine für Performance
- **Auto-Complete**: Echtzeit-Suchvorschläge
- **Advanced Search**: Filter nach Kategorien, Tags, Autoren
- **Search Indexing**: Background-Indexierung via Queue

### Community-Features
- **Comment-System**: Nested Comments mit Moderation
- **Like-System**: Artikel und Kommentar-Likes mit Real-time Notifications
- **Bookmark-System**: Artikel zur späteren Lektüre markieren
- **Push-Notifications**: Real-time Benachrichtigungen für Artikel-Interaktionen
- **Reputation-System**: Aktivitäts-basierte Punktevergabe
- **Tag-System**: Flexible Content-Kategorisierung
- **Report-System**: Community-basierte Moderation

---

## 👤 Profil-Management

### Erweiterte Profile
- **Basis-Informationen**: Name, Bio, Avatar
- **Social Media**: LinkedIn, Twitter, GitHub, Website
- **Privacy Settings**: Granulare Sichtbarkeits-Kontrolle
- **Activity Tracking**: Artikel- und Kommentar-Historie

### Privacy-System
```php
privacy_settings: {
    show_email: boolean,
    show_location: boolean,
    show_birthday: boolean,
    show_job_title: boolean,
    show_company: boolean,
    show_social_media: boolean,
    show_activity: boolean
}
```

### Avatar-System
- **Upload-Funktionalität**: Validierte Datei-Uploads
- **Storage-Integration**: Laravel Storage mit Symlinks
- **Fallback-System**: UI-Avatars für Benutzer ohne Bild

---

## 🛡️ Sicherheitsmaßnahmen

### XSS-Schutz
- **WikiSecurity Middleware**: Pattern-basierte XSS-Detection
- **Input Sanitization**: HTML-Escaping mit Laravel `e()` Funktion
- **Content Security Policy**: Restriktive CSP-Headers
- **Output Filtering**: Sichere Template-Ausgabe

### CSRF-Protection
- Laravel Standard CSRF-Token
- Custom CSRF-Validation für AJAX-Requests
- Form-basierte Protection

### Input-Validation
- **Request Classes**: Umfassende Validierung für alle Inputs
- **Database Validation**: Foreign Key Constraints
- **File Upload Validation**: Mime-Type und Größen-Checks

### Rate Limiting
- **API Rate Limits**: 60 Requests/Minute für authentifizierte Benutzer
- **Wiki Rate Limits**: Content-Creation Throttling
- **Search Rate Limits**: Suchengine-Schutz

### Cookie Security
- **HttpOnly Flags**: Verhindert JavaScript-Zugriff auf sensible Cookies
- **Secure Flags**: HTTPS-only Übertragung
- **SameSite Attributes**: CSRF-Schutz auf Cookie-Ebene

### RFC 9116 Compliance
- **Security.txt**: Standardisierte Security-Kontaktinformationen
- **Responsible Disclosure**: Koordinierte Vulnerability-Meldungen
- **Security Acknowledgments**: Hall of Fame für Security Researcher
- **Security Policy**: Umfassende Guidelines für Sicherheitsmeldungen

---

## ⚡ Performance-Optimierungen

### Datenbank-Optimierung
- **Eager Loading**: N+1 Problem-Vermeidung
- **Database Indexing**: Performance-optimierte Indizes
- **Query Optimization**: Efficient Eloquent Queries
- **Pagination**: Speicher-effiziente Listen-Anzeige

### Caching-Strategien
- **Redis Integration**: Session- und Cache-Storage
- **Query Caching**: Häufige Abfragen cachen
- **View Caching**: Template-Caching für statische Inhalte

### Queue-System
- **Laravel Horizon**: Queue-Monitoring und Management
- **Background Jobs**: Email-Versand, Search-Indexing
- **Failed Job Handling**: Retry-Mechanismen

---

## 🤖 RESTful API v1

### API-Architektur
- **Authentication**: Laravel Sanctum Bearer Token
- **Rate Limiting**: 60 Requests/Minute für authentifizierte User
- **Response Format**: Konsistente JSON-Struktur
- **Error Handling**: Standardisierte Fehler-Responses
- **Validation**: Request Classes für Input-Validation

### Authentifizierung
```php
// Header Format
Authorization: Bearer {API_TOKEN}
Content-Type: application/json
Accept: application/json
```

### Verfügbare Endpunkte

**User API:**
- `GET /api/user` - Authentifizierte User-Informationen

**Articles API:**
- `POST /api/v1/articles` - Artikel erstellen
- `GET /api/v1/articles` - Artikel auflisten (geplant)
- `PUT /api/v1/articles/{id}` - Artikel bearbeiten (geplant)
- `DELETE /api/v1/articles/{id}` - Artikel löschen (geplant)

### Response-Format
```json
{
  "success": true,
  "message": "Artikel wurde erfolgreich erstellt.",
  "data": {
    // Response-Daten
  },
  "timestamp": "2025-07-23T14:30:00.000000Z"
}
```

### Permission-Integration
- `use api` - Grundvoraussetzung für API-Zugang
- Zusätzliche Action-spezifische Permissions erforderlich
- Rollenbasierte Zugriffskontrolle über Spatie Permissions

---

## 🔧 Entwicklungsumgebung

### Docker-Setup
```yaml
Services:
- PHP 8.4+ (www.ki-coding.de-php)
- Apache (www.ki-coding.de-http)
- MySQL/PostgreSQL (www.ki-coding.de-db)
- Redis (www.ki-coding.de-redis)
- Meilisearch (www.ki-coding.de-meilisearch)
- Traefik (Load Balancer)
```

### Wichtige Packages
```json
Core Dependencies:
- laravel/breeze: Authentication Scaffolding
- laravel/sanctum: API Token Authentication
- spatie/laravel-permission: Rollen-System
- laravel/scout: Search Abstraction
- meilisearch/meilisearch-php: Search Engine
- league/commonmark: Markdown Processing
- laravel/horizon: Queue Management

Security:
- anhskohbo/no-captcha: reCAPTCHA v2 Integration

Frontend:
- @tailwindcss/forms: Form-Styling
- @tailwindcss/typography: Content-Styling
- alpinejs: Reactive Frontend Components

Notifications:
- Laravel Notification System (built-in)
```

### Deployment
- **Git-basierte Deployment**: Automatisch via Git Push
- **Environment-Management**: Docker-optimierte .env
- **Asset-Building**: Production-optimierte Builds
- **Cache-Management**: Automated Cache Clearing

---

## 📊 Admin-Features

### User-Management
- **Bulk-Operationen**: Mehrere Benutzer gleichzeitig verwalten
- **Role-Assignment**: Flexible Rollen-Zuweisung
- **Ban-System**: Temporäre/Permanente Bans
- **Activity-Monitoring**: User-Aktivitäts-Tracking

### Content-Moderation
- **Article-Approval**: Publishing-Workflow
- **Comment-Moderation**: Genehmigungsprozess
- **Report-Management**: Community-Reports bearbeiten
- **Featured Content**: Redaktionelle Empfehlungen

### System-Administration
- **Role-Management**: Spatie Permission Interface
- **Permission-Assignment**: Granulare Rechte-Verwaltung (ONLY role-based)
- **API-Token Management**: Sanctum Token Administration
- **System-Monitoring**: Performance und Health Checks
- **Invitation-System**: Kontrollierte Registrierung
- **Notification Management**: Push-Notification Übersicht

---

## 🔄 API-Integration

### Interne APIs
- **Search API**: Meilisearch Integration
- **Comment API**: AJAX-basierte Kommentar-Funktionen
- **User API**: Profile und Role-Management
- **Content API**: Article und Category Management
- **Notification API**: Real-time Push-Notifications
- **RESTful API v1**: Laravel Sanctum-basierte Artikel-API

### Externe Integrationen
- **reCAPTCHA v2**: Bot-Schutz für Formulare
- **Email Services**: SMTP/Mailtrap Integration
- **Search Engine**: Meilisearch für Performance

---

## 📈 Monitoring & Analytics

### Performance-Monitoring
- **Laravel Telescope**: Development Debugging
- **Laravel Horizon**: Queue-Monitoring
- **Database Query Logging**: Performance-Analyse

### Security-Monitoring
- **XSS-Attack Logging**: WikiSecurity Middleware
- **Failed Login Tracking**: Brute-Force Detection
- **Suspicious Activity Logging**: Security-Events
- **API Rate Limit Monitoring**: Sanctum Request Tracking

### Content-Analytics
- **Article Views**: Popularity-Tracking
- **User Engagement**: Comment und Like-Statistiken
- **Search Analytics**: Popular Search Terms
- **Notification Analytics**: Push-Notification Engagement
- **API Usage Statistics**: Endpoint-spezifische Metriken

---

## 🚀 Deployment & DevOps

### Produktions-Environment
- **Docker-basierte Infrastruktur**: Skalierbare Container
- **HTTPS-Enforcement**: SSL/TLS-Verschlüsselung
- **Load Balancing**: Traefik Integration
- **Database Optimization**: Production-tuned MySQL

### Backup-Strategien
- **Database Backups**: Automatisierte Sicherung
- **File Storage Backups**: Avatar und Asset-Backups
- **Code Repository**: Git-basierte Versionierung

### Monitoring
- **Health Checks**: Application Monitoring
- **Performance Metrics**: Response Time Tracking
- **Error Logging**: Comprehensive Error Tracking

---

## 🎯 Zukünftige Erweiterungen

### Geplante Features
- **API Documentation**: Swagger/OpenAPI Integration
- **Extended API**: Vollständige CRUD-API für alle Ressourcen
- **WebSocket Notifications**: Real-time Push via WebSockets
- **Mobile App**: REST API für Native Apps
- **Advanced Analytics**: User Behavior Analytics
- **Internationalization**: Multi-Language Support

### Performance-Verbesserungen
- **CDN Integration**: Asset-Distribution
- **Database Sharding**: Skalierbare DB-Architektur
- **Microservices**: Service-orientierte Architektur

---

## 📝 Entwickler-Hinweise

### Code-Standards
- **PSR-12**: PHP Coding Standards
- **Laravel Best Practices**: Framework-Konventionen
- **Type Hints**: Vollständige Type Annotations
- **DocBlocks**: Umfassende Code-Dokumentation

### Testing-Strategie
- **Unit Tests**: Model und Service Testing
- **Feature Tests**: Controller und Integration Testing
- **Browser Tests**: Laravel Dusk für E2E Testing

### Git-Workflow
- **Feature Branches**: Isolierte Feature-Entwicklung
- **Code Reviews**: Pull Request Workflow
- **Automated Deployment**: CI/CD Pipeline

---

## 📞 Support & Wartung

### Wartungs-Zyklen
- **Security Updates**: Monatliche Security Patches
- **Feature Updates**: Quarterly Feature Releases
- **Dependencies**: Regular Package Updates

### Troubleshooting
- **Log Files**: Comprehensive Error Logging
- **Debug Tools**: Laravel Telescope Integration
- **Performance Profiling**: Built-in Performance Tools

---

**Letzte Aktualisierung**: 23. Juli 2025  
**Version**: 1.1.0 (mit API v1, Notifications, Security.txt)  
**Wartung**: Christin Löhner  
**Entwicklungszeit**: 6+ Monate intensive Entwicklung

### Kürzlich hinzugefügte Features (v1.1.0)
- ✅ **RESTful API v1** mit Laravel Sanctum Authentication
- ✅ **Push-Notification System** für Real-time Benachrichtigungen
- ✅ **Bookmark-System** für Artikel
- ✅ **RFC 9116 Security.txt** Implementation
- ✅ **Security Acknowledgments** Hall of Fame
- ✅ **Cookie Security** mit HttpOnly/Secure/SameSite Flags
- ✅ **Ausschließlich rollenbasierte Permissions** (keine User-Permissions)
- ✅ **humans.txt** mit Technologie-Credits