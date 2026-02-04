# Database Management Utilities

Professional-grade database backup and restore system with automated scheduling, compression, and comprehensive monitoring.

## Features

### Core Functionality
- **Database Backup** - Create compressed backups of MySQL databases with advanced options
- **Database Restore** - Restore databases from backups with integrity verification
- **Automated Scheduling** - Schedule recurring backups (hourly, daily, weekly, monthly)
- **Compression** - Gzip compression to reduce storage requirements
- **Monitoring** - Track backup history, success rates, and database growth
- **Security** - User authentication, CSRF protection, and activity logging

### Advanced Features
- Backup retention policies with automatic cleanup
- Checksum verification for backup integrity
- Selective table backup/exclusion
- Email notifications for success/failure
- Comprehensive activity logging
- Role-based access control (Admin, Operator, Viewer)
- RESTful API for downloads and management

## Technical Stack

- **Backend**: PHP 7.4+ with PDO
- **Database**: MySQL 5.7+ / MariaDB 10.2+
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Design**: Custom CSS with data-focused aesthetic
- **Utilities**: mysqldump, gzip

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.2+
- Web server (Apache/Nginx)
- Shell access for mysqldump
- Write permissions for backup and log directories

### Step-by-Step Installation

1. **Clone or download the application**
   ```bash
   cd /var/www/html/
   # Upload all files to your web directory
   ```

2. **Create your config**
   ```bash
   cp config.example.php config.php
   ```

2. **Configure database connection**
   Edit `includes/config.php` and update:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'db_manager');
   ```

3. **Set directory permissions**
   ```bash
   chmod 755 backups/
   chmod 755 logs/
   chmod 755 temp/
   ```

4. **Run the installer**
   Navigate to: `http://yourdomain.com/install.php`
   
   This will:
   - Create the database and all required tables
   - Set up the default admin user
   - Configure initial settings
   - Create necessary directories

5. **Login with default credentials**
   - Username: `admin`
   - Password: `admin123`
   
   **IMPORTANT**: Change the admin password immediately!

6. **Delete the installer** (for security)
   ```bash
   rm install.php
   ```

7. **Remove diagnostic tools** (recommended)
   ```bash
   rm check.php
   ```

## Configuration

### Backup Settings

Edit `includes/config.php` to customize:

```php
// Backup Configuration
define('BACKUP_DIR', __DIR__ . '/../backups/');
define('BACKUP_PREFIX', 'backup_');
define('BACKUP_RETENTION_DAYS', 30);
define('MAX_BACKUP_SIZE', 500 * 1024 * 1024); // 500MB
define('COMPRESSION_ENABLED', true);
define('COMPRESSION_LEVEL', 9); // 1-9
```

### Email Notifications

Configure SMTP for email notifications:

```php
define('SMTP_ENABLED', true);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
define('NOTIFY_ON_SUCCESS', false);
define('NOTIFY_ON_ERROR', true);
define('ADMIN_EMAIL', 'admin@yourdomain.com');
```

### Security Settings

```php
define('SESSION_LIFETIME', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes
define('MAX_2FA_ATTEMPTS', 5);
define('TWO_FA_LOCKOUT_TIME', 300); // 5 minutes
```

## Usage

### Creating Manual Backups

1. Navigate to **Backup** page
2. Select database from dropdown
3. Configure options:
   - Enable compression (recommended)
   - Add DROP TABLE statements
   - Add table locks
   - Use single transaction (for InnoDB)
   - Exclude specific tables (optional)
4. Add description (optional)
5. Click **Create Backup**

### Restoring Backups

1. Navigate to **Restore** page
2. Select backup from list
3. Choose target database
4. Confirm restore operation
5. System will verify checksum before restore

### Scheduling Automated Backups

1. Navigate to **Schedule** page
2. Click **Create Schedule**
3. Configure:
   - Schedule name
   - Database to backup
   - Frequency (hourly/daily/weekly/monthly)
   - Time of day (for daily+)
   - Day of week (for weekly)
   - Day of month (for monthly)
   - Backup options
4. Enable the schedule
5. Set up cron job (see below)

### Setting Up Cron Job

For automated scheduled backups, add this to your crontab:

```bash
# Run every 5 minutes to check for scheduled backups
*/5 * * * * php /var/www/html/cron.php

# Alternative: Run at specific times
0 * * * * php /var/www/html/cron.php  # Every hour
0 2 * * * php /var/www/html/cron.php  # Daily at 2 AM
```

Create `cron.php` in root:

```php
<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Verify cron secret key for security
if (!isset($_GET['key']) || $_GET['key'] !== CRON_SECRET_KEY) {
    die('Unauthorized');
}

$executed = runScheduledBackups();
echo "Executed $executed scheduled backup(s)\n";
```

## Directory Structure

