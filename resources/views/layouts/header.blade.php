 <!--**********************************
            Header start
        ***********************************-->
 <div class="header">
     <div class="header-content">
         <nav class="navbar navbar-expand">
             <div class="collapse navbar-collapse justify-content-between">
                 <div class="header-left">
                     <div class="dashboard_bar">
                         Dashboard
                     </div>
                 </div>
                 <div class="header-right d-flex align-items-center">

                     <li class="nav-item ps-3">
                         <div class="dropdown header-profile2">
                             <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown"
                                 aria-expanded="false">
                                 <div class="header-info2 d-flex align-items-center">
                                     <div class="header-media">
                                         <img src="assets/images/user.jpg" alt="">
                                     </div>
                                 </div>
                             </a>
                             <div class="dropdown-menu dropdown-menu-end">
                                 <div class="card border-0 mb-0">
                                     <div class="card-header py-2">
                                         <div class="products">
                                             <img src="assets/images/user.jpg" class="avatar avatar-md" alt="">
                                             <div>
                                                 <h6>Haroon Rashid</h6>

                                             </div>
                                         </div>
                                     </div>
                                     <div class="card-body px-0 py-2">
                                         <a href="page-error-404.html" class="dropdown-item ai-icon ">
                                             <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                 <path fill-rule="evenodd" clip-rule="evenodd"
                                                     d="M11.9848 15.3462C8.11714 15.3462 4.81429 15.931 4.81429 18.2729C4.81429 20.6148 8.09619 21.2205 11.9848 21.2205C15.8524 21.2205 19.1543 20.6348 19.1543 18.2938C19.1543 15.9529 15.8733 15.3462 11.9848 15.3462Z"
                                                     stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round"
                                                     stroke-linejoin="round" />
                                                 <path fill-rule="evenodd" clip-rule="evenodd"
                                                     d="M11.9848 12.0059C14.5229 12.0059 16.58 9.94779 16.58 7.40969C16.58 4.8716 14.5229 2.81445 11.9848 2.81445C9.44667 2.81445 7.38857 4.8716 7.38857 7.40969C7.38 9.93922 9.42381 11.9973 11.9524 12.0059H11.9848Z"
                                                     stroke="var(--primary)" stroke-width="1.42857"
                                                     stroke-linecap="round" stroke-linejoin="round" />
                                             </svg>

                                             <span class="ms-2">Profile </span>
                                         </a>
                                         <a href="page-error-404.html" class="dropdown-item ai-icon ">
                                             <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-pie-chart">
                                                 <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                                 <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                                             </svg>



                                             <span class="ms-2">Settings </span>
                                         </a>
                                         <div style="margin: 20px;">
                                             <form action="{{ route('logout') }}" method="POST">
                                                 @csrf

                                                 <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg"
                                                     class="text-danger" width="18" height="18" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round">
                                                     <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                     <polyline points="16 17 21 12 16 7"></polyline>
                                                     <line x1="21" y1="12" x2="9" y2="12"></line>
                                                 </svg>
                                                 <span class="ml-2"> <input class="ms-2" type="submit" value="Logout"
                                                         style="border: none; background-color:white; color:gray; padding: auto;"></span>
                                             </form>
                                         </div>



                                         <!-- <form action="{{ route('logout') }}" method="POST">
                                                 @csrf
                                                 <input class="ms-2" type="submit" value="Logout">
                                             </form> -->

                                     </div>
                                 </div>

                             </div>
                         </div>
                     </li>
                     </ul>
                 </div>
             </div>
         </nav>
     </div>
 </div>
 <!--**********************************
            Header end ti-comment-alt
        ***********************************-->