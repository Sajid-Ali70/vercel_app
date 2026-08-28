// Global state
let allProducts = [];

// DOM Elements - Using conditional checks for multi-page support
const productGrid = document.getElementById('productGrid');
const bookingForm = document.getElementById('bookingForm');
const refundForm = document.getElementById('refundForm');

// Initialize Modals only if they exist on current page
const bookingModalEl = document.getElementById('bookingModal');
const bookingModal = bookingModalEl ? new bootstrap.Modal(bookingModalEl) : null;

const refundModalEl = document.getElementById('refundModal');
const refundModal = refundModalEl ? new bootstrap.Modal(refundModalEl) : null;

// Initialization
document.addEventListener('DOMContentLoaded', () => {
    // If we are on the Catalog page
    if (productGrid) {
        fetchProducts();
        setupFilters();
    }

    // Handle forms if they exist
    if (bookingForm) setupBookingForm();
    if (refundForm) setupRefundForm();
});

async function fetchProducts() {
    try {
        const snapshot = await db.collection('Products').orderBy('createdAt', 'desc').get();
        allProducts = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
        
        const countEl = document.getElementById('productCount');
        if (countEl) countEl.innerText = `${allProducts.length} devices found`;
        
        renderProducts(allProducts);
        populateBrandFilter(allProducts);
    } catch (error) {
        console.error("Error fetching products: ", error);
        if (productGrid) productGrid.innerHTML = '<div class="col-12 text-center text-danger">Failed to load products.</div>';
    }
}

