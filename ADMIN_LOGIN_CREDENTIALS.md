# 🔐 ADMIN LOGIN CREDENTIALS

**Platform:** Rencontre Éthique - Islamic Matrimony Platform (France)  
**Date:** August 10, 2026  
**Environment:** Production (https://web-production-aa6669.up.railway.app/)

---

## 👤 ADMIN USER

**Email:** `admin@rencontre-ethique.fr`  
**Password:** `Admin@12345`  
**Role:** `admin`  
**Mobile:** `0123456789`  
**Name:** Admin User

**Login URL:** https://web-production-aa6669.up.railway.app/admin/login

**Access:**
- ✅ Admin Dashboard
- ✅ User Management
- ✅ Biodata Approval
- ✅ Reports & Analytics
- ✅ Moderation Tools
- ✅ System Settings

---

## 👤 MODERATOR USER

**Email:** `moderator@rencontre-ethique.fr`  
**Password:** `Moderator@12345`  
**Role:** `moderator`  
**Mobile:** `0187654321`  
**Name:** Moderator User

**Access:**
- ✅ Moderation Queue
- ✅ Biodata Review
- ✅ User Reports
- ✅ Content Moderation

---

## 👤 TEST MEMBER USER

**Email:** `member@rencontre-ethique.test`  
**Password:** `Member@12345`  
**Role:** `member`  
**Mobile:** `0612345678`  
**Name:** Member Test User

**Access:**
- ✅ User Panel
- ✅ Profile Wizard
- ✅ Search & Browse
- ✅ Messaging
- ✅ Proposals & Likes
- ✅ Analytics

---

## 🔧 HOW TO USE

### **To Create These Users (Run Once on Production):**

```bash
cd /var/www/rencontre-ethique
php artisan db:seed --class=AdminSeeder
```

### **To Seed with All Seeders:**

```bash
php artisan migrate:fresh --seed
```

---

## ✅ SECURITY NOTES

⚠️ **IMPORTANT:** Change passwords immediately after first login!

**Recommended Actions:**
1. ✅ Log in with provided credentials
2. ✅ Change password to something secure
3. ✅ Enable 2FA if available
4. ✅ Keep credentials secure

**Password Requirements:**
- Minimum 8 characters
- Mix of uppercase & lowercase
- Numbers & special characters recommended

---

## 📋 USER ROLES

### **Admin**
- Full system access
- User management
- Biodata approval
- Reports & analytics
- System configuration

### **Moderator**
- Moderation queue
- Content review
- User reports
- Profile verification

### **Member**
- User panel
- Profile creation
- Search & browse
- Messaging
- Proposals & likes

---

## 🌐 LOGIN PAGES

**Admin Login:** https://web-production-aa6669.up.railway.app/admin/login  
**Member Login:** https://web-production-aa6669.up.railway.app/login  
**Registration:** https://web-production-aa6669.up.railway.app/register

---

## 📱 TEST SCENARIOS

### **Test Registration Flow:**
1. Go to: https://web-production-aa6669.up.railway.app/register
2. Fill in:
   - Gender: Homme/Femme
   - Name: Any name (3+ chars)
   - Email: Any email
   - Mobile: France format (01XXXXXXXXX)
   - Password: Any password (8+ chars)
3. Accept Terms & Privacy
4. Submit

### **Test Admin Dashboard:**
1. Go to: https://web-production-aa6669.up.railway.app/admin/login
2. Enter:
   - Email: `admin@rencontre-ethique.fr`
   - Password: `Admin@12345`
3. View:
   - User list
   - Biodata approvals
   - Analytics reports

### **Test Member Features:**
1. Go to: https://web-production-aa6669.up.railway.app/login
2. Enter:
   - Email: `member@rencontre-ethique.test`
   - Password: `Member@12345`
3. Access:
   - User dashboard
   - Profile wizard
   - Search & browse
   - Messaging

---

## 🗂️ SEEDER FILE LOCATION

**Path:** `database/seeders/AdminSeeder.php`

**Run Command:**
```bash
php artisan db:seed --class=AdminSeeder
```

---

## 📝 NOTES

- All users have `email_verified_at` set (no email verification needed)
- Users created with `updateOrCreate()` (safe to run multiple times)
- Passwords are hashed with bcrypt
- Roles: `admin`, `moderator`, `member`

---

**Status:** ✅ Ready to use  
**Created:** August 10, 2026  
**Platform:** https://web-production-aa6669.up.railway.app/