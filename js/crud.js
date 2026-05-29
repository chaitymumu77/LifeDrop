// crud.js - Handles Donor CRUD Operations and Search Parsing

$(document).ready(function () {
    initDonors();

    // -- 1. Load Dummy Data via AJAX (if empty) into LocalStorage --
    function initDonors() {
        if (!localStorage.getItem('lifedrop_donors')) {
            $.ajax({
                url: 'data/donors.json',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    localStorage.setItem('lifedrop_donors', JSON.stringify(data));
                    renderAdminTable();
                    renderPublicList();
                },
                error: function () {
                    console.error("Failed to load initial donors JSON.");
                }
            });
        } else {
            renderAdminTable();
            renderPublicList();
        }
    }

    function getDonors() {
        return JSON.parse(localStorage.getItem('lifedrop_donors')) || [];
    }

    function saveDonors(donors) {
        localStorage.setItem('lifedrop_donors', JSON.stringify(donors));
    }

    function getBadgeClass(bg) {
        if (bg.includes('A+')) return 'badge-ap';
        if (bg.includes('B+')) return 'badge-bp';
        if (bg.includes('O+')) return 'badge-op';
        if (bg.includes('-')) return 'badge-an';
        return 'bg-secondary';
    }

    // -- 2. READ (Public List Page) --
    function renderPublicList() {
        if ($('#publicDonorList').length === 0) return;
        let donors = getDonors();
        let html = '';
        donors.forEach(d => {
            html += `
                <div class="col-md-4 mb-3 donor-card" data-bg="${d.bloodGroup}" data-loc="${d.location.toLowerCase()}">
                    <div class="card h-100 shadow-sm" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'" onclick="window.location.href='donor-profile.php?id=${d.id}'">
                        <div class="card-body text-center">
                            <h2 class="text-danger mb-3">${d.bloodGroup}</h2>
                            <h5 class="card-title">${d.name}</h5>
                            <p class="card-text text-muted">
                                <i class="text-secondary">📍 ${d.location}</i><br>
                                📞 ${d.phone}
                            </p>
                            <button class="btn btn-outline-danger btn-sm mt-2">View Details</button>
                        </div>
                    </div>
                </div>
            `;
        });
        $('#publicDonorList').html(html);
    }

    // -- 3. READ (Admin Table) --
    function renderAdminTable() {
        if ($('#adminDonorTable').length === 0) return;
        let donors = getDonors();
        let $tbody = $('#adminDonorTable tbody');
        $tbody.empty();
        donors.forEach((d, index) => {
            let badgeClass = getBadgeClass(d.bloodGroup);
            $tbody.append(`
                <tr class="fade-in-row">
                    <td>${index + 1}</td>
                    <td>${d.name}</td>
                    <td><span class="badge ${badgeClass}">${d.bloodGroup}</span></td>
                    <td>${d.phone}</td>
                    <td>${d.location}</td>
                    <td>
                        <button class="btn btn-sm btn-primary edit-btn" data-id="${d.id}" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${d.id}">Delete</button>
                    </td>
                </tr>
            `);
        });
    }

    // -- 4. CREATE (Register or Admin Add) --
    window.addDonor = function (name, phone, bg, loc, age, weight, smoker, disease) {
        let donors = getDonors();
        let newDonor = {
            id: Date.now().toString(),
            name: name,
            phone: phone,
            bloodGroup: bg,
            location: loc,
            age: age || 18,
            weight: weight || 50,
            smoker: smoker || 'No',
            disease: disease || 'None'
        };
        donors.push(newDonor);
        saveDonors(donors);
        renderAdminTable();
        return true;
    };

    // Form submit intercept for add modal
    $('#addDonorForm').on('submit', function (e) {
        e.preventDefault();
        addDonor(
            $('#addName').val(),
            $('#addPhone').val(),
            $('#addBG').val(),
            $('#addLoc').val(),
            $('#addAge').val(),
            $('#addWeight').val(),
            $('#addSmoker').val(),
            $('#addDisease').val()
        );
        $('#addModal').modal('hide');
        this.reset();
        alert('Donor added successfully!');
    });

    // Register Form logic (Member 2)
    $('#registerDonorForm').on('submit', function (e) {
        e.preventDefault();
        addDonor(
            $('#regName').val(),
            $('#regPhone').val(),
            $('#regBG').val(),
            $('#regLoc').val(),
            $('#regAge').val(),
            $('#regWeight').val(),
            $('#regSmoker').val(),
            $('#regDisease').val()
        );
        $('#regAlert').removeClass('d-none').hide().fadeIn();
        this.reset();
    });

    // -- 5. UPDATE (Edit Donor) --
    // Populate modal
    $(document).on('click', '.edit-btn', function () {
        let id = $(this).data('id');
        let donors = getDonors();
        let donor = donors.find(d => d.id == id);
        if (donor) {
            $('#editId').val(donor.id);
            $('#editName').val(donor.name);
            $('#editPhone').val(donor.phone);
            $('#editBG').val(donor.bloodGroup);
            $('#editLoc').val(donor.location);
            $('#editAge').val(donor.age || '');
            $('#editWeight').val(donor.weight || '');
            $('#editSmoker').val(donor.smoker || 'No');
            $('#editDisease').val(donor.disease || '');
        }
    });

    // Save Edit
    $('#editDonorForm').on('submit', function (e) {
        e.preventDefault();
        let id = $('#editId').val();
        let donors = getDonors();
        let index = donors.findIndex(d => d.id == id);
        if (index > -1) {
            donors[index].name = $('#editName').val();
            donors[index].phone = $('#editPhone').val();
            donors[index].bloodGroup = $('#editBG').val();
            donors[index].location = $('#editLoc').val();
            donors[index].age = $('#editAge').val();
            donors[index].weight = $('#editWeight').val();
            donors[index].smoker = $('#editSmoker').val();
            donors[index].disease = $('#editDisease').val() || 'None';
            saveDonors(donors);
            renderAdminTable();
        }
        $('#editModal').modal('hide');
    });

    // -- 6. DELETE (Remove Donor) --
    $(document).on('click', '.delete-btn', function () {
        if (confirm("Are you sure you want to delete this donor?")) {
            let id = $(this).data('id');
            let donors = getDonors();
            donors = donors.filter(d => d.id != id);
            saveDonors(donors);

            // UI Element remove animation before logic update
            $(this).closest('tr').fadeOut(400, function () {
                renderAdminTable();
            });
        }
    });

    // -- 7. SEARCH/FILTERING using KeyUp event --
    $('#searchInput').on('keyup', function () {
        let query = $(this).val().toLowerCase();

        $('.donor-card').each(function () {
            let text = $(this).text().toLowerCase();
            if (text.includes(query)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Blood Group Filter 
    $('#bgFilter').on('change', function () {
        let filter = $(this).val();
        $('.donor-card').each(function () {
            if (filter === 'All' || $(this).data('bg') === filter) {
                $(this).fadeIn();
            } else {
                $(this).fadeOut();
            }
        });
    });

    // -- 8. DASHBOARD STATS (Dynamic DOM Updates) --
    if ($('#dashboardStats').length) {
        let donors = getDonors();
        $('#totalDonors').text(donors.length);

        let activeBgTypes = new Set(donors.map(d => d.bloodGroup)).size;
        $('#activeGroups').text(activeBgTypes);

        // Find most common
        let counts = {};
        donors.forEach(d => { counts[d.bloodGroup] = (counts[d.bloodGroup] || 0) + 1; });
        let maxBg = Object.keys(counts).reduce((a, b) => counts[a] > counts[b] ? a : b, 'N/A');
        $('#mostRequested').text(maxBg);
    }
});
