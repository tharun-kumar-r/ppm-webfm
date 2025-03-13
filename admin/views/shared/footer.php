<?php
if (!defined('BASEPATH')) {
  header('Location:/404');
}

?>
</div>
<div class="modal fade" id="fileManager" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content " style="height: 90vh;width:72vw;max-width:100vw">
     <iframe src="file-manager" style="width:100%;height:100%" ></iframe>
    </div>
  </div>
</div>
<button data-bs-toggle="modal" data-bs-target="#fileManager" class="btn btn-light d-none d-lg-block d-xl-block " style="position: fixed;bottom: 0;width: 200px;margin-left: 18px;margin-bottom: 15px; z-index: 60000;" class="nav-link" href="./">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-folder">
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2" />
    </svg>
  <span class="nav-link-title">
    File Manager
  </span>
</button>


<?php echo Config::IMPORT['footer'] . Config::IMPORT['popupjs'] . Config::IMPORT['appAdminJs']; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/4.3.0/apexcharts.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.2/index.min.js" defer></script>