---
name: wedding-os-architecture
description: Directives, domain-driven design guidelines, multi-tenancy rules, CQRS patterns, and grounded AI specifications for Wedding OS SaaS.
---

# Wedding OS Architecture & Domain-Driven Design (DDD) Skill

This skill governs the execution of all SaaS architectural features in **Wedding OS**.

## 1. Directory & Layer Structure (`app/Modules/`)

Every feature module in Wedding OS follows strict Domain-Driven Design isolation under `app/Modules/`:

```
app/Modules/{DomainName}/
├── Actions/                  # CQRS Commands (Single-Action write logic)
│   └── CreateTaskAction.php
├── Queries/                  # CQRS Queries (Read-only data getters for Inertia)
│   └── GetBudgetOverviewQuery.php
├── Data/                     # Readonly Data Transfer Objects (DTOs)
│   └── TaskData.php
├── Models/                   # Eloquent Models (Scoped with HasWorkspace trait)
│   └── Task.php
├── Events/                   # Domain Events for Notification Engine
│   └── VendorPaymentDue.php
├── Requests/                 # Form Validation Requests
│   └── StoreTaskRequest.php
└── Services/                 # Complex Domain Services
    └── CashFlowCalculator.php
```

---

## 2. Multi-Tenancy Scoping Rules

1. **Workspace Scope:** Every Model under `app/Modules/*/Models` (except `User` and `Workspace`) MUST contain a `workspace_id` column.
2. **Global Scope Trait:** Use a global scope trait `HasWorkspace` to automatically append `WHERE workspace_id = ?` to all database calls.
3. **Pest Feature Testing:** Every Pest test must assert that User A from Workspace 1 cannot access or mutate Task / Budget / Guest data belonging to Workspace 2.

---

## 3. Workflow-First & Keyboard Shortcuts Rules

- **Design Standard:** Clean, crisp, high-density Notion/Linear-style UI (`bg-slate-50`, `border-slate-200`, `text-slate-900`).
- **Keyboard Shortcuts System:** 
  - `Cmd + K` or `Ctrl + K`: Universal Command Palette.
  - `C`: Create Task / Add Budget Item / Register Guest.
  - `Esc`: Close drawers and modals instantly.

---

## 4. Grounded AI Engine Rules

- **Zero Hallucination Guarantee:** The AI assistant MUST ONLY execute structured queries against the database via `GroundedDataQueryService`.
- **Query Types Allowed:**
  - *"How much have I spent so far?"* -> Sum of `actual_amount` in `budget_items` for active workspace.
  - *"Which vendors have unpaid deposits?"* -> Query `vendors` joining `budget_items` where `payment_status != 'fully_paid'`.
  - *"Are there any overdue tasks?"* -> Query `tasks` where `due_date < NOW()` and `status != 'done'`.
- **Prohibited:** NEVER prompt LLM to generate generic advice without workspace context.
