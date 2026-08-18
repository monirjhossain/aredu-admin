<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
    <div class="brand flex-column-auto" id="kt_brand">
        <a href="{{ url('dashboard') }}" class="brand-logo">
            <img alt="Logo" src="{{ asset('media/custom/logo-white.png') }}" height="45" class="mr-4" style="border: 0px solid #8a8a8a; padding: 2px;" />
            <p style="color:white;"> <b> {{ Auth::user()->branchInfo->name ?? '' }}</b></p>
        </a>
        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            <span class="svg-icon svg-icon svg-icon-xl">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path
                            d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                            fill="#000000" fill-rule="nonzero"
                            transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
                        <path
                            d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                            fill="#000000" fill-rule="nonzero" opacity="0.3"
                            transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
                    </g>
                </svg>
            </span>
        </button>
    </div>
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="aside-menu" data-menu-vertical="1" data-menu-scroll="1"
            data-menu-dropdown-timeout="500">
            <ul class="menu-nav">
                <li class="menu-item {{ request()->is('dashboard') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                    <a href="{{ url('dashboard') }}" class="menu-link">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </span>
                    </a>
                </li>
                @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,3]))
                    <li class="menu-item {{ request()->is('branches') ? 'menu-item-open' : '' }} {{ request()->is('branches/*') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-text font-weight-bolder"><i class="fas fa-users"></i>Branches</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item {{ request()->is('branches') ? 'menu-item-active' : '' }}""
                                    aria-haspopup="true">
                                    <a href="{{ url('branches') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text font-weight-bolder">All Branch</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ request()->is('branches/create') ? 'menu-item-active' : '' }}"
                                    aria-haspopup="true">
                                    <a href="{{ route('branches.create') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text font-weight-bolder">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,4]))
                    <li class="menu-item {{ request()->is('user-management') ? 'menu-item-open' : '' }} {{ request()->is('user-management/*') ? 'menu-item-open' : '' }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <span class="menu-text font-weight-bolder"><i class="fas fa-users"></i>Users</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                <li class="menu-item {{ request()->is('user-management') ? 'menu-item-active' : '' }}""
                                    aria-haspopup="true">
                                    <a href="{{ url('user-management') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text font-weight-bolder">All User</span>
                                    </a>
                                </li>
                                <li class="menu-item {{ request()->is('user-management/create') ? 'menu-item-active' : '' }}"
                                    aria-haspopup="true">
                                    <a href="{{ route('user-management.create') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text font-weight-bolder">Add New</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,3,4]))
                <li class="menu-item {{ request()->is('finance_entries') ? 'menu-item-open' : '' }} {{ request()->is('finance_entries/*') ? 'menu-item-open' : '' }} {{ request()->is('finance_entrie') ? 'menu-item-open' : '' }} {{ request()->is('finance_entrie/*') ? 'menu-item-open' : '' }}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-coins"></i> Account</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('finance_entries') && request()->query('type') != 'expense' ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                <a href="{{ route('finance_entries.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">All Entries</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('finance_entrie/invoices') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                <a href="{{ route('finance_entries.invoices.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Invoices</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('finance_entrie/invoices/create') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                <a href="{{ route('finance_entries.invoices.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add Invoice</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('finance_entries') && request()->query('type') === 'expense' ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                <a href="{{ route('finance_entries.index', ['type' => 'expense']) }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Expense</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('finance_entries/create') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                <a href="{{ route('finance_entries.create', ['type' => 'expense']) }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add Expenses</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('finance_entrie/reports/create') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                <a href="{{ route('finance_entries.reports.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Reports</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,3]))
                <li class="menu-item {{ request()->is('posts') ? 'menu-item-open' : '' }} {{ request()->is('posts/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-pen"></i>Posts</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('posts') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('posts.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">All Post</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('posts/create') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('posts.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add New</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,3,4,5,6,7,8,9,10]))
                <li class="menu-item {{ request()->is('universities') ? 'menu-item-open' : '' }} {{ request()->is('universities/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-home"></i>Universities</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('universities/create') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('universities.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add New</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('universities') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('universities.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">All Universities</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,3,5]))
                <li class="menu-item {{ request()->is('pages') ? 'menu-item-open' : '' }} {{ request()->is('pages/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-layer-group"></i>pages</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('pages') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('pages.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">All Pages</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('pages/create') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('pages.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add New</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item {{ request()->is('success-story') ? 'menu-item-open' : '' }} {{ request()->is('success-story/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-plus"></i>success-story</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('success-story') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('success-story.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">All success-story</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('success-story/create') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('success-story.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add New</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item {{ request()->is('events') ? 'menu-item-open' : '' }} {{ request()->is('events/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-calendar"></i>events</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('events') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('events.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">All events</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('events/create') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('events.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add New</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item {{ request()->is('testimonials') ? 'menu-item-open' : '' }} {{ request()->is('testimonials/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-tag"></i>testimonials</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('testimonials') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('testimonials.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">All testimonials</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('testimonials/create') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('testimonials.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add New</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item {{ request()->is('sliders') ? 'menu-item-open' : '' }} {{ request()->is('sliders/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-list"></i>Sliders</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('sliders') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('sliders.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">All Sliders</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('sliders/create') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('sliders.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add New</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,3,5,6]))
                <li class="menu-item {{ request()->is('category') ? 'menu-item-open' : '' }} {{ request()->is('category/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-tag"></i>Categories</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('category') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('category.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">All Categorie</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('category/create') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('category.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add New</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="menu-item {{ request()->is('tag') ? 'menu-item-open' : '' }} {{ request()->is('tag/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-tag"></i>Tags</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('tag') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('tag.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">All Tag</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('tag/create') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('tag.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add New</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,3,5,6]))
                <li class="menu-item {{ request()->is('inquiries') ? 'menu-item-open' : '' }} {{ request()->is('inquiries/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-inbox"></i>Inquiries</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('inquiries') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('inquiries.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">All inquiries</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('inquiries/create') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('inquiries.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add New</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('application-qr-codes') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('application-qr-codes') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Application QR</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,3,4,5,6,7,8]))
                <li class="menu-item {{ request()->is('students') ? 'menu-item-open' : '' }} {{ request()->is('students/*') ? 'menu-item-open' : '' }} {{ request('student') ? 'menu-item-open' : '' }} {{ request()->is('finance_entrie') && request()->query('student') ==100 ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-school"></i>Students</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('students/create') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('students.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add New</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('students') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('students.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">All students</span>
                                </a>
                            </li>
                            @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,3]))
                            @foreach($branches as $item)
                                <li class="menu-item {{ request()->is('students') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('students.index') }}?branch_id={{$item->id}}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text font-weight-bolder">{{$item->name}}</span>
                                    </a>
                                </li>
                            @endforeach
                                <li class="menu-item {{ request()->is('students') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('students.index') }}?universitylist=1" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text font-weight-bolder">University Report</span>
                                    </a>
                                </li>
                                
                            @endif
                                <li class="menu-item {{ request()->is('students') ? 'menu-item-active' : '' }}" aria-haspopup="true">
                                    <a href="{{ route('students.index') }}?statuslist=1" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                        <span class="menu-text font-weight-bolder">Status Report</span>
                                    </a>
                                </li>

                                
                            {{-- <li class="menu-item {{ request()->is('students') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('students.index') }}?type=Processing" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Processing Students</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('students') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('students.index') }}?type=Inquery" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Inquery Students</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('students') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('students.index') }}?type=Admitted to us" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Admitted to us Students</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('students') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('students.index') }}?type=Paper Processing" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Paper Processing Students</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('students') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('students.index') }}?type=Paper Ready" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Paper Ready Students</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('students') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('students.index') }}?type=Paper Submitted" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Paper Submitted Students</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('students') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('students.index') }}?type=Rejected" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Rejected Students</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('students') ? 'menu-item-active' : '' }}""
                                aria-haspopup="true">
                                <a href="{{ route('students.index') }}?type=Admission Cancel" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Admission Cancel Students</span>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </li>
                @endif

                @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,3]))
                <li class="menu-item {{ request()->is('setting') ? 'menu-item-open' : '' }} {{ request()->is('setting/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-user"></i>Settings</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('setting/products') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('setting.products.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Products</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('setting/expenses') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('setting.expenses.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Expenses</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('setting/documents') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('setting.documents.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Documents</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('setting/roles') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('setting.roles.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">roles</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('setting/studies') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('setting.studies.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Study Level</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('setting/subjects') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('setting.subjects.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Subject</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('setting/sms_template') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('setting.sms_template.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">SMS Template</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('setting/student_status') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('setting.student_status.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Student Status</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('setting/university_status') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('setting.university_status.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">University status</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('setting/csca_exam') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('setting.csca_exam.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">CSCA Exam</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                {{-- <li class="menu-item {{ request()->is('todo') ? 'menu-item-open' : '' }} {{ request()->is('todo/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-layer-group"></i>To Do List</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('todo') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ url('todo') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">List</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
                @if(auth()->check() && in_array(auth()->user()->role_id, [1,2]))
                <li class="menu-item {{ request()->is('currencies') ? 'menu-item-open' : '' }} {{ request()->is('currencies/*') ? 'menu-item-open' : '' }}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-coins"></i>Currencies</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('currencies') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('currencies.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Currency List</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('currencies/create') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('currencies.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add Currency</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                @if(auth()->check() && in_array(auth()->user()->role_id, [1,2,4]))
                <li class="menu-item {{ request()->is('wallets') || request()->is('wallets/*') ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-wallet"></i> Wallets</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('wallets') ? 'menu-item-active' : '' }}">
                                <a href="{{ route('wallets.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Wallet List</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('wallets/create') ? 'menu-item-active' : '' }}">
                                <a href="{{ route('wallets.create') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Add Wallet</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('wallets/transfer') ? 'menu-item-active' : '' }}">
                                <a href="{{ route('wallets.transfer.form') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Transfer</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                <li class="menu-item {{ request()->is('my-profile') ? 'menu-item-open' : '' }} {{ request()->is('my-profile/*') ? 'menu-item-open' : '' }}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-text font-weight-bolder"><i class="fas fa-user"></i>My Profile</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->is('my-profile') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('my-profile.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">My Info</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('my-profile/basic') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('my-profile.basic_edit') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Change Basic Info</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('my-profile/image') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('my-profile.imageUpload') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Change Profile Picture</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('my-profile/change-password') ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('my-profile.changepassword') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot"><span></span></i>
                                    <span class="menu-text font-weight-bolder">Change Password</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
