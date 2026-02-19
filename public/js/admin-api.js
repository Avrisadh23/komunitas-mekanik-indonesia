// KMI Admin Dashboard API Integration V2
// Improved CRUD operations dengan better error handling dan logging

// ===== API HELPERS =====
const API_BASE = '/admin/api'; // FIXED: Must be /admin/api for admin routes

async function apiCall(endpoint, method = 'GET', data = null) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    console.log(`[API] ${method} ${endpoint}`, { csrfToken: !!csrfToken });
    
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    };

    if (data) {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(`${API_BASE}${endpoint}`, options);
        const responseText = await response.text();
        
        console.log(`[API] Response Status: ${response.status}`, responseText.substring(0, 100));
        
        if (!response.ok) {
            console.error(`[API] Error ${response.status}:`, responseText);
            throw new Error(`API error: ${response.status} - ${responseText}`);
        }
        
        const result = JSON.parse(responseText);
        console.log(`[API] Success:`, result);
        return result;
    } catch (error) {
        console.error(`[API] Call Error:`, error);
        showError(`Gagal: ${error.message}`);
        throw error;
    }
}

async function uploadFile(endpoint, formData, method = 'POST') {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    
    // Add _method field untuk Laravel routing jika PUT/DELETE
    if (method === 'PUT' || method === 'DELETE') {
        formData.append('_method', method);
    }
    
    console.log(`[UPLOAD] ${method} ${endpoint}`);
    
    const options = {
        method: 'POST', // Always POST, Laravel router akan handle _method
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        body: formData
    };

    try {
        const response = await fetch(`${API_BASE}${endpoint}`, options);
        const responseText = await response.text();
        
        console.log(`[UPLOAD] Response Status: ${response.status}`, responseText.substring(0, 200));
        
        if (!response.ok) {
            console.error(`[UPLOAD] Error ${response.status}:`, responseText);
            throw new Error(`Upload error: ${response.status}`);
        }
        
        const result = JSON.parse(responseText);
        console.log(`[UPLOAD] Success:`, result);
        return result;
    } catch (error) {
        console.error(`[UPLOAD] Error:`, error);
        showError(`Upload gagal: ${error.message}`);
        throw error;
    }
}

// ===== GALLERY CRUD =====
async function loadGalleries() {
    try {
        console.log('[Gallery] Loading...');
        const galleries = await apiCall('/galleries');
        console.log('[Gallery] Loaded:', galleries);
        renderGalleryList(galleries);
    } catch (error) {
        console.error('[Gallery] Load failed:', error);
        showError('Gagal memuat gallery');
    }
}

function renderGalleryList(galleries) {
    const list = document.getElementById('galleryList');
    if (!galleries || galleries.length === 0) {
        list.innerHTML = '<div class="no-data"><i class="fas fa-image"></i><p>Belum ada gallery.</p></div>';
        return;
    }
    
    console.log('[Gallery] Rendering', galleries.length, 'items');
    
    list.innerHTML = galleries.map((item, idx) => {
        let imageUrl = resolveImageUrl(item.image_url);
        if (imageUrl) {
            imageUrl += (imageUrl.includes('?') ? '&' : '?') + `t=${Date.now()}`;
        }
        console.log('[Gallery] Item ' + idx + ':', { id: item.id, title: item.title, imageUrl: imageUrl, image_path: item.image_path });
        
        // Create unique ID for error tracking
        const imgErrorId = 'img_' + item.id;
        
        return `
        <div class="item-card" data-id="${item.id}">
            <div class="item-image" style="background-color: #e0e0e0; height: 150px; border-radius: 8px 8px 0 0; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                ${imageUrl ? `
                    <img 
                        id="${imgErrorId}"
                        src="${imageUrl}" 
                        alt="${item.title}" 
                        style="width: 100%; height: 100%; object-fit: cover; display: block;"
                        onerror='var el = document.getElementById("${imgErrorId}"); if (el) { el.style.display = "none"; el.parentElement.innerHTML = "<i class=\\"fas fa-image\\" style=\\"font-size: 48px; color: #999;\\"></i>"; console.error("[Gallery] Image failed for item ${item.id}"); }'
                    >
                ` : '<i class="fas fa-image" style="font-size: 48px; color: #999;"></i>'}
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
        `;
    }).join('');
}

