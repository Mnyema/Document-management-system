
<!DOCTYPE html>
<html lang="en">

<head>
  <base href="/public">
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Document Management System</title>
  <!-- base:css -->
  <link rel="stylesheet" href="spica/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="spica/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="spica/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="spica/images/favicon.png" />
</head>


<style>
  .position-relative:hover .custom-dropdown-content {
      display: block !important;
  }

  .custom-dropdown-content {
    position: absolute;
    top: 100%;
    right: 5%;
    left: 2%;
    font-size: 12px;
    background-color: lightblue;
    padding: 10px;
    border-radius: 4px;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
    min-width: 200px;
    z-index: 1000;
  }

  .dropdown-item {
    text-align: left; /* Align the text to the left */
    width: 100%; /* Set the width to fill the box */
    white-space: nowrap; /* Prevent line breaks */
   /* overflow: hidden; 
    text-overflow: ellipsis; */
    left: 0%;
  }
  .link-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .divide{
    display: flex;
  }
</style>
<body>
  <div class="container-scroller d-flex">
    <!-- partial:./partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
        <li class="nav-item sidebar-category">
          <p>Navigation</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/">
            <i class="mdi mdi-view-quilt menu-icon"></i>
            <span class="menu-title">Dashboard</span>
            <div class="badge badge-info badge-pill"></div>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{route('posts.create')}}">
            <button class="btn bg-danger btn-sm menu-title"><i class="mdi mdi-upload"></i>  Upload Files</button>
          </a>
        </li>

        
        <li class="nav-item sidebar-category">
          <p>Documents</p>
          <span></span>
        </li>
        @foreach($posts as $post)
        @if($post->hasMedia('docs'))
            @php
               $media = $post->getFirstMedia('docs');
            @endphp
                <li class="nav-item ">
          <div class="position-relative">
            <a class="nav-link custom-dropdown-toggle" href="#" role="button" id="fileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <div style="flex-grow: 1;">
                        <i class="mdi mdi-file-document-box-outline menu-icon"></i>
                        <span class="menu-title">{{ pathinfo($post->getFirstMedia('docs')->file_name, PATHINFO_FILENAME) }}</span>
                    </div>
                    <span style="font-size: 20px; color: #999;">...</span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right d-none custom-dropdown-content" aria-labelledby="fileDropdown{{$post->id}}">
                    <div class="dropdown-item">
                        Path: {{$post->getFirstMediaUrl('docs')}}
                    </div>
                    <div class="dropdown-item">
                        Size: {{ formatBytes($post->getFirstMedia('docs')->size) }}
                    </div>
                    <div class="dropdown-item">
                      Date: {{ $post->getFirstMedia('docs')->created_at->format('Y-m-d H:i:s') }}
                  </div>
                    <!-- Add more file details here as needed -->
    
                    <div class="dropdown-divider"></div>
                    <div class="link-container">
                    <a class="dropdown-item" href="{{route('posts.show', $post->id)}}">Open File</a>
                    {{-- href="{{ asset($files->path) }}" --}}
                    <form method="POST" action="{{route('posts.destroy', $post->id)}}" onsubmit="return confirm('Are you sure');" class="dropdown-item btn btn-danger">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn">Delete</button>
                      </form>
                    </div>
                </div>
            </div>
        </li>
        @endif
    @endforeach
    
        
        
        
        <li class="nav-item sidebar-category">
          <p>Pages</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="/" aria-expanded="false" aria-controls="auth">
            <i class="mdi mdi-account menu-icon"></i>
            <span class="menu-title">User Pages</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="auth">
            <ul class="nav flex-column sub-menu">
              {{-- <li class="nav-item"> <a class="nav-link" href="spica/pages/samples/login.html"> Login </a></li> --}}
              <li class="nav-item"> <a class="nav-link" href="{{url('login')}}"> Login  </a></li>
              {{-- <li class="nav-item"> <a class="nav-link" href="spica/pages/samples/register.html"> Register </a></li>
              <li class="nav-item"> <a class="nav-link" href="spica/pages/samples/register-2.html"> Register 2 </a></li> --}}
              <li class="nav-item"> <a class="nav-link" href="/"> Lockscreen </a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item sidebar-category">
          <p>Documents</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/">
            <i class="mdi mdi-file-document-box-outline menu-icon"></i>
            <span class="menu-title">Documentation</span>
          </a>
        </li>
       
      </ul>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:./partials/_navbar.html -->
      <nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <div class="navbar-brand-wrapper">
            {{-- <a class="navbar-brand brand-logo" href="/"><img src="spica/images/logo.svg" alt="logo"/></a> --}}
            <h1 class="" href="/"> Document Management System
              {{-- <img src="spica/images/logo-mini.svg" alt="logo"/> --}}
            </h1>
          </div>
          <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1"></h4>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item">
              <h4 class="mb-0 font-weight-bold d-none d-xl-block">{{ date('F j, Y') }}</h4>
            </li>
            <li class="nav-item dropdown mr-1">
              <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="/" data-toggle="dropdown">
                <i class="mdi mdi-calendar mx-0"></i>
                <span class="count bg-info">2</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                      <img src="spica/images/faces/face4.jpg" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content flex-grow">
                    <h6 class="preview-subject ellipsis font-weight-normal">David Grey
                    </h6>
                    <p class="font-weight-light small-text text-muted mb-0">
                      The meeting is cancelled
                    </p>
                  </div>
                </a>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                      <img src="spica/images/faces/face2.jpg" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content flex-grow">
                    <h6 class="preview-subject ellipsis font-weight-normal">Tim Cook
                    </h6>
                    <p class="font-weight-light small-text text-muted mb-0">
                      New product launch
                    </p>
                  </div>
                </a>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                      <img src="spica/images/faces/face3.jpg" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content flex-grow">
                    <h6 class="preview-subject ellipsis font-weight-normal"> Johnson
                    </h6>
                    <p class="font-weight-light small-text text-muted mb-0">
                      Upcoming board meeting
                    </p>
                  </div>
                </a>
              </div>
            </li>
            <li class="nav-item dropdown mr-2">
              <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="/" data-toggle="dropdown">
                <i class="mdi mdi-email-open mx-0"></i>
                <span class="count bg-danger">1</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-success">
                      <i class="mdi mdi-information mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal">Application Error</h6>
                    <p class="font-weight-light small-text mb-0 text-muted">
                      Just now
                    </p>
                  </div>
                </a>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-warning">
                      <i class="mdi mdi-settings mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal">Settings</h6>
                    <p class="font-weight-light small-text mb-0 text-muted">
                      Private message
                    </p>
                  </div>
                </a>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-info">
                      <i class="mdi mdi-account-box mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal">New user registration</h6>
                    <p class="font-weight-light small-text mb-0 text-muted">
                      2 days ago
                    </p>
                  </div>
                </a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
        <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
          <ul class="navbar-nav mr-lg-2">
            <li class="nav-item nav-search d-none d-lg-block">
              {{-- <div class="input-group">
                <input type="text" class="form-control" placeholder="Search Here..." aria-label="search" aria-describedby="search">
              </div> --}}
              <form action="{{ route('search') }}" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search here" required>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="mdi mdi-file-find" style="font-size: 20px"></i></button>
                    </div>
                </div>
            </form>
            <div class="bg-primary" style="position:absolute; z-index:9999">
            @if (session()->has('search_results'))
            @php
                $results = session('search_results');
                session()->forget('search_results');
            @endphp
 
 
  <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
    <i class="mdi mdi-file-find menu-icon"></i>
    <span class="menu-title">Search Results: {{ request()->input('q') }}</span>
    <i class="menu-arrow"></i>
  </a>
  <div class="collapse" id="auth">
    <ul class="nav flex-column sub-menu">
      @foreach ($results['fileNameResults'] as $result)
      <li class="nav-item"> <a class="nav-link" href="{{route('posts.show', $result->id)}}"> {{ $result->file_name }}</a></li>
      @endforeach
     
      @foreach ($results['docxResults'] as $result)
      <li class="nav-item"> <a class="nav-link" href="{{route('posts.show', $result['id'])}}"> {{ $result['file_name'] }}: {!! str_replace(request()->input('q'), "<strong>" . request()->input('q') . "</strong>", htmlspecialchars_decode($result['sentence'])) !!} </a></li>
     @endforeach
     
     @foreach ($results['xlsxResults'] as $result)
     <li class="nav-item"> <a class="nav-link" href="{{route('posts.show', $result['id'])}}"> {{ $result['file_name'] }}: {!! str_replace(request()->input('q'), "<strong>" . request()->input('q') . "</strong>", htmlspecialchars_decode($result['cell_value'])) !!}</a></li>
    @endforeach

      @foreach ($results['pptxResults'] as $result)
      <li class="nav-item"> <a class="nav-link" href="{{route('posts.show', $result['id'])}}"> {{ $result['file_name'] }}: {!! str_replace(request()->input('q'), "<strong>" . request()->input('q') . "</strong>", htmlspecialchars_decode($result['sentence'])) !!}</a></li>
        @endforeach
     
        @foreach ($results['txtResults'] as $result)
        <li class="nav-item"> <a class="nav-link" href="p{{route('posts.show', $result['id'])}}"> {{ $result['file_name'] }}: {!! str_replace(request()->input('q'), "<strong>" . request()->input('q') . "</strong>", htmlspecialchars_decode($result['sentence'])) !!} </a></li>
    @endforeach
      
    @foreach ($results['pdfResults'] as $result)
    <li class="nav-item"> <a class="nav-link" href="p{{route('posts.show', $result['id'])}}"> {{ $result['file_name'] }}: {!! str_replace(request()->input('q'), "<strong>" . request()->input('q') . "</strong>", htmlspecialchars_decode($result['sentence'])) !!} </a></li>
