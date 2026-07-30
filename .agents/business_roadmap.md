# BUSINESS ROADMAP 24 THÁNG & CHỈ SỐ NORTH STAR METRIC — ELORIA WEDDING OS

---

## 🎯 Vision (Mục Tiêu Cuối Cùng)
**Eloria trở thành nền tảng quản lý và kết nối toàn bộ hệ sinh thái cưới tại Đông Nam Á.**
- Không phải một ứng dụng checklist đơn thuần.
- Là **Hệ điều hành điều phối (Operating System)** kết nối Dâu Rể, Wedding Planner, Ekip Vendor và Khách mời trên một môi trường duy nhất.

---

## ⭐ North Star Metric (Chỉ Số Định Hướng Hàng Đầu)
> **Chỉ số đo lường:** `Active Wedding Workspace`

Một Workspace được tính là **Active** khi thỏa mãn đồng thời 4 điều kiện:
1.  **≥2 thành viên** cùng truy cập (Cộng tác Dâu & Rể, hoặc Dâu Rể & Planner).
2.  **≥20 tasks** được khởi tạo và theo dõi tiến độ.
3.  **≥1 vendor** được liên kết quản lý hợp đồng/thanh toán đợt.
4.  **Có cập nhật trong vòng 7 ngày gần nhất**.

---

## 🗺️ Roadmap 24 Tháng (Phân Kỳ 8 Giai Đoạn)

### Phase 0 - Discovery (Tháng 1)
- **Mục tiêu:** Thấu hiểu quy trình thực tế trước khi viết dòng code đầu tiên.
- **Công việc:** Phỏng vấn 30 cặp đôi, 20 wedding planner, 20 vendor; thu thập file Excel, mẫu báo giá, hợp đồng, quy trình làm việc thực tế.
- **Deliverables:** PRD, User Journey, ERD, Wireframe.
- **KPI:** 70 cuộc phỏng vấn, 100 tài liệu thực tế, Top 20 pain points.

### Phase 1 - MVP (Tháng 2 – 4)
- **Mục tiêu:** Cung cấp giá trị ngay lập tức cho Cặp đôi sử dụng.
- **Tính năng:** Authentication, Wedding Workspace, Dashboard, Checklist, Budget, Guest, Timeline, Documents. *(Không AI, Không Marketplace, Không Payment)*.
- **KPI:** 50 workspace, 20 đám cưới thật, Retention D30 > 40%.

### Phase 2 - Collaboration (Tháng 5 – 7)
- **Mục tiêu:** Mở rộng trải nghiệm cộng tác đa người dùng.
- **Tính năng:** Invite Partner, Người thân gia đình, Bình luận, Mention (@), Notification, Activity Log, Phân quyền Role & Permission.
- **KPI:** Trung bình > 2.5 thành viên/workspace, 60% workspace có tính năng cộng tác.

### Phase 3 - Planner Edition (Tháng 8 – 10)
- **Mục tiêu:** Chuyển dịch chiến lược từ B2C sang B2B SaaS cho Agency.
- **Tính năng:** Multi-wedding Management, CRM Khách hàng, Báo giá, Hợp đồng, Calendar, Kanban điều phối Ekip.
- **KPI:** 10 agency dùng thử, 5 agency trả phí, 100 wedding được quản lý.

### Phase 4 - Vendor Edition (Tháng 11 – 13)
- **Mục tiêu:** Thu hút các Nhà cung cấp dịch vụ (Studio, Decor, Venue, Catering).
- **Tính năng:** Vendor Portal, Booking, Quote, Invoice, Tracking mốc nợ cọc 1/2/3, Team Schedule.
- **KPI:** 50 vendor active, 200 booking, 30% vendor hoạt động hàng tuần.

### Phase 5 - Marketplace (Tháng 14 – 18)
- **Mục tiêu:** Mở nền tảng kết nối 2 chiều Cầu & Cung.
- **Tính năng:** Search Vendor, Compare Quote, Review & Rating, Request for Quote (RFQ).
- **KPI:** 500 RFQ, 100 giao dịch/tháng, Conversion Rate RFQ → Booking > 15%.

### Phase 6 - Grounded AI (Tháng 19 – 21)
- **Mục tiêu:** Tích hợp AI hỗ trợ thông minh dựa trên dữ liệu thật.
- **Tính năng:** Budget Advisor, Timeline Optimizer, Smart Checklist, Contract Summary, Vendor Recommendation.
- **KPI:** 30% người dùng sử dụng AI hàng tuần, AI giúp giảm ≥20% thời gian lập kế hoạch.

### Phase 7 - Monetization & Regional Scale (Tháng 22 – 24)
- **Mục tiêu:** Đạt mốc tài chính bền vững và chuẩn bị mở rộng Đông Nam Á (Thái Lan, Indonesia, Philippines).
- **Tính năng:** Premium Couple, Premium Planner, Premium Vendor, Phí hoa hồng sàn & Phí cổng thanh toán.
- **KPI:** 300 khách trả phí, MRR > 5.000 USD, Churn < 5%/tháng.

---

## 🚦 Các Mốc "Go / No-Go" Decision Gates

```
[Gate 1: Sau MVP] ──► Retention D30 < 25% hoặc < 20 Active Workspace?
                      └─► NGUYÊN TẮC: Dừng viết tính năng mới, quay lại phỏng vấn người dùng.

[Gate 2: Sau Planner Edition] ──► Wedding Planner chưa sẵn sàng trả tiền?
                                  └─► NGUYÊN TẮC: Không vội xây Marketplace.

[Gate 3: Sau Vendor Edition] ──► Tối thiểu 100 Vendors + 300 Active Weddings?
                                 └─► NGUYÊN TẮC: Chỉ mở Marketplace khi đủ thanh khoản 2 chiều.
```

---

## 📊 Bảng Đề Xuất OKR Theo Quý (Quarterly Matrix)

| Quý | Objective (Mục Tiêu Nòng Cốt) | Key Results (Kết Quả Then Chốt) |
| :--- | :--- | :--- |
| **Q1** | Xác thực nhu cầu thực tế | 70 phỏng vấn người dùng, 50 Workspace thử nghiệm |
| **Q2** | Đạt Product-Market Fit (PMF) | D30 Retention > 40%, 100 Active Workspaces |
| **Q3** | Chứng minh khách hàng B2B trả tiền | 10 Planners dùng thử, 5 Planners trả phí hàng tháng |
| **Q4** | Xây dựng nền tảng hai chiều | 50 Active Vendors, 100 Bookings, MRR > 1.000 USD |