function renderProducts(products) {
    if (!productGrid) return;
    
    productGrid.innerHTML = '';
    if (products.length === 0) {
        productGrid.innerHTML = '<div class="col-12 text-center py-5"><h4>No mobiles match your search.</h4></div>';
        return;
    }

    products.forEach(product => {
        const card = `
            <div class="col-sm-6 col-lg-4">
                <div class="card product-card position-relative h-100">
                    ${product.isFeatured ? '<span class="badge badge-featured shadow-sm">Featured</span>' : ''}
                    <img src="${(product.images && product.images[0]) || 'https://via.placeholder.com/300'}" class="card-img-top" alt="${product.name}">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-light text-dark border">${product.brand}</span>
                        </div>
                        <h5 class="card-title fw-bold">${product.name}</h5>
                        <div class="mt-auto">
                            <div class="price-tag mb-1">₦${Number(product.cashPrice).toLocaleString()}</div>
                            <div class="installment-price mb-3">
                                <i class="fas fa-clock me-1"></i> ₦${Number(product.installmentPrice).toLocaleString()}/mo (${product.installmentDuration} months)
                            </div>
                            <button onclick="openBooking('${product.id}')" class="btn btn-primary-custom w-100 fw-bold">Book Now</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        productGrid.innerHTML += card;
    });
}

function openBooking(productId) {
    const product = allProducts.find(p => p.id === productId);
    if (!product || !bookingModal) return;

    document.getElementById('selectedProductId').value = productId;
    
    // Inject product summary into modal
    const summary = document.getElementById('bookingProductSummary');
    if (summary) {
        summary.innerHTML = `
            <img src="${product.images[0]}" alt="${product.name}" style="width: 60px; height: 60px; object-fit: contain;" class="me-3 bg-white rounded">
            <div>
                <h6 class="mb-0 fw-bold">${product.name}</h6>
                <small class="text-muted">Cash Price: ₦${Number(product.cashPrice).toLocaleString()}</small>
            </div>
        `;
    }

    document.getElementById('installmentSummary').classList.add('d-none');
    bookingForm.reset();
    bookingModal.show();
}

function updateInstallmentUI() {
    const plan = document.getElementById('paymentPlan').value;
    const summary = document.getElementById('installmentSummary');
    const productId = document.getElementById('selectedProductId').value;
    const product = allProducts.find(p => p.id === productId);

    if (!product) return;

    if (plan === 'cash') {
        summary.classList.add('d-none');
    } else {
        const duration = parseInt(plan);
        // Base monthly for the product's default duration
        const baseMonthly = product.installmentPrice;
        const baseDuration = product.installmentDuration || 3;
        const totalWithInterest = baseMonthly * baseDuration;
        
        // Simple recalculation for different durations (approximate)
        const adjustedMonthly = totalWithInterest / duration;
        
        summary.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <span><strong>Monthly Payment:</strong></span>
                <span class="h5 mb-0 fw-bold text-primary">₦${Math.round(adjustedMonthly).toLocaleString()}</span>
            </div>
            <div class="small text-muted mt-1">Plan duration: ${duration} months</div>
        `;
        summary.classList.remove('d-none');
    }
}

function setupBookingForm() {
    bookingForm.onsubmit = async (e) => {
        e.preventDefault();
        const btn = e.target.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';

        try {
            const productId = document.getElementById('selectedProductId').value;
            const product = allProducts.find(p => p.id === productId);
            const file = document.getElementById('idCardUpload').files[0];
            
            // Upload ID Card
            const storageRef = storage.ref(`id_cards/${Date.now()}_${file.name}`);
            const snapshot = await storageRef.put(file);
            const idCardUrl = await snapshot.ref.getDownloadURL();

            const orderId = 'ORD-' + Math.random().toString(36).substr(2, 9).toUpperCase();
            
            const orderData = {
                orderId: orderId,
                productId: productId,
                productName: product.name,
                customer: {
                    fullName: document.getElementById('custName').value,
                    phone: document.getElementById('custPhone').value,
                    email: document.getElementById('custEmail').value,
                    address: document.getElementById('deliveryAddress').value
                },
                paymentPlan: document.getElementById('paymentPlan').value,
                downPayment: parseFloat(document.getElementById('downPayment').value),
                totalAmount: product.cashPrice,
                status: 'Pending',
                idCardImage: idCardUrl,
                createdAt: firebase.firestore.FieldValue.serverTimestamp(),
                updatedAt: firebase.firestore.FieldValue.serverTimestamp()
            };

            await db.collection('Orders').add(orderData);
            alert(`Booking Successful! Order ID: ${orderId}. We will contact you shortly.`);
            bookingModal.hide();
        } catch (error) {
            console.error(error);
            alert('Booking failed. Please try again.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    };
}

function showRefundModal() {
    if (refundModal) refundModal.show();
}

function setupRefundForm() {
    refundForm.onsubmit = async (e) => {
        e.preventDefault();
        const btn = e.target.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Submitting...';

        try {
            const file = document.getElementById('proofUpload').files[0];
            const storageRef = storage.ref(`refund_proofs/${Date.now()}_${file.name}`);
            const snapshot = await storageRef.put(file);
            const proofUrl = await snapshot.ref.getDownloadURL();

            const refundData = {
                orderReference: document.getElementById('orderRef').value,
                phone: document.getElementById('refundPhone').value,
                reason: document.getElementById('refundReason').value,
                description: document.getElementById('refundDesc').value,
                bankDetails: {
                    bankName: document.getElementById('bankName').value,
                    accNumber: document.getElementById('accNumber').value,
                    accHolder: document.getElementById('accHolder').value
                },
                proofOfPayment: proofUrl,
                status: 'Pending',
                createdAt: firebase.firestore.FieldValue.serverTimestamp()
            };

            await db.collection('Refunds').add(refundData);
            alert('Refund request submitted successfully. We will review it within 48 hours.');
            refundModal.hide();
        } catch (error) {
            console.error(error);
            alert('Submission failed.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    };
}

// Filters logic
function setupFilters() {
    const searchBar = document.getElementById('searchBar');
    const brandFilter = document.getElementById('brandFilter');
    const priceFilter = document.getElementById('priceFilter');

    if (searchBar) searchBar.oninput = filterProducts;
    if (brandFilter) brandFilter.onchange = filterProducts;
    if (priceFilter) priceFilter.onchange = filterProducts;
}

function populateBrandFilter(products) {
    const brandFilter = document.getElementById('brandFilter');
    if (!brandFilter) return;

    const brands = [...new Set(products.map(p => p.brand))];
    brandFilter.innerHTML = '<option value="">All Brands</option>';
    brands.forEach(brand => {
        const opt = document.createElement('option');
        opt.value = brand;
        opt.textContent = brand;
        brandFilter.appendChild(opt);
    });
}

function filterProducts() {
    const search = document.getElementById('searchBar').value.toLowerCase();
    const brand = document.getElementById('brandFilter').value;
    const priceRange = document.getElementById('priceFilter').value;

    let filtered = allProducts.filter(p => {
        const matchesSearch = p.name.toLowerCase().includes(search) || p.brand.toLowerCase().includes(search);
        const matchesBrand = brand === '' || p.brand === brand;
        
        let matchesPrice = true;
        if (priceRange === '0-50000') matchesPrice = p.cashPrice <= 50000;
        else if (priceRange === '50000-150000') matchesPrice = p.cashPrice > 50000 && p.cashPrice <= 150000;
        else if (priceRange === '150000+') matchesPrice = p.cashPrice > 150000;

        return matchesSearch && matchesBrand && matchesPrice;
    });

    renderProducts(filtered);
    
    const countEl = document.getElementById('productCount');
    if (countEl) countEl.innerText = `${filtered.length} devices found`;
}
