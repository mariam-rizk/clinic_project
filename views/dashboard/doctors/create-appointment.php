
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Appointment</h1>
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
          <form action="dashboard.php?page=schedule-controller&action=add-schedule&doctor-id=<?=$_GET['doctor-id']?>" method="POST" enctype="multipart/form-data">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                   
                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="mb-3">
                                <!-- <input type="hidden" class="form-control" id="doctor-id" name="doctor-i> -->
                            <label for="day">Day</label>
                            <select name="day" class="form-control" aria-label="Default select example">
                                <option selected disabled>Select Day</option>
                                <option value="saturday">Saturday</option>
                                <option value="sunday">Sunday</option>
                                <option value="monday">Monday</option>
                                <option value="tuesday">Tuesday</option>
                                <option value="wednesday">Wednesday</option>
                                <option value="thursday">Thursday</option>
                                <option value="friday">Friday</option>
                            </select>

                             </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start-time">Start Time</label>
                                <input type="time" class="form-control" id="startTime" name="start_time">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end-time">End Time</label>
                                 <input type="time" class="form-control" id="endTime" name="end_time">
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