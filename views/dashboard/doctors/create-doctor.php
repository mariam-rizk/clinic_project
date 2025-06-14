
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Doctor</h1>
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
          <form action="dashboard.php?page=doctor-controller&action=add" method="POST" enctype="multipart/form-data">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                   
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone">
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="mb-3">
                            <label for="gender">Gender</label>
                            <select name="gender" class="form-control" aria-label="Default select example">
                                <option selected disabled>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Major</label>
                                <select name="major" class="form-control" aria-label="Default select example">
                                    <option selected disabled>Select Major</option>
                <?php
                use App\models\Major;
                $majors = Major::getAll($db);
                foreach($majors as $major):
                ?>
                                    <option value="<?=$major->getId();?>"><?=$major->getName();?></option>
                                   
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
                                <textarea name="bio" id="bio" class="form-control" cols="30"
                                    rows="5"></textarea>
                            </div>
                        </div>
                         <div class="col-md-12">
                            <div class="mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="users.html" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
         </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>