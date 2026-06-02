---
name: Réutiliser Collective
colors:
  surface: '#fcf9f8'
  surface-dim: '#dcd9d9'
  surface-bright: '#fcf9f8'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f6f3f2'
  surface-container: '#f0eded'
  surface-container-high: '#eae7e7'
  surface-container-highest: '#e5e2e1'
  on-surface: '#1c1b1b'
  on-surface-variant: '#424843'
  inverse-surface: '#313030'
  inverse-on-surface: '#f3f0ef'
  outline: '#727973'
  outline-variant: '#c1c8c1'
  surface-tint: '#446552'
  primary: '#2a4a38'
  on-primary: '#ffffff'
  primary-container: '#41624f'
  on-primary-container: '#b7dcc4'
  inverse-primary: '#abcfb8'
  secondary: '#605e59'
  on-secondary: '#ffffff'
  secondary-container: '#e6e2db'
  on-secondary-container: '#66645f'
  tertiary: '#424444'
  on-tertiary: '#ffffff'
  tertiary-container: '#595b5b'
  on-tertiary-container: '#d2d3d3'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#c6ebd3'
  primary-fixed-dim: '#abcfb8'
  on-primary-fixed: '#002112'
  on-primary-fixed-variant: '#2d4d3b'
  secondary-fixed: '#e6e2db'
  secondary-fixed-dim: '#cac6bf'
  on-secondary-fixed: '#1c1c18'
  on-secondary-fixed-variant: '#484742'
  tertiary-fixed: '#e2e2e2'
  tertiary-fixed-dim: '#c6c6c7'
  on-tertiary-fixed: '#1a1c1c'
  on-tertiary-fixed-variant: '#454747'
  background: '#fcf9f8'
  on-background: '#1c1b1b'
  surface-variant: '#e5e2e1'
typography:
  display-lg:
    fontFamily: Libre Caslon Text
    fontSize: 80px
    fontWeight: '400'
    lineHeight: 90px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Libre Caslon Text
    fontSize: 48px
    fontWeight: '400'
    lineHeight: 56px
  headline-lg-mobile:
    fontFamily: Libre Caslon Text
    fontSize: 32px
    fontWeight: '400'
    lineHeight: 40px
  headline-md:
    fontFamily: Libre Caslon Text
    fontSize: 32px
    fontWeight: '400'
    lineHeight: 40px
  body-lg:
    fontFamily: Hanken Grotesk
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Hanken Grotesk
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-caps:
    fontFamily: Hanken Grotesk
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.1em
spacing:
  unit: 8px
  container-max: 1440px
  gutter: 24px
  margin-desktop: 64px
  margin-mobile: 20px
---

## Brand & Style

The design system for this sustainable fashion brand is built on the intersection of **Eco-Conscious Minimalism** and **High-Fashion Editorial**. It aims to evoke a sense of "Conscious Luxury"—where sustainability is not just functional, but aspirational and exclusive.

The visual style is characterized by a "Modern Classical" approach. It balances the raw, organic nature of upcycled materials with the sharp, disciplined layouts of premium fashion journals. Key visual motifs include:
- **Dashed Line-work:** Inspired by tailoring and stitch patterns, used as dividers and decorative accents.
- **Asymmetric Balance:** Editorial layouts that prioritize negative space to create a "breathable" and high-end feel.
- **Mixed Textures:** Large-scale high-definition photography of fabrics juxtaposed against flat, solid color blocks of cream and forest green.

## Colors

The palette is rooted in earth tones that feel both organic and sophisticated. 

- **Primary (Forest Green):** Represents the heart of the brand's sustainable mission. Used for primary calls-to-action, logos, and high-impact headlines.
- **Secondary (Cream/Beige):** Acts as the primary canvas for the UI, providing a warmer, more premium alternative to stark white.
- **Tertiary (White):** Used sparingly for negative space within components and to create crisp highlights.
- **Neutral (Carbon):** A near-black utilized exclusively for body text and fine-line iconography to ensure accessibility and readability against the cream background.

## Typography

The typography strategy leverages high-contrast serif pairings to establish editorial authority. 

**Libre Caslon Text** is used for all major headings. It carries the "Migra/Edinburgh" aesthetic—sharp serifs and classic proportions that signal exclusivity. For extremely large "Display" moments, use tight tracking to emphasize the letterforms.

**Hanken Grotesk** serves as the functional anchor. It is a clean, contemporary sans-serif that remains legible at small sizes. Use the "Label-caps" style for metadata, navigation links, and small descriptors to maintain an organized, catalog-like appearance.

## Layout & Spacing

The layout follows a **Fixed Grid** philosophy for desktop (12 columns) to maintain the rigid structure of a fashion lookbook. On mobile, the system transitions to a single-column fluid layout with generous vertical padding.

Spacing is governed by an 8px base unit, but intentionally utilizes "Large Air" (64px+) between major sections to prevent the UI from feeling cluttered. Content should often be offset from the center or span specific column ranges (e.g., text spanning columns 2-6 while an image spans 7-12) to create visual interest.

## Elevation & Depth

This design system avoids traditional shadows in favor of **Tonal Layering** and **Fine Outlines**. 

Depth is communicated through:
- **Stacking:** Elements are placed on top of one another using slight color shifts (e.g., a White card on a Cream background).
- **Stitch Lines:** 1px dashed or solid lines in Forest Green serve as the primary containers for content, replacing shadows to define edges.
- **Image Overlays:** Text often overlaps image boundaries slightly to create a physical, layered feel reminiscent of a collage or mood board.

## Shapes

The shape language is strictly **Sharp (0px)**. 

To reflect the "tailored" nature of upcycled fashion, all buttons, image containers, and input fields must have crisp 90-degree corners. This reinforces the architectural and modern feel of the brand. The only exception to this rule is the brand logo itself and the "Dashed Circle" pattern, which acts as a soft organic counterpoint to the rigid grid.

## Components

### Buttons
Primary buttons are solid Forest Green with White text, using the `label-caps` typography. They feature no rounding. Secondary buttons utilize a 1px Forest Green border with a transparent background.

### Cards (Product & Editorial)
Product cards should be borderless with the background set to the secondary Cream color. The product image should fill the width, followed by the product name in `headline-md` and price in `body-md`. 

### Input Fields
Inputs are defined by a single bottom border (1px solid Forest Green) rather than a full box, creating a minimalist "form" feel. Labels sit above the line in `label-caps`.

### The "Stitch" Divider
A custom component consisting of a 1px dashed line. Use this to separate sections or to "frame" specific pieces of content, mimicking the construction of a garment.

### Chips
Used for sizes or material types (e.g., "Organic Cotton", "Upcycled Denim"). These are small White rectangles with a 1px Forest Green border, utilizing `label-caps` text.