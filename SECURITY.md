# Security Guidelines

## 🚨 IMPORTANT SECURITY NOTICE

This repository contains a Laravel application with sensitive configuration requirements. **NEVER commit sensitive data to version control!**

## Sensitive Files (NEVER commit these)

### Environment Configuration
- `.env` - Contains production passwords and secrets
- `.env.local`, `.env.production`, etc.
- Any file containing real passwords, API keys, or tokens

### Database & Storage
- `*.sqlite`, `*.db` - Local database files
- `/storage/logs/*.log` - May contain sensitive error information
- `/storage/app/public/avatars/` - User uploaded content
- Any backup files (`*.backup`, `*.bak`, `*.dump`)

### Secrets & Keys
- Private SSL certificates (`.key`, `.pem`, `.crt`)
- OAuth tokens and JWT secrets
- reCAPTCHA secret keys
- Meilisearch API keys
- Database passwords

## Before Going Public

1. **Environment Setup**
   ```bash
   cp .env.example .env
   # Edit .env with your secure values
   php artisan key:generate
   ```

2. **Change Default Credentials**
   - Admin email and password in `.env`
   - Database passwords
   - All API keys and secrets

3. **Verify .gitignore**
   - Ensure `.env` is ignored
   - Check no sensitive files are tracked
   ```bash
   git ls-files | grep -E "\.(env|key|log|backup)$"
   # Should return nothing!
   ```

4. **Security Headers**
   - SSL/TLS certificates properly configured
   - Security headers middleware enabled
   - CSRF protection active

## Production Deployment

- Use secure passwords (minimum 16 characters)
- Enable 2FA for admin accounts
- Regular security updates
- Monitor logs for suspicious activity
- Backup sensitive data securely (encrypted)

## Reporting Security Issues

If you discover security vulnerabilities, please report them privately to: security@ki-coding.de

**DO NOT** create public GitHub issues for security problems.