```
database-manager/
├── api/
│   ├── download.php          # Backup download endpoint
│   └── delete.php            # Backup deletion endpoint
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   └── js/
│       └── main.js           # JavaScript utilities
├── backups/                  # Backup storage (excluded from git)
├── includes/
│   ├── config.php            # Configuration settings
│   ├── functions.php         # Core functions
│   └── sidebar.php           # Navigation sidebar
├── logs/                     # Application logs (excluded from git)
├── temp/                     # Temporary files (excluded from git)
├── backup.php                # Backup creation page
├── restore.php               # Backup restore page
├── schedule.php              # Schedule management page
├── monitor.php               # Monitoring dashboard
├── settings.php              # Application settings
├── login.php                 # Authentication page
├── logout.php                # Logout handler
├── index.php                 # Main dashboard
└── install.php               # Installation script
```

## Database Schema

### Tables

- **users** - User accounts and authentication
- **backups** - Backup file records and metadata
- **restore_history** - Restore operation history
- **backup_schedules** - Automated backup schedules
- **activity_log** - User activity and system events
- **login_attempts** - Failed login tracking
- **settings** - Application configuration

## Security Best Practices

1. **Change Default Password** - Immediately after installation
2. **Use Strong Passwords** - Minimum 12 characters with complexity
3. **Enable HTTPS** - Use SSL/TLS for production
4. **Restrict File Permissions** - Backup and log directories
5. **Regular Updates** - Keep PHP and MySQL updated
6. **Backup Encryption** - Consider encrypting backup files
7. **Access Control** - Use role-based permissions
8. **Delete Installer** - Remove install.php after setup
9. **Monitor Logs** - Regular review of activity logs
10. **Firewall Rules** - Restrict database access

## Security Notes

- **CSRF Protection**: All state-changing actions require a valid CSRF token.
- **Delete API**: The delete endpoint requires a POST request with `csrf_token` in the body and a valid session cookie.
- **Secure Headers**: The app sends security headers (CSP, X-Frame-Options, etc.) from `config.php`.

## Security Section

### Production Checklist
1. Remove `install.php` and `check.php` after setup.
2. Move `backups/` outside the web root or block direct access at the web server.
3. Enable HTTPS and secure cookies.
4. Use a non-root MySQL user with least privileges.
5. Keep PHP/MySQL patched and monitor logs.

## Backup Best Practices

1. **Regular Schedule** - Daily backups at minimum
2. **Multiple Locations** - Store backups off-site
3. **Test Restores** - Regularly verify backup integrity
4. **Monitor Disk Space** - Ensure adequate storage
5. **Retention Policy** - Keep backups for 30+ days
6. **Compression** - Enable to reduce storage costs
7. **Documentation** - Use descriptions for backups
8. **Notifications** - Enable error notifications
9. **Verify Checksums** - Always check integrity
10. **Incremental Strategy** - Consider incremental backups

## Troubleshooting

### Backup Fails

**Problem**: Backup creation fails with error

**Solutions**:
- Check MySQL credentials in config.php
- Verify mysqldump is installed: `which mysqldump`
- Check write permissions on backup directory
- Review error logs in logs/ directory
- Ensure sufficient disk space

### Restore Fails

**Problem**: Database restore fails

**Solutions**:
- Verify backup file exists and isn't corrupted
- Check checksum matches
- Ensure target database exists
- Verify MySQL user has required privileges
- Check error logs for specific issues

### Scheduled Backups Not Running

**Problem**: Cron jobs not executing

**Solutions**:
- Verify cron job is configured correctly
- Check cron.php exists and is executable
- Verify CRON_SECRET_KEY is correct
- Review cron logs: `grep CRON /var/log/syslog`
- Test manual execution: `php cron.php?key=YOUR_KEY`

### Permission Errors

**Problem**: Cannot create/write files

**Solutions**:
```bash
# Set correct ownership
chown -R www-data:www-data backups/ logs/ temp/

# Set correct permissions
chmod 755 backups/ logs/ temp/
```

## Performance Optimization

1. **Compression Level** - Balance between speed and size (level 6-9)
2. **Single Transaction** - Enable for InnoDB tables
3. **Exclude Large Tables** - Skip temporary/cache tables
4. **Off-Peak Scheduling** - Run backups during low traffic
5. **Database Optimization** - Regular OPTIMIZE TABLE
6. **Index Maintenance** - Keep indexes optimized
7. **Clean Old Backups** - Automatic retention cleanup

## API Endpoints

### Download Backup
```
GET /api/download.php?id={backup_id}
```

### Delete Backup
```
POST /api/delete.php

Body (application/x-www-form-urlencoded):
  id={backup_id}
  csrf_token={token}
```

## Browser Compatibility

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## License

This project is available for use in personal and commercial projects.

## Support

For issues, questions, or feature requests:
- Review the troubleshooting section
- Check application logs in logs/ directory
- Verify MySQL and PHP error logs

## Changelog

### Version 1.0.0
- Initial release
- Core backup/restore functionality
- Automated scheduling
- User authentication
- Activity logging
- Email notifications
- Compression support
- Monitoring dashboard

## Credits

Developed with professional-grade architecture and refined design aesthetic.

