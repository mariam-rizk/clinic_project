<?php
use App\models\Contact;


$contactModel = new Contact($db);
$contacts = $contactModel->getAll();

$search = $_GET['search'] ?? null;

$limit = 10;
$currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$currentPage = max($currentPage, 1);
$offset = ($currentPage - 1) * $limit;

if ($search) {
    $contacts = $contactModel->search($search);
    $totalContacts = count($contacts);
    $totalPages = 1;
} else {
    $contacts  = $contactModel->pagination($limit, $offset);
    $totalContacts = $contactModel->paginationCount();
    $totalPages = ceil($totalContacts / $limit);
}
?>

<div class="content-wrapper">
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Contact Messages</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <form method="GET" class="input-group input-group" style="width: 250px;">
                            <input type="hidden" name="page" value="manage_contacts">
                            <input type="text" name="search" class="form-control float-right" placeholder="Search by subject" value="<?= htmlspecialchars($search ?? '') ?>">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>From (Email)</th>
                                <th>Subject</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($contacts)): ?>
                                <?php foreach ($contacts as $contact): ?>
                                    <tr>
                                        <td><?= $contact['id'] ?></td>
                                        <td><?= htmlspecialchars($contact['name']) ?></td>
                                        <td><?= htmlspecialchars($contact['email']) ?></td>
                                        <td><?= htmlspecialchars($contact['subject']) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info view-message-btn" 
                                                data-id="<?= $contact['id'] ?>"
                                                data-name="<?= htmlspecialchars($contact['name']) ?>"
                                                data-email="<?= htmlspecialchars($contact['email']) ?>"
                                                data-phone="<?= htmlspecialchars($contact['phone']) ?>"
                                                data-subject="<?= htmlspecialchars($contact['subject']) ?>"
                                                data-message="<?= htmlspecialchars($contact['message']) ?>">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No messages found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="message-card" class="card d-none mt-4">
                <div class="card-header">
                    <h3 class="card-title" id="message-subject">Subject</h3>
                </div>
                <div class="card-body">
                    <p><strong>From:</strong> <span id="message-email"></span></p>
                    <p><strong>Name:</strong> <span id="message-name"></span></p>
                    <p><strong>Phone:</strong> <span id="message-phone"></span></p>
                    <hr>
                    <p id="message-body"></p>
                </div>
            </div>
        </div>
    </section>
</div>

										
</div>
<div class="card-footer clearfix">
    <ul class="pagination pagination m-0 float-right">
        <?php if ($currentPage > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=manage_contacts&p=<?= $currentPage - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">«</a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="?page=manage_contacts&p=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=manage_contacts&p=<?= $currentPage + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">»</a>
            </li>
        <?php endif; ?>
    </ul>
</div>
</div>
</div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.view-message-btn');
    const card = document.getElementById('message-card');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('message-subject').textContent = this.dataset.subject;
            document.getElementById('message-email').textContent = this.dataset.email;
            document.getElementById('message-name').textContent = this.dataset.name;
            document.getElementById('message-phone').textContent = this.dataset.phone;
            document.getElementById('message-body').textContent = this.dataset.message;

            card.classList.remove('d-none');
            card.scrollIntoView({ behavior: 'smooth' });
        });
    });
});
</script>