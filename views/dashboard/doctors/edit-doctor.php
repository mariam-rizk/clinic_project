<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Doctor</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="users.html" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="dashboard.php?page=doctor-controller&action=edit" method="POST" enctype="multipart/form-data">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <?php

use App\models\Doctor;

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $doctor_id = trim($_POST['doctor_id']);
    $doctor =Doctor::getById($db, $doctor_id);
}

?>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="hidden" name="doctor_id" 
                                        value="<?=$doctor->getId()?>">
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="<?=$doctor->getName()?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" class="form-control"
                                        value="<?=$doctor->getEmail()?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        value="<?=$doctor->getPhone()?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">

                                    <label for="gender">Gender</label>
                                    <select name="gender" class="form-control" aria-label="Default select example">
                                        <option disabled>Select Gender</option>
                                        <option value="male"
                                            <?php echo $doctor->getGender() == 'male' ? 'selected' : ''; ?>>Male
                                        </option>
                                        <option value="female"
                                            <?php echo $doctor->getGender() == 'female' ? 'selected' : ''; ?>>Female
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Major</label>
                                    <select name="major" class="form-control" aria-label="Default select example">
                                        <option disabled>Select Major</option>
                                        <?php
                use App\models\Major;
                $majors = Major::getAll($db);
                foreach($majors as $major):
                ?>
                                        <option <?php $doctor->getMajorId($db)==$major->getName() ? 'selected' :'' ?>
                                            value="<?=$major->getId();?>"><?=$major->getName();?></option>

                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="image">Image</label>
                                    <input type="file" name="image" id="image" class="form-control" placeholder="Image">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="bio">Bio</label>
                                    <textarea name="bio" id="bio" class="form-control" cols="30" rows="5"> <?=$doctor->getBio()?> </textarea>
                                </div>
                            </div>
                            <!-- <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Password">
                                </div>
                            </div> -->
                        </div>

                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-warning">Edit</button>
                    <a href="users.html" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>