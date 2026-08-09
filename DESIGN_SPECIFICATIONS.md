# Premium Admin Dashboard & Moderation - Design Specifications

## Visual Hierarchy & Layout

### Dashboard Page Structure
```
┌─────────────────────────────────────────────────────┐
│  ADMIN DASHBOARD                                     │
│  Welcome back. Here's your performance overview.    │
└─────────────────────────────────────────────────────┘

┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐
│ Total    │  │ Pending  │  │ Active   │  │ Flagged  │
│ Members  │  │ Approvals│  │ Users    │  │ Profiles │
│    42    │  │    7     │  │   38     │  │    2     │
│↑ 12%    │  │ ⏳ Review │  │✓ Online  │  │⚠ Attn   │
└──────────┘  └──────────┘  └──────────┘  └──────────┘

┌─────────────────────────────────┐  ┌──────────────┐
│ Registration Trends (7-day)     │  │ Platform     │
│ ████ ████ ███ █████ ███ ██████ ██│  │ Health       │
│ System:   100%  ✓              │  │ DB:      98% │
│ Avg: 58/day  Peak: 72         │  │ API:   99.8% │
└─────────────────────────────────┘  │ Storage: 64% │
                                     └──────────────┘

┌──────────────────────────────────────────────────────┐
│ Recent Activity                                      │
│ ✓ Profile Approved    Sarah Johnson    2 mins ago   │
│ ⚠ Profile Flagged     Michael Smith    15 mins ago  │
│ ✨ New Registration   Emma Wilson      1 hour ago   │
│ ✕ Profile Rejected    James Brown      3 hours ago  │
│ 🔒 Verification Done  Lisa Anderson    5 hours ago  │
└──────────────────────────────────────────────────────┘
```

---

## Moderation Page Structure
```
┌──────────────────────────────────────────────────┐
│ PROFILE MODERATION                                │
│ Review and manage pending profile submissions    │
└──────────────────────────────────────────────────┘

[📋 All Pending (15)]  [⚠️ Flagged (2)]  [✓ Verified (8)]  [❌ Incomplete (5)]

┌─────────────────────────┐  ┌─────────────────────────┐
│ ┌───────────────────┐   │  │ ┌───────────────────┐   │
│ │  👤 Photo Area    │   │  │ │  👤 Photo Area    │   │
│ │  [Verified] [Top] │   │  │ │  ⚠ Incomplete     │   │
│ ├───────────────────┤   │  │ ├───────────────────┤   │
│ │ Sarah Johnson     │   │  │ │ Michael Smith     │   │
│ │ 28, New York      │  85%│ │ 32, Boston        │  72%│
│ │ Professional...   │   │  │ │ Seeking...        │   │
│ │ [▼ Show Details] │   │  │ │ [▼ Show Details] │   │
│ │ [✓ Approve] [⚠ Flag] [✕ Reject] │  │ [✓ Approve] [⚠ Flag] [✕ Reject] │
│ └───────────────────┘   │  │ └───────────────────┘   │
└─────────────────────────┘  └─────────────────────────┘

[Continues in 2-column grid...]
```

---

## Color & Style Guide

### Card Styles - Dashboard

**Base Card:**
- Background: `bg-white/5` (5% white opacity)
- Backdrop: `backdrop-blur-xl`
- Border: `border border-white/10` (10% white)
- Shadow: `shadow-2xl`
- Rounded: `rounded-2xl` (16px radius)

**Hover States:**
- Border: Brightens to `[color]/50` (50% opacity)
- Glow: `-inset-px gradient blur-xl` layer appears
- Background: Gradient overlay `from-[color]/10 to-transparent`
- Icons: `group-hover:scale-110` (110% size)

### Card Styles - Moderation

**Profile Cards:**
- Header Height: 192px (h-48) with gradient background
- Content Padding: 24px (p-6)
- Actions Grid: 3 equal columns with 12px gap
- Border Radius: 16px (rounded-2xl)

**Action Buttons:**
- Height: 48px (py-3)
- Padding: 16px (px-4)
- Border: 2px solid with color opacity
- Text: Bold, uppercase, tracking-wider
- On Action: Pulse animation (2s loop)

---

## Color Specifications

### Primary Brand Colors
```
Sapphire Blue:    #0f3a7d → Used for primary gradients, main accents
Teal Accent:      #17a2b8 → Used for secondary highlights
Coral Pink:       #ff6b6b → Used for alert/premium touches
```

