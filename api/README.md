# ☕ Kopi Store RESTful API Documentation

Dokumentasi lengkap untuk Routing dan API CRUD (Create, Read, Update, Delete) pada aplikasi Kopi.

## 🚀 Base URL
`http://localhost/kopi/api/index.php`

---

## 🛠️ Modul Resource & Endpoints

### 1. Produk Kopi (`resource=produk`)

| Method | Endpoint Parameter | Deskripsi | Payload (Body / Query) |
| :--- | :--- | :--- | :--- |
| `GET` | `?resource=produk` | Mengambil seluruh daftar produk | Query optional: `search`, `jenis` |
| `GET` | `?resource=produk&id={id}` | Mengambil detail 1 produk berdasarkan ID | - |
| `POST` | `?resource=produk` | Menambah produk baru | JSON / Form-Data: `nama_kopi`, `jenis_kopi`, `stok`, `harga`, `berat` (max 1000g), `deskripsi`, `gambar` (file) |
| `PUT` | `?resource=produk&id={id}` | Memperbarui data produk | JSON / Form-Data: `nama_kopi`, `jenis_kopi`, `stok`, `harga`, `berat`, `deskripsi` |
| `DELETE` | `?resource=produk&id={id}` | Menghapus produk berdasarkan ID | - |

---

### 2. Pelanggan (`resource=pelanggan`)

| Method | Endpoint Parameter | Deskripsi | Payload (Body) |
| :--- | :--- | :--- | :--- |
| `GET` | `?resource=pelanggan` | Mengambil seluruh data pelanggan | - |
| `GET` | `?resource=pelanggan&id={id}` | Mengambil detail 1 pelanggan | - |
| `POST` | `?resource=pelanggan` | Mendaftarkan pelanggan baru | JSON: `username`, `email`, `password`, `no_hp`, `alamat` |
| `PUT` | `?resource=pelanggan&id={id}` | Memperbarui data pelanggan | JSON: `username`, `email`, `no_hp`, `alamat`, `password` (opsional) |
| `DELETE` | `?resource=pelanggan&id={id}` | Menghapus pelanggan | - |

---

### 3. Transaksi / Pesanan (`resource=transaksi`)

| Method | Endpoint Parameter | Deskripsi | Payload (Body / Query) |
| :--- | :--- | :--- | :--- |
| `GET` | `?resource=transaksi` | Mengambil daftar semua pesanan | Query optional: `id_pelanggan`, `status` |
| `GET` | `?resource=transaksi&id={id}` | Detail pesanan + items | - |
| `POST` | `?resource=transaksi` | Membuat pesanan baru | JSON: `id_pelanggan`, `total_harga`, `metode_pembayaran`, `status_pesanan`, `status_pembayaran` |
| `PUT` | `?resource=transaksi&id={id}` | Memperbarui status pesanan | JSON: `status_pesanan`, `status_pembayaran`, `metode_pembayaran` |
| `DELETE` | `?resource=transaksi&id={id}` | Menghapus pesanan | - |

---

## 💻 Contoh Penggunaan JavaScript (`fetch`)

### 1. GET (Fetch All Products)
```javascript
fetch('http://localhost/kopi/api/index.php?resource=produk')
  .then(response => response.json())
  .then(result => console.log(result))
  .catch(error => console.error('Error:', error));
```

### 2. POST (Tambah Produk Baru)
```javascript
fetch('http://localhost/kopi/api/index.php?resource=produk', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    nama_kopi: 'Kopi Arabika Ruteng',
    jenis_kopi: 'Arabika',
    stok: 25,
    harga: 45000,
    berat: 250, // gram (maksimal 1000g)
    deskripsi: 'Kopi khas Manggarai dengan aroma floral dan acitidy seimbang.'
  })
})
.then(response => response.json())
.then(result => console.log(result));
```

### 3. PUT (Update Data Produk)
```javascript
fetch('http://localhost/kopi/api/index.php?resource=produk&id=1', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    stok: 30,
    harga: 48000
  })
})
.then(response => response.json())
.then(result => console.log(result));
```

### 4. DELETE (Hapus Produk)
```javascript
fetch('http://localhost/kopi/api/index.php?resource=produk&id=1', {
  method: 'DELETE'
})
.then(response => response.json())
.then(result => console.log(result));
```

---

## 📋 Contoh Respon JSON

```json
{
    "status": true,
    "code": 200,
    "message": "Data produk berhasil diambil.",
    "data": {
        "total": 1,
        "items": [
            {
                "id_produk": 1,
                "nama_kopi": "Kopi Arabika Ruteng",
                "jenis_kopi": "Arabika",
                "stok": 25,
                "harga": 45000,
                "berat": 250,
                "status": "Tersedia",
                "url_foto": "assets/images/produk/prod_66a7b.jpg"
            }
        ]
    }
}
```
