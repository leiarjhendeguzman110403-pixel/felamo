$(document).ready(function () {
  const level_id = $("#hidden_level_id").val();

  const showAlert = (type, message) => {
    $("#alert").removeClass().addClass(`alert ${type}`).text(message).show();
    setTimeout(() => {
      $("#alert").fadeOut("slow", function () { $(this).removeClass().text("").hide(); });
    }, 2000);
  };

  const loadAralins = () => {
    $.ajax({
      type: "POST",
      url: "../backend/api/web/aralin.php",
      data: { requestType: "GetAralin", level_id },
      success: function (response) {
        let res = typeof response === "string" ? JSON.parse(response) : response;

        if (res.status === "success") {
          const aralins = res.data;
          let html = "";

          if (aralins.length === 0) {
            html = `<div class="col-12 text-center py-5 text-muted">
                      <i class="bi bi-journal-x fs-1"></i>
                      <p class="mt-2">No lessons added yet.</p>
                    </div>`;
          } else {
            aralins.forEach((aralin) => {
              // --- CARD UI TEMPLATE ---
              html += `
                <div class="col-12">
                  <div class="card shadow-sm border-0 h-100 aralin-card">
                    <div class="card-body d-flex align-items-start gap-3">
                      
                      <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded bg-main text-white" 
                           style="width: 50px; height: 50px; font-weight: bold; font-size: 1.2rem;">
                        ${aralin.aralin_no}
                      </div>

                      <div class="flex-grow-1">
                        <h5 class="fw-bold text-dark mb-1">${aralin.title}</h5>
                        <p class="text-muted small mb-2 text-truncate-2">${aralin.summary}</p>
                        
                        <div class="d-flex align-items-center gap-2">
                           ${ aralin.attachment_filename ? 
                              `<span class="badge bg-light text-main border border-danger">
                                <i class="bi bi-play-circle-fill me-1"></i>Video Content
                               </span>` : '' 
                           }
                        </div>
                      </div>

                      <div class="d-flex flex-column gap-2">
                        <a href="/backend/storage/videos/${aralin.attachment_filename}" target="_blank" 
                           class="btn btn-sm btn-outline-main d-flex align-items-center justify-content-center" 
                           title="Watch Video" style="width: 140px;">
                           <i class="bi bi-play-fill me-2"></i> Watch
                        </a>

                        <div class="btn-group w-100" role="group">
                            <button class="btnEditAralin btn btn-sm btn-light border" 
                              data-id="${aralin.id}" 
                              data-title="${aralin.title}" 
                              data-summary="${aralin.summary}" 
                              data-details="${aralin.details}" 
                              data-filename="${aralin.attachment_filename}"
                              title="Edit Lesson">
                              <i class="bi bi-pencil-square"></i>
                            </button>
                            
                            <a class="btn btn-sm btn-light border" 
                               href="watch_history.php?aralinId=${aralin.id}" 
                               title="View Watch History">
                               <i class="bi bi-people"></i>
                            </a>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              `;
            });
          }

          $("#antas-list-container").html(html);
        } else {
          $("#antas-list-container").html(`<div class="alert alert-danger">Failed to load: ${res.message}</div>`);
        }
      },
      error: function () {
        $("#antas-list-container").html(`<div class="alert alert-danger">Server Error</div>`);
      },
    });
  };

  // --- FORM HANDLERS (Unchanged logic, kept for functionality) ---
  $("#insert-aralin-form").on("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    $.ajax({
      type: "POST", url: "../backend/api/web/aralin.php", data: formData, processData: false, contentType: false,
      success: function (response) {
        let res = typeof response === "string" ? JSON.parse(response) : response;
        if (res.status === "success") {
          showAlert("alert-success", res.message);
          loadAralins();
          $("#insertAralinModal").modal("hide");
          $("#insert-aralin-form")[0].reset();
        } else { showAlert("alert-danger", "Upload failed: " + res.message); }
      }
    });
  });

  $(document).on("click", ".btnEditAralin", function () {
    const id = $(this).data("id");
    const title = $(this).data("title");
    const summary = $(this).data("summary");
    const details = $(this).data("details");
    const filename = $(this).data("filename");

    $("#edit-aralin-id").val(id);
    $("#edit-aralin-title").val(title);
    $("#edit-aralin-summary").val(summary);
    $("#edit-aralin-details").val(details);
    $("#current-video-link").attr("href", "/backend/storage/videos/" + filename);
    $("#editAralinModal").modal("show");
  });

  $("#edit-aralin-form").on("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    $.ajax({
      type: "POST", url: "../backend/api/web/aralin.php", data: formData, processData: false, contentType: false,
      success: function (response) {
        let res = typeof response === "string" ? JSON.parse(response) : response;
        if (res.status === "success") {
          showAlert("alert-success", res.message);
          loadAralins();
          $("#editAralinModal").modal("hide");
          $("#edit-aralin-form")[0].reset();
        } else { showAlert("alert-danger", "Update failed: " + res.message); }
      }
    });
  });

  loadAralins();
});