// ── DUMMY DATA ──
const donors = [
  { id: 1, name: "Rahim Uddin", blood: "A+", location: "Chittagong", phone: "01711-234567", age: 28, weight: 72, smoker: false, disease: "None", lastDonation: "2025-12-10" },
  { id: 2, name: "Salma Begum", blood: "O+", location: "Dhaka", phone: "01812-345678", age: 32, weight: 58, smoker: false, disease: "None", lastDonation: "2026-05-01" },
  { id: 3, name: "Karim Hossain", blood: "B+", location: "Sylhet", phone: "01911-456789", age: 25, weight: 65, smoker: false, disease: "None", lastDonation: null },
  { id: 4, name: "Nasrin Akter", blood: "AB+", location: "Chittagong", phone: "01711-567890", age: 30, weight: 55, smoker: false, disease: "None", lastDonation: "2026-04-15" },
  { id: 5, name: "Tariq Ahmed", blood: "O-", location: "Rajshahi", phone: "01611-678901", age: 35, weight: 80, smoker: true, disease: "Mild Asthma", lastDonation: "2025-11-20" },
  { id: 6, name: "Fatema Khanam", blood: "A-", location: "Dhaka", phone: "01511-789012", age: 27, weight: 52, smoker: false, disease: "None", lastDonation: "2026-03-01" },
  { id: 7, name: "Mizan Rahman", blood: "B-", location: "Comilla", phone: "01811-890123", age: 40, weight: 75, smoker: false, disease: "None", lastDonation: null },
  { id: 8, name: "Riya Chowdhury", blood: "AB-", location: "Chittagong", phone: "01911-901234", age: 22, weight: 50, smoker: false, disease: "None", lastDonation: "2025-10-05" },
  { id: 9, name: "Sohel Rana", blood: "O+", location: "Barishal", phone: "01711-012345", age: 29, weight: 68, smoker: false, disease: "None", lastDonation: "2026-01-10" },
  { id: 10, name: "Mita Dey", blood: "A+", location: "Khulna", phone: "01612-123456", age: 33, weight: 60, smoker: false, disease: "None", lastDonation: null },
  { id: 11, name: "Arif Islam", blood: "B+", location: "Chittagong", phone: "01512-234567", age: 26, weight: 70, smoker: true, disease: "None", lastDonation: "2026-02-14" },
  { id: 12, name: "Shahnaz Parvin", blood: "O+", location: "Mymensingh", phone: "01812-345679", age: 38, weight: 62, smoker: false, disease: "Diabetes (controlled)", lastDonation: "2025-09-20" },
];

const bloodRequests = [
  { id: 1, patient: "Abdul Mannan", blood: "O+", hospital: "Chittagong Medical College", units: 2, phone: "01711-111222", status: "pending", date: "2026-06-20" },
  { id: 2, patient: "Rokeya Sultana", blood: "A+", hospital: "Square Hospital Dhaka", units: 1, phone: "01812-222333", status: "pending", date: "2026-06-19" },
  { id: 3, patient: "Jahangir Alam", blood: "B-", hospital: "Sylhet MAG Osmani", units: 3, phone: "01911-333444", status: "completed", date: "2026-06-18" },
  { id: 4, patient: "Champa Rani", blood: "AB+", hospital: "DMCH Dhaka", units: 2, phone: "01611-444555", status: "pending", date: "2026-06-17" },
];

const notices = [
  { id: 1, title: "🚨 Urgent: O- Blood Needed in Chittagong", body: "Chittagong Medical College Hospital urgently needs O- blood donors. Please contact 01711-999000.", date: "2026-06-22" },
  { id: 2, title: "📢 Blood Donation Camp - July 5th", body: "LifeDrop is organizing a free blood donation camp at Chittagong City Corporation Hall. All donors are welcome.", date: "2026-06-20" },
  { id: 3, title: "✅ Thank You Donors!", body: "Thanks to our 12 registered donors, we successfully fulfilled 3 emergency requests this month.", date: "2026-06-15" },
];

// ── HELPERS ──
function eligibility(lastDonation) {
  if (!lastDonation) return { eligible: true, msg: "Ready for first donation!" };
  const last = new Date(lastDonation);
  const today = new Date();
  const months = (today.getFullYear() - last.getFullYear()) * 12 + (today.getMonth() - last.getMonth());
  if (months >= 3) return { eligible: true, msg: `Last donated ${months} months ago. Eligible!` };
  return { eligible: false, msg: `Donated ${months} month(s) ago. Wait ${3 - months} more month(s).` };
}

function formatDate(d) {
  if (!d) return "N/A";
  return new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

function navHTML(activePage) {
  const pages = [
    ["index.html", "Home"],
    ["donor-list.html", "Find Donor"],
    ["search.html", "Search"],
    ["faq.html", "FAQ"],
    ["notice.html", "Notice"],
    ["contact.html", "Contact"],
  ];
  const session = JSON.parse(localStorage.getItem('lifeDropUser') || 'null');
  let authLink = `<li><a href="login.html" ${activePage === 'login' ? 'class="active"' : ''}>Login</a></li>`;
  if (session) {
    if (session.role === 'admin') {
      authLink = `<li><a href="dashboard.html" ${activePage === 'dashboard' ? 'class="active"' : ''}>Admin Dash</a></li>
                  <li><a href="#" onclick="logout()">Logout</a></li>`;
    } else {
      authLink = `<li><a href="profile.html" ${activePage === 'profile' ? 'class="active"' : ''}>My Account</a></li>
                  <li><a href="#" onclick="logout()">Logout</a></li>`;
    }
  }
  return `
    <nav class="navbar">
      <a class="navbar-brand" href="index.html">🩸 LifeDrop</a>
      <button class="hamburger" onclick="toggleNav()">☰</button>
      <ul class="nav-links" id="navLinks">
        ${pages.map(([href, label]) => `<li><a href="${href}" ${activePage === href.replace('.html','') ? 'class="active"' : ''}>${label}</a></li>`).join('')}
        ${authLink}
      </ul>
    </nav>`;
}

function footerHTML() {
  return `<footer><p>© 2026 <span>LifeDrop</span> Blood Management System — Chittagong, Bangladesh</p></footer>`;
}

function toggleNav() {
  document.getElementById('navLinks').classList.toggle('open');
}

function logout() {
  localStorage.removeItem('lifeDropUser');
  window.location.href = 'login.html';
}
