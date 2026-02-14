# Changelog

All notable changes to `sndpbag/admin-panel` will be documented in this file.

## [1.1.0] - 2026-02-14

### Added
- **Database Backup Feature**: One-click database backup with download functionality
  - Pure PHP implementation (no system dependencies)
  - Cross-platform compatible (Windows, Linux, Mac)
  - Permission-based access control
  - Complete export of table structures and data
  
- **Maintenance Mode**: Put website into maintenance during updates
  - Toggle switch for enable/disable
  - Beautiful animated maintenance page
  - Secret bypass URL with unique token
  - IP whitelist support
  - Customizable maintenance message
  - Real-time status indicator
  - Permission-protected (super admin only)

### Updated
- README.md with comprehensive guides for new features
- Settings page UI with new sections
- Migration system for maintenance settings storage

### Technical Details
- New migration: `create_site_settings_table`
- New model: `SiteSetting` with helper methods
- New middleware: `CheckMaintenanceMode`
- New routes for maintenance toggle and bypass
- Controller methods: `backupDatabase()`, `toggleMaintenanceMode()`, `updateMaintenanceSettings()`, `bypassMaintenance()`

---

## [1.0.2] - Previous Release

### Previous Features
- Complete authentication system with 2FA
- Role-based access control (RBAC)
- User management with activity logs
- Dynamic theme customization
- Dark mode support
- PWA (Progressive Web App) support
- Social login (Google, Facebook)
- Permission management system
- Data export (PDF, CSV, Excel)
