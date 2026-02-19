# Gallery Carousel Testing Guide

## Overview
Gallery carousel dengan responsive pagination untuk menampilkan unlimited items dengan sliding animation.

## Architecture

### CSS (public/css/style.css)
```
Desktop (> 1024px):     3 items per view, gap: 2rem
                        flex: 0 0 calc((100% - 2 * 2rem) / 3)

Tablet (≤ 1024px):      2 items per view, gap: 2rem
                        flex: 0 0 calc(50% - 1rem) = calc((100% - 2rem) / 2)

Mobile (≤ 768px):       1 item per view
                        flex: 0 0 100%
```

### JavaScript (resources/views/pages/home.blade.php)

**Responsive Detection:**
```javascript
function getItemsPerView() {
    if (window.innerWidth > 1024) return 3;
    if (window.innerWidth > 768) return 2;
    return 1;
}
```

**Carousel State:**
- `galleryState.items` - Array of gallery items from API
- `galleryState.itemsPerView` - Dynamic based on screen width
- `galleryState.currentIndex` - Current starting position (0-based)
- `galleryState.isMoving` - Lock during animation (400ms)

**Pagination Logic:**
```javascript
// Calculate max viewable start position
const maxIndex = Math.max(0, items.length - itemsPerView);

// Next button
newIndex = (currentIndex + 1) > maxIndex ? 0 : currentIndex + 1; // Wrap to start

// Prev button  
newIndex = (currentIndex - 1) < 0 ? maxIndex : currentIndex - 1; // Wrap to end
```

**Offset Calculation:**
```javascript
const itemWidth = firstItem.offsetWidth;        // Actual rendered width (px)
const gap = 32;                                  // 2rem = 32px
const offsetPixels = -index * (itemWidth + gap); // Total slide distance
carousel.style.transform = `translateX(${offsetPixels}px)`;
```

### API (app/Http/Controllers/GalleryController.php)

**Endpoint:** `GET /api/gallery`
- Returns: Array of Gallery items with is_active=true
- Filter: `Gallery::active()->get()->toArray()` - Only active items
- Includes: `image_url` computed from image_path via Model accessor

**Model Attributes:**
- `title` - Gallery title
- `description` - Gallery description
- `image_path` - Path to stored image (e.g., 'storage/galleries/filename')
- `is_active` - Boolean flag (must be true to appear)
- `order` - Display order (default: incrementing)
- `image_url` - Computed URL via `getImageUrlAttribute()`

## Testing Scenarios

### ✅ Scenario 1: 4 Items on Desktop (3 per view)
1. Create 4 gallery items via admin dashboard
2. Verify homepage shows items 1,2,3 initially
3. Click Next → should show 2,3,4
4. Click Next → should wrap to 1,2,3
5. Click Prev → should show 4,1,2 (from wrapped position)

**Expected Console Output:**
```
[Build] Items: 4, Per View: 3, Max Index: 1
[Move 1] Index: 1, Items: 4, ItemWidth: ??px, Offset: -??px
```

### ✅ Scenario 2: 6 Items on Desktop
1. Create 6 items
2. Initial view: 1,2,3
3. After Click 1: 2,3,4
4. After Click 2: 3,4,5
5. After Click 3: 4,5,6
6. After Click 4: 1,2,3 (wrap around)

**Expected Final Position:** itemOffset = -(3 × itemWidth) for item 4

### ✅ Scenario 3: 2 Items on Tablet View (2 per view)
1. Create 2 items and open on tablet/1024px viewport
2. Should show both items (no wrapping needed)
3. Next button should wrap immediately
4. Check CSS applies: `flex: 0 0 calc(50% - 1rem)`

### ✅ Scenario 4: Single Item on Mobile
1. Create any number of items, view on mobile (< 768px)
2. All items should display one per view
3. Pagination should work normally
4. Check CSS applies: `flex: 0 0 100%`

### ✅ Scenario 5: Responsive Resize
1. Create 4+ items on desktop
2. View initial carousel state (3 items visible)
3. Manually resize browser window to tablet size
4. Gallery should automatically reflow to 2 items per view
5. Current index should reset to 0
6. Click next should progress through items correctly

