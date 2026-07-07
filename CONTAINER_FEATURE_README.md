# 📦 FITUR CONTAINER MANAGEMENT - DOKUMENTASI

## ✅ Yang Sudah Dibuat

### 1. Database Structure
- **Table baru**: `order_containers` - Menyimpan detail setiap container
- **Fields baru di import_orders & export_orders**:
  - `container_size` - Jenis kontainer (20', 40', LCL)
  - `container_quantity` - Jumlah kontainer

### 2. Database Schema: `order_containers`

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| order_id | bigint | Foreign key ke `orders` table |
| order_type | enum | 'import' atau 'export' |
| container_size | string | 20', 40', LCL |
| container_number | string | No kontainer (abc123, qwerty, dll) |
| container_type | string | GP, OT, HC, RF, dll |
| vendor | string | Nama vendor |
| combo_with | bigint | Foreign key ke container lain (same order, same type) |
| combine_with | string | Format: order_number/container_number |

---

## 🎯 Fitur Utama

### 1. **LCL Type**
- Untuk LCL: hanya perlu input **Jenis Kontainer** (LCL), **Jumlah**, dan **Vendor**
- Type Container, Combo, dan Combine **tidak diperlukan** untuk LCL

**Contoh:**
```
Order: 25768/IMP/003
Jenis Container: LCL
Jumlah: 5
Vendor: Hendri
```

### 2. **20' / 40' Type dengan Detail Container**
- Setiap container punya detail lengkap:
  - **No Container**: Nomor unik container
  - **Type Container**: GP, OT, HC, RF
  - **Vendor**: Nama vendor
  - **Combo**: Bisa combo dengan container lain yang type-nya **sama**
  - **Combine**: Bisa combine dengan container dari order lain

**Contoh:**
```
Order: 25768/IMP/001
Jenis: 20'
Jumlah: 5 kontainer

Container #1:
- No: abc123
- Type: GP
- Vendor: Trucking Sendiri
- Combo: 6789 (combo dengan container #3)
- Combine: -

Container #2:
- No: bcd123
- Type: OT
- Vendor: BI
- Combo: -
- Combine: -

Container #3:
- No: 6789
- Type: GP
- Vendor: BI
- Combo: abc123 (combo dengan container #1)
- Combine: -
```

### 3. **Combo Feature**
- **Apa itu Combo?**
  - Menggabungkan 2 container **dalam order yang sama**
  - Hanya bisa combo dengan container yang **type-nya sama** (GP dengan GP, OT dengan OT)
  
- **Cara Pakai:**
  - Di dropdown Combo, pilih nomor container yang mau di-combo
  - Dropdown otomatis hanya menampilkan container yang type-nya sama

### 4. **Combine Feature**
- **Apa itu Combine?**
  - Menggabungkan container dengan container dari **order lain**
  
- **Cara Pakai:**
  - Input format: `[No Order]/[No Container]`
  - Contoh: `25768/IMP/001/qwerty`

**Contoh Combine:**
```
Order: 25768/IMP/002
Container #1:
- No: qwerty
- Type: GP
- Vendor: ASST
- Combine: 25768/IMP/001/qwerty
  (Combine dengan container 'qwerty' dari order 25768/IMP/001)
```

---

## 🔧 Model & Relasi

### OrderContainer Model
```php
// Relasi ke Order
$container->order

// Relasi ke Container yang di-combo
$container->comboContainer

// Containers yang combo dengan container ini
$container->comboCombinations

// Get combo container number
$container->getComboContainerNumberAttribute()
```

### Order Model
```php
// Get all containers untuk order ini
$order->containers

// Filter by type
$order->containers()->where('container_type', 'GP')->get()
```

### ImportOrder / ExportOrder Model
```php
// Get containers untuk import/export order
$importOrder->containers
$exportOrder->containers
```

---

## 📋 Cara Penggunaan

### 1. Create New Order
1. Pilih **Jenis Kontainer** (20', 40', LCL)
2. Input **Jumlah Kontainer**
3. **Jika LCL**:
   - Input vendor saja
   - Submit form
4. **Jika 20' / 40'**:
   - Form akan auto-generate input untuk setiap container
   - Isi detail untuk setiap container:
     - No Container
     - Type Container
     - Vendor
     - Combo (opsional)
     - Combine (opsional)
   - Submit form

