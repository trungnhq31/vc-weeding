---
name: Eloria Bright Pastel & Glassmorphism Wedding OS Design System
colors:
  surface: '#FAF8F5'
  surface-dim: '#F3EFEA'
  surface-bright: '#FFFDF9'
  surface-container-lowest: '#FFFFFF'
  surface-container-low: '#FDF2F8'
  surface-container: '#FCE7F3'
  surface-container-high: '#FBCFE8'
  surface-container-highest: '#F472B6'
  on-surface: '#881337'
  on-surface-variant: '#9F1239'
  inverse-surface: '#881337'
  inverse-on-surface: '#FAF8F5'
  outline: '#FBCFE8'
  outline-variant: '#F472B6'
  surface-tint: '#EC4899'
  primary: '#EC4899'
  on-primary: '#FFFFFF'
  primary-container: '#FDF2F8'
  on-primary-container: '#881337'
  inverse-primary: '#F43F5E'
  secondary: '#D97706'
  on-secondary: '#FFFFFF'
  secondary-container: '#FEF3C7'
  on-secondary-container: '#78350F'
  tertiary: '#10B981'
  on-tertiary: '#FFFFFF'
  tertiary-container: '#D1FAE5'
  on-tertiary-container: '#065F46'
  error: '#EF4444'
  on-error: '#FFFFFF'
  error-container: '#FEE2E2'
  on-error-container: '#991B1B'
  background: '#FAF8F5'
  on-background: '#881337'
typography:
  display-lg:
    fontFamily: Playfair Display
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Playfair Display
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Playfair Display
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
  title-md:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
rounded:
  sm: 0.75rem
  DEFAULT: 1.25rem
  md: 1.5rem
  lg: 2rem
  xl: 2.5rem
  full: 9999px
spacing:
  unit: 8px
  container-max: 1280px
  gutter: 24px
  margin-desktop: 48px
  margin-mobile: 16px
---

## Brand & Visual Style Directive — Bright Pastel & Glassmorphism

Eloria is the Operating System for Planning a Wedding.

### Aesthetic Principles (STRICTLY ENFORCED)
1. **Bright & Romantic Pastel Palette (NO DARK SLATE/BLACK BACKGROUNDS)**:
   - Base Background: Soft Warm Cream (`#FAF8F5`, `#FFFDF9`), Soft Rose Blush (`#FDF2F8`), Warm Ivory (`#FFFDF9`).
   - Accents: Soft Rose Gold (`#EC4899`, `#F43F5E`), Champagne Gold (`#D97706`, `#F59E0B`), Soft Sage Green (`#10B981`).
   - Text & Headings: Deep Warm Rosewood (`#881337`), Rich Slate (`#334155`).

2. **Pastel Gradients**:
   - Soft radiant backgrounds mixing blush pink, warm cream, and champagne gold:
     `bg-gradient-to-r from-rose-100/90 via-amber-50/70 to-pink-100/80`
     `bg-gradient-to-br from-rose-50 via-white to-amber-50/40`

3. **Glassmorphism Layering**:
   - Containers and cards use translucent glass surfaces with backdrop blur and white borders:
     `backdrop-blur-md bg-white/80 border border-white/70 shadow-lg shadow-rose-900/5`
     `backdrop-blur-lg bg-rose-50/60 border border-rose-200/50`

4. **Typography**:
   - Headlines: **Playfair Display** serif font for romantic elegance.
   - Body & Controls: **Inter** sans-serif font with generous line-height (`leading-relaxed`) and comfortable padding (`p-6` to `p-8`).

## Page Specifications for Dashboard Generation
- **Timeline Dashboard (`/wedding/timeline`)**: Master countdown hero banner with pastel gradient, 8-timeframe checklist cards, progress ring, budget summary.
- **Budget Manager (`/wedding/budget`)**: Cash flow ledger, auto budget allocator, overrun alert, deposit payment due dates.
- **Guest List & Seating (`/wedding/guests`)**: Guest groups, RSVP tracking pills, drag-and-drop seating canvas with round/rectangular tables.
- **Digital Invitation Customizer (`/wedding/invitation-editor`)**: Live wax seal modal button ("MỞ THIỆP"), template selector, countdown, wishes wall.
- **Contract & Documents Repository (`/wedding/documents`)**: Vendor contract storage cards, PDF preview modals, deposit payment milestone log.
