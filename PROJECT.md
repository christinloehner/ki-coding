# PROJECT.md - Technische Projektdokumentation

## 🎯 Projektübersicht

**KI-Coding.de** ist eine professionelle Laravel-basierte Wiki-Plattform für die KI-Programmierung-Community. Das System kombiniert moderne Web-Technologien mit robusten Sicherheitsfeatures und einem umfassenden Content-Management-System.

### Technische Basis
- **Framework**: Laravel 12.0 (neueste Version)
- **PHP**: 8.2+
- **Datenbank**: MySQL/PostgreSQL
- **Frontend**: Tailwind CSS + Vite
- **Containerisierung**: Docker
- **Suchengine**: Meilisearch

---

## 🏗️ Architektur-Übersicht

### MVC-Struktur

#### Models
- **User**: Erweiterte Benutzerprofile mit Spatie Permissions Integration
- **Article**: Wiki-Artikel mit Revision-System und Soft Deletes
- **Category**: Hierarchische Kategorien für Content-Organisation
- **Comment**: Moderiertes Kommentar-System mit Like-Funktionalität
- **Tag**: Flexible Content-Kategorisierung
- **Revision**: Vollständige Versionshistorie für Artikel

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
Über 80 granulare Permissions für präzise Zugriffskontrolle:
- `view articles`, `edit articles`, `delete articles`
- `publish articles`, `feature articles`
- `manage categories`, `manage tags`
- `moderate comments`, `ban users`
- `manage roles`, `assign roles`

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
- views_count, likes_count
- deletion_requested_at, deletion_requested_by
- deleted_at (Soft Deletes)
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
- **Like-System**: Artikel und Kommentar-Likes
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
- **API Rate Limits**: Schutz vor Brute-Force-Angriffen
- **Wiki Rate Limits**: Content-Creation Throttling
- **Search Rate Limits**: Suchengine-Schutz

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

## 🔧 Entwicklungsumgebung

### Docker-Setup
```yaml
Services:
- PHP 8.2+ (www.ki-coding.de-php)
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
- **Permission-Assignment**: Granulare Rechte-Verwaltung
- **System-Monitoring**: Performance und Health Checks
- **Invitation-System**: Kontrollierte Registrierung

---

## 🔄 API-Integration

### Interne APIs
- **Search API**: Meilisearch Integration
- **Comment API**: AJAX-basierte Kommentar-Funktionen
- **User API**: Profile und Role-Management
- **Content API**: Article und Category Management

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

### Content-Analytics
- **Article Views**: Popularity-Tracking
- **User Engagement**: Comment und Like-Statistiken
- **Search Analytics**: Popular Search Terms

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
- **Mobile App**: REST API für Mobile Apps
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

**Letzte Aktualisierung**: 19. Juli 2025  
**Version**: 1.0.0  
**Wartung**: Christin  
**Entwicklungszeit**: 6+ Monate intensive Entwicklung