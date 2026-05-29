// app.js - Global Interactions, Animations, and AJAX

$(document).ready(function () {

    // 1. Navbar Active State Highlighter
    var currentPath = window.location.pathname.split('/').pop();
    if (currentPath == '') currentPath = 'index.html'; // default
    $('.nav-link').each(function () {
        var href = $(this).attr('href');
        if (href.includes(currentPath)) {
            $(this).addClass('active');
        }
    });

    // 2. Fade In Animation on Load (Page UI Effect)
    $('.fade-in-on-load').hide().fadeIn(1200);

    // 3. Emergency Blood Request Form Submission
    $('#bloodRequestForm').on('submit', function (e) {
        e.preventDefault();
        let pName = $('#patientName').val();
        let bGroup = $('#bloodGroup').val();
        let hName = $('#hospitalName').val();
        let units = $('#bloodUnits').val();
        let phone = $('#contactPhone').val();
        
        if (!pName || !bGroup || !phone) {
            $('#requestAlert').hide().removeClass('alert-success').addClass('alert-danger').text('Please fill in all required fields.').slideDown();
        } else {
            // Send data to backend PHP script
            $.ajax({
                type: 'POST',
                url: 'process_request.php',
                data: { 
                    patient_name: pName, 
                    blood_group: bGroup, 
                    hospital: hName, 
                    units: units, 
                    phone: phone 
                },
                success: function(response) {
                    $('#requestAlert').hide().removeClass('alert-danger').addClass('alert-success').text(response).slideDown();
                    $('#bloodRequestForm')[0].reset();
                },
                error: function(xhr) {
                    $('#requestAlert').hide().removeClass('alert-success').addClass('alert-danger').text('Submission failed. Please check your connection.').slideDown();
                }
            });
        }
    });

    // 4. AJAX Call #1: Load Hospitals JSON on Contact Page
    if ($('#hospitalList').length) {
        $.ajax({
            url: 'data/hospitals.json',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                let html = '';
                data.forEach(h => {
                    html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                ${h.name} <span class="badge bg-primary rounded-pill">${h.location}</span>
                             </li>`;
                });
                $('#hospitalList').html(html).hide().slideDown(800);
            },
            error: function () {
                $('#hospitalList').html('<li class="list-group-item text-danger">Failed to load hospital partners.</li>');
            }
        });
    }

    // 5. XML Parsing: Notice Board Page
    if ($('#noticeBoardContainer').length) {
        $.ajax({
            url: 'data/notices.xml',
            method: 'GET',
            dataType: 'xml',
            success: function (xml) {
                let html = '<div class="row">';
                $(xml).find('notice').each(function () {
                    let title = $(this).find('title').text();
                    let date = $(this).find('date').text();
                    let content = $(this).find('content').text();
                    let type = $(this).attr('type') === 'urgent' ? 'border-danger' : 'border-primary';
                    let badge = $(this).attr('type') === 'urgent' ? '<span class="badge bg-danger">Urgent</span>' : '<span class="badge bg-primary">Info</span>';

                    html += `
                        <div class="col-md-6 mb-3">
                            <div class="card ${type} h-100">
                                <div class="card-body">
                                    <h5 class="card-title">${title} ${badge}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">${date}</h6>
                                    <p class="card-text">${content}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                $('#noticeBoardContainer').html(html).hide().fadeIn(1500);
            },
            error: function () {
                $('#noticeBoardContainer').html('<div class="alert alert-danger">Failed to load notices from XML.</div>');
            }
        });
    }

    // FAQ Accordion is handled by Bootstrap, but let's add a custom toggle text change event
    $('.accordion-button').on('click', function () {
        console.log("Accordion clicked: " + $(this).text().trim());
    });
    
    // 6. Homepage Marquee Notice Logic
    if ($('#liveNoticeBar').length) {
        console.log("Fetching notices for marquee...");
        $.ajax({
            url: 'data/notices.xml',
            method: 'GET',
            dataType: 'xml',
            success: function (xml) {
                console.log("Notices XML loaded successfully.");
                let notices = $(xml).find('notice');
                let targetNotice = null;
                
                // Find urgent first
                notices.each(function() {
                    if ($(this).attr('type') === 'urgent') {
                        targetNotice = $(this);
                        return false; // break loop
                    }
                });
                
                // Fallback to first notice if no urgent found
                if (!targetNotice && notices.length > 0) {
                    targetNotice = $(notices[0]);
                }
                
                if (targetNotice) {
                    let title = targetNotice.find('title').text();
                    let content = targetNotice.find('content').text();
                    console.log("Displaying notice: " + title);
                    $('#marqueeText').text(title + " - " + content);
                    $('#liveNoticeBar').slideDown(600);
                } else {
                    console.log("No notices found in XML.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Failed to load notices XML: " + error);
            }
        });
    }

});