async function saveGallery(event) {
    event.preventDefault();
    
    const title = document.getElementById('galleryTitle').value;
    const description = document.getElementById('galleryDesc').value;
    const imageFile = document.getElementById('galleryImage').files[0];
    
    console.log('[Gallery] Saving:', { title, description, hasImage: !!imageFile });
    
    const formData = new FormData();
    formData.append('title', title);
    formData.append('description', description);
    
    if (imageFile) {
        formData.append('image', imageFile);
        console.log('[Gallery] Image file:', imageFile.name, imageFile.size);
    }

    try {
        const sponsorForm = document.getElementById('galleryForm');
        const editId = sponsorForm.dataset.editId;
        let result;
        
        if (editId) {
            console.log('[Gallery] Updating ID:', editId);
            result = await uploadFile(`/galleries/${editId}`, formData, 'PUT');
            showSuccess('Gallery berhasil diupdate');
            delete sponsorForm.dataset.editId;
        } else {
            console.log('[Gallery] Creating new');
            result = await uploadFile('/galleries', formData, 'POST');
            showSuccess('Gallery berhasil ditambahkan');
        }
        
        await loadGalleries();
        toggleForm('galleryForm');
        event.target.reset();
    } catch (error) {
        console.error('[Gallery] Save failed:', error);
        showError('Gagal menyimpan gallery');
    }
}

async function editGalleryItem(id) {
    try {
        console.log('[Gallery] Loading ID:', id);
        const gallery = await apiCall(`/galleries/${id}`);
        console.log('[Gallery] Loaded item:', gallery);
        
        document.getElementById('galleryTitle').value = gallery.title;
        document.getElementById('galleryDesc').value = gallery.description;
        document.getElementById('galleryForm').dataset.editId = id;
        toggleForm('galleryForm');
    } catch (error) {
        console.error('[Gallery] Edit failed:', error);
        showError('Gagal memuat gallery');
    }
}

async function deleteGalleryItem(id) {
    if (!confirm('Yakin ingin menghapus?')) return;
    
    try {
        console.log('[Gallery] Deleting ID:', id);
        await apiCall(`/galleries/${id}`, 'DELETE');
        showSuccess('Gallery berhasil dihapus');
        await loadGalleries();
    } catch (error) {
        console.error('[Gallery] Delete failed:', error);
        showError('Gagal menghapus gallery');
    }
}

// ===== PRODUCT CRUD =====
async function loadProducts() {
    try {
        console.log('[Product] Loading...');
        const products = await apiCall('/products');
        console.log('[Product] Loaded:', products);
        renderProductList(products);
    } catch (error) {
        console.error('[Product] Load failed:', error);
        showError('Gagal memuat produk');
    }
}

function renderProductList(products) {
    const list = document.getElementById('productList');
    if (!products || products.length === 0) {
        list.innerHTML = '<div class="no-data"><i class="fas fa-box"></i><p>Belum ada produk.</p></div>';
        return;
    }
    
    console.log('[Product] Rendering', products.length, 'items');
    
    list.innerHTML = products.map((item, idx) => {
        let imageUrl = resolveImageUrl(item.image_url);
        if (imageUrl) {
            imageUrl += (imageUrl.includes('?') ? '&' : '?') + `t=${Date.now()}`;
        }
        const imgErrorId = 'prod_img_' + item.id;
        
        return `
        <div class="item-card" data-id="${item.id}">
            <div class="item-image" style="background-color: #e0e0e0; height: 150px; border-radius: 8px 8px 0 0; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                ${imageUrl ? `
                    <img 
                        id="${imgErrorId}"
                        src="${imageUrl}" 
                        alt="${item.name}" 
                        style="width: 100%; height: 100%; object-fit: cover; display: block;"
                        onerror='var el = document.getElementById("${imgErrorId}"); if (el) { el.style.display = "none"; el.parentElement.innerHTML = "<i class=\\"fas fa-box\\" style=\\"font-size: 48px; color: #999;\\"></i>"; console.error("[Product] Image failed for item ${item.id}"); }'
                    >
                ` : '<i class="fas fa-box" style="font-size: 48px; color: #999;"></i>'}
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
        `;
    }).join('');
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
            result = await uploadFile(`/products/${editId}`, formData, 'PUT');
            showSuccess('Produk berhasil diupdate');
            delete document.getElementById('productForm').dataset.editId;
        } else {
            result = await uploadFile('/products', formData, 'POST');
            showSuccess('Produk berhasil ditambahkan');
        }
        
        await loadProducts();
        toggleForm('productForm');
        event.target.reset();
    } catch (error) {
        console.error('[Product] Save failed:', error);
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
        console.error('[Product] Edit failed:', error);
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
        console.error('[Product] Delete failed:', error);
        showError('Gagal menghapus produk');
    }
}

