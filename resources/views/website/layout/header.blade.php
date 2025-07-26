<!doctype HTML>
<html>

<head>
    <title>{{__('Login')}}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <script src="{{asset('js/app.js')}}"></script>
</head>

<body>
    <section>
        <div class="flex xxxxl:items-center justify-between">
            <div class="lg:w-[20%] s:w-[90%]">
                <a href="{{url('/')}}"><img src="{{asset('website/image/logo.png')}}" alt="" class="object-cover"></a>
            </div>
            <div class="lg:w-[80%] s:w-[10%] m:w-[7%] l:w-[5%] md:w-[4%] flex items-center justify-between">
                <div class="relative">
                    <button data-collapse-toggle="navbar-default" type="button" aria-controls="navbar-default" aria-expanded="false" class="lg:hidden">
                        <svg class="w-6 h-6 mt-2" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                        </svg>
                    </button>
                    <div class="z-10 hidden lg:block s:bg-[#FFFFFF] s:mt-10 s:-ml-[230px] s:border s:border-light-gray s:shadow s:rounded-[5px] s:p-3 lg:bg-white lg:mt-0 lg:-ml-[0px] lg:border-[0px] lg:shadow-none lg:p-0" id="navbar-default">
                        <div class="relative mx-auto text-gray-600 lg:hidden mb-5 ">

                            <form action="{{url('/search_query')}}">
                             
                                <input class="xxxxl:w-[290px] h-[46px] s:w-full border border-light-gray rounded-lg px-3 font-mulish font-medium text-base" type="search" name="search" placeholder="Search Parking">
                                <button type="submit" class="absolute right-0 top-2 mr-4 bg-light-blue w-8 h-8 rounded">
                                    <img src="{{asset('website/icon/search.svg')}}" alt="" class="mx-auto">
                                </button>

                            </form>
                        </div>
                        <ul class="flex lg:flex-row s:flex-col xxxxl:gap-8 s:gap-5 xl:gap-10 xxl:gap-12">
                            <a href="{{url('/')}}">
                                <li class="font-poppins font-medium text-lg capitalize {{ $activePage == 'home' ? 's:bg-primary s:text-white s:p-2 s:rounded-[5px] lg:text-primary lg:bg-white lg:p-0 lg:rounded-[0px]' : 'text-[#556987]'}}">{{__('Home')}}</li>
                            </a>
                            @if(Auth::guard('appuser')->check())
                            <a href="{{url('/display_parking_booking')}}">
                                <li class="font-poppins font-medium text-lg capitalize {{ $activePage == 'booking' ? 's:bg-primary s:text-white s:p-2 s:rounded-[5px] lg:text-primary lg:bg-white lg:p-0 lg:rounded-[0px]' : 'text-[#556987]'}}">{{__('My bookings')}}</li>
                            </a>
                            @else
                            <li class="font-poppins font-medium text-lg capitalize {{ $activePage == 'booking' ? 's:bg-primary s:text-white s:p-2 s:rounded-[5px] lg:text-primary lg:bg-white lg:p-0 lg:rounded-[0px]' : 'text-[#556987]'}} cursor-pointer" data-modal-target="SignIn" data-modal-toggle="SignIn" type="button">{{__('My bookings')}}</li>

                            @endif
                            @if(!Auth::guard('appuser')->check())
                            <a href="{{url('/about_us')}}">
                                <li class="font-poppins font-medium text-lg capitalize {{ $activePage == 'aboutus' ? 's:bg-primary s:text-white s:p-2 s:rounded-[5px] lg:text-primary lg:bg-white lg:p-0 lg:rounded-[0px]' : 'text-[#556987]'}}">{{__('About us')}}</li>
                            </a>
                            <a href="{{url('/contact_us')}}">
                                <li class="font-poppins font-medium text-lg capitalize {{ $activePage == 'contactus' ? 's:bg-primary s:text-white s:p-2 s:rounded-[5px] lg:text-primary lg:bg-white lg:p-0 lg:rounded-[0px]' : 'text-[#556987]'}}">{{__('Contact us')}}</li>
                            </a>
                            @endif
                        </ul>

                        {{-- mobile menu --}}
                        @if(Auth::guard('appuser')->check())
                        <div class="lg:hidden mt-5">
                            <div class="flex flex-row space-x-2 items-center">
                                @if(isset(Auth::guard('appuser')->user()->image))
                                    @if(Auth::guard('appuser')->user()->image)
                                        <img src="{{Auth::guard('appuser')->user()->image}}" alt="" class="rounded-full xxxxl:w-[46px] xxxxl:h-[46px] l:w-[35px] l:h-[35px] object-cover" id="dropdownNavbarLink">
                                    @else
                                        <img src="{{asset('upload/'.Auth::guard('appuser')->user()->image)}}" alt="" class="rounded-full xxxxl:w-[46px] xxxxl:h-[46px] l:w-[35px] l:h-[35px] object-cover" id="dropdownNavbarLink">
                                    @endif
                                @endif
                                <h5 class="font-poppins font-semibold text-[#556987] text-lg">{{Auth::guard('appuser')->user()->name}}</h5>
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg></button>
                                <!-- Dropdown menu -->
                                <div id="dropdownNavbar1" class="z-10 hidden font-normal bg-white rounded-[16px] shadow w-[201px] !mt-6 !ml-5">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                                        <li>
                                            <a href="{{url('/display_parking_booking')}}" class="block px-4 py-2 font-poppins font-normal text-sm text-[#333F51] tracking-wide flex items-center gap-3"><img src="{{asset('website/icon/booking.svg')}}" alt="">{{__('My bookings')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{url('/user_vehicle')}}" class="block px-4 py-2 font-poppins font-normal text-sm text-[#333F51] tracking-wide flex items-center gap-3"><img src="{{asset('website/icon/car.svg')}}" alt="">{{__('My vehicle')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{url('/profile')}}" class="block px-4 py-2 font-poppins font-normal text-sm text-[#333F51] tracking-wide flex items-center gap-3"><img src="{{asset('website/icon/profile.svg')}}" alt="">{{__('Profile')}}</a>
                                        </li>
                                        <li>
                                            <a href="#" class="block px-4 py-2 font-poppins font-normal text-sm text-[#333F51] tracking-wide flex items-center gap-3" data-modal-target="change-password" data-modal-toggle="change-password" type="button"><img src="{{asset('website/icon/lock.svg')}}" alt="">{{__('Change Password')}}</a>
                                        </li>
                                        <li>
                                            <a href="#" class="block px-4 py-2 font-poppins font-normal text-sm text-[#EF5944] tracking-wide flex items-center gap-3" data-modal-target="logout" data-modal-toggle="logout"><img src="{{asset('website/icon/logout.svg')}}" alt="">{{__('Logout')}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="flex gap-10 lg:hidden mt-5">
                            <button type="button" class="font-poppins font-medium text-[#556987] text-lg capitalize tracking-wide" data-modal-target="SignUp" data-modal-toggle="SignUp">{{__('Signup')}}</button>
                            <button type="button" class="font-poppins font-medium text-white text-base capitalize w-[91px] h-[46px] bg-primary rounded-[6px]" data-modal-target="SignIn" data-modal-toggle="SignIn">{{__('Login')}}</button>
                        </div>
                        @endif
                    </div>
                </div>
                <!-- component -->
                <div class="relative text-gray-600 s:hidden lg:block ">
                    <form action="{{ url('/search_query') }}">
                       
                        <input class="xxxxl:w-[290px] h-[46px] s:w-full border border-light-gray rounded-lg px-3 font-mulish font-medium text-base" type="search" name="search" placeholder="Search Parking">
                        <button type="submit" class="absolute right-0 top-2 mr-4 bg-light-blue w-8 h-8 rounded">
                            <img src="{{ asset('website/icon/search.svg') }}" alt="" class="mx-auto">
                        </button>

                    </form>

                </div>

                @if(Auth::guard('appuser')->check())

                <div class="s:hidden lg:block">
                    <a href="javascript:void(0)" class="flex flex-row xxxxl:space-x-5 lg:space-x-2 items-center" data-dropdown-toggle="dropdownNavbar">
                        @if(isset(Auth::guard('appuser')->user()->image))
                            @if(Auth::guard('appuser')->user()->image)
                                <img src="{{Auth::guard('appuser')->user()->image}}" alt="" class="rounded-full xxxxl:w-[46px] xxxxl:h-[46px] l:w-[35px] l:h-[35px] object-cover" id="dropdownNavbarLink">
                            @else
                                <img src="{{asset('upload/'.Auth::guard('appuser')->user()->image)}}" alt="" class="rounded-full xxxxl:w-[46px] xxxxl:h-[46px] l:w-[35px] l:h-[35px] object-cover" id="dropdownNavbarLink">
                            @endif
                        @endif

                        <h5 class="font-poppins font-semibold text-[#556987] text-lg">{{Auth::guard('appuser')->user()->name}}</h5>
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg></button>
                        <div id="dropdownNavbar" class="z-10 hidden font-normal bg-white rounded-[16px] shadow w-[201px] !mt-6 !ml-10">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
                                <li>
                                    <a href="{{url('/display_parking_booking')}}" class="block px-4 py-2 font-poppins font-normal text-sm text-[#333F51] tracking-wide flex items-center gap-3"><img src="{{asset('website/icon/booking.svg')}}" alt="">{{__('My bookings')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('/user_vehicle')}}" class="block px-4 py-2 font-poppins font-normal text-sm text-[#333F51] tracking-wide flex items-center gap-3"><img src="{{asset('website/icon/car.svg')}}" alt="">{{__('My vehicles')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('/user_profile')}}" class="block px-4 py-2 font-poppins font-normal text-sm text-[#333F51] tracking-wide flex items-center gap-3"><img src="{{asset('website/icon/profile.svg')}}" alt="">{{__('Profile')}}</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 font-poppins font-normal text-sm text-[#333F51] tracking-wide flex items-center gap-3" data-modal-target="change-password" data-modal-toggle="change-password" type="button"><img src="{{asset('website/icon/lock.svg')}}" alt="">{{__('Change Password')}}</a>
                                </li>
                                <li>
                                    <a href="javascript::void()" class="block px-4 py-2 font-poppins font-normal text-sm text-[#EF5944] tracking-wide flex items-center gap-3" data-modal-target="logout" data-modal-toggle="logout"><img src="{{asset('website/icon/logout.svg')}}" alt="">{{__('Logout')}}</a>
                                </li>
                            </ul>
                        </div>
                    </a>
                </div>
                @else

                <div class="flex s:hidden lg:block">
                    <button type="button" class="font-poppins font-medium text-[#556987] text-lg capitalize tracking-wide" data-modal-target="SignUp" data-modal-toggle="SignUp">{{__('Signup')}}</button>
                    <button type="button" class="font-poppins font-medium text-white text-base capitalize w-[91px] h-[46px] bg-primary rounded-[6px] xxxxl:ml-10 xl:ml-5 xxl:ml-10" data-modal-target="SignIn" data-modal-toggle="SignIn">{{__('Login')}}</button>
                </div>
                @endif

                {{-- Sign In --}}
                <div id="SignIn" aria-hidden="true" tabindex="-1" class="fixed top-10 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-modal md:h-full" >
                    <div class="relative xxxxl:w-[450px] s:w-full h-full max-w-2xl md:h-auto">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-2xl shadow dark:bg-gray-700 p-8">
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white absolute right-5" data-modal-hide="SignIn">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                            <div class="col-12 mt-5">
                                <div id="errors-list" style="color:red"></div>
                                <!-- Modal body -->
                                <img src="{{asset('website/image/login-logo.png')}}" alt="" class="mx-auto mb-8">
                                <h5 class="font-poppins font-semibold text-2xl text-[#2A3342] text-center mb-16">{{__('Sign in to your account')}}</h5>
                                <div class="flex justify-center gap-4 mb-6">
                                    <a href="{{ url('auth/google') }}" class="px-4 py-2 bg-warning text-white rounded flex items-center gap-2 hover:bg-red-600">
                                        <i class="fab fa-google"></i> Google
                                    </a>
                                    <a href="{{ url('auth/facebook') }}" class="px-4 py-2 bg-blue-600 text-white rounded flex items-center gap-2 hover:bg-blue-700">
                                        <i class="fab fa-facebook-f"></i> Facebook
                                    </a>
                                </div>
                                <form action="">
                                    @csrf
                                    <div class="form-group mb-5">
                                        <label for="email" class="font-poppins font-medium text-black text-base tracking-wide">{{__('Email')}}</label>
                                        <input type="email" name="loginEmail" value="{{old('email')}}" class="form-control border border-[#D5DAE1] w-full p-2 rounded-[8px] mt-3" />

                                        <div class="email" style="color:red"></div>

                                    </div>
                                    <div class="form-group mb-5">
                                        <label for="password" class="font-poppins font-medium text-black text-base tracking-wide">{{__('Password')}}</label>
                                        <div class="flex relative">
                                            <input type="password" name="loginPassword" id="loginpasswords" value="{{old('password')}}" class="form-control border border-[#D5DAE1] w-full p-2 rounded-[8px] mt-3">
                                            <i class="far fa-eye text-[#536471] absolute right-5 top-6" id="signintogglePassword"></i>
                                        </div>
                                        <div class="password" style="color:red"></div>

                                    </div>
                                    <div class="flex justify-between items-center mb-3 s:flex-col l:flex-row">
                                        <div class="flex items-start mb-6">
                                            <div class="flex items-center h-5">
                                                
                                            </div>
                                            
                                        </div>
                                        <button type="button" class="btn1 font-poppins font-medium text-primary text-sm s:mb-5" data-modal-target="ForgotPassword" data-modal-toggle="ForgotPassword">{{__('Forgot your password?')}}</button>
                                    </div>
                                    <button type="button" class="w-full bg-primary rounded-[6px] h-12 font-poppins font-medium text-base text-white tracking-wide mb-5" onclick="signIn()">{{__('Sign In')}}</button>
                                   
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

               

                {{-- Sign Up --}}
                <div id="SignUp" tabindex="-1" aria-hidden="true" class="fixed top-5 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:max-h-full">
                    <div class="relative h-full md:h-auto flex items-center justify-center mx-auto lg:mt-[5%]">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-2xl  p-8 xxxxl:w-[450px] s:w-full">
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white absolute right-5" data-modal-hide="SignUp">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                            <!-- Modal body -->
                            <img src="{{asset('website/image/login-logo.png')}}" alt="" class="mx-auto mb-8">
                            <h5 class="font-poppins font-semibold text-2xl text-[#2A3342] text-center mb-10">{{__('Create an account')}}</h5>
                            <form action="">
                                @csrf
                                <div class="form-group mb-4">
                                    <label for="name" class="font-poppins font-medium text-black text-base tracking-wide">{{__('Name')}}</label>
                                    <input type="text" name="name" value="{{old('name')}}" class="form-control border border-[#D5DAE1] w-full p-2 rounded-[8px] mt-3" maxlength="20">
                                    <div class="name" style="color:red"></div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="email" class="font-poppins font-medium text-black text-base tracking-wide">{{__('Email address')}}</label>
                                    <input type="text" name="email" value="{{old('email')}}" class="form-control border border-[#D5DAE1] w-full p-2 rounded-[8px] mt-3" />
                                    <div class="email" style="color:red"></div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="password" class="font-poppins font-medium text-black text-base tracking-wide">{{__('Password')}}</label>
                                    <div class="flex relative">
                                        <input type="password" name="password" id="passwords" value="{{old('password')}}" class="form-control border border-[#D5DAE1] w-full p-2 rounded-[8px] mt-3" />
                                        <i class="far fa-eye text-[#536471] absolute right-5 top-6" id="signUptogglePassword"></i>
                                    </div>
                                    <div class="password" style="color:red"></div>
                                </div>
                                <div class="form-group mb-10">
                                    <label for="cpassword" class="font-poppins font-medium text-black text-base tracking-wide">{{__('Confirm Password')}}</label>
                                    <div class="flex relative">
                                        <input type="password" name="confirmpassword" id="confirmpassword" value="{{old('confirmpassword')}}" class="form-control border border-[#D5DAE1] w-full p-2 rounded-[8px] mt-3" />
                                        <i class="fa-regular fa-eye text-[#536471] absolute right-5 top-6" id="signuptoggleCPassword"></i>

                                    </div>
                                    <div class="confirmpassword" style="color:red"></div>
                                </div>
                                <button type="button" class="w-full bg-primary rounded-[6px] h-12 font-poppins font-medium text-base text-white tracking-wide" onclick="signUp()">{{__('Create account')}}
                                    <!-- <div id="process-circle" class="inline-block h-5 w-5 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite] ml-2 mt-1 " role="status"></div> -->
                                </button>
                         
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Forgot Password --}}
                <div id="ForgotPassword" tabindex="-1" aria-hidden="true" class="modal fixed top-5 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-modal md:h-full">
                    <div class="relative xxxxl:w-[450px] s:w-full h-full max-w-2xl md:h-auto">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-2xl shadow dark:bg-gray-700 p-8">
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white absolute right-5" data-modal-hide="ForgotPassword">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                            <!-- Modal body -->
                            <img src="{{asset('website/image/login-logo.png')}}" alt="" class="mx-auto mb-8">
                            <h5 class="font-poppins font-semibold text-2xl text-[#2A3342] text-center mb-16">{{__('Forgot Password')}}</h5>
                            <form action="">
                                @csrf
                                <div class="form-group mb-10">
                                    <label for="email" class="font-poppins font-medium text-black text-base tracking-wide">{{__('Email address')}}</label>
                                    <input type="text" name="fpemail" value="{{old('fpemail')}}" class="border border-[#D5DAE1] w-full p-2 rounded-[8px] mt-3">
                                    <div class="fpemail" style="color:red"></div>
                                    <div id="fp-list" style="color:red"></div>
                                </div>
                                <button type="button" class="w-full bg-primary rounded-[6px] h-12 font-poppins font-medium text-base text-white tracking-wide mb-10" onclick="sendMail()">{{__('Send')}}</button>
                                <p class="text-center text-black font-poppins font-medium text-sm tracking-wide">{{__('Already have account ?')}} <button class="text-primary" type="button" data-modal-target="SignIn" data-modal-toggle="SignIn">{{__('Login')}}</button></p>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Change Password --}}
                <div id="change-password" tabindex="-1" aria-hidden="true" class="fixed top-5 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-modal md:h-full">
                    <div class="relative xxxxl:w-[450px] s:w-full h-full max-w-2xl md:h-auto">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-2xl shadow p-8">
                            <div id="password-error" style="color:red"></div>
                            <!-- Modal body -->
                            <div class="flex">
                                <h5 class="font-poppins font-semibold text-[#404F65] text-2xl mb-8">{{__('Change password')}}</h5>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white absolute right-5" data-modal-hide="change-password">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <form action="">
                                @csrf
                                <div class="form-group mb-5">
                                    <label for="cpassword" class="font-poppins font-medium text-black text-base tracking-wide">{{__('Current Password')}}</label>
                                    <div class="flex relative">
                                        <input type="password" name="old_password" id="oldpassword" class="border border-[#D5DAE1] rounded-lg w-full mt-3 font-poppins font-normal text-[#667085] text-base form-control" value="{{old('oldpassword')}}">
                                        <i class="fa-regular fa-eye absolute right-5 top-6" id="oldtogglePasswords"></i>
                                    </div>
                                    <div id="notmatch-error" style="color:red"></div>
                                </div>
                                <div class="form-group mb-5">
                                    <label for="npassword" class="font-poppins font-medium text-black text-base tracking-wide">{{__('New Password')}}</label>
                                    <div class="flex relative">
                                        <input type="password" name="npassword" id="npassword" class="border border-[#D5DAE1] rounded-lg w-full mt-3 font-poppins font-normal text-[#667085] text-base form-control" value="{{old('npassword')}}">
                                        <i class="fa-regular fa-eye absolute right-5 top-6" id="newpassword"></i>
                                    </div>
                                    <div class="password" style="color:red"></div>
                                </div>
                                <div class="form-group mb-5">
                                    <label for="confirmpassword" class="font-poppins font-medium text-black text-base tracking-wide">{{__('Confirm Password')}}</label>
                                    <div class="flex relative">
                                        <input type="password" name="confirm_password" id="cpassword" class="border border-[#D5DAE1] rounded-lg w-full mt-3 font-poppins font-normal text-[#667085] text-base form-control @error('confirm_password') is-invalid @enderror" autocomplete="confirm_password">
                                        <i class="fa-regular fa-eye absolute right-5 top-6" id="confirmPassword"></i>
                                    </div>
                                    <div class="confirmpassword" style="color:red"></div>
                                </div>
                                <button type="button" class="w-full bg-primary rounded-md font-poppins font-medium text-white text-base py-3 tracking-wide mt-7" onclick="changePassword()">{{__('Update')}}</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Logout --}}
                <div id="logout" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                    <div class="relative xxxxl:w-[450px] s:w-full h-full max-w-2xl md:h-auto">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div class="p-5 rounded-t">
                                <h4 class="font-poppins font-medium text-2xl text-[#404F65] mb-5">{{__('Log out?')}}</h4>
                                <p class="font-poppins font-normal text-lg text-[#404F65] mb-5">{{__('Are you sure to log out of the system?')}}</p>
                                <div class="flex gap-5 justify-end">
                                    <button type="button" class="border border-[#D5DAE1] rounded-[8px] p-3 text-[#8896AB] font-poppins font-normal text-base xxxxl:px-5 tracking-wide" data-modal-hide="logout">{{__('Cancel')}}</button>
                                    <a href="{{ route('logout') }}" type="button" class="bg-dark-orange rounded-[8px] p-3 text-white font-poppins font-normal text-base xxxxl:px-5 tracking-wide">{{__('Log out')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </section>
</body>
<script src="{{asset('website/js/custom.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $(".btn1").click(function() {
            $("#SignIn").hide();
        });
    });
</script>

</html>