# Changelog - Eloria Wedding Planner OS

Tất cả các thay đổi kiến trúc, tính năng và bản ghi cơ sở dữ liệu của dự án **Eloria — The Operating System for Planning a Wedding** được ghi nhận chi tiết tại đây.

---

## [Sprint 4] - 2026-07-30

### Added
- **Vendor CRM Engine (`app/Modules/Vendor/`):**
  - Migration `2026_07_30_000009_create_vendors_table.php` quản lý danh bạ đối tác (`workspace_id`, `name`, `category`, `contact_name`, `phone`, `email`, `contract_amount`, `paid_amount`, `payment_status`, `due_date`, `notes`).
  - Eloquent Model `Vendor` tích hợp `HasWorkspace` trait đảm bảo cô lập đa người dùng (multi-tenant).
  - CQRS Single Actions: `CreateVendorAction` khởi tạo nhà cung cấp và `RecordVendorPaymentAction` cập nhật lịch thanh toán dở dang/hoàn tất.
  - Service `VendorCrmService` tính tổng giá trị hợp đồng, tổng chi thực tế, dư nợ tồn và nhắc hạn thanh toán 7 ngày tới.
  - Inertia Page `Vendors.vue` thiết kế tối giản Notion/Linear style (`bg-slate-50`, bảng danh bạ sắc nét, modal tạo mới & thanh toán).
- **Grounded AI Assistant Engine (`app/Modules/GroundedAI/`):**
  - Service `GroundedDataQueryService` truy vấn dữ liệu thực tế duy nhất từ Workspace (Ngân sách, Dòng tiền, Task quá hạn, Hạn thanh toán Vendors, Sơ đồ bàn tiệc).
  - Action `QueryGroundedAiAction` xử lý truy vấn AI cam kết **ZERO Hallucination** (chỉ tổng hợp số liệu thực tế mà không tự sinh dữ liệu ảo).
  - Component Vue `GroundedAiDrawer.vue` tích hợp phím tắt toàn hệ thống `Cmd+K` / `Ctrl+K`.
- **Controllers & Routing:**
  - `VendorController` & `GroundedAiController` với các API/Inertia endpoints tương ứng.
- **Pest Unit & Feature Tests:**
  - Test suite `tests/Feature/VendorAndGroundedAiTest.php` kiểm tra toàn bộ luồng tạo nhà cung cấp, thanh toán, cô lập multi-tenant và độ chính xác của Grounded AI.

---

## [Sprint 3] - 2026-07-30

### Added
- **Interactive Seating Canvas & Tables Engine (`app/Modules/Guest/`):**
  - Migration `2026_07_30_000008_create_tables_table.php` hỗ trợ quản lý sơ đồ bàn tiệc (`table_name`, `capacity`, `zone_name`, `shape`).
  - Model `Table` và mối quan hệ `Table -> Guests`.
  - Service `SeatingPlannerService` kiểm tra xung đột số chỗ bàn tiệc (`over_capacity_alert`), tính toán số khách chưa xếp bàn.
  - Action `AssignGuestToTableAction` xử lý xếp vị trí khách mời vào bàn tiệc.
- **Invitation Catalog Live Preview Controller (`app/Modules/Invitation/`):**
  - Route `/wedding/templates/{template_id}/preview` hỗ trợ xem trước trực tiếp các mẫu thiệp.
  - Component Vue `InvitationTemplateSelector.vue` cho phép đổi mẫu thiệp live.

---

## [Sprint 2] - 2026-07-30

### Added
- **Core Task Management Engine (`app/Modules/Task/`):**
  - Migration `tasks` phân loại mảng công việc cưới, độ ưu tiên (`low`, `medium`, `high`, `urgent`), deadline và chi phí.
  - CQRS Actions: `CreateTaskAction`, `UpdateTaskStatusAction`.
- **Financial Cash Flow Engine (`app/Modules/Budget/`):**
  - Migration `budget_items` theo dõi chi phí thực tế vs ước tính và quản lý tiền cọc.
  - Service `CashFlowCalculatorService` tính toán tổng dòng tiền, dư nợ còn lại, cảnh báo vỡ ngân sách `is_overrun_alert`, và lọc danh sách nợ sắp đến hạn trong 7 ngày.
  - CQRS Actions: `CreateBudgetItemAction`, `RecordPaymentAction`.
- **Pest Unit & Feature Tests:** 11/11 tests passed 100%.

---

## [Sprint 1] - 2026-07-30

### Added
- **Multi-Tenant Architecture (`app/Modules/Workspace/`):**
  - Migrations `workspaces`, `workspace_members`, `invitation_templates`, `workspace_invitations`.
  - Global Scope Trait `HasWorkspace` cô lập dữ liệu theo `workspace_id`.
  - Nạp kho 4 mẫu thiệp cưới độc bản: `romantic-pastel`, `royal-gold`, `modern-slate`, `botanical-sage`.
