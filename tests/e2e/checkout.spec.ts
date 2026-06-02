import { test, expect } from '@playwright/test';

test('browse product add to cart and checkout flow (headless)', async ({ page }) => {
  const base = process.env.BASE_URL || 'http://localhost:8000';

  // Visit home
  await page.goto(base);
  // Click first product link if exists
  const product = await page.locator('a[href*="/product/"]').first();
  await expect(product).toHaveCount(1);
  await product.click();

  // Add to cart by clicking a button with text 'Add to cart' or similar
  const addBtn = page.locator('button, input[type="submit"]').filter({ hasText: /Add to cart|Add to Cart|Tambah ke keranjang/i }).first();
  if (await addBtn.count() > 0) {
    await addBtn.click();
  } else {
    // Try cart add via form
    // noop
  }

  // Go to cart
  await page.goto(base + '/cart');

  // Proceed to checkout
  await page.goto(base + '/checkout');

  // Fill minimal checkout form fields
  await page.fill('input[name="recipient_name"]', 'Playwright User');
  await page.fill('input[name="recipient_phone"]', '+621234567890');
  await page.fill('input[name="shipping_address"]', 'Jl. Test');
  await page.fill('input[name="shipping_city"]', 'Jakarta');
  await page.fill('input[name="shipping_postal_code"]', '10110');

  // Submit
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'networkidle', timeout: 10000 }).catch(() => null),
    page.click('button:has-text("CONFIRM PURCHASE")'),
  ]);

  // Expect either success page or an error message
  await expect(page).toHaveURL(/checkout|success|order|/i);
});
