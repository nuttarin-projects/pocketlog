<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>สมัครสมาชิก - PocketLog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="bg-light d-flex align-items-center py-5 min-vh-100">
    <div class="container" style="max-width: 420px;">
        <div class="card border-0 shadow-sm p-4">
            <h4 class="text-center text-primary fw-bold mb-3">💰 PocketLog</h4>
            <p class="text-center text-muted small mb-4">สร้างบัญชีใหม่เพื่อเริ่มบันทึกรายรับ-รายจ่าย</p>

            @if($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">ชื่อ-นามสกุล</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">รหัสผ่าน (อย่างน้อย 6 ตัวอักษร)</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ยืนยันรหัสผ่านอีกครั้ง</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">สมัครสมาชิก</button>
            </form>

            <div class="text-center small">
                มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}" class="text-decoration-none">เข้าสู่ระบบ</a>
            </div>
        </div>
    </div>
</body>
</html>
