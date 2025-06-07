
        <div class="container">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="fw-bold my-4 h4">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="?page=home">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">contact</li>
                </ol>
            </nav>
            <div class="d-flex flex-column gap-3 account-form mx-auto mt-5">
                <form class="form" method="post" action="?page=contact_controller" novalidate>
                    <div class="form-items">
                        <div class="mb-3">
            <label class="form-label required-label" for="name">Name</label>
            <input type="text" name="name"
                class="form-control <?php if(isset($errors['name'])) echo 'is-invalid'; ?>"
                id="name"
                value="<?= htmlspecialchars($user['name'] ?? ($old['name'] ?? '')) ?>"
                <?= $user ? 'readonly' : '' ?>
                required>
            <?php if (isset($errors['name'])): ?>
                <?php foreach ($errors['name'] as $error): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>

       <div class="mb-3">
       <label class="form-label required-label" for="email">Email</label>
        <input type="email" name="email"
        class="form-control <?php if(isset($errors['email'])) echo 'is-invalid'; ?>"
        id="email"
        value="<?= htmlspecialchars($user['email'] ?? ($old['email'] ?? '')) ?>"
        <?= $user ? 'readonly' : '' ?>
        required>
        <?php if (isset($errors['email'])): ?>
        <?php foreach ($errors['email'] as $error): ?>
            <div class="invalid-feedback"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

                        <div class="mb-3">
                            <label class="form-label required-label" for="phone">Phone</label>
                            <input type="tel" name="phone" class="form-control  <?php if(isset($errors['phone'])) echo 'is-invalid'; ?>" id="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
                            <?php if (isset($errors['phone'])): ?>
                                <?php foreach ($errors['phone'] as $error): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($error) ?></div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

       
                        <div class="mb-3">
                            <label class="form-label required-label" for="subject">subject</label>
                            <input type="text" name="subject" class="form-control  <?php if(isset($errors['subject'])) echo 'is-invalid'; ?>" id="subject" value="<?= htmlspecialchars($old['subject'] ?? '') ?>" required>
                            <?php if (isset($errors['subject'])): ?>
                                <?php foreach ($errors['subject'] as $error): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($error) ?></div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required-label" for="message">message</label>
                            <textarea class="form-control  <?php if(isset($errors['message'])) echo 'is-invalid'; ?>" name="message" id="message"  required></textarea>
                            <?php if (isset($errors['message'])): ?>
                                <?php foreach ($errors['message'] as $error): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($error) ?></div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>

        </div>
    </div>