### Status Colors
```
Success (Approve):   #10b981 (emerald-500)
Warning (Flag):      #f59e0b (amber-500)
Error (Reject):      #f43f5e (rose-500)
Info:               #06b6d4 (cyan-500)
```

### Neutral Palette
```
Dark BG:           #0f172a (slate-950)
Medium BG:         #0f172a-via-1e293b (slate-900)
Text Primary:      #ffffff (white)
Text Secondary:    #cbd5e1 (slate-300)
Text Muted:        #94a3b8 (slate-400)
Borders:           #f1f5f9/10 (white with 10% opacity)
```

---

## Animation Specifications

### Standard Transition (Cards)
- Duration: 300ms
- Timing: `cubic-bezier(0.4, 0, 0.2, 1)` (ease-in-out)
- Properties: `all` or specific (color, border, background)

### Hover Reveal
- Duration: 500ms
- Easing: Same as standard
- Effect: Opacity 0 → 100%, gradient overlay reveals
- Applied to: Card glow layers, background gradients

### Icon Scale
- Duration: 300ms
- Transform: `scale-100` → `scale-110` (110%)
- Trigger: Card hover via `group-hover`

### Button Action
- Pulse: 2s infinite loop
- Opacity: 1 → 0.5 → 1
- Trigger: While `actionInProgress === profileId`

### Expand Details
- Duration: 300ms
- Slide: Y-axis -8px → 0
- Fade: 0 → 100%
- Class: `.animate-in .slide-in-from-top-2`

### Filter Tab Active
- Duration: 300ms
- Background: Flat → Full gradient
- Border: `border-white/10` → `border-[color]/50`
- Shadow: None → `shadow-lg shadow-[color]/50`

---

## Responsive Breakpoints

### Dashboard
```
Mobile (< 768px):     1 column
Tablet (768-1024px):  2 columns  
Desktop (>1024px):    4 columns (stats), 3 columns (charts)
```

### Moderation
```
Mobile (< 768px):     1 column
Tablet (768-1024px):  1 column
Desktop (>1024px):    2 columns (lg:grid-cols-2)
```

---

## Typography Scale

| Element | Size | Weight | Letter Spacing | Case |
|---------|------|--------|-----------------|------|
| Page Title | 36px | bold | normal | normal |
| Card Title | 20px | bold | normal | normal |
| Metric Value | 48px | bold | normal | normal |
| Label | 14px | semibold | 0.1em | uppercase |
| Body | 16px | normal | normal | normal |
| Small | 13px | normal | normal | normal |
| Tiny | 12px | normal | normal | normal |

---

## Glassmorphism Formula

Perfect "expensive" glass effect requires:

1. **Backdrop Blur:** `backdrop-blur-xl` (24px blur)
2. **Base Transparency:** `bg-white/5` or `bg-slate/5`
3. **Subtle Border:** `border border-white/10`
4. **Depth Shadow:** `shadow-2xl` 
5. **Glow on Hover:** Absolute glow layer with `blur-xl`
6. **Gradient Overlay:** Subtle directional gradient

**Formula in Tailwind:**
```html
<div class="backdrop-blur-xl bg-white/5 border border-white/10 shadow-2xl rounded-2xl">
  <div class="absolute -inset-px bg-gradient-to-r from-[#0f3a7d]/20 to-[#17a2b8]/20 rounded-2xl opacity-0 hover:opacity-100 blur-xl -z-10"></div>
  <!-- Content -->
</div>
```

---

## Premium Spacing System

All spacing follows 8px base unit:

```
2px   (0.5 × 4px)
4px   (0.5 × 8px)
8px   (1 × 8px)      px-2, py-2
12px  (1.5 × 8px)    px-3, py-3
16px  (2 × 8px)      px-4, py-4, rounded-2xl
20px  (2.5 × 8px)    
24px  (3 × 8px)      px-6, py-6
32px  (4 × 8px)      px-8, py-8
48px  (6 × 8px)      gap-6
64px  (8 × 8px)      
```

---

## File Statistics

| File | Lines | Size | Components | Computed Properties |
|------|-------|------|-----------|-------------------|
| Dashboard.vue | 352 | 22KB | 1 main + 5 inline | 4 (stats, activity, chart, max) |
| Moderation.vue | 360 | 20KB | 1 main + grid | 3 (pending, filtered, filters) |

**Total Markup:** 712 lines
**Premium Features:** 50+ interactive elements
**Animations:** 8 keyframe animations + 15+ transitions
**Responsive Breakpoints:** Mobile/Tablet/Desktop optimized

