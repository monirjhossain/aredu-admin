# Student Application File Upload & Display Feature

## Overview
This feature allows users to upload and manage three types of files (Admission Notice, JW Copy, Visa Copy) for each university application. The files can be PDF, JPG, JPEG, or PNG. Uploaded files are viewable and downloadable from the student's Application Information table.

---

## Implementation Plan

### 1. Database & Model Update
- **Migration:**
  - Add three nullable string fields to the `student_universities` table:
    - `admission_notice`
    - `jw_copy`
    - `visa_copy`
- **Model:**
  - Add these fields to the `$fillable` array in the `StudentUniversity` model.

### 2. Edit Form Update
- In `student_applications/edit.blade.php`, add three file input fields below the Remarks field:
  - Admission Notice (file input)
  - JW Copy (file input)
  - Visa Copy (file input)
- Set `accept` attribute to `.pdf,.jpg,.jpeg,.png` for each input.

### 3. Controller Update
- In the update method:
  - Handle file uploads for each field.
  - Store files in a directory like `storage/app/public/student_applications/`.
  - Save the file path in the corresponding database field.
  - Optionally, delete old files if replaced.

### 4. Student Details Page (Application Information Table)
- In `students/show.blade.php`:
  - Add three new columns: Admission Notice, JW Copy, Visa Copy.
  - If a file exists, show an icon/button for view and download.
  - For images: show a modal/preview; for PDFs: open in a new tab.
  - Add a download button for each file.

### 5. Routes & Controller for File Access
- Add routes and controller methods for:
  - Viewing files (image preview or PDF view)
  - Downloading files
- Ensure proper file access and security.

---

## Summary Task List
1. Create migration for new fields.
2. Update StudentUniversity model.
3. Update edit form with file inputs.
4. Update controller for file upload logic.
5. Update student details table for file view/download.
6. Add routes and controller methods for file access.

---

**Note:**
- Only PDF, JPG, JPEG, PNG files are allowed.
- Files are stored per application and linked to the student.
- UI/UX: View (modal/preview) and Download options for each file.

---

For further implementation, follow this plan step by step.