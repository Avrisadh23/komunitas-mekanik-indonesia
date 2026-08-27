@extends('layouts.app')

@section('title', 'Bengkel Rekanan - Komunitas Mekanik Indonesia')

@section('content')
<section class="hero" style="padding: 3rem 0; min-height: 300px; background: linear-gradient(180deg, #E63946 0%, #A4161A 50%, rgba(255,255,255,0.1) 100%);">
    <div class="container" style="display: flex; align-items: center;">
        <div class="hero-content">
            <h1 style="font-size: 2.5rem;">Bengkel Rekanan KMI</h1>
            <p>Daftar bengkel profesional yang tergabung dalam komunitas</p>
        </div>
    </div>
</section>

<section class="bengkel-section">
    <div class="container">
        <h2>Bengkel Rekanan Per Provinsi</h2>
        <p style="text-align: center; color: #666; margin-bottom: 2rem;">Pilih provinsi untuk melihat daftar bengkel mitra kami</p>
        
        <div class="filter-container">
            <select id="provinsiFilter" class="province-select" onchange="filterBengkel()">
                <option value="">-- Semua Provinsi --</option>
            </select>
        </div>

        <div id="bengkelContainer" class="bengkel-list-container">
            <div style="text-align: center; padding: 2rem;">
                <p>Loading Data Bengkel...</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-card">
            <h3>Ingin Bergabung Sebagai Bengkel Rekanan?</h3>
            <p>Daftarkan bengkel Anda dan nikmati berbagai keuntungan sebagai mitra KMI</p>
            <a href="https://wa.me/6282114693145" class="btn btn-primary" target="_blank">Hubungi Kami Sekarang</a>
        </div>
    </div>
</section>

@endsection

@section('extra_js')
<script>
let bengkelDataByProvince = {};
let currentProvince = '';

document.addEventListener('DOMContentLoaded', function() {
    loadBengkelData();
});

function loadBengkelData() {
    fetch('/api/bengkel')
        .then(response => response.json())
        .then(data => {
            bengkelDataByProvince = data;
            populateProvinsiFilter();
            displayAllBengkel();
        })
        .catch(error => console.error('Error loading bengkel data:', error));
}

function populateProvinsiFilter() {
    const select = document.getElementById('provinsiFilter');
    Object.keys(bengkelDataByProvince).sort().forEach(provinsi => {
        const option = document.createElement('option');
        option.value = provinsi;
        option.textContent = provinsi;
        select.appendChild(option);
    });
}

function displayAllBengkel() {
    const container = document.getElementById('bengkelContainer');
    container.innerHTML = '';
    
    Object.keys(bengkelDataByProvince).sort().forEach(provinsi => {
        const section = document.createElement('div');
        section.className = 'bengkel-province-section';
        section.innerHTML = `<h3 class="province-title">${provinsi}</h3>`;
        
        const tableDiv = document.createElement('div');
        tableDiv.className = 'bengkel-table-wrapper';
        tableDiv.innerHTML = createBengkelTable(bengkelDataByProvince[provinsi]);
        
        section.appendChild(tableDiv);
        container.appendChild(section);
    });
}

function filterBengkel() {
    const provinsi = document.getElementById('provinsiFilter').value;
    const container = document.getElementById('bengkelContainer');
    container.innerHTML = '';
    
    if (!provinsi) {
        displayAllBengkel();
        return;
    }
    
    if (bengkelDataByProvince[provinsi]) {
        const section = document.createElement('div');
        section.className = 'bengkel-province-section';
        section.innerHTML = `<h3 class="province-title">${provinsi}</h3>`;
        
        const tableDiv = document.createElement('div');
        tableDiv.className = 'bengkel-table-wrapper';
        tableDiv.innerHTML = createBengkelTable(bengkelDataByProvince[provinsi]);
        
        section.appendChild(tableDiv);
        container.appendChild(section);
    }
}

function createBengkelTable(bengkelList) {
    let html = `
        <table class="bengkel-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Bengkel</th>
                    <th>Pemilik</th>
                    <th>Kota</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    bengkelList.forEach((bengkel, index) => {
        html += `
            <tr>
                <td class="cell-no">${index + 1}</td>
                <td class="cell-nama"><strong>${bengkel.nama}</strong></td>
                <td class="cell-pemilik">${bengkel.pemilik}</td>
                <td class="cell-kota">${bengkel.kota}</td>
                <td class="cell-alamat">${bengkel.alamat}</td>
                <td class="cell-telepon">
                    <a href="tel:${bengkel.telepon}" class="phone-link">${bengkel.telepon}</a>
                </td>
            </tr>
        `;
    });
    
    html += `
            </tbody>
        </table>
    `;
    
    return html;
}
</script>
@endsection

<style>
.filter-container {
    display: flex;
    justify-content: center;
    margin-bottom: 2.5rem;
}

.province-select {
    padding: 0.8rem 1.2rem;
    font-size: 1rem;
    border: 2px solid #E63946;
    border-radius: 8px;
    background-color: white;
    color: #333;
    min-width: 300px;
    cursor: pointer;
    transition: all 0.3s;
}

.province-select:hover,
.province-select:focus {
    border-color: #0052A3;
    box-shadow: 0 2px 8px rgba(0, 82, 163, 0.2);
}

.bengkel-province-section {
    margin-bottom: 3rem;
}

.province-title {
    font-size: 1.3rem;
    color: #E63946;
    margin-bottom: 1.5rem;
    padding-bottom: 0.8rem;
    border-bottom: 3px solid #E63946;
    font-weight: 600;
}

.bengkel-table-wrapper {
    overflow-x: auto;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.bengkel-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.bengkel-table thead {
    background: linear-gradient(to right, #0052A3, #003D7A);
    color: white;
}

.bengkel-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
    border-bottom: 3px solid #E63946;
}

.bengkel-table tbody tr {
    border-bottom: 1px solid #eee;
    transition: all 0.3s;
}

.bengkel-table tbody tr:hover {
    background-color: #f5f5f5;
    box-shadow: inset 0 0 8px rgba(230, 57, 70, 0.08);
}

.bengkel-table td {
    padding: 1rem;
    font-size: 0.95rem;
}

.cell-no {
    font-weight: 600;
    color: #E63946;
    width: 60px;
}

.cell-nama {
    color: #0052A3;
    font-weight: 500;
    min-width: 200px;
}

.cell-pemilik {
    color: #555;
    min-width: 180px;
}

.cell-kota {
    color: #666;
    min-width: 120px;
}

.cell-alamat {
    color: #777;
    font-size: 0.9rem;
    min-width: 250px;
}

.cell-telepon {
    min-width: 140px;
}

.phone-link {
    color: #E63946;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.phone-link:hover {
    color: #A4161A;
    text-decoration: underline;
}

.cta-section {
    background: linear-gradient(135deg, #0052A3 0%, #003D7A 100%);
    padding: 4rem 0;
}

.cta-card {
    background: white;
    padding: 3rem;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    max-width: 600px;
    margin: 0 auto;
}

.cta-card h3 {
    color: #E63946;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.cta-card p {
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.bengkel-section {
    padding: 4rem 0;
    background: white;
}

@media (max-width: 768px) {
    .province-select {
        min-width: 100%;
    }
    
    .bengkel-table {
        font-size: 0.85rem;
    }
    
    .bengkel-table th,
    .bengkel-table td {
        padding: 0.8rem 0.5rem;
    }
    
    .cell-nama,
    .cell-pemilik,
    .cell-alamat {
        min-width: auto;
    }
}
</style>
