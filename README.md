# 🤖 KI-Coding.de - Community Wiki Platform

[![Laravel](https://img.shields.io/badge/Laravel-12.21.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![PHPUnit](https://img.shields.io/badge/PHPUnit-12.2.7-366488?style=for-the-badge&logo=php&logoColor=white)](https://phpunit.de)
[![Docker](https://img.shields.io/badge/Docker-Enabled-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Code Coverage](https://gitlab.apboard.de/private/ki-coding/badges/dev/coverage.svg)](https://gitlab.apboard.de/private/ki-coding/-/commits/dev)

> **Eine moderne, sicherheitsfokussierte Wiki-Plattform für die KI-Programmierung-Community**

---

## 🌟 Projekt-Highlights

**KI-Coding.de** ist eine professionelle Laravel-basierte Wiki-Plattform, die speziell für die wachsende KI-Programmierung-Community entwickelt wurde. Das System kombiniert moderne Web-Technologien mit robusten Sicherheitsfeatures und einem umfassenden Content-Management-System.

### ✨ Hauptfeatures

- 🔐 **Fortgeschrittenes Rollen-System** - 5-stufige Hierarchie mit 51 granularen Permissions
- 📝 **Professionelles Wiki-System** - Markdown-Editor, Versionshistorie mit MediaWiki-Style Diff, Featured Articles
- 🛡️ **Security-First Design** - XSS-Schutz, CSRF-Protection, Content Security Policy, RFC 9116 Security.txt
- 👥 **Community-Features** - Kommentare, Likes, Real-time Notifications, Moderation
- 🎨 **Modernes Design** - Logo-basierte Farbpalette, Glassmorphism, Responsive Design
- 🔍 **Erweiterte Suche** - Meilisearch-Integration mit Auto-Complete
- 👤 **Umfassende Profile** - Avatar-Upload, Privacy-Settings, Social Media Integration
- 🔔 **Push-Notification System** - Real-time Benachrichtigungen für Likes, Kommentare, Bookmarks
- 🤖 **RESTful API v1** - Vollständige API mit Bearer Token Authentifizierung

---

## 🚀 Quick Start

### Voraussetzungen

- **Docker & Docker Compose**
- **Git**
- **Node.js 18+** (für Asset-Building)

### Installation

```bash
# Repository klonen
git clone https://github.com/your-org/ki-coding.de.git
cd ki-coding.de

# Environment-Datei kopieren
cp .env.example .env

# Docker Container starten
docker-compose up -d

# Dependencies installieren
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; composer install"
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; npm install"

# Datenbank Migration
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan migrate"

# Rollen und Permissions erstellen (ZUERST)
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan db:seed --class=RolesAndPermissionsSeeder"

# Admin-User erstellen (basierend auf .env)
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan db:seed --class=AdminUserSeeder"

# Optional: Demo-Content laden (nur für Development)
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan db:seed --class=WikiSeeder"

# Assets kompilieren
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; npm run build"

# Storage-Link erstellen
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan storage:link"
```

### Admin-Account Konfiguration

Das System erstellt automatisch einen Administrator-Account basierend auf den Konfigurationen in der `.env` Datei:

```env
ADMIN_EMAIL=your-admin@email.com
ADMIN_PASSWORD=your-secure-password
ADMIN_NAME="Your Admin Name"
```

**Wichtiger Sicherheitshinweis**: Stelle sicher, dass du ein starkes, einzigartiges Passwort für den Admin-Account verwendest und diese Credentials sicher verwahrst.

---

## 🏗️ Technische Architektur

### Tech Stack

| Komponente | Technologie | Version |
|------------|-------------|---------|
| **Backend** | Laravel | 12.21.0 |
| **Frontend** | Tailwind CSS + Vite | 3.4+ |
| **Datenbank** | MySQL/PostgreSQL | 8.0+ / 14+ |
| **Search Engine** | Meilisearch | 1.5+ |
| **Cache/Sessions** | Redis | 7.0+ |
| **Queue Management** | Laravel Horizon | - |
| **Container** | Docker | 24.0+ |

### Architektur-Übersicht

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │     Backend     │    │    Database     │
│                 │    │                 │    │                 │
│ • Tailwind CSS  │◄───┤ • Laravel 12.21 │◄───┤ • MySQL/PostgreSQL
│ • Vite Build    │    │ • PHP 8.4+      │    │ • Redis Cache   │
│ • Alpine.js     │    │ • Spatie Perms  │    │ • Meilisearch   │
│ • Responsive    │    │ • Queue System  │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

---

## 👥 Rollen & Permissions

### Rollen-Hierarchie

```
🔴 Admin (System Administrator)
├── Vollständige System-Kontrolle
├── Rollen-Management
├── User-Verwaltung
└── System-Konfiguration

🟠 Moderator (Community Manager)
├── Content-Moderation
├── User-Management (Bans)
├── Report-System
└── Admin-Panel Zugang

🟡 Editor (Content Manager)
├── Artikel publizieren
├── Featured Articles
├── Kategorie-Management
└── Comment-Moderation

🟢 Contributor (Content Creator)
├── Artikel erstellen/bearbeiten
├── Tag-Management
├── Draft-System
└── API-Zugang

⚪ User (Basis-Rolle)
├── Artikel lesen
├── Kommentare schreiben
├── Profil bearbeiten
└── Notifications erhalten
```

### Permission-System

**Ausschließlich rollenbasierte Permissions** - keine direkten User-Permissions für maximale Sicherheit:

- **Content**: `view/create/edit/delete/publish articles`, `use api`
- **Community**: `moderate comments`, `ban users`, `manage reports`
- **Administration**: `manage roles`, `assign roles`, `delete users`
- **System**: `access admin panel`, `manage settings`, `view users`

---

## 🛡️ Sicherheitsfeatures

### Implementierte Schutzmaßnahmen

- **🔒 XSS-Protection**: WikiSecurity Middleware mit Pattern-Detection
- **🛡️ CSRF-Protection**: Laravel Standard + Custom Implementation
- **📋 Input Validation**: Umfassende Request Validation Classes
- **👮 Permission System**: Spatie Laravel Permissions (ONLY role-based)
- **🚧 Rate Limiting**: API & Wiki Rate Limits
- **📊 Activity Logging**: Verdächtige Aktivitäten-Tracking
- **🚫 Ban System**: Temporäre/Permanente User-Bans
- **🔐 Content Security Policy**: Restriktive CSP-Headers
- **🔒 Cookie Security**: HttpOnly, Secure, SameSite Flags
- **📋 RFC 9116 Security.txt**: Responsible Disclosure Guidelines
- **🏆 Security Acknowledgments**: Hall of Fame für Security Researcher

### Security Headers

```http
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Content-Security-Policy: default-src 'self'; ...
Strict-Transport-Security: max-age=31536000
```

---

## 📝 Wiki-Features

### Content-Management

- **📄 Markdown-Editor**: WYSIWYG mit Live-Preview
- **📚 Revision-System**: Vollständige Versionshistorie mit MediaWiki-Style Diff-Vergleich
- **⭐ Featured Articles**: Redaktionelle Empfehlungen
- **🗂️ Kategorien**: Hierarchische Organisation
- **🏷️ Tag-System**: Flexible Kategorisierung
- **🔍 Volltext-Suche**: Meilisearch-Integration

### Community-Funktionen

- **💬 Comment-System**: 6-Level Nested Comments mit Moderation
- **👍 Like-System**: Artikel und Kommentar-Likes
- **🔔 Push-Notifications**: Real-time Benachrichtigungen für Artikel-Interaktionen
- **📌 Bookmark-System**: Artikel zur späteren Lektüre markieren
- **🏆 Reputation-System**: Aktivitäts-basierte Punktevergabe
- **🚨 Report-System**: Community-basierte Moderation
- **👤 Profile**: Umfassende Benutzerprofile

---

## 🤖 RESTful API v1

### API-Features

- **🔐 Bearer Token Authentication**: Laravel Sanctum-basierte Authentifizierung
- **📋 Role-based Access Control**: API-Zugang über `use api` Permission
- **🚧 Rate Limiting**: 60 Requests/Minute für authentifizierte Benutzer
- **📊 Consistent Response Format**: Einheitliche JSON-Antworten
- **📖 Comprehensive Documentation**: Vollständige API-Dokumentation im Dashboard
- **🔒 Security-First**: HTTPS, Token-Scoping, Input Validation

### Verfügbare Endpunkte

| Methode | Endpunkt | Beschreibung | Permission |
|---------|----------|--------------|------------|
| `GET` | `/api/user` | Authentifizierte User-Informationen | - |
| `POST` | `/api/v1/articles` | Artikel erstellen | `create articles` |

### Beispiel-Request

```bash
curl -X POST https://www.ki-coding.de/api/v1/articles \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Mein API-Artikel",
    "content": "Inhalt des Artikels...",
    "category_slug": "tutorials",
    "status": "published"
  }'
```

---

## 🎨 Design-System

### Farbpalette (Logo-basiert)

```css
/* Primary Colors (APB Logo) */
--primary-green: #4CAF50;    /* Haupt-Branding */
--secondary-orange: #E67E22; /* Akzent-Farbe */
--accent-blue: #3498DB;      /* Highlight-Farbe */

/* Extended Palette */
--purple-family: #8B5CF6 → #3B0764;
--teal-family: #14B8A6 → #042F2E;
--pink-family: #EC4899 → #4C0519;
```

### Design-Features

- **🌈 Moderne Gradienten**: sunset, ocean, forest, dawn
- **✨ Glassmorphism**: backdrop-blur-Effekte
- **📱 Responsive Design**: Mobile-First Approach
- **🎯 Accessibility**: WCAG 2.1 konform
- **⚡ Performance**: Optimierte Asset-Delivery

---

## 🔧 Entwicklung

### Lokale Entwicklung

```bash
# Development Server starten
docker-compose up -d

# Assets im Watch-Modus
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; npm run dev"

# Queue Worker starten
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan horizon"

# Tests ausführen
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan test"
```

### Code-Standards

- **PSR-12**: PHP Coding Standards
- **Laravel Best Practices**: Framework-Konventionen
- **Type Hints**: Vollständige Type Annotations
- **DocBlocks**: Umfassende Code-Dokumentation

### Testing

```bash
# Unit Tests
php artisan test --filter=Unit

# Feature Tests
php artisan test --filter=Feature

# Coverage Report
php artisan test --coverage
```

---

## 📊 Performance

### Optimierungen

- **🗃️ Database Indexing**: Performance-optimierte Indizes
- **🔄 Query Optimization**: Eager Loading, N+1 Vermeidung
- **💾 Redis Caching**: Session und Query Caching
- **📦 Asset Optimization**: Vite-basierte Bundling
- **🔍 Search Performance**: Externe Meilisearch Engine

### Monitoring

- **📈 Laravel Horizon**: Queue-Monitoring
- **🔍 Laravel Telescope**: Development Debugging
- **📊 Performance Metrics**: Response Time Tracking
- **🚨 Error Logging**: Comprehensive Error Tracking

---

## 🚀 Deployment

### Produktions-Deployment

```bash
# Assets für Produktion bauen
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; npm run build"

# Cache optimieren
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan config:cache"
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan route:cache"
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan view:cache"

# Migration ausführen
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan migrate --force"

# Rollen und Permissions (Production)
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan db:seed --class=RolesAndPermissionsSeeder --force"

# Admin-User erstellen (Production)
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan db:seed --class=AdminUserSeeder --force"

# Queue Worker starten
docker exec --user web www.ki-coding.de-php bash -c "cd /var/www/current ; php artisan horizon"
```

### Docker-Services

```yaml
services:
  php:         # PHP 8.4+ with Laravel
  http:        # Apache Web Server
  db:          # MySQL/PostgreSQL Database
  redis:       # Cache & Session Storage
  meilisearch: # Search Engine
  traefik:     # Load Balancer & SSL
```

---

## 📈 Roadmap

### Geplante Features

- **📱 Mobile App**: REST API für Native Apps
- **🌍 Internationalization**: Multi-Language Support
- **📊 Advanced Analytics**: User Behavior Analytics
- **🔌 Plugin System**: Erweiterbare Architektur
- **📤 Content Export**: PDF/EPUB Export
- **🔔 WebSocket Notifications**: Real-time Push via WebSockets
- **🤖 Extended API**: Vollständige CRUD-API für alle Ressourcen

### Performance-Verbesserungen

- **🌐 CDN Integration**: Global Asset Distribution
- **🔄 Database Sharding**: Skalierbare DB-Architektur
- **🏗️ Microservices**: Service-orientierte Architektur
- **📱 Progressive Web App**: Offline-Funktionalität

---

## 🤝 Contributing

### Beitrag-Richtlinien

1. **🔀 Fork** das Repository
2. **🌿 Feature Branch** erstellen: `git checkout -b feature/amazing-feature`
3. **💾 Commit** deine Änderungen: `git commit -m 'Add amazing feature'`
4. **📤 Push** zum Branch: `git push origin feature/amazing-feature`
5. **🔄 Pull Request** erstellen

### Code-Review-Prozess

- **✅ Code Standards**: PSR-12 Compliance
- **🧪 Tests**: Feature und Unit Tests erforderlich
- **📖 Dokumentation**: Code-Kommentare und DocBlocks
- **🔒 Security**: Security Review bei sensiblen Änderungen

---

## 📞 Support & Wartung

### Support-Kanäle

- **📧 Email**: admin@ki-coding.de
- **🔒 Security**: security@ki-coding.de (für Sicherheitslücken)
- **💬 Issues**: [GitLab Issues](https://gitlab.com/ki-coding/issues)
- **📖 Documentation**: [Interne Dokumentation](PROJECT.md)
- **🔒 Security Policy**: [/security-policy](https://www.ki-coding.de/security-policy)
- **🏆 Security Acknowledgments**: [/security-acknowledgments](https://www.ki-coding.de/security-acknowledgments)

### Wartungs-Zyklen

- **🔒 Security Updates**: Monatlich
- **🆕 Feature Updates**: Quartalsweise
- **📦 Dependency Updates**: Bei Bedarf
- **🐛 Bugfixes**: Nach Priorität

---

## 📝 License & Credits

### Lizenz
**Proprietary Software** - Alle Rechte vorbehalten.  
© 2025 KI-Coding.de Community

### Credits

- **Framework**: [Laravel](https://laravel.com) - The PHP Framework for Web Artisans
- **Permissions**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- **Search**: [Meilisearch](https://meilisearch.com) - Lightning Fast Search
- **Styling**: [Tailwind CSS](https://tailwindcss.com) - Utility-First CSS Framework
- **Icons**: [Font Awesome](https://fontawesome.com) - Icon Library
- **Security Standards**: [RFC 9116](https://tools.ietf.org/rfc/rfc9116.txt) - Security.txt Format
- **Humans.txt**: [/humans.txt](https://www.ki-coding.de/humans.txt) - Team & Technology Credits

### Entwickelt mit ❤️ für die KI-Community

---

## 📊 Projekt-Statistiken

```
📁 Codebase Size:    ~59,300 Zeilen (PHP: 37,196 | Blade: 17,536 | JS: 1,458 | CSS: 3,111)
🧩 Components:       90 Blade Components  
🎯 Features:         25+ Hauptfeatures
🔐 Permissions:      51 Granulare Rechte (ONLY role-based)
🎨 UI Components:    90 Blade Templates
📝 Database Tables:  39 Migrations
🧪 Test Suite:       50 Tests in 13 Test Files (PHPUnit 12.2.7)
⚡ Performance:      < 200ms Average Response
🚀 Framework:        Laravel 12.21.0 + PHP 8.4
🔔 Notifications:    Real-time Push System
🤖 API Endpoints:    RESTful API v1 (Article Creation)
🔒 Security:         RFC 9116 compliant
```

---

**🚀 Bereit für die Zukunft der KI-Programmierung!**

> *„Wissen teilen, Gemeinschaft stärken, Innovation fördern"*