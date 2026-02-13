$(document).ready(function () {
    const auth_user_id = $("#hidden_user_id").val();

    const loadAntas = () => {
        $.ajax({
            type: "POST",
            url: "../backend/api/web/levels.php",
            data: { requestType: "GetLevels", auth_user_id },
            success: function (response) {
                let res = JSON.parse(response);

                if (res.status === "success") {
                    let levels = res.data;
                    let html = "";

                    if (levels.length === 0) {
                        html = `
                            <div class="col-12">
                                <div class="alert alert-light text-center border text-muted shadow-sm">
                                    Walang antas na nahanap.
                                </div>
                            </div>`;
                    } else {
                        levels.forEach((level) => {
                            let markahan = "";
                            let icon = "";
                            
                            switch (level.level) {
                                case 1:
                                    markahan = "Unang Markahan";
                                    icon = "bi-1-square-fill";
                                    break;
                                case 2:
                                    markahan = "Pangalawang Markahan";
                                    icon = "bi-2-square-fill";
                                    break;
                                case 3:
                                    markahan = "Pangatlong Markahan";
                                    icon = "bi-3-square-fill";
                                    break;
                                case 4:
                                    markahan = "Ika-apat na Markahan";
                                    icon = "bi-4-square-fill";
                                    break;
                                default:
                                    markahan = "Markahan " + level.level;
                                    icon = "bi-layers-fill";
                            }

                            html += `
                                <div class="col-12">
                                    <div class="card shadow-sm border-0 markahan-card">
                                        <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                                            
                                            <div class="d-flex align-items-center">
                                                <div class="level-icon-wrapper me-3">
                                                    <i class="bi ${icon} fs-3 text-main"></i>
                                                </div>
                                                <div>
                                                    <h5 class="card-title fw-bold text-dark mb-0">${markahan}</h5>
                                                    <p class="card-text text-muted small mb-0">
                                                        ${level.description || 'Quarterly assessment management.'}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center gap-2">
                                                <a href="level_details.php?level=${level.id}" class="btn btn-sm btn-outline-main d-flex align-items-center">
                                                    <i class="bi bi-eye me-2"></i> View
                                                </a>
                                                
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                        <li><a class="dropdown-item" href='create_assessment.php?level=${level.id}'><i class="bi bi-pencil-square me-2"></i>Create Assessment</a></li>
                                                        <li><a class="dropdown-item" href='taken_assessments.php?level=${level.id}'><i class="bi bi-journal-check me-2"></i>View Scores</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    }

                    $("#levels-container").html(html);
                } else {
                    $("#levels-container").html(`<div class="col-12 text-center text-danger">Failed to load levels.</div>`);
                }
            },
            error: function () {
                $("#levels-container").html(`<div class="col-12 text-center text-danger">Server error.</div>`);
            },
        });
    };

    loadAntas();
});