### 2. Edit Existing Order
- Form akan load existing containers
- Bisa ubah jumlah container (akan auto tambah/kurang input fields)
- Bisa ubah detail setiap container
- Save changes

### 3. View/Delete Order
- Saat view order, akan tampil semua container details
- Delete order akan otomatis delete semua containers terkait (cascade delete)

---

## 🧪 Validation Rules

### Container Size & Quantity
```php
'container_size' => 'nullable|string'
'container_quantity' => 'nullable|integer|min:1'
```

### Per Container (Non-LCL)
```php
'containers.*.number' => 'required|string'
'containers.*.type' => 'required|string'
'containers.*.vendor' => 'required|string'
'containers.*.combo' => 'nullable|string'
'containers.*.combine' => 'nullable|string'
```

---

## 🎨 Frontend Component

### Component: `x-container-input`
**Location**: `resources/views/components/container-input.blade.php`

**Props**:
- `:order` - Order object (untuk edit mode)
- `:existingContainers` - Array of existing containers (untuk edit mode)

**Usage:**
```blade
<x-container-input :order="null" :existingContainers="[]" />
```

**Features**:
- Dynamic container inputs (auto tambah/kurang based on quantity)
- Alpine.js powered
- Combo dropdown auto-filter by container type
- Validation client-side

---

## 📂 Files Modified/Created

### Migrations
- ✅ `2026_06_30_000001_create_order_containers_table.php`
- ✅ `2026_06_30_000002_add_container_fields_to_orders.php`

### Models
- ✅ `app/Models/OrderContainer.php` (NEW)
- ✅ `app/Models/Order.php` (Updated - added containers relation)
- ✅ `app/Models/ImportOrder.php` (Updated - added containers relation & fillable)
- ✅ `app/Models/ExportOrder.php` (Updated - added containers relation & fillable)

### Controllers
- ✅ `app/Http/Controllers/OrderController.php` (Updated)
  - `storeImport()` - Handle container save
  - `updateImport()` - Handle container update
  - `storeExport()` - Handle container save
  - `updateExport()` - Handle container update
  - `saveContainers()` - Helper method (NEW)

### Views
- ✅ `resources/views/components/container-input.blade.php` (NEW)
- ✅ `resources/views/orders/import/create.blade.php` (Updated)
- 🔄 `resources/views/orders/import/edit.blade.php` (TODO)
- 🔄 `resources/views/orders/export/create.blade.php` (TODO)
- 🔄 `resources/views/orders/export/edit.blade.php` (TODO)

---

## 🚀 Next Steps

### Yang Perlu Dilakukan:

1. **Update View Edit Forms**
   - [ ] `orders/import/edit.blade.php` - Add container component
   - [ ] `orders/export/edit.blade.php` - Add container component

2. **Update Index/Show Pages**
   - [ ] `orders/index.blade.php` - Display container count
   - [ ] Show container details in order detail page

3. **Testing**
   - [ ] Test create order dengan LCL
   - [ ] Test create order dengan 20'/40' + multiple containers
   - [ ] Test combo feature
   - [ ] Test combine feature
   - [ ] Test edit order (tambah/kurang container)
   - [ ] Test delete order (cascade delete containers)

4. **Optional Enhancements**
   - [ ] Add validation untuk format combine (regex)
   - [ ] Add search/autocomplete untuk combine field
   - [ ] Add container tracking page
   - [ ] Export containers to Excel/PDF
   - [ ] Container statistics/dashboard

---

## 🐛 Troubleshooting

### Issue: Migration Failed
**Solution**: 
```bash
php artisan migrate:rollback --step=2
php artisan migrate
```

### Issue: Containers Not Saving
**Check**:
1. `order_id` ada di ImportOrder/ExportOrder?
2. Form input name format: `containers[0][number]`, `containers[0][type]`, dll
3. JavaScript working? Check browser console

### Issue: Combo Dropdown Empty
**Check**:
1. Ada container lain yang **type-nya sama**?
2. Container lain sudah punya **No Container**?
3. Alpine.js loaded?

---

## 📞 Support

Untuk pertanyaan atau bug report, contact developer.

---

**Last Updated**: 2026-06-30
**Version**: 1.0.0
