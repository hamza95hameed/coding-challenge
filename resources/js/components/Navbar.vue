<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow">
        <div class="container">
            <a ></a>
            <router-link class="navbar-brand" :to="{ name: 'Index'}"> Coding Challenge</router-link>
            <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button> -->

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto" v-if="currentUser == ''">
                    <!-- Authentication Links -->
                    
                            <li class="nav-item">
                                <router-link class="nav-link" :to="{ name: 'Login'}">Login</router-link>
                            </li>
                       
                            <li class="nav-item">
                                <router-link class="nav-link" :to="{ name: 'Register'}">Register</router-link>
                            </li>
                </ul>
                <ul class="navbar-nav ms-auto" v-else>
                    
                            <li class="nav-item dropdown">
                                <span class="nav-link">{{ currentUser.name }}</span>                                


                                <!-- <div class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div> -->
                            </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
    export default{
        data(){
            return{
                currentUser: '',
                token: localStorage.getItem('token')
            }
        },
        mounted() {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            axios.get('api/user').then((response) => {
                this.currentUser = response.data
            }).catch((errors) => {
                console.log(errors)
            })
        },
    }
</script>