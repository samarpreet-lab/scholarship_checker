# Design System Document: Academic Editorial

## 1. Overview & Creative North Star

### Creative North Star: "The Intellectual Curator"
This design system moves away from the generic, "form-heavy" aesthetic of traditional government or educational portals. Instead, it adopts an **Academic Editorial** approach. We treat scholarship eligibility not as a bureaucratic hurdle, but as a prestigious invitation. 

By leveraging **intentional asymmetry**, **exaggerated white space**, and **tonal depth**, we create an experience that feels like a high-end digital journal. The layout avoids rigid, centered grids in favor of a sophisticated "weighted" composition—where large, authoritative typography anchors the page, and interactive elements float within breathable, layered containers.

---

## 2. Colors & Surface Philosophy

The palette is anchored in deep, intellectual blues and "paper" whites. We avoid the "flatness" of standard web apps by utilizing a sophisticated layering system.

### The "No-Line" Rule
**Prohibit 1px solid borders for sectioning.** To maintain a premium, editorial feel, boundaries must be defined solely through background color shifts or subtle tonal transitions. Use `surface-container-low` against a `surface` background to denote change in context.

### Surface Hierarchy & Nesting
Treat the UI as a series of physical layers—like fine vellum or frosted glass. 
*   **Base Layer:** `surface` (#faf8ff)
*   **Sectional Shifts:** Use `surface-container` (#ededf6) for large content areas.
*   **Interactive Focus:** Use `surface-container-lowest` (#ffffff) for primary cards to create a "lifted" effect against the slightly tinted background.

### The "Glass & Gradient" Rule
To add visual "soul," use subtle gradients on primary CTAs. Transition from `primary` (#063669) to `primary_container` (#274e82) at a 135-degree angle. For floating navigation or modal overlays, apply `backdrop-blur: 12px` to a semi-transparent `surface` token to create a high-end glassmorphism effect.

---

## 3. Typography

The system uses a pairing of **Manrope** (Display/Headlines) and **Inter** (Body/Labels) to balance modern authority with high-utility readability.

*   **Display & Headline (Manrope):** These are your "Editorial Anchors." Use `display-lg` for hero eligibility results. The wide stance of Manrope conveys stability and institutional trust.
*   **Body & Title (Inter):** Inter is optimized for the density of scholarship criteria. Use `body-lg` for descriptions to ensure zero eye strain.
*   **Intentional Scale:** Use high contrast between `headline-lg` (2rem) and `body-md` (0.875rem) to create a clear information hierarchy that guides the student's eye through the eligibility process.

---

## 4. Elevation & Depth

We eschew traditional "drop shadows" for **Tonal Layering**. Depth is a result of light and material, not artificial ink.

*   **The Layering Principle:** Place a `surface-container-lowest` card on a `surface-container-low` background. This creates a natural "pop" without a single pixel of shadow.
*   **Ambient Shadows:** If a floating element (like a "Check Eligibility" FAB) is required, use a shadow with a 24px blur and 4% opacity, tinted with `on-surface` (#191b22). It should feel like a soft glow, not a dark smudge.
*   **The "Ghost Border" Fallback:** If a container requires more definition (e.g., in high-density data views), use a "Ghost Border": `outline-variant` (#c3c6d5) at **15% opacity**. Never use 100% opaque outlines.

---

## 5. Components

### Buttons
*   **Primary:** A gradient of `primary` to `primary_container`. Border radius: `md` (0.375rem). Text: `label-md` in `on-primary`.
*   **Secondary:** Ghost style. No background; `outline` token at 20% opacity. Text in `primary`.
*   **Tertiary:** No background or border. Text in `secondary`. Use for "Cancel" or "Back" actions.

### Input Fields
*   **The Editorial Input:** Abandon the four-sided box. Use a `surface-container-high` background with a `DEFAULT` (0.25rem) bottom-only radius. 
*   **Focus State:** Transition the background to `surface-container-highest` and add a 2px bottom-accent in `primary`.

### Cards & Lists
*   **The "No-Divider" Rule:** Forbid 1px horizontal lines between list items. Instead, use 16px of vertical whitespace or a subtle toggle between `surface` and `surface-container-low` backgrounds for zebra-striping.
*   **Eligibility Cards:** Use `xl` (0.75rem) corner radius. Content should be padded with a minimum of 32px to maintain the "Academic Editorial" breathing room.

### Progress Steppers (Scholarship Context)
*   Instead of a standard "dot" stepper, use a "Weighted Line." A thin `outline-variant` line that grows into a thick `primary` bar as the user completes eligibility steps.

---

## 6. Do's and Don'ts

### Do
*   **Do** use asymmetrical margins (e.g., a wider left margin for headlines) to mimic high-end magazine layouts.
*   **Do** use `tertiary` (#651f00) sparingly for "Urgent" or "Deadline" callouts—it provides a sophisticated warmth against the deep blues.
*   **Do** prioritize "Overlapping Elements." Allow a card to slightly overlap a header background to create a sense of architectural depth.

### Don't
*   **Don't** use pure black (#000000) for text. Always use `on-surface` (#191b22) to keep the contrast "soft-academic."
*   **Don't** use sharp 0px corners. Use the `DEFAULT` (0.25rem) or `md` (0.375rem) to maintain the "friendly yet official" requirement.
*   **Don't** crowd the interface. If a screen feels full, increase the `surface` padding rather than shrinking the text.