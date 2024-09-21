<style>
    /* .navfont {
        font-size: 12px;
    } */
    .navsetitem {
        margin-left: -2.5em;
    }

    body:not(.horizontal-navigation) .navigation .navigation-menu-body ul li>a {
        font-size: 1em;
    }
</style>
<!-- begin::navigation -->
<div class="navigation">
    <div class="navigation-header">
        <span>Navigation</span>
        <a href="#">
            <i class="ti-close"></i>
        </a>
    </div>
    <div class="navigation-menu-body">
        <ul>
            <li>
                <a href="<?= site_url('Admin/Dashboard'); ?>" class="<?= ($this->uri->segment(2) == "Dashboard") ? "active" : "" ?>">
                    <span class="nav-link-icon">
                        <i class="fa-solid fa-tachometer-alt"></i>
                    </span>
                    <span class="navfont">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?= site_url('Admin/Marriage_Grant/all_mg_list'); ?>" class="<?= ($this->uri->segment(3) == "all_mg_list") ? "active" : "" ?>">
                    <span class="nav-link-icon">
                        <i class="fa-solid fa-file"></i>
                    </span>
                    <span class="navfont">All Marriage Grant List</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="nav-link-icon">
                        <i class="fa-solid fa-cog"></i>
                    </span>
                    <span class="navfont">Setup Menu</span>
                </a>
                <ul>
                    <li>
                        <a href="<?= site_url('Admin/Setup/manage_user'); ?>" class="<?= ($this->uri->segment(3) == "manage_user") ? "active" : "" ?>">
                            <span class="nav-link-icon">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <span class="navfont">User Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('Admin/Setup/manage_regions'); ?>" class="<?= ($this->uri->segment(3) == "manage_regions") ? "active" : "" ?>">
                            <span class="nav-link-icon">
                                <i class="fa-solid fa-globe"></i>
                            </span>
                            <span class="navfont">Region Management</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- end::navigation -->