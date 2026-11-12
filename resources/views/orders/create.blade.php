@extends('layouts.app') @section('title','Order baru') @section('heading','Transaksi order')
@section('content')
<div class="order-heading mb-4">
    <div><div class="text-primary fw-semibold small text-uppercase mb-2">Transaksi baru</div><h1 class="h3 fw-bold mb-1">Buat order laundry</h1><p class="text-secondary mb-0">Isi data pelanggan dan pilih layanan yang akan dikerjakan.</p></div>
    <a class="btn btn-light border" href="{{ route('orders.index') }}">← Kembali ke order</a>
</div>
@if($items->isEmpty())
    <div class="card p-4 text-center"><div class="fs-1 mb-2">🧺</div><h2 class="h5">Belum ada layanan aktif</h2><p class="text-secondary">Aktifkan kategori dan tambahkan item terlebih dahulu sebelum membuat order.</p><a class="btn btn-primary align-self-center" href="{{ route('items.create') }}">Tambah item layanan</a></div>
@else
<form method="POST" action="{{ route('orders.store') }}" id="orderForm">@csrf
<div class="row g-4 align-items-start">
    <div class="col-lg-8">
        <div class="card order-card mb-4"><div class="order-card-title"><span class="step-number">1</span><div><h2 class="h6 fw-bold mb-1">Informasi pelanggan</h2><p class="text-secondary small mb-0">Data kontak untuk identitas order.</p></div></div><div class="row g-3 p-4 pt-3">
            <div class="col-md-6"><label class="form-label">Nama pelanggan <span class="text-danger">*</span></label><input class="form-control form-control-lg" name="customer_name" value="{{ old('customer_name') }}" placeholder="Contoh: Siti Aminah" required autocomplete="name"></div>
            <div class="col-md-6"><label class="form-label">Nomor HP <span class="text-danger">*</span></label><input class="form-control form-control-lg" name="customer_phone" type="tel" value="{{ old('customer_phone') }}" placeholder="08xxxxxxxxxx" required autocomplete="tel"></div>
            <div class="col-md-6"><label class="form-label">Tanggal masuk <span class="text-danger">*</span></label><input type="date" class="form-control" name="entry_date" value="{{ old('entry_date',today()->toDateString()) }}" required></div>
            <div class="col-12"><label class="form-label">Catatan <span class="text-secondary fw-normal">(opsional)</span></label><textarea class="form-control" name="notes" rows="3" placeholder="Contoh: noda di bagian lengan, pisahkan pakaian putih">{{ old('notes') }}</textarea></div>
        </div></div>
        <div class="card order-card"><div class="order-card-title"><span class="step-number">2</span><div class="flex-grow-1"><h2 class="h6 fw-bold mb-1">Layanan yang dipesan</h2><p class="text-secondary small mb-0">Pilih layanan dan masukkan jumlah pcs atau berat kg.</p></div><button type="button" class="btn btn-sm btn-outline-primary" id="addRow">＋ Tambah layanan</button></div>
            <div class="table-responsive px-4 pb-3"><table class="table order-table mb-0"><thead><tr><th style="min-width:260px">Layanan</th><th style="width:150px">Jumlah</th><th class="text-end" style="width:150px">Subtotal</th><th style="width:48px"></th></tr></thead><tbody id="itemRows"></tbody></table><div id="emptyRows" class="empty-items d-none"><span class="fs-3">🧼</span><span>Pilih “Tambah layanan” untuk memasukkan layanan.</span></div></div>
        </div>
    </div>
    <div class="col-lg-4"><div class="card order-summary"><div class="summary-top"><div class="summary-icon">✦</div><div><h2 class="h6 fw-bold mb-1">Ringkasan order</h2><p class="small mb-0">Total dihitung otomatis</p></div></div><div class="summary-body"><div class="summary-line"><span>Jumlah layanan</span><strong id="serviceCount">0 layanan</strong></div><div class="summary-line align-items-start"><span>Estimasi selesai</span><strong class="text-end" id="estimate">Pilih layanan</strong></div><hr><div class="d-flex justify-content-between align-items-center mb-4"><span class="fw-semibold">Total pembayaran</span><strong class="total-price" id="total">Rp 0</strong></div><button class="btn btn-primary btn-lg w-100" id="submitOrder">Simpan order <span class="ms-1">→</span></button><p class="text-secondary text-center small mt-3 mb-0">Kode order akan dibuat otomatis.</p></div></div></div>