// ===== SPONSOR CRUD =====
async function loadSponsors() {
    try {
        console.log('[Sponsor] Loading...');
        const sponsors = await apiCall('/sponsors');
        console.log('[Sponsor] Loaded:', sponsors);
        renderSponsorList(sponsors);
    } catch (error) {
        console.error('[Sponsor] Load failed:', error);
        showError('Gagal memuat sponsor');
    }
}

function renderSponsorList(sponsors) {
    const list = document.getElementById('sponsorList');
    if (!sponsors || sponsors.length === 0) {
        list.innerHTML = '<div class="no-data"><i class="fas fa-star"></i><p>Belum ada sponsor.</p></div>';
        return;
    }
    
    console.log('[Sponsor] Rendering', sponsors.length, 'items');
    
    list.innerHTML = sponsors.map((item, idx) => {
        let logoUrl = resolveImageUrl(item.logo_url);
        if (logoUrl) {
            logoUrl += (logoUrl.includes('?') ? '&' : '?') + `t=${Date.now()}`;
        }
        const imgErrorId = 'sponsor_img_' + item.id;
        
        return `
        <div class="item-card" data-id="${item.id}">
            <div class="item-image" style="background-color: #f5f5f5; height: 150px; border-radius: 8px 8px 0 0; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                ${logoUrl ? `
                    <img 
                        id="${imgErrorId}"
                        src="${logoUrl}" 
                        alt="${item.name}" 
                        style="max-width: 80%; max-height: 80%; object-fit: contain; display: block;"
                        onerror='var el = document.getElementById("${imgErrorId}"); if (el) { el.style.display = "none"; el.parentElement.innerHTML = "<i class=\\"fas fa-image\\" style=\\"font-size: 48px; color: #999;\\"></i>"; console.error("[Sponsor] Image failed for item ${item.id}"); }'
                    >
                ` : '<i class="fas fa-image" style="font-size: 48px; color: #999;"></i>'}
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
        `;
    }).join('');
}

async function saveSponsor(event) {
    event.preventDefault();
    const formData = new FormData();
    formData.append('name', document.getElementById('sponsorNama').value);
    formData.append('url', document.getElementById('sponsorUrl').value || '');
    
    const descField = document.getElementById('sponsorDesc');
    if (descField) {
        formData.append('description', descField.value || '');
    }
    
    const logoFile = document.getElementById('sponsorLogo').files[0];
    if (logoFile) {
        formData.append('logo', logoFile);
    }

    try {
        const sponsorForm = document.getElementById('sponsorForm');
        const editId = sponsorForm.dataset.editId;
        let result;
        
        if (editId) {
            result = await uploadFile(`/sponsors/${editId}`, formData, 'PUT');
            showSuccess('Sponsor berhasil diupdate');
            delete sponsorForm.dataset.editId;
        } else {
            result = await uploadFile('/sponsors', formData, 'POST');
            showSuccess('Sponsor berhasil ditambahkan');
        }
        
        event.target.reset();
        if (descField) descField.value = '';
        
        await loadSponsors();
        toggleForm('sponsorForm');
    } catch (error) {
        console.error('[Sponsor] Save failed:', error);
        showError('Gagal menyimpan sponsor');
    }
}

