
<body>
    <div class="page-wrapper">
        <nav class="navbar navbar-expand-lg navbar-expand-md bg-blue sticky-top">
            <div class="container">
                <div class="navbar-brand">
                    <a class="fw-bold text-white m-0 text-decoration-none h3" href="?page=home">VCare</a>
                </div>
                <button class="navbar-toggler btn-outline-light border-0 shadow-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <div class="d-flex gap-3 flex-wrap justify-content-center" role="group">
                        <a type="button" class="btn btn-outline-light navigation--button" href="?page=home">Home</a>
                        <a type="button" class="btn btn-outline-light navigation--button"
                            href="?page=majors">majors</a>
                        <a type="button" class="btn btn-outline-light navigation--button"
                            href="?page=doctors">Doctors</a>
                        <?php if ($user): ?>
                        <div class="d-flex align-items-center gap-2">
                        <a type="button" class="btn btn-outline-light navigation--button" href="?page=history">History</a>
                        <a type="button" class="btn btn-outline-light navigation--button" href="?page=profile">Profile</a>
                        <a type="button" class="btn btn-outline-light navigation--button" onclick="return confirm('Are you sure you want to logout?')" href="?page=logout">logout</a>
                        <span class="text-white fw-bold">
                            Welcome, <?=htmlspecialchars($user['name'])?>
                            <?php if($user['role'] === 'admin') echo '(admin)';?>
                        </span>
                        <?php else: ?>
                        <a type="button" class="btn btn-outline-light navigation--button" href="?page=login">login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
         </nav>

