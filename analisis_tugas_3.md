Nama: Fadhil Nico Haryanto
Kelas: SI-48-09
NIM: 102022400335

Analisis Tugas 3: 
 
 Berdasarkan yang saya kerjakan saya akan memilih untuk transaksi yang kritis yaitu transactioncreated, yaitu proses pembuatan transaksi baru melalui endpoint POST/API/V1/Transactions.

 Alasan: 
 1. pemilihan tersebut berdasarkan pada karakteristik pembuatan transaksi adalah operasi inti dari beberapa service yang secara langsung itu adalah mengubah kondisi data sistem. dalam konteks service transaksi setiap transaksi yang dibuat untuk mempresentasikan perpindahan nilai / data dari pihak pengirim penerima (sender) atau disebut juga dengan (receiver) dengan nominal terentu. oleh karena itu,saya memilih proses ini tidak hanya bersifat administratif, tetapi juga memiliki konsekuensi terhadap integritas data, akuntabilitas sistem, serta keandalan proses bisnis secara keseluruhan.

 2. Aspek keamanan operasi pembuatan transaksi perlu dilindungi oleh mekanisme Federated Single Sign-On (SSO). Integrasi SSO memastikan bahwa akses terhadap sistem tidak hanya bergantung pada autentikasi lokal, tetapi juga diverifikasi melalui penyedia identitas terpusat. Pendekatan ini penting dalam arsitektur enterprise karena mendukung sentralisasi manajemen identitas, mengurangi duplikasi data pengguna, serta meningkatkan konsistensi kontrol akses antar layanan.

 3. Ketiga, dari aspek integrasi antar layanan, proses TransactionCreated perlu disebarkan melalui Message Broker RabbitMQ. Setelah transaksi dibuat, informasi tersebut berpotensi dibutuhkan oleh berbagai downstream services, seperti layanan notifikasi, pelaporan, analitik, monitoring, atau rekonsiliasi. Dengan menggunakan pola komunikasi berbasis event, sistem utama tidak perlu memanggil seluruh layanan lain secara langsung. Hal ini menciptakan arsitektur yang lebih loosely coupled, meningkatkan skalabilitas, serta mengurangi beban sinkron pada aplikasi utama.

 Berdasarkan pertimbangan tersebut, endpoint POST /api/v1/transactions dipilih sebagai transaksi kritis karena merepresentasikan proses bisnis utama yang berdampak langsung terhadap data finansial, membutuhkan validasi identitas melalui SSO, memerlukan pencatatan audit yang kuat, serta perlu didistribusikan ke sistem lain melalui message broker. Dengan demikian, implementasi ini tidak hanya memenuhi kebutuhan fungsional, tetapi juga mendukung kualitas arsitektur dari sisi keamanan, integritas data, skalabilitas, auditability, dan compliance.

 
B. Sequance Diagram
 ![alt text](<Sequence Diagram1.jpg>)