async function editSponsorItem(id) {
    try {
        const sponsor = await apiCall(`/sponsors/${id}`);
        document.getElementById('sponsorNama').value = sponsor.name;
        document.getElementById('sponsorUrl').value = sponsor.url || '';
        const descField = document.getElementById('sponsorDesc');
        if (descField) {
            descField.value = sponsor.description || '';
        }
        document.getElementById('sponsorForm').dataset.editId = id;
        toggleForm('sponsorForm');
    } catch (error) {
        console.error('[Sponsor] Edit failed:', error);
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
        console.error('[Sponsor] Delete failed:', error);
        showError('Gagal menghapus sponsor');
    }
}

// ===== BENGKEL CRUD =====
// Update kota options based on selected province
const INDONESIA_CITIES = {
    'Aceh': ['Banda Aceh', 'Langsa', 'Lhokseumawe', 'Sabang', 'Subulussalam', 'Aceh Barat', 'Aceh Besar', 'Pidie'],
    'Sumatera Utara': ['Medan', 'Binjai', 'Pematangsiantar', 'Tebing Tinggi', 'Sibolga', 'Tanjungbalai', 'Gunungsitoli', 'Padangsidimpuan', 'Deli Serdang'],
    'Sumatera Barat': ['Padang', 'Bukittinggi', 'Payakumbuh', 'Pariaman', 'Padang Panjang', 'Sawahlunto', 'Solok', 'Agam', 'Tanah Datar'],
    'Riau': ['Pekanbaru', 'Dumai', 'Kampar', 'Bengkalis', 'Indragiri Hilir', 'Indragiri Hulu', 'Pelalawan', 'Rokan Hilir', 'Rokan Hulu', 'Siak'],
    'Kepulauan Riau': ['Tanjungpinang', 'Batam', 'Bintan', 'Karimun', 'Natuna', 'Lingga', 'Kepulauan Anambas'],
    'Jambi': ['Jambi', 'Sungai Penuh', 'Batanghari', 'Bungo', 'Kerinci', 'Merangin', 'Muaro Jambi', 'Sarolangun', 'Tanjung Jabung Barat', 'Tanjung Jabung Timur', 'Tebo'],
    'Bengkulu': ['Bengkulu', 'Bengkulu Selatan', 'Bengkulu Tengah', 'Bengkulu Utara', 'Kaur', 'Kepahiang', 'Lebong', 'Mukomuko', 'Rejang Lebong', 'Seluma'],
    'Sumatera Selatan': ['Palembang', 'Prabumulih', 'Lubuklinggau', 'Pagar Alam', 'Banyuasin', 'Empat Lawang', 'Lahat', 'Muara Enim', 'Musi Banyuasin', 'Musi Rawas', 'Ogan Ilir', 'Ogan Komering Ilir', 'Ogan Komering Ulu'],
    'Kepulauan Bangka Belitung': ['Pangkalpinang', 'Bangka', 'Bangka Barat', 'Bangka Selatan', 'Bangka Tengah', 'Belitung', 'Belitung Timur'],
    'Lampung': ['Bandar Lampung', 'Metro', 'Lampung Barat', 'Lampung Selatan', 'Lampung Tengah', 'Lampung Timur', 'Lampung Utara', 'Mesuji', 'Pesawaran', 'Pringsewu', 'Tanggamus', 'Tulang Bawang', 'Way Kanan'],
    'Banten': ['Serang', 'Cilegon', 'Tangerang', 'Tangerang Selatan', 'Lebak', 'Pandeglang'],
    'DKI Jakarta': ['Jakarta Barat', 'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Utara', 'Kepulauan Seribu'],
    'Jawa Barat': ['Bandung', 'Bekasi', 'Bogor', 'Cimahi', 'Cirebon', 'Depok', 'Sukabumi', 'Tasikmalaya', 'Banjar', 'Bandung Barat', 'Ciamis', 'Cianjur', 'Garut', 'Indramayu', 'Karawang', 'Kuningan', 'Majalengka', 'Pangandaran', 'Purwakarta', 'Subang', 'Sumedang'],
    'Jawa Tengah': ['Semarang', 'Surakarta', 'Magelang', 'Pekalongan', 'Salatiga', 'Tegal', 'Banjarnegara', 'Banyumas', 'Batang', 'Blora', 'Boyolali', 'Brebes', 'Cilacap', 'Demak', 'Grobogan', 'Jepara', 'Karanganyar', 'Kebumen', 'Kendal', 'Klaten', 'Kudus', 'Pati', 'Pemalang', 'Purbalingga', 'Purworejo', 'Rembang', 'Sragen', 'Sukoharjo', 'Temanggung', 'Wonogiri', 'Wonosobo'],
    'DI Yogyakarta': ['Yogyakarta', 'Bantul', 'Gunungkidul', 'Kulon Progo', 'Sleman'],
    'Jawa Timur': ['Surabaya', 'Malang', 'Madiun', 'Kediri', 'Mojokerto', 'Blitar', 'Pasuruan', 'Probolinggo', 'Batu', 'Banyuwangi', 'Bojonegoro', 'Bondowoso', 'Gresik', 'Jember', 'Jombang', 'Lamongan', 'Lumajang', 'Magetan', 'Nganjuk', 'Ngawi', 'Pacitan', 'Pamekasan', 'Ponorogo', 'Sampang', 'Sidoarjo', 'Situbondo', 'Sumenep', 'Trenggalek', 'Tuban', 'Tulungagung'],
    'Bali': ['Denpasar', 'Badung', 'Bangli', 'Buleleng', 'Gianyar', 'Jembrana', 'Karangasem', 'Klungkung', 'Tabanan'],
    'Nusa Tenggara Barat': ['Mataram', 'Bima', 'Dompu', 'Lombok Barat', 'Lombok Tengah', 'Lombok Timur', 'Lombok Utara', 'Sumbawa', 'Sumbawa Barat'],
    'Nusa Tenggara Timur': ['Kupang', 'Alor', 'Belu', 'Ende', 'Flores Timur', 'Lembata', 'Malaka', 'Manggarai', 'Manggarai Barat', 'Manggarai Timur', 'Nagekeo', 'Ngada', 'Rote Ndao', 'Sabu Raijua', 'Sikka', 'Sumba Barat', 'Sumba Barat Daya', 'Sumba Tengah', 'Sumba Timur', 'Timor Tengah Selatan', 'Timor Tengah Utara'],
    'Kalimantan Barat': ['Pontianak', 'Singkawang', 'Bengkayang', 'Kapuas Hulu', 'Kayong Utara', 'Ketapang', 'Kubu Raya', 'Landak', 'Melawi', 'Mempawah', 'Sambas', 'Sanggau', 'Sekadau', 'Sintang'],
    'Kalimantan Tengah': ['Palangka Raya', 'Barito Selatan', 'Barito Timur', 'Barito Utara', 'Gunung Mas', 'Kapuas', 'Katingan', 'Kotawaringin Barat', 'Kotawaringin Timur', 'Lamandau', 'Murung Raya', 'Pulang Pisau', 'Seruyan', 'Sukamara'],
    'Kalimantan Selatan': ['Banjarmasin', 'Banjarbaru', 'Balangan', 'Banjar', 'Barito Kuala', 'Hulu Sungai Selatan', 'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Kotabaru', 'Tabalong', 'Tanah Bumbu', 'Tanah Laut', 'Tapin'],
    'Kalimantan Timur': ['Samarinda', 'Balikpapan', 'Bontang', 'Berau', 'Kutai Barat', 'Kutai Kartanegara', 'Kutai Timur', 'Mahakam Ulu', 'Paser', 'Penajam Paser Utara'],
    'Kalimantan Utara': ['Tarakan', 'Bulungan', 'Malinau', 'Nunukan', 'Tana Tidung'],
    'Sulawesi Utara': ['Manado', 'Bitung', 'Tomohon', 'Kotamobagu', 'Bolaang Mongondow', 'Bolaang Mongondow Selatan', 'Bolaang Mongondow Timur', 'Bolaang Mongondow Utara', 'Kepulauan Sangihe', 'Kepulauan Siau Tagulandang Biaro', 'Kepulauan Talaud', 'Minahasa', 'Minahasa Selatan', 'Minahasa Tenggara', 'Minahasa Utara'],
    'Gorontalo': ['Gorontalo', 'Boalemo', 'Bone Bolango', 'Gorontalo Utara', 'Pohuwato'],
    'Sulawesi Tengah': ['Palu', 'Banggai', 'Banggai Kepulauan', 'Banggai Laut', 'Buol', 'Donggala', 'Morowali', 'Morowali Utara', 'Parigi Moutong', 'Poso', 'Sigi', 'Tojo Una-Una', 'Toli-Toli'],
    'Sulawesi Barat': ['Mamuju', 'Majene', 'Mamasa', 'Mamuju Tengah', 'Pasangkayu', 'Polewali Mandar'],
    'Sulawesi Selatan': ['Makassar', 'Parepare', 'Palopo', 'Bantaeng', 'Barru', 'Bone', 'Bulukumba', 'Enrekang', 'Gowa', 'Jeneponto', 'Kepulauan Selayar', 'Luwu', 'Luwu Timur', 'Luwu Utara', 'Maros', 'Pangkajene dan Kepulauan', 'Pinrang', 'Sidenreng Rappang', 'Sinjai', 'Soppeng', 'Takalar', 'Tana Toraja', 'Toraja Utara', 'Wajo'],
    'Sulawesi Tenggara': ['Kendari', 'Bau-Bau', 'Bombana', 'Buton', 'Buton Selatan', 'Buton Tengah', 'Buton Utara', 'Kolaka', 'Kolaka Timur', 'Kolaka Utara', 'Konawe', 'Konawe Kepulauan', 'Konawe Selatan', 'Konawe Utara', 'Muna', 'Muna Barat', 'Wakatobi'],
    'Maluku': ['Ambon', 'Tual', 'Buru', 'Buru Selatan', 'Kepulauan Aru', 'Maluku Barat Daya', 'Maluku Tengah', 'Maluku Tenggara', 'Kepulauan Tanimbar', 'Seram Bagian Barat', 'Seram Bagian Timur'],
    'Maluku Utara': ['Ternate', 'Tidore Kepulauan', 'Halmahera Barat', 'Halmahera Tengah', 'Halmahera Timur', 'Halmahera Selatan', 'Halmahera Utara', 'Kepulauan Sula', 'Pulau Morotai', 'Pulau Taliabu'],
    'Papua': ['Jayapura', 'Biak Numfor', 'Keerom', 'Kepulauan Yapen', 'Mamberamo Raya', 'Sarmi', 'Supiori', 'Waropen'],
    'Papua Barat': ['Manokwari', 'Fakfak', 'Kaimana', 'Manokwari Selatan', 'Pegunungan Arfak', 'Teluk Bintuni', 'Teluk Wondama'],
    'Papua Selatan': ['Merauke', 'Asmat', 'Boven Digoel', 'Mappi'],
    'Papua Tengah': ['Nabire', 'Deiyai', 'Dogiyai', 'Intan Jaya', 'Mimika', 'Paniai', 'Puncak', 'Puncak Jaya'],
    'Papua Pegunungan': ['Jayawijaya', 'Lanny Jaya', 'Mamberamo Tengah', 'Nduga', 'Pegunungan Bintang', 'Tolikara', 'Yahukimo', 'Yalimo'],
    'Papua Barat Daya': ['Sorong', 'Maybrat', 'Raja Ampat', 'Sorong Selatan', 'Tambrauw'],
};

