@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard Officer</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('officer.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">

        {{-- 🚨 NOTIFIKASI SLOT PARKIR MODERN --}}
        <div id="slotNotification" class="notification-card" style="display: none;">
            <div class="notification-content">
                <div class="notification-icon" id="notifIconWrapper">
                    <i class="bi" id="notifIcon"></i>
                </div>
                <div class="notification-text">
                    <h4 class="notification-title" id="notifTitle"></h4>
                    <p class="notification-message" id="notifMessage"></p>
                </div>
                <button type="button" class="notification-close" onclick="closeNotification()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="notification-progress" id="notifProgress"></div>
        </div>

        {{-- ROW 1: STATISTIK --}}
        <div class="row">

            {{-- SLOT KOSONG --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow-sm border-0" style="border-left:5px solid #28a745;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small">Slot Kosong</h6>
                                <h3 class="fw-bold mb-0 text-success">
                                    <span id="slotKosong">{{ $slotKosong }}</span>/<span id="slotTotal">{{ $slotTotal }}</span>
                                </h3>
                                <small class="text-muted">
                                    <i class="bi bi-p-square"></i> slot parkir
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-p-circle" style="font-size: 2rem; color: #28a745;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SLOT TERISI --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow-sm border-0" style="border-left:5px solid #dc3545;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small">Slot Terisi</h6>
                                <h3 class="fw-bold mb-0 text-danger" id="slotTerisi">{{ $slotTerisi }}</h3>
                                <small class="text-muted">
                                    <i class="bi bi-car-front"></i> saat ini
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-car-front-fill" style="font-size: 2rem; color: #dc3545;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KENDARAAN MASUK --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow-sm border-0" style="border-left:5px solid #20c997;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small">Kendaraan Masuk</h6>
                                <h3 class="fw-bold mb-0" style="color: #20c997;" id="kendaraanMasuk">{{ $kendaraanMasukHariIni }}</h3>
                                <small class="text-muted">
                                    <i class="bi bi-calendar-day"></i> Hari ini
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-arrow-down-circle-fill" style="font-size: 2rem; color: #20c997;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KENDARAAN KELUAR --}}
            <div class="col-lg-3 col-md-6 col-12 mb-3">
                <div class="card shadow-sm border-0" style="border-left:5px solid #fd7e14;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 small">Kendaraan Keluar</h6>
                                <h3 class="fw-bold mb-0 text-warning" id="kendaraanKeluar">{{ $kendaraanKeluarHariIni }}</h3>
                                <small class="text-muted">
                                    <i class="bi bi-calendar-day"></i> Hari ini
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-arrow-up-circle-fill" style="font-size: 2rem; color: #fd7e14;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ROW 2: DAFTAR PARKIR RECORD HARI INI --}}
        <div class="row mt-2">
            <div class="col-12 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-list-ul text-primary"></i>
                                Daftar Parkir Hari Ini
                            </h5>
                            {{-- TOTAL SELURUH DATA --}}
                            <span class="badge bg-primary" id="totalRecords">{{ $recordsHariIni->total() }} Record</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Tiket</th>
                                        <th>Jenis Kendaraan</th>
                                        <th>Slot Parkir</th>
                                        <th>Area</th>
                                        <th>Waktu Masuk</th>
                                        <th>Waktu Keluar</th>
                                        <th>Status</th>
                                        <th>Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody id="recordsTableBody">
                                    @forelse($recordsHariIni as $index => $record)
                                        <tr>
                                            <td>{{ $recordsHariIni->firstItem() + $index }}</td>
                                            <td>
                                                <span class="badge bg-dark">{{ $record->ticket_code }}</span>
                                            </td>
                                            <td>
                                                @if ($record->tarif && $record->tarif->vehicleType)
                                                    <i class="bi bi-{{ $record->tarif->vehicleType->name == 'Motor' ? 'bicycle' : 'car-front' }}"></i>
                                                    {{ $record->tarif->vehicleType->name }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($record->parkingSlot)
                                                    <span class="badge bg-secondary">{{ $record->parkingSlot->slot_code }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($record->parkingSlot && $record->parkingSlot->area)
                                                    {{ $record->parkingSlot->area->name }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($record->entry_time)
                                                    <small>{{ \Carbon\Carbon::parse($record->entry_time)->format('H:i') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($record->exit_time)
                                                    <small>{{ \Carbon\Carbon::parse($record->exit_time)->format('H:i') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($record->status == 'in')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-arrow-down-circle"></i> Masuk
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-arrow-up-circle"></i> Keluar
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($record->payment_status == 'paid')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Lunas
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-clock"></i> Belum Bayar
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                                <span class="text-muted">Tidak ada data parkir hari ini</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- PAGINATION --}}
                        <div class="mt-3 d-flex justify-content-end" id="paginationContainer">
                            {{ $recordsHariIni->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
<script>
let currentPage = 1;

function loadDashboardData(page = 1) {
    // Update currentPage
    currentPage = page;
    
    console.log('🔄 Loading dashboard data for page:', page);
    
    $.ajax({
        url: "{{ route('officer.dashboard.loadData') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            page: page
        },
        success: function(res) {
            console.log('✅ Dashboard data loaded:', res);
            console.log('Slot Kosong:', res.slotKosong);
            console.log('Slot Terisi:', res.slotTerisi);
            console.log('Records count:', res.records.length);
            
            if (res.success) {
                // UPDATE STATISTIK dengan logging
                console.log('Updating #slotKosong to:', res.slotKosong);
                $('#slotKosong').text(res.slotKosong);
                
                console.log('Updating #slotTerisi to:', res.slotTerisi);
                $('#slotTerisi').text(res.slotTerisi);
                
                console.log('Updating #slotTotal to:', res.slotTotal);
                $('#slotTotal').text(res.slotTotal);
                
                console.log('Updating #kendaraanMasuk to:', res.kendaraanMasukHariIni);
                $('#kendaraanMasuk').text(res.kendaraanMasukHariIni);
                
                console.log('Updating #kendaraanKeluar to:', res.kendaraanKeluarHariIni);
                $('#kendaraanKeluar').text(res.kendaraanKeluarHariIni);
                
                console.log('Updating #totalRecords to:', res.pagination.total + ' Record');
                $('#totalRecords').text(res.pagination.total + ' Record');

                // UPDATE TABLE
                console.log('Updating table with', res.records.length, 'records');
                updateRecordsTable(res.records, res.pagination);
                
                // 🚨 CEK DAN TAMPILKAN NOTIFIKASI
                checkSlotStatus(res.slotKosong, res.slotTotal, res.slotTerisi);
                
                console.log('✅ UI Update completed!');
            } else {
                console.error('❌ Response success = false');
            }
        },
        error: function(xhr) {
            console.error('❌ AJAX Error:', xhr.status, xhr.statusText);
            console.error('Response:', xhr.responseText);
        }
    });
}

function updateRecordsTable(records, pagination) {
    console.log('📋 Updating records table...');
    console.log('Records to display:', records);
    
    const tbody = $('#recordsTableBody');
    
    if (!tbody.length) {
        console.error('❌ #recordsTableBody not found!');
        return;
    }
    
    tbody.empty();
    console.log('Table body cleared');

    if (records.length === 0) {
        console.log('No records to display');
        tbody.append(`
            <tr>
                <td colspan="9" class="text-center py-4">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <span class="text-muted">Tidak ada data parkir hari ini</span>
                </td>
            </tr>
        `);
        $('#paginationContainer').empty();
        return;
    }

    console.log('Rendering', records.length, 'records');
    records.forEach(function(record, idx) {
        console.log('Rendering record', idx, ':', record);
        
        const statusBadge = record.status === 'in' 
            ? '<span class="badge bg-success"><i class="bi bi-arrow-down-circle"></i> Masuk</span>'
            : '<span class="badge bg-danger"><i class="bi bi-arrow-up-circle"></i> Keluar</span>';

        const paymentBadge = record.payment_status === 'paid'
            ? '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Lunas</span>'
            : '<span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Belum Bayar</span>';

        const vehicleDisplay = record.vehicle_type !== '-'
            ? `<i class="bi bi-${record.vehicle_icon}"></i> ${record.vehicle_type}`
            : '<span class="text-muted">-</span>';

        const slotDisplay = record.slot_code !== '-'
            ? `<span class="badge bg-secondary">${record.slot_code}</span>`
            : '<span class="text-muted">-</span>';

        const row = `
            <tr>
                <td>${record.no}</td>
                <td><span class="badge bg-dark">${record.ticket_code}</span></td>
                <td>${vehicleDisplay}</td>
                <td>${slotDisplay}</td>
                <td>${record.area_name}</td>
                <td><small>${record.entry_time}</small></td>
                <td><small>${record.exit_time}</small></td>
                <td>${statusBadge}</td>
                <td>${paymentBadge}</td>
            </tr>
        `;
        
        tbody.append(row);
    });
    
    console.log('✅ Table rendered with', tbody.find('tr').length, 'rows');

    // UPDATE PAGINATION
    updatePagination(pagination);
}

function updatePagination(pagination) {
    console.log('📄 Updating pagination:', pagination);
    
    const container = $('#paginationContainer');
    
    if (!container.length) {
        console.error('❌ #paginationContainer not found!');
        return;
    }
    
    container.empty();

    if (pagination.last_page <= 1) {
        console.log('Only 1 page, no pagination needed');
        return;
    }

    let paginationHTML = '<nav><ul class="pagination pagination-sm mb-0">';

    // Previous button
    if (pagination.current_page > 1) {
        paginationHTML += `
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" onclick="loadDashboardData(${pagination.current_page - 1})">
                    Previous
                </a>
            </li>
        `;
    }

    // Page numbers
    for (let i = 1; i <= pagination.last_page; i++) {
        const active = i === pagination.current_page ? 'active' : '';
        paginationHTML += `
            <li class="page-item ${active}">
                <a class="page-link" href="javascript:void(0)" onclick="loadDashboardData(${i})">${i}</a>
            </li>
        `;
    }

    // Next button
    if (pagination.current_page < pagination.last_page) {
        paginationHTML += `
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" onclick="loadDashboardData(${pagination.current_page + 1})">
                    Next
                </a>
            </li>
        `;
    }

    paginationHTML += '</ul></nav>';
    container.html(paginationHTML);
    
    console.log('✅ Pagination updated');
}

// 🚨 FUNGSI CEK STATUS SLOT & TAMPILKAN NOTIFIKASI
let lastNotificationStatus = null; // Untuk tracking status sebelumnya

function checkSlotStatus(slotKosong, slotTotal, slotTerisi) {
    const persenKosong = (slotKosong / slotTotal) * 100;
    const persenTerisi = (slotTerisi / slotTotal) * 100;
    
    console.log('📊 Slot Status - Kosong:', slotKosong, '/', slotTotal, '=', persenKosong.toFixed(1) + '%');
    
    const notification = $('#slotNotification');
    const notifIcon = $('#notifIcon');
    const notifTitle = $('#notifTitle');
    const notifMessage = $('#notifMessage');
    
    // Reset classes
    notification.removeClass('alert-danger alert-warning alert-info alert-success');
    
    let currentStatus = 'normal';
    
    if (slotKosong === 0) {
        // 🔴 PENUH - CRITICAL
        currentStatus = 'full';
        notification.addClass('alert-danger');
        notifIcon.removeClass().addClass('bi bi-exclamation-triangle-fill fs-2 me-3');
        notifTitle.html('🚨 PARKIR PENUH!');
        notifMessage.html(`<strong>Semua slot parkir telah terisi (${slotTerisi}/${slotTotal}).</strong><br>
            ⚠️ <strong>HARAP INFORMASIKAN KEPADA PENGUNJUNG DI GATE MASUK</strong> bahwa tidak ada slot tersedia!<br>
            💡 Arahkan kendaraan untuk mencari parkir alternatif.`);
        notification.slideDown();
        
        // Browser Notification hanya jika status berubah
        if (lastNotificationStatus !== 'full') {
            showBrowserNotification(
                '🚨 PARKIR PENUH!',
                `Semua ${slotTotal} slot parkir telah terisi. Informasikan ke pengunjung di gate!`,
                'urgent'
            );
            playNotificationSound();
        }
        
    } else if (persenKosong <= 10) {
        // 🟠 HAMPIR PENUH - WARNING
        currentStatus = 'critical';
        notification.addClass('alert-warning');
        notifIcon.removeClass().addClass('bi bi-exclamation-circle-fill fs-2 me-3');
        notifTitle.html('⚠️ Parkir Hampir Penuh!');
        notifMessage.html(`<strong>Hanya tersisa ${slotKosong} slot kosong (${persenKosong.toFixed(0)}%).</strong><br>
            📢 Bersiaplah untuk menginformasikan kepada pengunjung bahwa slot parkir hampir habis.<br>
            🚦 Antisipasi kemungkinan parkir penuh dalam waktu dekat.`);
        notification.slideDown();
        
        // Browser Notification
        if (lastNotificationStatus !== 'critical' && lastNotificationStatus !== 'full') {
            showBrowserNotification(
                '⚠️ Parkir Hampir Penuh',
                `Hanya ${slotKosong} slot tersisa. Bersiap informasikan ke pengunjung.`,
                'warning'
            );
        }
        
    } else if (persenKosong <= 25) {
        // 🟡 PERHATIAN
        currentStatus = 'warning';
        notification.addClass('alert-info');
        notifIcon.removeClass().addClass('bi bi-info-circle-fill fs-2 me-3');
        notifTitle.html('ℹ️ Perhatian: Slot Terbatas');
        notifMessage.html(`Slot parkir tersisa <strong>${slotKosong} dari ${slotTotal}</strong> (${persenKosong.toFixed(0)}%).<br>
            Monitor terus ketersediaan slot parkir.`);
        notification.slideDown();
        
    } else {
        // 🟢 AMAN - Sembunyikan notifikasi
        currentStatus = 'normal';
        notification.slideUp();
    }
    
    // Update last status
    lastNotificationStatus = currentStatus;
}

// 🔔 BROWSER NOTIFICATION
function showBrowserNotification(title, body, urgency = 'normal') {
    if (!("Notification" in window)) {
        console.log('Browser does not support notifications');
        return;
    }
    
    if (Notification.permission === "granted") {
        const options = {
            body: body,
            icon: urgency === 'urgent' ? '/images/alert-icon.png' : '/images/warning-icon.png',
            badge: '/images/parking-badge.png',
            tag: 'parking-slot-alert',
            requireInteraction: urgency === 'urgent', // Tetap tampil sampai di-close
            vibrate: urgency === 'urgent' ? [200, 100, 200] : [100],
        };
        
        const notification = new Notification(title, options);
        
        // Auto close setelah 10 detik (kecuali urgent)
        if (urgency !== 'urgent') {
            setTimeout(() => notification.close(), 10000);
        }
        
        notification.onclick = function() {
            window.focus();
            notification.close();
        };
    }
}

// 🔊 PLAY NOTIFICATION SOUND (Optional - jika ingin ada suara)
function playNotificationSound() {
    // Buat beep sound menggunakan Web Audio API
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.5);
    } catch (e) {
        console.log('Audio notification not supported');
    }
}

// AUTO REFRESH SETIAP 3 DETIK
setInterval(function() {
    console.log('⏰ Auto-refresh triggered');
    loadDashboardData(currentPage);
}, 3000);

$(document).ready(function() {
    console.log('🚀 Document ready, loading initial data');
    
    // Cek apakah jQuery ada
    if (typeof $ === 'undefined') {
        console.error('❌ jQuery not loaded!');
        return;
    }
    
    // Cek apakah elemen ada
    console.log('#slotKosong exists:', $('#slotKosong').length > 0);
    console.log('#slotTerisi exists:', $('#slotTerisi').length > 0);
    console.log('#recordsTableBody exists:', $('#recordsTableBody').length > 0);
    
    // 🔔 Request Browser Notification Permission
    if ("Notification" in window && Notification.permission === "default") {
        Notification.requestPermission();
    }
    
    // Load pertama kali
    loadDashboardData(1);
});
</script>

<style>
/* 🚨 MODERN NOTIFICATION CARD */
.notification-card {
    position: relative;
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    margin-bottom: 24px;
    overflow: hidden;
    transform: translateY(-20px);
    opacity: 0;
    animation: slideInNotif 0.5s ease-out forwards;
    border-left: 6px solid;
}

@keyframes slideInNotif {
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.notification-content {
    display: flex;
    align-items: flex-start;
    padding: 20px 24px;
    gap: 20px;
}

.notification-icon {
    flex-shrink: 0;
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    position: relative;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.notification-icon i {
    animation: iconBounce 2s ease-in-out infinite;
    display: flex;
    align-items: center;
    justify-content: center;
}

@keyframes iconBounce {
    0%, 100% {
        transform: scale(1) rotate(0deg);
    }
    25% {
        transform: scale(1.1) rotate(-5deg);
    }
    75% {
        transform: scale(1.1) rotate(5deg);
    }
}

.notification-text {
    flex: 1;
    padding-right: 40px;
}

.notification-title {
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 8px 0;
    line-height: 1.3;
    display: flex;
    align-items: center;
    gap: 8px;
}

.notification-message {
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
    color: #4a5568;
}

.notification-message strong {
    font-weight: 600;
    color: #2d3748;
}

.highlight-text {
    display: inline-block;
    background: rgba(255, 255, 255, 0.5);
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 600;
    animation: highlightPulse 1.5s ease-in-out infinite;
    margin: 4px 0;
}

@keyframes highlightPulse {
    0%, 100% {
        background: rgba(255, 255, 255, 0.5);
        transform: scale(1);
    }
    50% {
        background: rgba(255, 255, 255, 0.9);
        transform: scale(1.02);
    }
}

.notification-close {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 36px;
    height: 36px;
    border: none;
    background: rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    color: #64748b;
    font-size: 14px;
}

.notification-close:hover {
    background: rgba(0, 0, 0, 0.1);
    transform: scale(1.1) rotate(90deg);
    color: #334155;
}

.notification-progress {
    height: 5px;
    background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
    width: 0%;
    transition: width 0.5s ease;
    position: relative;
    overflow: hidden;
}

.notification-progress::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* 🔴 DANGER STYLE */
.notif-danger {
    border-left-color: #ef4444;
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    animation: slideInNotif 0.5s ease-out forwards, shakeDanger 0.6s ease-in-out 0.5s;
}

@keyframes shakeDanger {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
    20%, 40%, 60%, 80% { transform: translateX(4px); }
}

.notif-danger .notification-title {
    color: #dc2626;
}

.notif-danger .notification-progress {
    background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
}

.icon-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #dc2626;
    box-shadow: 0 4px 20px rgba(220, 38, 38, 0.3), 0 0 0 0 rgba(220, 38, 38, 0.4);
    animation: iconPulseRed 2s ease-in-out infinite;
}

@keyframes iconPulseRed {
    0%, 100% {
        box-shadow: 0 4px 20px rgba(220, 38, 38, 0.3), 0 0 0 0 rgba(220, 38, 38, 0.4);
    }
    50% {
        box-shadow: 0 4px 25px rgba(220, 38, 38, 0.4), 0 0 0 8px rgba(220, 38, 38, 0);
    }
}

/* 🟠 WARNING STYLE */
.notif-warning {
    border-left-color: #f59e0b;
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
}

.notif-warning .notification-title {
    color: #d97706;
}

.notif-warning .notification-progress {
    background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 100%);
}

.icon-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #d97706;
    box-shadow: 0 4px 20px rgba(245, 158, 11, 0.3), 0 0 0 0 rgba(245, 158, 11, 0.4);
    animation: iconPulseOrange 2s ease-in-out infinite;
}

@keyframes iconPulseOrange {
    0%, 100% {
        box-shadow: 0 4px 20px rgba(245, 158, 11, 0.3), 0 0 0 0 rgba(245, 158, 11, 0.4);
    }
    50% {
        box-shadow: 0 4px 25px rgba(245, 158, 11, 0.4), 0 0 0 8px rgba(245, 158, 11, 0);
    }
}

/* 🔵 INFO STYLE */
.notif-info {
    border-left-color: #3b82f6;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
}

.notif-info .notification-title {
    color: #2563eb;
}

.notif-info .notification-progress {
    background: linear-gradient(90deg, #60a5fa 0%, #3b82f6 100%);
}

.icon-info {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #2563eb;
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3), 0 0 0 0 rgba(59, 130, 246, 0.4);
    animation: iconPulseBlue 2s ease-in-out infinite;
}

@keyframes iconPulseBlue {
    0%, 100% {
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3), 0 0 0 0 rgba(59, 130, 246, 0.4);
    }
    50% {
        box-shadow: 0 4px 25px rgba(59, 130, 246, 0.4), 0 0 0 8px rgba(59, 130, 246, 0);
    }
}

/* 🟢 SUCCESS STYLE */
.notif-success {
    border-left-color: #10b981;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
}

.notif-success .notification-title {
    color: #059669;
}

.icon-success {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #059669;
    box-shadow: 0 4px 20px rgba(16, 185, 129, 0.25);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .notification-content {
        padding: 16px;
        gap: 16px;
    }
    
    .notification-icon {
        width: 56px;
        height: 56px;
        font-size: 28px;
    }
    
    .notification-title {
        font-size: 18px;
    }
    
    .notification-message {
        font-size: 13px;
    }
    
    .notification-close {
        width: 32px;
        height: 32px;
        top: 16px;
        right: 16px;
    }
}
</style>
@endsection