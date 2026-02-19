// KMI Admin Dashboard API Integration
// File ini menghandle semua CRUD operations untuk admin panel

// ===== API HELPERS =====
const API_BASE = '/api';

async function apiCall(endpoint, method = 'GET', data = null) {
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    };

    if (data) {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(`${API_BASE}${endpoint}`, options);
        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        console.error('API Call Error:', error);
        throw error;
    }
}

async function uploadFile(endpoint, formData, method = 'POST') {
    // Add _method field untuk Laravel routing jika PUT/DELETE
    if (method === 'PUT' || method === 'DELETE') {
        formData.append('_method', method);
    }
    
    const options = {
        method: 'POST', // Always POST, Laravel router akan handle _method
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: formData
    };

    try {
        const response = await fetch(`${API_BASE}${endpoint}`, options);
        if (!response.ok) {
            throw new Error(`Upload error: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        console.error('Upload Error:', error);
        throw error;
    }
}

// ===== GALLERY CRUD =====
async function loadGalleries() {
    try {
        const galleries = await apiCall('/galleries');
        renderGalleryList(galleries);
    } catch (error) {
        showError('Gagal memuat gallery');
    }
}

function renderGalleryList(galleries) {
    const list = document.getElementById('galleryList');
    if (!galleries || galleries.length === 0) {
        list.innerHTML = '<div class="no-data"><i class="fas fa-image"></i><p>Belum ada gallery.</p></div>';
        return;
    }
    
    list.innerHTML = galleries.map(item => `
        <div class="item-card" data-id="${item.id}">
            <div class="item-image" style="background-color: #e0e0e0; height: 150px; border-radius: 8px 8px 0 0; overflow: hidden;">
                ${item.image_url ? `<img src="${item.image_url}?t=${Date.now()}" alt="${item.title}" style="width: 100%; height: 100%; object-fit: cover;">` : '<i class="fas fa-image" style="font-size: 48px; color: #999;"></i>'}
            </div>
            <div class="item-content">
                <h3>${item.title}</h3>
                <p>${(item.description || '').substring(0, 80)}...</p>
                <div class="item-actions">
                    <button class="btn-action btn-edit" onclick="editGalleryItem(${item.id})"><i class="fas fa-edit"></i> Edit</button>
                    <button class="btn-action btn-delete" onclick="deleteGalleryItem(${item.id})"><i class="fas fa-trash"></i> Hapus</button>
                </div>
            </div>
        </div>
    `).join('');
}

async function saveGallery(event) {
    event.preventDefault();
    const formData = new FormData();
    formData.append('title', document.getElementById('galleryTitle').value);
    formData.append('description', document.getElementById('galleryDesc').value);
    
    const imageFile = document.getElementById('galleryImage').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }

    try {
        const editId = document.getElementById('galleryForm').dataset.editId;
        let result;
        
        if (editId) {
            // UPDATE existing
            console.log('Updating gallery:', editId);
            result = await uploadFile(`/galleries/${editId}`, formData, 'PUT');
            showSuccess('Gallery berhasil diupdate');
            delete document.getElementById('galleryForm').dataset.editId;
        } else {
            // CREATE new
            console.log('Creating new gallery');
            result = await uploadFile('/galleries', formData, 'POST');
            showSuccess('Gallery berhasil ditambahkan');
        }
        
        await loadGalleries();
        toggleForm('galleryForm');
        event.target.reset();
    } catch (error) {
        showError('Gagal menyimpan gallery');
    }
}

async function editGalleryItem(id) {
    try {
        const gallery = await apiCall(`/galleries/${id}`);
        document.getElementById('galleryTitle').value = gallery.title;
        document.getElementById('galleryDesc').value = gallery.description;
        // Store ID for update
        document.getElementById('galleryForm').dataset.editId = id;
        toggleForm('galleryForm');
    } catch (error) {
        showError('Gagal memuat gallery');
    }
}

async function deleteGalleryItem(id) {
    if (!confirm('Yakin ingin menghapus?')) return;
    
    try {
        await apiCall(`/galleries/${id}`, 'DELETE');
        showSuccess('Gallery berhasil dihapus');
        await loadGalleries();
    } catch (error) {
        showError('Gagal menghapus gallery');
    }
}

// ===== PRODUCT CRUD =====
async function loadProducts() {
    try {
        const products = await apiCall('/products');
        renderProductList(products);
    } catch (error) {
        showError('Gagal memuat produk');
    }
}

function renderProductList(products) {
    const list = document.getElementById('productList');
    if (!products || products.length === 0) {
        list.innerHTML = '<div class="no-data"><i class="fas fa-box"></i><p>Belum ada produk.</p></div>';
        return;
    }
    
    list.innerHTML = products.map(item => `
        <div class="item-card" data-id="${item.id}">
            <div class="item-image" style="background-color: #e0e0e0; height: 150px; border-radius: 8px 8px 0 0; overflow: hidden;">
                ${item.image_url ? `<img src="${item.image_url}?t=${Date.now()}" alt="${item.name}" style="width: 100%; height: 100%; object-fit: cover;">` : '<i class="fas fa-box" style="font-size: 48px; color: #999;"></i>'}
            </div>
            <div class="item-content">
                <h3>${item.name}</h3>
                <p><strong>Kategori:</strong> ${item.category || '-'}</p>
                <p>${(item.description || '').substring(0, 60)}...</p>
                ${item.price ? `<p style="color: #d32f2f; font-weight: bold;">Rp ${formatCurrency(item.price)}</p>` : ''}
                <div class="item-actions">
                    <button class="btn-action btn-edit" onclick="editProductItem(${item.id})"><i class="fas fa-edit"></i> Edit</button>
                    <button class="btn-action btn-delete" onclick="deleteProductItem(${item.id})"><i class="fas fa-trash"></i> Hapus</button>
                </div>
            </div>
        </div>
    `).join('');
}

async function saveProduct(event) {
    event.preventDefault();
    const formData = new FormData();
    formData.append('name', document.getElementById('productNama').value);
    formData.append('description', document.getElementById('productDesc').value);
    formData.append('category', document.getElementById('productKategori').value);
    formData.append('price', document.getElementById('productHarga').value);
    
    const imageFile = document.getElementById('productGambar').files[0];
    if (imageFile) {
        formData.append('image', imageFile);
    }

    try {
        const editId = document.getElementById('productForm').dataset.editId;
        let result;
        
        if (editId) {
            // UPDATE existing
            console.log('Updating product:', editId);
            result = await uploadFile(`/products/${editId}`, formData, 'PUT');
            showSuccess('Produk berhasil diupdate');
            delete document.getElementById('productForm').dataset.editId;
        } else {
            // CREATE new
            console.log('Creating new product');
            result = await uploadFile('/products', formData, 'POST');
            showSuccess('Produk berhasil ditambahkan');
        }
        
        await loadProducts();
        toggleForm('productForm');
        event.target.reset();
    } catch (error) {
        showError('Gagal menyimpan produk');
    }
}

async function editProductItem(id) {
    try {
        const product = await apiCall(`/products/${id}`);
        document.getElementById('productNama').value = product.name;
        document.getElementById('productDesc').value = product.description;
        document.getElementById('productKategori').value = product.category || '';
        document.getElementById('productHarga').value = product.price || '';
        document.getElementById('productForm').dataset.editId = id;
        toggleForm('productForm');
    } catch (error) {
        showError('Gagal memuat produk');
    }
}

async function deleteProductItem(id) {
    if (!confirm('Yakin ingin menghapus?')) return;
    
    try {
        await apiCall(`/products/${id}`, 'DELETE');
        showSuccess('Produk berhasil dihapus');
        await loadProducts();
    } catch (error) {
        showError('Gagal menghapus produk');
    }
}

// ===== SPONSOR CRUD =====
async function loadSponsors() {
    try {
        const sponsors = await apiCall('/sponsors');
        renderSponsorList(sponsors);
    } catch (error) {
        showError('Gagal memuat sponsor');
    }
}

function renderSponsorList(sponsors) {
    const list = document.getElementById('sponsorList');
    if (!sponsors || sponsors.length === 0) {
        list.innerHTML = '<div class="no-data"><i class="fas fa-star"></i><p>Belum ada sponsor.</p></div>';
        return;
    }
    
    list.innerHTML = sponsors.map(item => `
        <div class="item-card" data-id="${item.id}">
            <div class="item-image" style="background-color: #f5f5f5; height: 150px; border-radius: 8px 8px 0 0; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                ${item.logo_url ? `<img src="${item.logo_url}?t=${Date.now()}" alt="${item.name}" style="max-width: 80%; max-height: 80%; object-fit: contain;">` : '<i class="fas fa-image" style="font-size: 48px; color: #999;"></i>'}
            </div>
            <div class="item-content">
                <h3>${item.name}</h3>
                ${item.description ? `<p>${item.description}</p>` : ''}
                <div class="item-actions">
                    <button class="btn-action btn-edit" onclick="editSponsorItem(${item.id})"><i class="fas fa-edit"></i> Edit</button>
                    <button class="btn-action btn-delete" onclick="deleteSponsorItem(${item.id})"><i class="fas fa-trash"></i> Hapus</button>
                </div>
            </div>
        </div>
    `).join('');
}

async function saveSponsor(event) {
    event.preventDefault();
    const formData = new FormData();
    formData.append('name', document.getElementById('sponsorNama').value);
    
    // Add description field
    const descField = document.getElementById('sponsorDesc');
    if (descField) {
        formData.append('description', descField.value || '');
    }
    
    formData.append('url', document.getElementById('sponsorUrl').value || '');
    
    const logoFile = document.getElementById('sponsorLogo').files[0];
    if (logoFile) {
        formData.append('logo', logoFile);
    }

    try {
        const sponsorForm = document.getElementById('sponsorForm');
        const editId = sponsorForm.dataset.editId;
        let result;
        
        if (editId) {
            // UPDATE existing
            console.log('Updating sponsor:', editId);
            result = await uploadFile(`/sponsors/${editId}`, formData, 'PUT');
            showSuccess('Sponsor berhasil diupdate');
            delete sponsorForm.dataset.editId;
        } else {
            // CREATE new
            console.log('Creating new sponsor');
            result = await uploadFile('/sponsors', formData, 'POST');
            showSuccess('Sponsor berhasil ditambahkan');
        }
        
        // Clear form
        event.target.reset();
        if (descField) descField.value = '';
        
        // Reload and close
        await loadSponsors();
        toggleForm('sponsorForm');
    } catch (error) {
        console.error('Error saving sponsor:', error);
        showError('Gagal menyimpan sponsor');
    }
}

async function editSponsorItem(id) {
    try {
        const sponsor = await apiCall(`/sponsors/${id}`);
        document.getElementById('sponsorNama').value = sponsor.name;
        document.getElementById('sponsorUrl').value = sponsor.url || '';
        // sponsorDesc field tidak ada di form, skip jika tidak ada element
        const descField = document.getElementById('sponsorDesc');
        if (descField) {
            descField.value = sponsor.description || '';
        }
        document.getElementById('sponsorForm').dataset.editId = id;
        toggleForm('sponsorForm');
    } catch (error) {
        console.error('Error loading sponsor:', error);
        showError('Gagal memuat sponsor');
    }
}

async function deleteSponsorItem(id) {
    if (!confirm('Yakin ingin menghapus?')) return;
    
    try {
        await apiCall(`/sponsors/${id}`, 'DELETE');
        showSuccess('Sponsor berhasil dihapus');
        await loadSponsors();
    } catch (error) {
        showError('Gagal menghapus sponsor');
    }
}

// ===== BENGKEL CRUD =====
async function loadBengkels() {
    try {
        const bengkels = await apiCall('/bengkels');
        renderBengkelList(bengkels);
    } catch (error) {
        showError('Gagal memuat bengkel');
    }
}

function renderBengkelList(bengkels) {
    const list = document.getElementById('bengkelList');
    if (!bengkels || bengkels.length === 0) {
        list.innerHTML = '<div class="no-data"><i class="fas fa-tools"></i><p>Belum ada bengkel.</p></div>';
        return;
    }
    
    // Group by province
    const groupedByProvince = {};
    bengkels.forEach(item => {
        const prov = item.province || 'Lainnya';
        if (!groupedByProvince[prov]) {
            groupedByProvince[prov] = [];
        }
        groupedByProvince[prov].push(item);
    });
    
    let html = '';
    Object.keys(groupedByProvince).sort().forEach(province => {
        html += `<h3 style="margin-top: 2rem; color: #E63946; border-bottom: 2px solid #E63946; padding-bottom: 0.5rem;">${province}</h3>`;
        html += groupedByProvince[province].map(item => `
            <div class="item-card" data-id="${item.id}">
                <div class="item-content">
                    <h4><i class="fas fa-store"></i> ${item.name}</h4>
                    <p><strong>Pemilik:</strong> ${item.owner}</p>
                    <p><strong>Kota:</strong> ${item.city}</p>
                    <p><strong>Alamat:</strong> ${item.address}</p>
                    <p><strong>Telepon:</strong> <a href="tel:${item.phone}">${item.phone}</a></p>
                    ${item.email ? `<p><strong>Email:</strong> ${item.email}</p>` : ''}
                    <div class="item-actions">
                        <button class="btn-action btn-edit" onclick="editBengkelItem(${item.id})"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn-action btn-delete" onclick="deleteBengkelItem(${item.id})"><i class="fas fa-trash"></i> Hapus</button>
                    </div>
                </div>
            </div>
        `).join('');
    });
    
    list.innerHTML = html;
}

async function saveBengkel(event) {
    event.preventDefault();
    const data = {
        name: document.getElementById('bengkelNama').value,
        owner: document.getElementById('bengkelPemilik').value,
        province: document.getElementById('bengkelProvinsi').value,
        city: document.getElementById('bengkelKota').value,
        address: document.getElementById('bengkelAlamat').value,
        phone: document.getElementById('bengkelTelepon').value,
        email: document.getElementById('bengkelEmail').value || null,
        description: document.getElementById('bengkelDeskripsi').value || null,
    };

    try {
        const editId = document.getElementById('bengkelForm').dataset.editId;
        let result;
        
        if (editId) {
            // UPDATE existing
            console.log('Updating bengkel:', editId);
            result = await apiCall(`/bengkels/${editId}`, 'PUT', data);
            showSuccess('Bengkel berhasil diupdate');
            delete document.getElementById('bengkelForm').dataset.editId;
        } else {
            // CREATE new
            console.log('Creating new bengkel');
            result = await apiCall('/bengkels', 'POST', data);
            showSuccess('Bengkel berhasil ditambahkan');
        }
        
        await loadBengkels();
        toggleForm('bengkelForm');
        event.target.reset();
    } catch (error) {
        showError('Gagal menyimpan bengkel');
    }
}

async function editBengkelItem(id) {
    try {
        const bengkel = await apiCall(`/bengkels/${id}`);
        document.getElementById('bengkelNama').value = bengkel.name;
        document.getElementById('bengkelPemilik').value = bengkel.owner;
        document.getElementById('bengkelProvinsi').value = bengkel.province;
        document.getElementById('bengkelKota').value = bengkel.city;
        document.getElementById('bengkelAlamat').value = bengkel.address;
        document.getElementById('bengkelTelepon').value = bengkel.phone;
        document.getElementById('bengkelEmail').value = bengkel.email || '';
        document.getElementById('bengkelDeskripsi').value = bengkel.description || '';
        document.getElementById('bengkelForm').dataset.editId = id;
        toggleForm('bengkelForm');
    } catch (error) {
        showError('Gagal memuat bengkel');
    }
}

async function deleteBengkelItem(id) {
    if (!confirm('Yakin ingin menghapus?')) return;
    
    try {
        await apiCall(`/bengkels/${id}`, 'DELETE');
        showSuccess('Bengkel berhasil dihapus');
        await loadBengkels();
    } catch (error) {
        showError('Gagal menghapus bengkel');
    }
}

// ===== UTILITY FUNCTIONS =====
function toggleForm(formId) {
    const form = document.getElementById(formId);
    form.classList.toggle('hidden');
}

function showSuccess(message) {
    alert(message);
}

function showError(message) {
    alert('Error: ' + message);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'decimal',
        minimumFractionDigits: 0
    }).format(amount);
}

function updateKotaOptions() {
    const provinsi = document.getElementById('bengkelProvinsi').value;
    const kotaSelect = document.getElementById('bengkelKota');
    
    kotaSelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
    
    // Menggunakan API untuk get cities
    if (provinsi) {
        fetch(`/api/cities-by-province/${provinsi}`)
            .then(r => r.json())
            .then(cities => {
                cities.forEach(city => {
                    const opt = document.createElement('option');
                    opt.value = city;
                    opt.textContent = city;
                    kotaSelect.appendChild(opt);
                });
            });
    }
}

// ===== INITIALIZE =====
document.addEventListener('DOMContentLoaded', function() {
    loadGalleries();
    loadProducts();
    loadSponsors();
    loadBengkels();
});
