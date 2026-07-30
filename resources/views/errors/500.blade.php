<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Lỗi Máy Chủ | Quốc Trung & Hồng Vân</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Be Vietnam Pro"', 'sans-serif'],
                        serif: ['"Cormorant Garamond"', 'serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-[#FAF8F5] text-slate-800 font-sans flex items-center justify-center p-6 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-rose-100/70 via-amber-50/40 to-[#FAF8F5] antialiased">
    
    <div class="max-w-xl w-full text-center space-y-8 bg-white/90 backdrop-blur-md p-8 md:p-12 rounded-3xl border border-rose-200/90 shadow-2xl shadow-rose-200/40 relative overflow-hidden">
        
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-100/90 text-rose-900 text-xs font-bold uppercase tracking-widest border border-rose-300">
            ⚠️ Đang Kiểm Tra Máy Chủ
        </div>

        <div class="space-y-3 relative z-10">
            <h1 class="text-7xl md:text-8xl font-serif font-extrabold text-rose-950 tracking-tight">500</h1>
            <h2 class="text-2xl md:text-3xl font-serif font-bold text-rose-900">Hệ Thống Đang Cập Nhật Tạm Thời</h2>
            <p class="text-sm md:text-base font-serif italic text-slate-600 leading-relaxed max-w-md mx-auto">
                "Đang có gián đoạn nhỏ kết nối dịch vụ. Vui lòng tải lại trang hoặc quay lại sau ít phút."
            </p>
        </div>

        <div class="pt-4 border-t border-rose-100 space-y-3 relative z-10">
            <div class="flex flex-wrap items-center justify-center gap-3 text-xs font-bold">
                <button onclick="window.location.reload()" class="px-6 py-3 rounded-full bg-rose-600 hover:bg-rose-700 text-white shadow-md shadow-rose-200 transition-all cursor-pointer">
                    🔄 Thử Tải Lại Trang
                </button>
                <a href="/wedding" class="px-6 py-3 rounded-full bg-rose-100 hover:bg-rose-200 text-rose-900 border border-rose-300 transition-all">
                    🌹 Về Trang Thiệp Cưới
                </a>
            </div>
        </div>

        <div class="pt-4 text-[11px] text-slate-400 font-serif italic">
            Trân trọng • VCWedding Engine • Quốc Trung & Hồng Vân
        </div>
    </div>

</body>
</html>
