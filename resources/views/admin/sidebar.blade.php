<!-- <div class="col-md-3 sidebar-nav">
    <div class="panel panel-default panel-flush">
        <div class="panel-heading">
            Navigation
        </div>
        <div class="panel-body">
            <ul class="nav" role="tablist">
                @foreach($laravelAdminMenus->menus as $section)
                    @if($section->items)
                        @foreach($section->items as $menu)
                            <li role="presentation">
                                <a href="{{ url($menu->url) }}">
                                    {{ $menu->title }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </div>
    </div>    
</div> -->

    <div class="col-md-2 sidebarblock">
        <div class="row">
            <div class="sidebar">
                <div class="nav-header">Navigation</div>
                <nav>
                    <ul id="admin-sidebar">
                        <li class="">
                            <a href="/admin/users">
                                <img src="/assets/images/admin/menu-icon1.png" class="img-responsive" alt="icon">
                                <img src="/assets/images/admin/menu-icon1-white.png" class="img-responsive onhovershow" alt="icon">
                                Users
                            </a>
                        </li>
                        <li>
                            <a href="/admin/roles">
                                <img src="/assets/images/admin/menu-icon2.png" class="img-responsive" alt="icon">
                                <img src="/assets/images/admin/menu-icon2-white.png" class="img-responsive onhovershow" alt="icon">
                                Roles
                            </a>
                        </li>
                        <li>
                            <a href="/admin/permissions">
                                <img src="/assets/images/admin/menu-icon3.png" class="img-responsive" alt="icon">
                                <img src="/assets/images/admin/menu-icon3-white.png" class="img-responsive onhovershow" alt="icon">
                                Permissions
                            </a>
                        </li>
                        <li>
                            <a href="/admin/give-role-permissions">
                                <img src="/assets/images/admin/menu-icon4.png" class="img-responsive" alt="icon">
                                <img src="/assets/images/admin/menu-icon4-white.png" class="img-responsive onhovershow" alt="icon">
                                Give Role Permission
                            </a>
                        </li>
                        <li>
                            <a href="/admin/posts">
                                <img src="/assets/images/admin/menu-icon5.png" class="img-responsive" alt="icon">
                                <img src="/assets/images/admin/menu-icon5-white.png" class="img-responsive onhovershow" alt="icon">
                                Posts
                            </a>
                        </li>
                        <li>
                            <a href="/admin/donates">
                                <img src="/assets/images/admin/menu-icon6.png" class="img-responsive" alt="icon">
                                <img src="/assets/images/admin/menu-icon6-white.png" class="img-responsive onhovershow" alt="icon">
                                Approve Donates
                            </a>
                        </li>
                        <li>
                            <a href="/admin/discounts">
                                <img src="/assets/images/admin/menu-icon7.png" class="img-responsive" alt="icon">
                                <img src="/assets/images/admin/menu-icon7-white.png" class="img-responsive onhovershow" alt="icon">
                                Manage Discount Codes
                            </a>
                        </li>
                        <li>
                            <a href="/admin/upload_policies_rules">
                                <img src="/assets/images/admin/menu-icon8.png" class="img-responsive" alt="icon">
                                <img src="/assets/images/admin/menu-icon8-white.png" class="img-responsive onhovershow" alt="icon">
                                Policies and Rules Upload
                            </a>
                        </li> 
                        <li>
                            <a href="/admin/upload_firmware">
                                <img src="/assets/images/admin/menu-icon9.png" class="img-responsive" alt="icon">
                                <img src="/assets/images/admin/menu-icon9-white.png" class="img-responsive onhovershow" alt="icon">
                                Upload Firmware
                            </a>
                        </li>
                        <li>
                            <a href="/admin/sponsors">
                                <img src="/assets/images/admin/menu-icon10.png" class="img-responsive" alt="icon">
                                <img src="/assets/images/admin/menu-icon10-white.png" class="img-responsive onhovershow" alt="icon">
                                Sponsors
                            </a>
                        </li>
                        <li>
                            <a href="/admin/message_history">
                                <img src="/assets/images/admin/menu-icon11.png" class="img-responsive" alt="icon">
                                <img src="/assets/images/admin/menu-icon11-white.png" class="img-responsive onhovershow" alt="icon">
                                Messages from Customers
                            </a>
                        </li>
                        <li>
                            <a href="/messages">
                                <img src="/assets/images/admin/menu-icon12.png" class="img-responsive" alt="icon">
                                <img src="/assets/images/admin/menu-icon12-white.png" class="img-responsive onhovershow" alt="icon">
                                Chats from Customer
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

   <script>
        $('#admin-sidebar li').on('click', function() {
            console.log('click', $(this));
            $(this).addClass('active');
        });
    </script>

