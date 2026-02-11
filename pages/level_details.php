<?php
include("components/header.php");

// Authorization and Validation
if (isset($_GET['level'])) {
    $level_id = $_GET['level'];
    $levelResult = $AuthController->GetUsingId("levels", $level_id);

    if ($levelResult->num_rows > 0) {
        $level = $levelResult->fetch_assoc();
        if ($level['teacher_id'] != $auth_user_id) {
            header("Location: ../index.php");
        }
    } else {
        header("Location: ../index.php");
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>

<input type="hidden" id="hidden_user_id" value="<?= $auth_user_id ?>">
<input type="hidden" id="hidden_level_id" value="<?= $level_id ?>">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-main fw-bold mb-0">
                <i class="bi bi-journals me-2"></i>
                <?php
                switch ($level['level']) {
                    case 1: echo 'Unang Markahan'; break;
                    case 2: echo 'Pangalawang Markahan'; break;
                    case 3: echo 'Pangatlong Markahan'; break;
                    case 4: echo 'Ika-apat na Markahan'; break;
                    default: echo 'Detalye ng Markahan';
                }
                ?>
            </h4>
            <small class="text-muted">Manage your lessons and content for this level.</small>
        </div>
        <button class="btn btn-main text-light shadow-sm" data-bs-toggle="modal" data-bs-target="#insertAralinModal">
            <i class="bi bi-plus-lg me-1"></i> New Aralin
        </button>
    </div>

    <div id="antas-list-container" class="row g-3">
        </div>
</div>

<div class="modal fade" id="insertAralinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="insert-aralin-form" enctype="multipart/form-data" method="POST">
            <input type="hidden" name="requestType" value="InsertAralin">
            <input type="hidden" name="level_id" value="<?= $level_id ?>">

            <div class="modal-content">
                <div class="modal-header bg-main text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Aralin</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Lesson Title</label>
                        <input type="text" class="form-control" name="title" placeholder="e.g., Introduction to Programming" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Summary (Short Description)</label>
                            <textarea class="form-control" name="summary" rows="4" placeholder="Brief overview displayed on the card..." required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Full Details</label>
                            <textarea class="form-control" name="details" rows="4" placeholder="In-depth content..." required></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Video Material</label>
                        <input type="file" class="form-control" name="attachment" accept="video/*" required>
                        <small class="text-muted">Supported formats: mp4, webm, mov</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-main text-light">Save Lesson</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editAralinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="edit-aralin-form" enctype="multipart/form-data" method="POST">
            <input type="hidden" name="requestType" value="EditAralin">
            <input type="hidden" name="aralin_id" id="edit-aralin-id">
            <input type="hidden" name="level_id" value="<?= $level_id ?>">

            <div class="modal-content">
                <div class="modal-header bg-main text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Aralin</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="edit-aralin-title" name="title" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Summary</label>
                            <textarea class="form-control" id="edit-aralin-summary" name="summary" rows="4" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Details</label>
                            <textarea class="form-control" id="edit-aralin-details" name="details" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Video</label>
                        <div class="p-2 border rounded bg-light d-flex align-items-center justify-content-between">
                            <span class="text-muted"><i class="bi bi-film me-2"></i>Current file attached</span>
                            <a href="#" target="_blank" id="current-video-link" class="btn btn-sm btn-outline-primary">Preview Video</a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Replace Video (Optional)</label>
                        <input type="file" class="form-control" name="attachment" accept="video/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-main text-light">Update Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include("components/footer-scripts.php"); ?>
<script src="scripts/levelsDetails.js?v=2"></script>
<?php include("components/footer.php"); ?>