<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>

    <div>
        <a href="<?= base_url('dashboard') ?>" class="sidebar-logo d-flex flex-column align-items-center text-center">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="site logo" class="light-logo mb-2" style="max-width: 120px;">
            <img src="<?= base_url('assets/images/logo-light.png') ?>" alt="site logo" class="dark-logo mb-2" style="max-width: 120px;">
            <img src="<?= base_url('assets/images/logo-icon.png') ?>" alt="site logo" class="logo-icon" style="max-width: 60px;">
        </a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a href="<?= base_url('dashboard') ?>">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('patients') ?>">
                    <iconify-icon icon="mdi:account-multiple-outline" class="menu-icon"></iconify-icon>
                    <span>Manage Patients</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('doctors') ?>">
                    <iconify-icon icon="healthicons:doctor-male-outline" class="menu-icon"></iconify-icon>
                    <span>Manage Doctors</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('equipment') ?>">
                    <iconify-icon icon="mdi:medical-bag" class="menu-icon"></iconify-icon>
                    <span>Manage Equipment</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('supplies') ?>">
                    <iconify-icon icon="mdi:package-variant" class="menu-icon"></iconify-icon>
                    <span>Manage Supplies</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('drugs') ?>">
                    <iconify-icon icon="mdi:pill" class="menu-icon"></iconify-icon>
                    <span>Manage Drugs</span>
                </a>
            </li>

            <li>
                <a href="<?= base_url('visits') ?>">
                    <iconify-icon icon="mdi:clipboard-text-outline" class="menu-icon"></iconify-icon>
                    <span>Manage Visits</span>
                </a>
            </li>


        </ul>
    </div>
</aside>