async function updateKotaOptions() {
    const provinceSelect = document.getElementById('bengkelProvinsi');
    const kotaSelect = document.getElementById('bengkelKota');
    const province = provinceSelect.value;
    
    console.log('[Bengkel] updateKotaOptions called, province:', province);
    
    // Reset kota select
    kotaSelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
    
    if (!province) return;
    
    // Add loading state
    kotaSelect.innerHTML = '<option value="">-- Memuat kota... --</option>';
    
    let cities = [];

    try {
        // Gunakan fetch langsung untuk menghindari error log 404 yang berisik dari apiCall
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const response = await fetch(`${API_BASE}/cities-by-province/${encodeURIComponent(province)}`, {
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        });

        if (response.ok) {
            const result = await response.json();
            if (Array.isArray(result)) cities = result;
            else if (result.data && Array.isArray(result.data)) cities = result.data;
        }
    } catch (error) {
        // Silent fail, lanjut ke fallback tanpa error log yang mengganggu
    }

    // Fallback ke data statis jika API gagal atau kosong
    if (!cities || cities.length === 0) {
        cities = INDONESIA_CITIES[province] || [];
    }

    // Render options
    kotaSelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
    
    if (Array.isArray(cities) && cities.length > 0) {
        cities.forEach(city => {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            kotaSelect.appendChild(option);
        });
    } else {
        kotaSelect.innerHTML = '<option value="">-- Tidak ada data kota --</option>';
    }
}

