<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PocketLog - บัญชีรายรับรายจ่าย</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="bg-light">

<div class="container py-4" style="max-width: 850px;">
    <!-- ส่วนหัว ชื่อผู้ใช้ ปุ่มออกจากระบบ และตัวเลือกเดือน -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex align-items-center gap-3">
            <h1 class="h3 fw-bold text-primary mb-0">💰 PocketLog</h1>
            <span class="badge bg-secondary-subtle text-dark border px-2 py-1">
                👤 {{ Auth::user()->name }}
            </span>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- ฟอร์มเลือกเดือน -->
            <form method="GET" action="{{ route('pocketlog.index') }}" class="d-flex align-items-center gap-2">
                <label class="form-label mb-0 text-nowrap small">เดือน:</label>
                <input type="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()" class="form-control form-control-sm">
            </form>

            <!-- ปุ่มออกจากระบบ -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger text-nowrap">ออกจากระบบ</button>
            </form>
        </div>
    </div>

    <!-- แจ้งเตือนเมื่อบันทึก/ลบสำเร็จ -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- การ์ดสรุปยอดรายเดือน -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 border-start border-success border-4 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">รายรับรวม ({{ $selectedMonth }})</div>
                    <div class="h4 fw-bold text-success mb-0">+{{ number_format($totalIncome, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 border-start border-danger border-4 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">รายจ่ายรวม ({{ $selectedMonth }})</div>
                    <div class="h4 fw-bold text-danger mb-0">-{{ number_format($totalExpense, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 border-start border-primary border-4 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">คงเหลือสุทธิ</div>
                    <div class="h4 fw-bold {{ $balance >= 0 ? 'text-primary' : 'text-danger' }} mb-0">
                        {{ number_format($balance, 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ฟอร์มบันทึกรายการรายวัน -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">บันทึกรายการใหม่</h5>
            <form method="POST" action="{{ route('transactions.store') }}" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <select name="type" class="form-select" required>
                        <option value="expense">รายจ่าย (-)</option>
                        <option value="income">รายรับ (+)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="title" placeholder="ชื่อรายการ เช่น ค่ากาแฟ" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="amount" placeholder="จำนวนเงิน" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary w-100">+ บันทึกรายการ</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ตารางแสดงรายการรายวัน -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">รายการประจำเดือน {{ $selectedMonth }}</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>วันที่</th>
                        <th>รายการ</th>
                        <th class="text-end">จำนวนเงิน</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $item)
                        <tr>
                            <td class="text-muted small">{{ \Carbon\Carbon::parse($item->transaction_date)->format('d/m/Y') }}</td>
                            <td class="fw-medium">{{ $item->title }}</td>
                            <td class="text-end fw-bold {{ $item->type === 'income' ? 'text-success' : 'text-danger' }}">
                                {{ $item->type === 'income' ? '+' : '-' }}{{ number_format($item->amount, 2) }}
                            </td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('transactions.destroy', $item->id) }}" onsubmit="return confirm('ยืนยันการลบรายการนี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">ยังไม่มีรายการในเดือนนี้</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
