
<div class="container">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="fw-bold my-4 h4">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="?page=home">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Register</li>
        </ol>
    </nav>

    <div class="d-flex flex-column gap-3 account-form mx-auto mt-5">
        <form class="form" method="post" action="?page=register_controller" novalidate>
            <div class="form-items">
                <div class="mb-3">
                    <label class="form-label required-label" for="name">Name</label>
                    <input type="text" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                    <?php if (isset($errors['name'])): ?>
                            <?php foreach ((array)$errors['name'] as $error): ?>
                            <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label required-label" for="phone">Phone</label>
                    <input type="tel" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="phone" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
                    <?php if (isset($errors['phone'])): ?>
                        <?php foreach ((array)$errors['phone'] as $error): ?>
                        <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label required-label" for="email">Email</label>
                    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                    <?php if (isset($errors['email'])): ?>
                    <?php foreach ((array)$errors['email'] as $error): ?>
                        <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>

                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label required-label" for="password">Password</label>
                    <input type="password" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="password" name="password" required>
                    <?php if (isset($errors['password'])): ?>
                        <?php foreach ((array)$errors['password'] as $error): ?>
                        <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>
   
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label required-label" for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password" required>
                    <?php if (isset($errors['confirm_password'])): ?>
                        <?php foreach ((array)$errors['confirm_password'] as $error): ?>
                        <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>
    
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label required-label" for="gender">Gender</label>
                    <select class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male" <?= (isset($old['gender']) && $old['gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= (isset($old['gender']) && $old['gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
                    </select>
                    <?php if (isset($errors['gender'])): ?>
                        <?php foreach ((array)$errors['gender'] as $error): ?>
                        <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>

                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label required-label" for="dateofbirth">Birth Date</label>
                    <input type="date" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="dateofbirth" name="dateofbirth" value="<?= htmlspecialchars($old['dateofbirth'] ?? '') ?>" required>
                    <?php if (isset($errors['dateofbirth'])): ?>
                        <?php foreach ((array)$errors['dateofbirth'] as $error): ?>
                        <div class="invalid-feedback d-block" style="color:red;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Create Account</button>
        </form>

        <div class="d-flex justify-content-center gap-2">
            <span>Already have an account?</span><a class="link" href="?page=login">Login</a>
        </div>
    </div>
</div>

