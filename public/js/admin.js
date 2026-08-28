// Admin Dashboard v3.0 Logic
let productModal;
let isInitializing = false;

console.log("Admin JS v3.0 Loaded - Monitoring UI");

// Prevent any accidental form submissions project-wide
document.addEventListener('submit', (e) => {
    e.preventDefault();
    console.warn("Blocked a form submission to prevent refresh.");
}, true);

async function initAdmin() {
    if (isInitializing) return;
    isInitializing = true;
    
    try {
        const pModalEl = document.getElementById('productModal');
        if (pModalEl && typeof bootstrap !== 'undefined') {
            productModal = new bootstrap.Modal(pModalEl);
        }

        const btnSave = document.getElementById('btnSaveProduct');
        if (btnSave) {
            btnSave.onclick = async function(e) {
                if(e) e.preventDefault();
                
                const pId = document.getElementById('editProductId').value;
                const name = document.getElementById('pName').value;
                const cashPrice = parseFloat(document.getElementById('pCashPrice').value);
                const instPrice = parseFloat(document.getElementById('pInstPrice').value);

                if (!name || isNaN(cashPrice) || isNaN(instPrice)) {
                    alert("Please fill in Name, Cash Price, and Installment Price.");
                    return;
                }

                btnSave.disabled = true;
                const originalText = btnSave.innerHTML;
                btnSave.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';

                try {
                    let imageUrls = [];
                    const fileInput = document.getElementById('pImages');
                    const files = fileInput ? fileInput.files : [];
                    
                    if (files.length > 0) {
                        for(let i=0; i < files.length; i++) {
                            const ref = storage.ref(`products/${Date.now()}_${files[i].name}`);
                            const snap = await ref.put(files[i]);
                            const url = await snap.ref.getDownloadURL();
                            imageUrls.push(url);
                        }
                    }

                    const productData = {
                        name: name,
                        brand: document.getElementById('pBrand').value,
                        model: document.getElementById('pModel').value,
                        specs: {
                            ram: document.getElementById('pRam').value || '',
                            storage: document.getElementById('pStorage').value || ''
                        },
                        cashPrice: cashPrice,
                        installmentPrice: instPrice,
                        stock: parseInt(document.getElementById('pStock').value) || 0,
                        updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                    };

                    if (imageUrls.length > 0) productData.images = imageUrls;

                    if (pId) {
                        await db.collection('Products').doc(pId).update(productData);
                    } else {
                        productData.createdAt = firebase.firestore.FieldValue.serverTimestamp();
                        if (!productData.images) productData.images = [];
                        await db.collection('Products').add(productData);
                    }

                    alert("Product saved successfully!");
                    if (productModal) productModal.hide();
                    loadDashboardData();
                } catch (err) {
                    console.error("Save error:", err);
                    alert("Save failed: " + err.message);
                } finally {
                    btnSave.disabled = false;
                    btnSave.innerHTML = originalText;
                }
            };
        }
    } catch (err) {
        console.error("Init error:", err);
    }
}

auth.onAuthStateChanged(function(user) {
    const loginSection = document.getElementById('loginSection');
    const adminDashboard = document.getElementById('adminDashboard');

    if (user) {
        if (loginSection) loginSection.classList.add('d-none');
        if (adminDashboard) adminDashboard.classList.remove('d-none');
        initAdmin();
        loadDashboardData();
    } else {
        if (loginSection) loginSection.classList.remove('d-none');
        if (adminDashboard) adminDashboard.classList.add('d-none');
        isInitializing = false;
    }
});

function bindLogin() {
    const btnLogin = document.getElementById('btnLogin');
    if (btnLogin) {
        btnLogin.onclick = async function(e) {
            if(e) e.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const pass = document.getElementById('loginPassword').value;

            if (!email || !pass) {
                alert("Enter email and password.");
                return;
            }

            btnLogin.disabled = true;
            const originalText = btnLogin.innerHTML;
            btnLogin.innerHTML = 'Logging in...';

            try {
                await auth.signInWithEmailAndPassword(email, pass);
            } catch (error) {
                alert("Login failed: " + error.message);
                btnLogin.disabled = false;
                btnLogin.innerHTML = originalText;
            }
        };
    } else {
        setTimeout(bindLogin, 500);
    }
}

async function loadDashboardData() {
    try {
        const [ordersSnap, productsSnap] = await Promise.all([
            db.collection('Orders').get(),
            db.collection('Products').get()
        ]);

        let revenue = 0;
        ordersSnap.forEach(doc => {
            revenue += (doc.data().downPayment || 0);
        });

        const statTotal = document.getElementById('statTotalOrders');
        const statRev = document.getElementById('statRevenue');
        if (statTotal) statTotal.innerText = ordersSnap.size;
        if (statRev) statRev.innerText = 'Rs. ' + revenue.toLocaleString();

        const tbody = document.getElementById('productTableBody');
        if (tbody) {
            tbody.innerHTML = '';
            productsSnap.forEach(doc => {
                const p = doc.data();
                tbody.innerHTML += `
                    <tr>
                        <td><img src="${(p.images && p.images[0]) || ''}" width="40" class="rounded"></td>
                        <td>${p.name}</td>
                        <td>${p.brand}</td>
                        <td>Rs. ${p.cashPrice.toLocaleString()}</td>
                        <td>${p.stock}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" onclick="editProduct('${doc.id}')">Edit</button>
                        </td>
                    </tr>`;
            });
        }
    } catch (e) {
        console.error("Load Error:", e);
    }
}

window.logout = () => auth.signOut().then(() => window.location.reload());

window.showSection = (name, e) => {
    if (e) e.preventDefault();
    document.querySelectorAll('.dashboard-section').forEach(s => s.classList.add('d-none'));
    const target = document.getElementById(name + 'Section');
    if (target) target.classList.remove('d-none');
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
    if (e && e.currentTarget) e.currentTarget.classList.add('active');
    return false;
};

window.openAddProductModal = () => {
    document.getElementById('editProductId').value = '';
    document.getElementById('pName').value = '';
    document.getElementById('pBrand').value = '';
    document.getElementById('pModel').value = '';
    document.getElementById('pCashPrice').value = '';
    document.getElementById('pInstPrice').value = '';
    document.getElementById('pStock').value = '';
    if (productModal) productModal.show();
};

window.editProduct = async (id) => {
    const doc = await db.collection('Products').doc(id).get();
    const p = doc.data();
    document.getElementById('editProductId').value = id;
    document.getElementById('pName').value = p.name || '';
    document.getElementById('pBrand').value = p.brand || '';
    document.getElementById('pModel').value = p.model || '';
    document.getElementById('pCashPrice').value = p.cashPrice || '';
    document.getElementById('pInstPrice').value = p.installmentPrice || '';
    document.getElementById('pStock').value = p.stock || '';
    document.getElementById('pRam').value = p.specs?.ram || '';
    document.getElementById('pStorage').value = p.specs?.storage || '';
    if (productModal) productModal.show();
};

bindLogin();
