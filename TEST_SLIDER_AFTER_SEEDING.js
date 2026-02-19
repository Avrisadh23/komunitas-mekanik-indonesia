/**
 * QUICK TEST: Gallery Slider After Seeding
 * Copy-paste ke DevTools Console
 */

console.log('\n' + '='.repeat(60));
console.log('🎯 GALLERY SLIDER STATUS AFTER SEEDING');
console.log('='.repeat(60) + '\n');

// CHECK 1: Gallery State
console.log('📊 STATE:');
console.log('  ✓ Total Items:', galleryState.items.length, '(should be 9)');
console.log('  ✓ Items Per View:', galleryState.itemsPerView, '(should be 3)');
console.log('  ✓ Current Index:', galleryState.currentIndex, '(should be 0)');

const maxIdx = Math.max(0, galleryState.items.length - galleryState.itemsPerView);
console.log('  ✓ Max Valid Index:', maxIdx, '(should be 6)');

// CHECK 2: DOM Elements
const carousel = document.getElementById('galleryCarousel');
console.log('\n📐 DOM:');
console.log('  ✓ Items in DOM:', carousel?.children.length, '(should be 9)');

if (carousel && carousel.children.length > 0) {
    const firstItem = carousel.children[0];
    const itemWidth = firstItem.offsetWidth;
    const gap = 32;
    console.log('  ✓ Item Width:', itemWidth, 'px');
    console.log('  ✓ Gap:', gap, 'px');
    console.log('  ✓ Per Slide Width:', (itemWidth + gap) * 3, 'px (for 3 items)');
}

// CHECK 3: Display
console.log('\n👁️  DISPLAY:');
console.log('  Index 0 shows items: 1, 2, 3');
console.log('  Index 1 shows items: 2, 3, 4');
console.log('  Index 2 shows items: 3, 4, 5');
console.log('  ... dst');

// CHECK 4: Quick Test
console.log('\n🧪 TO TEST:');
console.log('  1. Look at page - should show 3 items');
console.log('  2. Click Next button - should show items 2, 3, 4');
console.log('  3. Click Next again - should show items 3, 4, 5');
console.log('  4. Click Prev - should go back');
console.log('  5. Click Next 7 times total - should loop back to items 1, 2, 3');

console.log('\n' + '='.repeat(60) + '\n');