async function loadBengkels() {
    try {
        console.log('[Bengkel] Loading...');
        const bengkels = await apiCall('/bengkels');
        console.log('[Bengkel] Loaded:', bengkels);
        renderBengkelList(bengkels);
    } catch (error) {
        console.error('[Bengkel] Load failed:', error);
        showError('Gagal memuat bengkel');
    }
}

function renderBengkelList(bengkels) {
    const list = document.getElementById('bengkelList');
    if (!bengkels || bengkels.length === 0) {
        list.innerHTML = '<div class="no-data"><i class="fas fa-tools"></i><p>Belum ada bengkel.</p></div>';
        return;
    }
    
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
            result = await apiCall(`/bengkels/${editId}`, 'PUT', data);
            showSuccess('Bengkel berhasil diupdate');
            delete document.getElementById('bengkelForm').dataset.editId;
        } else {
            result = await apiCall('/bengkels', 'POST', data);
            showSuccess('Bengkel berhasil ditambahkan');
        }
        
        await loadBengkels();
        toggleForm('bengkelForm');
        event.target.reset();
    } catch (error) {
        console.error('[Bengkel] Save failed:', error);
        showError('Gagal menyimpan bengkel');
    }
}

async function editBengkelItem(id) {
    try {
        const bengkel = await apiCall(`/bengkels/${id}`);
        document.getElementById('bengkelNama').value = bengkel.name;
        document.getElementById('bengkelPemilik').value = bengkel.owner;
        document.getElementById('bengkelProvinsi').value = bengkel.province;
        
        // Load cities for this province
        await updateKotaOptions();
        
        // Set city after cities are loaded
        document.getElementById('bengkelKota').value = bengkel.city;
        
        document.getElementById('bengkelAlamat').value = bengkel.address;
        document.getElementById('bengkelTelepon').value = bengkel.phone;
        document.getElementById('bengkelEmail').value = bengkel.email || '';
        document.getElementById('bengkelDeskripsi').value = bengkel.description || '';
        document.getElementById('bengkelForm').dataset.editId = id;
        toggleForm('bengkelForm');
    } catch (error) {
        console.error('[Bengkel] Edit failed:', error);
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
        console.error('[Bengkel] Delete failed:', error);
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

function resolveImageUrl(url) {
    if (!url) return null;
    // Fix: Handle placeholder URLs that are missing the domain (causes ERR_NAME_NOT_RESOLVED)
    if (url.match(/^\d+x\d+/)) {
        return `https://placehold.co/${url}`;
    }
    return url;
}

// ===== INITIALIZE =====
console.log('[INIT] Admin API starting...');
document.addEventListener('DOMContentLoaded', function() {
    console.log('[INIT] DOM loaded, loading data...');
    
    // Batasi karakter deskripsi gallery menjadi 200
    const galleryDesc = document.getElementById('galleryDesc');
    if (galleryDesc) {
        galleryDesc.maxLength = 200;
        galleryDesc.placeholder = "Deskripsi (Maksimal 200 karakter)";
    }

    loadGalleries();
    loadProducts();
    loadSponsors();
    loadBengkels();

    // Event listener untuk dropdown provinsi
    const provinceSelect = document.getElementById('bengkelProvinsi');
    if (provinceSelect) {
        provinceSelect.addEventListener('change', updateKotaOptions);
    }
});
console.log('[INIT] Admin API ready');