**Expected Console Output:**
```
[Resize] Changed from 3 to 2 items per view
```

### ✅ Scenario 6: Edit/Update Image
1. Create gallery item with image
2. Admin: Edit item, upload new image
3. Success message appears
4. Admin page: Verify thumbnail shows new image (cache-bust)
5. User page: Refresh and verify new image displays

**Cache Busting:** All image URLs include `?t=${Date.now()}` parameter

## Debug Console Commands

### View Current State
```javascript
console.table({
    items: galleryState.items.length,
    perView: galleryState.itemsPerView,
    currentIndex: galleryState.currentIndex,
    maxIndex: Math.max(0, galleryState.items.length - galleryState.itemsPerView),
    isMoving: galleryState.isMoving
});
```

### Force Carousel Scroll to Specific Index
```javascript
updateSlidePosition(2); // Go to item 3 (0-based)
```

### Reload Gallery Data
```javascript
fetchGalleryData(); // Fetch from API and rebuild
```

### Manually Calculate Offset (for debugging)
```javascript
const firstItem = document.querySelector('.gallery-item');
const itemWidth = firstItem.offsetWidth;
const offset = -2 * (itemWidth + 32); // 2 items over, 32px gap
console.log(`ItemWidth: ${itemWidth}px, Offset: ${offset}px`);
```

## Common Issues & Solutions

### Issue: Only 3 Items Show, Can't Scroll to Item 4
**Cause:** CSS flex-basis includes gap in percentage calculation
**Solution:** Updated to `calc((100% - 2 * 2rem) / 3)` to exclude gap from width

### Issue: Items Visible but Can't Click Buttons
**Cause:** `.carousel-nav` has `pointer-events: none`, buttons need `pointer-events: all`
**Solution:** Already implemented in CSS

### Issue: Images Not Showing in Admin List
**Cause:** image_url accessor returning hardcoded fallback
**Solution:** Changed to return null, let frontend handle fallback

### Issue: Edited Images Still Show Old Version
**Cause:** Browser cache + model accessor not returning updated path
**Solution:** 
1. Cache busting with `?t=${Date.now()}` parameter
2. Model set is_active=true in controller update
3. Explicit toArray() in responses

### Issue: Gallery Shifts Awkwardly on Resize
**Cause:** itemsPerView changes mid-scroll to item out of viewport range
**Solution:** Added window resize handler that resets currentIndex to 0 with debouncing

## Performance Considerations

- **Animation Duration:** 400ms (CSS transition)
- **Auto-refresh:** Every 30 seconds (checks for new items via API)
- **Resize Debounce:** 250ms (prevents excessive recalculation)
- **Move Lock:** Prevents rapid clicking (400ms animation block)

## Files Modified

1. [public/css/style.css](public/css/style.css#L254-L266)
   - Gallery item flex-basis corrected for gap calculation
   - Media queries updated and verified

2. [resources/views/pages/home.blade.php](resources/views/pages/home.blade.php#L100-L120)
   - Added `getItemsPerView()` responsive detection
   - Added window resize listener with debounce
   - Updated `buildGalleryHTML()` with better logging
   - Fixed `updateSlidePosition()` to use pixel-based offset
   - Added gap constant (32px) for accurate calculation

3. [app/Models/Gallery.php](app/Models/Gallery.php#L25-L35)
   - `getImageUrlAttribute()` returns null (not hardcoded fallback)
   - Allows frontend to choose appropriate fallback via localImages

4. [app/Http/Controllers/GalleryController.php](app/Http/Controllers/GalleryController.php#L14-L16)
   - `index()` returns `Gallery::active()->get()->toArray()`
   - Only active items displayed
   - `store()` and `update()` set is_active=true by default

## Verification Steps

Before marking as complete, verify:

- [ ] Create 4 items via admin, see all 3 initially on homepage
- [ ] Click next, see items 2,3,4
- [ ] Click next, see items wrap to 1,2,3
- [ ] Click prev, see items go backward correctly
- [ ] Edit item image, see new image on both admin and homepage
- [ ] Resize to tablet, verify 2 items per view
- [ ] Resize to mobile, verify 1 item per view
- [ ] Open console, verify no errors and pagination logs showing correctly

