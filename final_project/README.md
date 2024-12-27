# Health Bridge - Hospital Management System  

**Health Bridge** is a comprehensive hospital management system designed to streamline operations for hospitals, clinics, and healthcare providers. The system allows users to manage patient data, doctors, appointments, and other essential services efficiently.  

---

## Features  

### **1. User Roles**  
- **Admin**:  
  - Manage doctors and patients.  
  - View and handle contact requests.  
  - Monitor system-wide activity.  
- **Doctor**:  
  - View and manage appointments.  
  - Update personal details, specialty, and availability.  
- **Patient**:  
  - Book and manage appointments.  
  - Access personal health records.  

### **2. Doctor Management**  
- Add, update, and delete doctors.  
- Assign specialties and manage contact details.  
- Upload doctor photos for easy identification.  

### **3. Appointment System**  
- Schedule, cancel, and complete appointments.  
- View doctor availability.  

### **4. Patient Management**  
- Maintain detailed patient records.  
- Role-based system: convert patients to doctors when needed.  

### **5. Contact Management**  
- Handle user-submitted contact requests with status updates (pending/resolved).  

---

## Technology Stack  

### **Frontend**  
- **HTML5, CSS3, JavaScript**:  
  - Responsive design for all devices.  
  - Dashboard and forms styled with modern CSS.  

### **Backend**  
- **PHP**:  
  - Handles dynamic server-side functionality.  

### **Database**  
- **MySQL**:  
  - Efficient relational database for storing user, doctor, and appointment data.  

---

## Installation Guide  

### **Prerequisites**  
- A server environment like **XAMPP** or **WAMP**.  
- **PHP** (>= 7.4), **MySQL** (>= 5.7).  

### **Steps**  
1. Clone the repository:  
   ```bash  
   git clone https://github.com/Abdallah1470/group-10-projects.git  
   ```  
2. Navigate to the project folder:  
   ```bash  
   cd group-10-projects/final_project/web_project  
   ```  
3. Move the project folder to your web server directory (e.g., `htdocs` in XAMPP).  
4. Import the `hospital_db` database:  
   - Open phpMyAdmin.  
   - Create a database named `hospital_db`.  
   - Import the provided SQL file (`hospital_db.sql`) into the database.  
5. Configure database credentials:  
   - Open `config/db.php`.  
   - Update the database credentials (host, username, password, database name).  

   ```php  
   $host = 'localhost';  
   $db = 'hospital_db';  
   $user = 'root';  
   $password = '';  
   ```  
6. Start the server and open the application:  
   - Go to `http://localhost/web_project`.  

---

## Usage  

### **Admin Dashboard**  
- Navigate to `http://localhost/web_project/pages/dashboard/admin.php`.  
- Use the admin account to log in and access features like managing doctors, patients, and appointments.  

### **Doctor and Patient Login**  
- Doctors and patients can log in using their credentials and access their respective dashboards.  

---

## Folder Structure  

```plaintext  
web_project/  
├── assets/                     # Static files (CSS, JS, Images)  
│   ├── css/                    # Stylesheets  
│   │   ├── dashboard.css  
│   │   ├── responsive.css  
│   │   └── style.css  
│   ├── images/                 # Image assets  
│   │   ├── doctor_default.png  
│   │   ├── hospital-bg.png  
│   │   └── logo.png  
│   └── js/                     # JavaScript files  
│       ├── dashboard.js  
│       └── main.js  
├── config/                     # Configuration files  
│   └── db.php  
├── includes/                   # Authentication and utility scripts  
│   ├── auth.php  
│   ├── footer.php  
│   └── header.php  
├── pages/                      # Frontend pages  
│   ├── about.php  
│   ├── admin/                  # Admin pages  
│   │   ├── appointments.php  
│   │   ├── contact_requests.php  
│   │   ├── doctors.php  
│   │   └── services.php  
│   ├── appointments/           # Appointment management  
│   │   ├── book.php  
│   │   ├── manage.php  
│   │   ├── update_status.php  
│   │   └── view.php  
│   ├── contact.php  
│   ├── dashboard/              # Dashboard pages  
│   │   ├── admin.php  
│   │   ├── doctor.php  
│   │   ├── patient.php  
│   │   └── view_doctors.php  
│   ├── login.php  
│   ├── logout.php  
│   ├── register.php  
│   ├── services/               # Service management  
│   │   ├── add.php  
│   │   ├── delete.php  
│   │   ├── edit.php  
│   │   └── manage.php  
│   └── services.php  
├── sql/                        # SQL scripts  
│   └── hospital_db.sql  
├── uploads/                    # Uploaded files (photos, documents)  
│   ├── doctor_1.png  
│   ├── doctor_2.png  
│   ├── doctor_3.png  
│   ├── doctor_4.png  
│   ├── doctor_default.png  
│   └── logo_1.jpg  
└── index.php                   # Main entry point of the application   
```  

---  

**Health Bridge** – Your all-in-one hospital management solution.  