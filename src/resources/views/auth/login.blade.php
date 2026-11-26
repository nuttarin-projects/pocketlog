<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ - PocketLog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container" style="max-width: 400px;">
        <div class="card border-0 shadow-sm p-4">
            <h4 class="text-center text-primary fw-bold mb-3">💰 PocketLog</h4>
            <p class="text-center text-muted small mb-4">เข้าสู่ระบบเพื่อจัดการบัญชีของคุณ</p>

            @if($errors->any())
                <div class="alert alert-danger py-2 small">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">รหัสผ่าน</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">เข้าสู่ระบบ</button>
            </form>

            <div class="text-center small">
                ยังไม่มีบัญชี? <a href="{{ route('register') }}" class="text-decoration-none">สมัครสมาชิก</a>
            </div>
        </div>
    </div>
</body>
</html>
