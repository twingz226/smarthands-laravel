# Admin Profile Management Feature

## Overview
This feature allows administrators to view and edit their profile information directly from the admin panel, including profile photo upload functionality. The profile management is accessible from both the sidebar menu and the header navigation.

## Features Implemented

### 1. Profile View (`/admin/profile`)
- Displays current admin information including:
  - Full name
  - Email address
  - Phone number (if provided)
  - Address (if provided)
  - Member since date
  - Last updated timestamp
- Shows admin profile photo (or default admin logo if no photo uploaded)
- Includes an "Edit Profile" button

### 2. Profile Edit (`/admin/profile/edit`)
- Form to update profile information:
  - Full name (required)
  - Email address (required, with unique validation)
  - Phone number (optional)
  - Address (optional)
- Profile photo upload:
  - Display current profile photo
  - Upload new photo (JPEG, PNG, JPG, GIF, max 2MB)
  - Automatic old photo deletion when new one is uploaded
- Password change functionality:
  - Current password verification
  - New password (minimum 8 characters)
  - Password confirmation
  - Eye icons to show/hide passwords (using Boxicons like login form)
- Form validation with error messages
- Cancel and Update buttons

### 3. Navigation Integration
- **Sidebar Menu**: Added "My Profile" menu item with user icon
- **Header Navigation**: Added "Profile" link in the top-right navigation
- **User Info Section**: Updated to show actual admin name and profile photo
- **Clickable User Info**: Clicking on the user info in sidebar now leads to profile page

## Files Created/Modified

### New Files:
- `app/Http/Controllers/Admin/ProfileController.php` - Controller for profile management
- `resources/views/admin/profile/show.blade.php` - Profile view page
- `resources/views/admin/profile/edit.blade.php` - Profile edit form
- `ADMIN_PROFILE_FEATURE.md` - This documentation

### New Migrations:
- `database/migrations/2025_07_07_110100_add_missing_fields_to_users_table.php` - Adds phone, address, points fields
- `database/migrations/2025_07_07_105854_add_profile_photo_to_users_table.php` - Adds profile_photo field

### Modified Files:
- `routes/admin.php` - Added profile routes
- `resources/views/admin/partials/sidebar.blade.php` - Added profile menu item and dynamic user name/photo
- `resources/views/admin/partials/header.blade.php` - Added profile link in header
- `app/Models/User.php` - Added profile_photo field and getProfilePhotoUrlAttribute method

## Routes Added:
- `GET /admin/profile` - Show profile (route: `admin.profile.show`)
- `GET /admin/profile/edit` - Edit profile form (route: `admin.profile.edit`)
- `PUT /admin/profile/update` - Update profile (route: `admin.profile.update`)

## Security Features:
- Password change requires current password verification
- Email uniqueness validation (excluding current user)
- CSRF protection on all forms
- Authentication middleware on all routes
- File upload validation (image types, size limits)
- Automatic old photo cleanup when new photo is uploaded

## File Storage:
- Profile photos are stored in `storage/app/public/profile-photos/`
- Files are named with pattern: `admin_{user_id}_{timestamp}.{extension}`
- Storage link is created for public access via `/storage/profile-photos/`
- Default admin logo is used when no profile photo is uploaded

## Usage Instructions:

1. **Access Profile**: Click on "My Profile" in the sidebar or "Profile" in the header
2. **View Profile**: See all current profile information and photo
3. **Edit Profile**: Click "Edit Profile" button
4. **Update Information**: Fill in the form and optionally upload a new photo
5. **Change Password**: Optionally provide current password and new password
6. **Save Changes**: Click "Update Profile" to save changes

## Photo Upload Guidelines:
- Supported formats: JPEG, PNG, JPG, GIF
- Maximum file size: 2MB
- Photos are automatically cropped to fit circular display
- Old photos are automatically deleted when a new one is uploaded

## Default Admin User:
- Email: `admin@example.com`
- Password: `adminpassword123`
- **Important**: Change this password after first login for security

## Technical Notes:
- Uses existing User model fields (name, email, phone, address) plus new profile_photo field
- Follows the same layout pattern as other admin pages
- Responsive design compatible with existing admin theme
- Error handling and success messages included
- Form validation with user-friendly error messages
- File upload handling with proper validation and cleanup 