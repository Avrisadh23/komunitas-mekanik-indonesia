/**
 * DEBUG GALLERY SLIDER
 * Copy-paste ke DevTools Console untuk quick debug
 */

console.log('\n🔧 GALLERY SLIDER DEBUG');
console.log('=' .repeat(50));

// 1. Check container width
const container = document.querySelector('.carousel-container');
console.log('\n1️⃣  CONTAINER:');
console.log('  - querySelector("#galleryCarousel"):', document.getElementById('galleryCarousel'));
console.log('  - Container width:', container?.offsetWidth, 'px');
console.log('  - Window width:', window.innerWidth, 'px');

// 2. Check items
const carousel = document.getElementById('galleryCarousel');
console.log('\n2️⃣  ITEMS IN DOM:');
console.log('  - Total children:', carousel?.children.length);
if (carousel) {
    Array.from(carousel.children).forEach((item, idx) => {
        console.log(`    Item ${idx + 1}: width=${item.offsetWidth}px, class=${item.className}`);
    });
}

// 3. Check CSS gap
const gridStyle = window.getComputedStyle(document.querySelector('.gallery-grid'));
console.log('\n3️⃣  CSS STYLES:');
console.log('  - Gap:', gridStyle.gap);
console.log('  - Display:', gridStyle.display);
console.log('  - Overflow-x:', gridStyle.overflowX);
console.log('  - Transform:', gridStyle.transform);

// 4. Check galleryState
console.log('\n4️⃣  GALLERY STATE:');
console.log('  - Current index:', galleryState?.currentIndex);
console.log('  - Items per view:', galleryState?.itemsPerView);
console.log('  - Total items loaded:', galleryState?.items?.length);
console.log('  - Is moving:', galleryState?.isMoving);

// 5. Check current transform
console.log('\n5️⃣  CURRENT TRANSFORM:');
const carouselStyle = carousel?.style.transform;
console.log('  - Applied:', carouselStyle || 'none');

// 6. Manual calculation
if (carousel) {
    const firstItem = carousel.querySelector('.gallery-item');
    const itemW = firstItem?.offsetWidth || 0;
    const gap = 32;
    const nextOffset = -(1 * (itemW + gap));
    console.log('\n6️⃣  MANUAL NEXT SLIDE CALCULATION:');
    console.log(`  - Item width: ${itemW}px`);
    console.log(`  - Gap: ${gap}px`);
    console.log(`  - Should be offset: ${nextOffset}px`);
    console.log(`  - Current offset:  ${carouselStyle}`);
}

console.log('\n' + '='.repeat(50) + '\n');

// Quick test function
window.testNextSlide = () => {
    console.log('\n🧪 Testing nextGallery()...');
    nextGallery();
};

console.log('📌 Run testNextSlide() to test next button\n');
