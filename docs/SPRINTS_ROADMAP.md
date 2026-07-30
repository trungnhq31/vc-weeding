# Eloria Wedding Planner OS - Development Sprints & Architecture Roadmap

Tài liệu này tổng hợp toàn bộ Lộ trình Phát triển (Development Sprints Roadmap) của sản phẩm **Eloria — The Operating System for Planning a Wedding** dành cho Cô dâu, Chú rể, Wedding Planner và Nhà cung cấp dịch vụ (Vendors).

---

## 📌 Sprint Overview & Status Matrix

| Sprint | Tên Phân Hệ (Module Scope) | Trạng Thái | Công Việc Trọng Tâm |
| :--- | :--- | :--- | :--- |
| **Sprint 1** | Multi-Tenant Architecture & Invitation Catalog | ✅ **HOÀN THÀNH** | Schema `workspaces`, `workspace_members`, `invitation_templates`, `workspace_invitations`, Global Scope `HasWorkspace`, 4 Mẫu Thiệp Mời. |
| **Sprint 2** | Core Workflow Engine (Task & Financial Budgeting) | ✅ **HOÀN THÀNH** | Module `Task` (Kanban, Deadline, Priority), Module `Budget` (Cash Flow Calculator, Overrun Alert, Deposit Tracking, Reminders). |
| **Sprint 3** | Invitation Catalog Engine, Guests & Seating Canvas | ✅ **HOÀN THÀNH** | Live Preview Mẫu Thiệp, Quản lý Khách Mời (Xác nhận tham dự), Sơ đồ Bàn tiệc Kéo thả (Seating Canvas) & Kiểm tra quá tải bàn. |
| **Sprint 4** | Vendor CRM & Grounded AI Assistant | ✅ **HOÀN THÀNH** | Quản lý Nhà cung cấp (Vendors CRM), Lưu trữ Hợp đồng/Chứng từ, AI Grounded Assistant (Cảnh báo vỡ ngân sách, nhắc nợ không ảo giác). |

---

## 🚀 Detailed Sprint Breakdown

### Sprint 1: Multi-Tenant Workspace & Base DDD Architecture
- **Mục tiêu:** Xây dựng nền tảng cô lập dữ liệu theo `workspace_id`, phân quyền theo vai trò (`groom`, `bride`, `planner`, `vendor`).
- **Database Migrations:**
  - `workspaces`: Lưu thông tin đám cưới, ngày tổ chức, trần ngân sách.
  - `workspace_members`: Phân quyền thành viên.
  - `invitation_templates`: Kho 4 mẫu thiệp cưới (`romantic-pastel`, `royal-gold`, `modern-slate`, `botanical-sage`).
  - `workspace_invitations`: Lưu cấu hình thiệp cưới riêng của từng Workspace.
- **DDD Structure:** Khởi tạo `app/Modules/Workspace/` và `app/Modules/Invitation/`.

### Sprint 2: Core Workflow Engine - Task & Financial Budgeting
- **Mục tiêu:** Xây dựng hệ thống quản lý công việc và dòng tiền ngân sách chuẩn xác.
- **Database Migrations:**
  - `tasks`: Quản lý danh mục công việc theo mảng (*Venue, Attire, Media, Ceremony, Reception*), mức độ ưu tiên và hạn chót.
  - `budget_items`: Theo dõi chi phí dự kiến vs thực tế, khoản đã cọc và hạn thanh toán đợt tiếp theo.
- **Services & CQRS:**
  - `CashFlowCalculatorService`: Tính toán tổng trần ngân sách, tổng đã chi, dư nợ còn lại, cảnh báo vỡ ngân sách `is_overrun_alert` và lọc các khoản nợ sắp đến hạn trong 7 ngày.
  - CQRS Actions: `CreateTaskAction`, `UpdateTaskStatusAction`, `CreateBudgetItemAction`, `RecordPaymentAction`.

### Sprint 3: Invitation Catalog Engine, Guest Management & Seating Planner Canvas
- **Mục tiêu:** Cung cấp trình xem trước/tùy biến mẫu thiệp cưới live, quản lý danh sách khách mời (xác nhận tham dự thuần Việt) và sơ đồ bàn tiệc kéo thả.
- **Database Migrations:**
  - `tables`: Quản lý sơ đồ bàn tiệc (`workspace_id`, `table_name`, `capacity`, `zone_name`, `shape`).
  - Thêm `table_id` liên kết trong `guests`.
- **Phân Hệ Thiệp Cưới Online Live Preview:**
  - Giao diện Live Preview hỗ trợ chuyển đổi linh hoạt giữa 4 mẫu thiệp.
  - Tùy chỉnh tiêu đề, màu sắc chủ đạo, nhạc nền, bật/tắt phong bì sáp và QR check-in.
- **Phân Hệ Sơ Đồ Bàn Tiệc (Seating Canvas):**
  - Gán khách mời vào bàn tiệc, kiểm tra sức chứa bàn tiệc, cảnh báo quá tải (`table_over_capacity`).

### Sprint 4: Vendor CRM & Grounded AI Assistant Engine
- **Mục tiêu:** Quản lý nhà cung cấp, lưu trữ hợp đồng chứng từ và trợ lý AI thông minh từ dữ liệu thực (Zero Hallucination).
- **Database Migrations:**
  - `vendors`: Quản lý nhà cung cấp đối tác (`workspace_id`, `name`, `category`, `contact_name`, `phone`, `email`, `contract_amount`, `paid_amount`, `payment_status`, `due_date`, `notes`).
- **DDD Modules & Services:**
  - `app/Modules/Vendor`: CQRS `CreateVendorAction`, `RecordVendorPaymentAction`, Service `VendorCrmService`.
  - `app/Modules/GroundedAI`: Service `GroundedDataQueryService` & Action `QueryGroundedAiAction` (Zero Hallucination).
- **Inertia Pages & Components:**
  - `Vendors.vue`: Giao diện Vendor CRM Notion/Linear style (`bg-slate-50`, crisp tables, payment modals).
  - `GroundedAiDrawer.vue`: Trợ lý AI truy vấn nhanh số liệu thực tế qua phím tắt `Cmd+K`.