@endforeach
    </ul>
  </div>
 {{-- @else 
 <li class="nav-item">No result</li> --}}
@endif
            </div>      </li>
          </ul>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              
                @if (Route::has('login'))
                @auth
                <x-app-layout>

                </x-app-layout>

                @else
                <span class="nav-profile-name"> <a class="nav-link" href="{{url('login')}}"> Login /  </a></span>
                <span class="nav-profile-name"> <a class="nav-link" href="{{url('register')}}"> Register </a></span>
                @endauth
                @endif
              
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item">
                  <i class="mdi mdi-settings text-primary"></i>
                  Settings
                </a>
                <a class="dropdown-item">
                  <i class="mdi mdi-logout text-primary"></i>
                  Logout
                </a>
              </div>
            </li>
            <li class="nav-item">
              <a href="/" class="nav-link icon-link">
                <i class="mdi mdi-plus-circle-outline"></i>
              </a>
            </li>
            <li class="nav-item">
              <a href="/" class="nav-link icon-link">
                <i class="mdi mdi-web"></i>
              </a>
            </li>
            <li class="nav-item">
              <a href="/" class="nav-link icon-link">
                <i class="mdi mdi-clock-outline"></i>
              </a>
            </li>
          </ul>
        </div>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          @if(session()->has('message'))
                    <div class="alert alert-success mdi mdi-check-circle-outline">
                      <button type="button" class="close" data-dismiss="alert">
                        x
                      </button>
                         {{session()->get('message')}}
                    </div>
                    @endif
         
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="divide" style="display: flex; align-items: center;">
                    <i class="mdi mdi-book-open-page-variant" style="margin-right: 10px;"></i>
                    <h4 class="card-title f-6" style="flex: 3;">{{ pathinfo($data['media']->file_name, PATHINFO_FILENAME) }}</h4>
                    <div style="flex: 1; display: flex; justify-content: flex-end;">
                      <a class="btn btn-info f-2" style="margin-left: 10px;" href="{{ route('download',  ['id' => $data['media']->id])}}"><i class="mdi mdi-arrow-down-bold-circle"></i></a>

                      <form method="POST" action="{{route('posts.destroy', $post->id)}}" onsubmit="return confirm('Are you sure');" class="dropdown-item btn btn-danger">
                        @csrf
                        @method('DELETE')
                         
                        <button type="submit" style=" width:auto; height:auto; align-items:center; background-color:red;" class=" "  >
                          <i class="mdi mdi-delete input-group-append" style="font-size: 40px; color:white; margin-right:5%; margin-left:2px; margin-bottom:0%"></i> 
                        </button>
                        </form>

                        {{-- <a onclick="return confirm('Are you sure to delete this document?')" class="btn btn-danger f-2" style="margin-left: 10px;" href="">Delete</a> --}}
                    </div>
                   
                </div>
                <div >
                  
                  {{-- <iframe src="{{ asset('storage/' .$selected->path) }}" style="width: 100%; height: 400px; border: none;"></iframe> --}}
                  @if ($data['media']->mime_type === 'application/pdf')
                  <embed src="data:application/pdf;base64,{{base64_encode($data['contents'])}}" type="application/pdf" width="100%" height="600px">
              @elseif (strpos($data['media']->mime_type, 'image/') === 0)
                  <img src="data:{{$data['media']->mime_type}};base64,{{base64_encode($data['contents'])}}" alt="Image">
              @elseif ($data['media']->mime_type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                  {!! $data['contents'] !!}
              @elseif($data['media']->mime_type==='text/plain')
                  {{$data['contents']}}
              @elseif($data['media']->mime_type==='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                  {!! $data['htmlContent']!!}
               @elseif ($data['media']->mime_type === 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
                  {!! $data['htmlContent'] !!}
              
              @else
                  <p>Unsupported media type</p>
              @endif
                
                
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- row end -->
          <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card bg-facebook d-flex align-items-center">
                <div class="card-body py-5">
                  <div
                    class="d-flex flex-row align-items-center flex-wrap justify-content-md-center justify-content-xl-start py-1">
                    <i class="mdi mdi-facebook text-white icon-lg"></i>
                    <div class="ml-3 ml-md-0 ml-xl-3">
                      <h5 class="text-white font-weight-bold">2.62 Subscribers</h5>
                      <p class="mt-2 text-white card-text">You main list growing</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card bg-google d-flex align-items-center">
                <div class="card-body py-5">
                  <div
                    class="d-flex flex-row align-items-center flex-wrap justify-content-md-center justify-content-xl-start py-1">
                    <i class="mdi mdi-google-plus text-white icon-lg"></i>
                    <div class="ml-3 ml-md-0 ml-xl-3">
                      <h5 class="text-white font-weight-bold">3.4k Followers</h5>
                      <p class="mt-2 text-white card-text">You main list growing</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card bg-twitter d-flex align-items-center">
                <div class="card-body py-5">
                  <div
                    class="d-flex flex-row align-items-center flex-wrap justify-content-md-center justify-content-xl-start py-1">
                    <i class="mdi mdi-twitter text-white icon-lg"></i>
                    <div class="ml-3 ml-md-0 ml-xl-3">
                      <h5 class="text-white font-weight-bold">3k followers</h5>
                      <p class="mt-2 text-white card-text">You main list growing</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- row end -->
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:./partials/_footer.html -->
        <footer class="footer">
          <div class="card">
            <div class="card-body">
              <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © CodeWithFashion</span>
                <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Distributed By: <a href="/" target="_blank">GetCore</a></span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap dashboard templates</a> from Bootstrapdash.com</span>
              </div>
            </div>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- base:js -->
  <script src="spica/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="spica/vendors/chart.js/Chart.min.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="spica/js/off-canvas.js"></script>
  <script src="spica/js/hoverable-collapse.js"></script>
  <script src="spica/js/template.js"></script>
  <!-- endinject -->
  <!-- plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="spica/js/dashboard.js"></script>
  <!-- End custom js for this page-->

  <script>
    document.querySelector('.bg-primary').addEventListener('change', function() {
        window.location.href = this.options[this.selectedIndex].value;
    });
</script>

</body>

</html>