<<<<<<< HEAD
Fokus utama tugas ini adalah mengintegrasikan transaksi kritis dengan sistem keamanan terpusat melalui Federated SSO, melakukan pencatatan audit ke sistem Legacy SOAP/XML, serta mendistribusikan event bisnis secara asinkron melalui RabbitMQ.

Saya membutuhkan bantuan untuk mengembangkan endpoint `POST /api/v1/transactions` sebagai transaksi kritis karena endpoint tersebut merupakan state-changing operation yang membuat data transaksi baru dan berdampak langsung terhadap integritas data bisnis. Implementasi harus memastikan bahwa proses transaksi tidak hanya tersimpan di database lokal, tetapi juga tervalidasi melalui SSO, tercatat pada sistem audit pusat, dan dipublikasikan sebagai event `TransactionCreated` ke message broker.

Tolong susun solusi secara lengkap menggunakan Laravel dengan pendekatan Service Layer agar kode lebih modular, terstruktur, dan maintainable. Solusi harus mencakup integrasi SSO Machine-to-Machine menggunakan API Key, integrasi SSO Warga menggunakan akun warga, pembacaan payload JWT, pemetaan user dan role lokal, penyimpanan transaksi ke database, pengiriman audit SOAP ke endpoint `/soap/v1/audit`, serta publish event JSON ke endpoint `/api/v1/messages/publish`.
=======

## Tugas 2 — Build Your Service
## Mata Kuliah Integrasi Aplikasi Enterprise

---

### Prompt 1 — Analisis Requirement Tugas
Analisis dokumen Tugas 2 Build Your Service dan Standard Integration Contract. Identifikasi seluruh kebutuhan teknis yang wajib diimplementasikan pada backend service berbasis enterprise integration, meliputi:
- REST API
- format response JSON
- API Key Authentication
- Swagger/OpenAPI Documentation
- GraphQL Query
- Docker deployment
- standar endpoint API

Jelaskan juga hubungan tiap requirement terhadap konsep integrasi aplikasi enterprise.

---

### Prompt 2 — Perancangan Arsitektur Service
Bantu saya merancang arsitektur backend Mini-Service menggunakan Laravel sebagai service provider untuk sistem enterprise. Service harus mampu mendukung komunikasi data antar aplikasi menggunakan protokol HTTP dan format JSON sesuai integration contract.

---

### Prompt 3 — Perancangan Resource dan Endpoint
Bantu saya menentukan resource dan endpoint REST API yang sesuai untuk Inventory Service dengan standar RESTful API enterprise.

Ketentuan endpoint:
- GET /api/v1/items
- GET /api/v1/items/{id}
- POST /api/v1/items

Jelaskan fungsi masing-masing endpoint dan metode HTTP yang digunakan.

---

### Prompt 4 — Standard JSON Response
Bantu saya membuat standard response wrapper sesuai Standard Integration Contract untuk response sukses dan response error pada Laravel API.

Gunakan struktur:
- status
- message
- data
- meta
- errors

Pastikan struktur response konsisten untuk seluruh endpoint API.

---

### Prompt 5 — Implementasi Middleware Authentication
Bantu saya membuat middleware authentication menggunakan API Key pada Laravel dengan request header:
X-IAE-KEY

Middleware harus:
- memvalidasi API Key
- menolak request unauthorized
- mengembalikan response JSON sesuai integration contract

---

### Prompt 6 — Desain Database dan Migration
Bantu saya membuat desain database untuk Inventory Service menggunakan Laravel migration.

Field tabel:
- id
- item_name
- stock
- price
- created_at
- updated_at

Gunakan struktur yang sesuai dengan praktik backend modern dan mudah diintegrasikan dengan service lain.

---

### Prompt 7 — Implementasi REST Controller
Bantu saya membuat REST controller Laravel untuk:
- menampilkan seluruh data item
- menampilkan detail item berdasarkan id
- menambahkan item baru

Gunakan:
- Eloquent ORM
- JSON response standard
- status code HTTP yang sesuai
- clean code structure

---

### Prompt 8 — Validasi Request Data
Bantu saya membuat validasi request pada endpoint POST menggunakan Laravel validation agar data input lebih aman, konsisten, dan sesuai kebutuhan sistem enterprise.

Validasi meliputi:
- required field
- numeric validation
- minimum value
- custom error response JSON

---

### Prompt 9 — Implementasi Swagger Documentation
Bantu saya mengintegrasikan Swagger/OpenAPI menggunakan L5-Swagger pada Laravel.

Buatkan dokumentasi endpoint untuk:
- GET all items
- GET item by id
- POST new item

Tambahkan:
- parameter request
- response example
- authentication header
- status code documentation

---

### Prompt 10 — Implementasi GraphQL
Bantu saya mengintegrasikan GraphQL menggunakan Lighthouse pada Laravel.

Buatkan minimal 1 query untuk mengambil data item dengan fleksibilitas field selection sesuai konsep GraphQL.

Pastikan query dapat mengakses data yang sama dengan REST API.

---

### Prompt 11 — Konfigurasi GraphQL Playground
Bantu saya mengaktifkan GraphQL Playground pada Laravel agar query dapat diuji secara langsung melalui browser untuk kebutuhan pengujian integrasi service.

---

### Prompt 12 — Konfigurasi Docker
Bantu saya membuat konfigurasi Docker dan docker-compose untuk menjalankan Laravel API beserta MySQL database.

Pastikan:
- service dapat dijalankan menggunakan container
- environment configuration terpisah
- API dapat diakses melalui browser/postman

---

### Prompt 13 — Pengujian Endpoint API
Bantu saya membuat skenario pengujian REST API menggunakan Postman untuk memastikan:
- endpoint berjalan normal
- status code sesuai
- response JSON valid
- authentication berhasil
- validasi error berjalan dengan benar

---

>>>>>>> 2d3a04638b2499e38ca6897529c1c4a8fa88b97a