</div></form>
@endif
@endsection
@push('styles')<style>
    .order-heading{display:flex;justify-content:space-between;align-items:center;gap:16px}.order-card{overflow:hidden}.order-card-title{display:flex;align-items:center;gap:13px;padding:20px 24px;border-bottom:1px solid #edf0f5}.step-number{width:34px;height:34px;border-radius:11px;background:#edf4ff;color:#3478f6;display:grid;place-items:center;font-weight:700}.order-card .form-control,.order-card .form-select{border-color:#e2e8f0;border-radius:9px}.order-card .form-control:focus,.order-card .form-select:focus{border-color:#8ab0ff;box-shadow:0 0 0 .2rem #3478f61a}.order-table thead th{background:transparent;padding-top:14px}.order-table td{padding-top:12px;padding-bottom:12px}.order-table .form-select,.order-table .form-control{font-size:.9rem}.line-total{font-weight:650;color:#26364b}.remove-row{border-radius:8px}.empty-items{padding:24px;text-align:center;color:#8390a2;display:flex;flex-direction:column;gap:8px}.order-summary{position:sticky;top:20px;overflow:hidden}.summary-top{padding:22px 24px;background:linear-gradient(120deg,#1f5fce,#4788ff);color:#fff;display:flex;align-items:center;gap:13px}.summary-top p{color:#dce9ff}.summary-icon{width:38px;height:38px;border-radius:12px;background:#ffffff24;display:grid;place-items:center;font-size:20px}.summary-body{padding:22px 24px}.summary-line{display:flex;justify-content:space-between;gap:15px;margin-bottom:19px;font-size:.9rem}.summary-line span{color:#78869a}.total-price{font-size:1.45rem;color:#1b5fd1}.order-summary .btn{border-radius:10px;font-weight:650}.order-summary .btn:disabled{opacity:.65}@media(max-width:991px){.order-summary{position:static}}@media(max-width:575px){.order-heading{align-items:flex-start;flex-direction:column}.order-card-title{padding:17px}.order-card .row.g-3{padding:16px!important}.order-card-title .btn{font-size:.75rem}}
</style>@endpush
@push('scripts')<script>
const services={{ Illuminate\Support\Js::from($servicesJson) }};
let row=0;
const money=value=>'Rp '+Number(value).toLocaleString('id-ID',{maximumFractionDigits:2});
function addRow(selected=null,qty='1'){
    const body=document.getElementById('itemRows'),index=row++,tr=document.createElement('tr');
    const serviceCell=document.createElement('td'),select=document.createElement('select');
    select.className='form-select item-select';select.name=`items[${index}][item_id]`;select.required=true;
    const placeholder=document.createElement('option');placeholder.value='';placeholder.textContent='Pilih layanan';select.appendChild(placeholder);
    services.forEach(service=>{const option=document.createElement('option');option.value=service.id;option.textContent=`${service.name} · ${service.category} · ${money(service.price)}/${service.unit}`;if(String(service.id)===String(selected))option.selected=true;select.appendChild(option)});
    serviceCell.appendChild(select);
    const qtyCell=document.createElement('td'),input=document.createElement('input');input.className='form-control qty-input';input.name=`items[${index}][qty]`;input.type='number';input.min='.01';input.step='.01';input.value=qty;input.required=true;input.inputMode='decimal';qtyCell.appendChild(input);
    const subtotalCell=document.createElement('td');subtotalCell.className='line-total text-end text-nowrap';subtotalCell.textContent=money(0);
    const removeCell=document.createElement('td'),remove=document.createElement('button');remove.type='button';remove.className='btn btn-sm btn-outline-danger remove-row';remove.setAttribute('aria-label','Hapus layanan');remove.textContent='×';removeCell.appendChild(remove);
    tr.append(serviceCell,qtyCell,subtotalCell,removeCell);body.appendChild(tr);
    select.addEventListener('change',calculate);input.addEventListener('input',calculate);remove.addEventListener('click',()=>{tr.remove();calculate()});calculate();
}
function calculate(){
    let total=0,days=0,count=0;
    document.querySelectorAll('#itemRows tr').forEach(tr=>{const service=services.find(item=>String(item.id)===tr.querySelector('select').value),qty=parseFloat(tr.querySelector('input').value)||0,subtotal=service?service.price*qty:0;total+=subtotal;if(service){days=Math.max(days,service.days);count++}tr.querySelector('.line-total').textContent=money(subtotal)});
    document.getElementById('total').textContent=money(total);document.getElementById('serviceCount').textContent=`${count} layanan`;
    const date=document.querySelector('[name=entry_date]').value;
    document.getElementById('estimate').textContent=days&&date?new Date(new Date(date+'T12:00:00').getTime()+days*86400000).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'}):'Pilih layanan';
}
document.getElementById('addRow')?.addEventListener('click',()=>addRow());document.querySelector('[name=entry_date]')?.addEventListener('change',calculate);
const previousItems={{ Illuminate\Support\Js::from($oldItemsJson) }};
if(previousItems.length){previousItems.forEach(item=>addRow(item.item_id,item.qty))}else{addRow()}
</script>@endpush
