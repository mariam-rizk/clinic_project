<div class="container">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="fw-bold my-4 h4">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="index.php">Home</a></li>
            <?php 
                    if(isset($_GET['major'])):
                    ?>
            <li class="breadcrumb-item"><a class="text-decoration-none" href="index.php?page=doctors">Doctors</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $_GET['major'] ??''; ?></li>
            <?php else: ?>
            <li class="breadcrumb-item active" aria-current="page">All Doctors</li>
            <?php endif; ?>
        </ol>
    </nav>
    <div class="doctors-grid">

        <?php
    use App\models\Doctor;
    if(isset($_GET['major'])){
        $major = $_GET['major'];
        $doctors = Doctor::getByMajor($db,$major);
    }else{
        $doctors = Doctor::getAll($db);
    }

    foreach($doctors as $doctor):
?>
        <div class="card p-2" style="width: 18rem;">
            <img src="../<?=$doctor->getImage();?>" class="card-img-top rounded-circle card-image-circle" alt="major">
            <div class="card-body d-flex flex-column gap-1 justify-content-center">
                <h4 class="card-title fw-bold text-center"><?= $doctor->getName();?></h4>
                <h6 class="card-title fw-bold text-center"><?= $doctor->getMajorId($db);?></h6>
                <form action="index.php?page=booking" method="POST">
                    <input type="hidden" name="id" value="<?= $doctor->getId();?>">
                    <button type="submit" class="btn btn-outline-primary card-button m-4">Book an
                        appointment </button>
                </form>
                <!-- <a href="index.php?page=booking"  class="btn btn-outline-primary card-button">Book an appointment</a> -->
            </div>
        </div>
        <?php endforeach; ?>

    </div>
    <nav class="mt-5" aria-label="navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link page-prev" href="#" aria-label="Previous">
                    <span aria-hidden="true">
                        < </span>
                </a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link page-next" href="#" aria-label="Next">
                    <span aria-hidden="true"> > </span>
                </a>
            </li>
        </ul>
    </nav>
</div>
</div>