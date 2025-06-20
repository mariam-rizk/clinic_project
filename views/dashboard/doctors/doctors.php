<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Doctors</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="dashboard.php?page=create-doctor" class="btn btn-primary">New Doctor</a>
                </div>
            </div>
        </div>

    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="text" name="table_search" class="form-control float-right"
                                placeholder="Search">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Major</th>
                                <!-- <th width="100">Status</th> -->
                                <th width="100">Schedule</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
	use App\models\Doctor;
	$doctors = Doctor::getAll($db);
	foreach($doctors as $doctor):
	
	?>
                            <tr>
                                <td><?= $doctor->getId();?></td>
                                <td><a class="nav-link p-0 pr-3" data-toggle="dropdown" href="#">
                                        <img src="dashboard_assets/img/avatar5.png" class='img-circle elevation-2'
                                            width="40" height="40" alt="">
                                    </a></td>
                                <td><?= $doctor->getName();?></td>

                                <td><?= $doctor->getEmail()?></td>
                                <td><?= $doctor->getPhone();?></td>
                                <td><?= $doctor->getGender()?></td>
                                <td><?= $doctor->getMajorId($db)?></td>
                                <td>
                                    <form action="dashboard.php?page=doctor-schedule&doctor-id=<?=$doctor->getId()?>" method="POST">
                                        <input type="hidden" name="doctor_id" value="<?=$doctor->getId();?>">
                                        <button type="submit" class="btn btn-primary">
                                                View
                                            </button>
                                    </form>
                                </td>
                                <td>
                                    <!-- <form action="dashboard.php?page=doctor-controller&action=edit" method="POST"> -->
                                    <form action="dashboard.php?page=edit-doctor" method="POST">
                                        <input type="hidden" name="doctor_id" value="<?=$doctor->getId();?>">
                                        <button type="submit" class="btn"><a href="#">
                                                <svg class="filament-link-icon w-4 h-4 mr-1"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                    </path>
                                                </svg>
                                            </a></button>
                                    </form>

                                    <form action="dashboard.php?page=doctor-controller&action=delete" method="POST">
                                        <input type="hidden" name="doctor_id" value="<?=$doctor->getId();?>">
                                        <button type="submit" class="btn"><a href="" class="text-danger w-4 h-4 mr-1">
                                                <svg wire:loading.remove.delay="" wire:target=""
                                                    class="filament-link-icon w-4 h-4 mr-1"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                    <path ath fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach;?>

                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>