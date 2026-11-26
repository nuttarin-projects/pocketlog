# 💰 PocketLog

ระบบบันทึกรายรับ-รายจ่ายประจำวัน พร้อมสรุปยอดคงเหลือรายเดือน พัฒนาด้วย **PHP Laravel** และรันบน **Docker** (Nginx + PHP-FPM)

## ✨ ฟีเจอร์หลัก (Features)
- **ระบบสมาชิก (Authentication):** สมัครสมาชิก เข้าสู่ระบบ และแยกข้อมูลบัญชีรายบุคคล
- **บันทึกรายการรายวัน:** เพิ่มและลบรายการรายรับ-รายจ่าย พร้อมระบุวันที่
- **สรุปยอดรายเดือน:** คำนวณรายรับรวม รายจ่ายรวม และยอดคงเหลือสุทธิ แยกตามเดือนที่เลือก

## 🛠️ เทคโนโลยีที่ใช้ (Tech Stack)
- **Backend:** PHP 8.3, Laravel Framework
- **Frontend:** Blade Template, Bootstrap 5
- **Web Server:** Nginx (Alpine)
- **Database:** MySQL (เชื่อมต่อผ่าน Shared Docker Network)
- **Environment:** Docker & Docker Compose

---

## 🚀 วิธีติดตั้งและรันโปรเจกต์ (เมื่อ Clone โค้ดใหม่)

### สิ่งที่ต้องเตรียมก่อน (Prerequisites)
1. ติดตั้ง **Docker Desktop** และเปิดใช้งานให้เรียบร้อย
2. มี Network กลางชื่อ `shared_db_net` และ Container MySQL กลางชื่อ `db` รันอยู่แล้ว (หากยังไม่มี ให้สร้าง Network ด้วยคำสั่ง `docker network create shared_db_net`)
3. สร้างฐานข้อมูลชื่อ **`pocketlog_db`** ใน MySQL (หรือผ่าน phpMyAdmin)

### ขั้นตอนการติดตั้ง
1. **Clone โปรเจกต์ลงเครื่อง:**
   ```bash
   git clone https://github.com/nuttarin-projects/pocketlog.git

2. **เปิดใช้งาน Docker Container:**
   ```bash
   docker compose up -d --build
   ```

3. **ติดตั้ง Dependency ของ Laravel (`vendor`):**
   ```bash
   docker compose exec app composer install
   ```

4. **ตั้งค่าไฟล์ `.env`:**
   ```bash
   docker compose exec app cp .env.example .env
   ```
   เปิดไฟล์ `src/.env` และแก้ไขการเชื่อมต่อฐานข้อมูลให้ตรงกับ MySQL กลาง:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=pocketlog_db
   DB_USERNAME=your_db_username
   DB_PASSWORD=your_db_password
   ```

5. **สร้าง Application Key, ตั้งสิทธิ์โฟลเดอร์ และสร้างตารางฐานข้อมูล:**
   ```bash
   docker compose exec app php artisan key:generate
   docker compose exec app chmod -R 777 storage bootstrap/cache
   docker compose exec app php artisan migrate
   ```

6. **เข้าใช้งานผ่านเบราว์เซอร์:**
   เปิดเว็บไปที่ 👉 **`http://localhost:8002`**

---

## 📁 โครงสร้างโฟลเดอร์ (Project Structure)
```text
pocketlog/
├── docker/
│   ├── nginx/
│   │   └── default.conf    # ตั้งค่า Nginx Web Server
│   └── php/
│       └── Dockerfile      # ตั้งค่า PHP 8.3-FPM และ Composer
├── src/                    # โค้ดโปรเจกต์ Laravel ทั้งหมด
├── docker-compose.yml      # ตั้งค่า Container (เชื่อมต่อ shared_db_net)
└── README.md               # คู่มือการติดตั้งและใช้งาน
```
