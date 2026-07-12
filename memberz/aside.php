 <div class="col-md-3 col-xl-2 bg-white border-end sticky-top d-none d-md-flex flex-column justify-content-between p-3">
          <div>
            <div class="d-flex align-items-center justify-content-between mb-4 px-2">
              <span class="fw-bold text-dark small text-uppercase tracking-wider">Main Menu</span>
              <i class="bi bi-person text-muted"></i>
            </div>
            
            <!-- Navigation Menu Items -->
            <ul class="nav nav-pills flex-column gap-1">
              <?php foreach ($closeMenuMe as $menuList):?>
                <li class="nav-item">
                  <a href="<?= $menuList[1]  ?>" class="nav-link <?= $dataUrl == $menuList[1] ? 'active bg-success text-white':'text-muted' ?> d-flex align-items-center rounded-3 py-2.5">
                    <i class="bi <?= $menuList[2]  ?> me-3"></i> <?= $menuList[0]  ?>
                  </a>
                </li>
              <?php endforeach ?>
            </ul>
          </div>
        </div>