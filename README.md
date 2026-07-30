# Eloria — The Operating System for Planning a Wedding

> **Tagline:** "The operating system for planning a wedding."

**Eloria** là nền tảng SaaS Hệ Điều Hành Quản Lý Đám Cưới (Wedding OS) dành riêng cho Cô dâu, Chú rể, Gia đình, Wedding Planner và các Nhà cung cấp dịch vụ (Vendors).

---

## 💡 Triết Lý Cốt Lõi: "Workflow-First, Supporting AI"

Eloria không phải là một ứng dụng AI chatbot chung chung. **Eloria là một hệ điều hành quản lý công việc và dòng tiền chuyên nghiệp (Workflow-First)** với tốc độ xử lý tức thì, phím tắt phím nhanh (`Cmd+K`), cô lập Workspace đa đám cưới, và sơ đồ bàn tiệc kéo thả.

- **Workflow over AI:** Mọi dữ liệu về Ngân sách, Công việc, Khách mời, Sơ đồ bàn tiệc và Nhà cung cấp đều nằm trong một không gian làm việc hợp nhất (Unified Workspace).
- **Grounded AI (Không Ảo Giác):** AI đóng vai trò như một trợ lý quản lý dòng tiền — tự động phát hiện vỡ ngân sách, nhắc nhở thanh toán đợt cọc Vendor và tổng hợp tiến độ hoàn toàn dựa trên dữ liệu thực.

---

## 🛠️ Công Nghệ Nền Tảng (Tech Stack)

- **Backend:** Laravel 13.x (PHP 8.4+) kết hợp kiến trúc Domain-Driven Design (DDD) & CQRS.
- **Frontend:** Inertia.js v2 + Vue 3 (Composition API `<script setup>` với TypeScript).
- **Styling:** Tailwind CSS v4 + Lucide Icons.
- **Realtime WebSockets:** Laravel Reverb (Event Broadcasting & Nhắc nhở tự động).
- **Admin Panel:** FilamentPHP v5.
- **Database & Cache:** MySQL 8.0 & Redis 7.
- **Containerization:** Docker Compose (PHP-FPM, Nginx, MySQL, Redis, Reverb, Queue Worker, Vite Dev Server).

---

## 📌 Các Phân Hệ Chính Trong Eloria

1. **Dashboard & Multi-Tenant Workspace:** Quản lý nhiều đám cưới cô lập trên cùng một tài khoản, phân quyền Role-based (`groom`, `bride`, `planner`, `vendor`).
2. **Task & Workflow Engine:** Quản lý công việc chuẩn bị cưới theo phân loại, độ ưu tiên, deadline và chi phí.
3. **Financial Cash Flow Engine:** Quản lý ngân sách dòng tiền, đối soát chi phí thực tế vs ước tính, quản lý cọc và cảnh báo vỡ ngân sách tự động.
4. **Online Invitation Catalog Engine:** Kho 4 mẫu thiệp cưới độc bản (*Romantic Pastel, Royal Gold, Modern Slate, Botanical Sage*) với trình Live Preview và link cá nhân hóa kèm QR Code check-in.
5. **Interactive Seating Canvas:** Sơ đồ bàn tiệc kéo thả, gán khách mời và tự động kiểm tra cảnh báo sức chứa bàn tiệc (`over_capacity_alert`).
6. **Vendor CRM & Document Center:** Quản lý danh bạ nhà cung cấp, lưu trữ hợp đồng chứng từ và theo dõi đợt thanh toán 1/2/3.

---

## 🚀 Khởi Chạy Hệ Thống Với Docker

### Lifecycle Commands
- **Khởi động tất cả dịch vụ:** `docker compose up -d` (hoặc `npm run docker:up`)
- **Tải lại migrations & seeders:** `docker compose exec app php artisan migrate:fresh --seed`
- **Chạy bộ kiểm thử tự động (Pest PHP):** `docker compose exec app vendor/bin/pest`

### Cổng Dịch Vụ (Service Topology)
- **Eloria Web Application:** [http://localhost:8085](http://localhost:8085)
- **Eloria WebSockets (Reverb):** Port 8082
- **MySQL Database:** Port 3307
- **Redis Cache & Queue:** Port 6381

---

## 📄 Tài Liệu Phát Triển (Documentation)

- **Sprint Roadmap:** [docs/SPRINTS_ROADMAP.md](docs/SPRINTS_ROADMAP.md)
- **Changelog:** [docs/CHANGELOG.md](docs/CHANGELOG.md)
- **System Architecture Rules:** [.agents/AGENTS.md](.agents/AGENTS.md)
