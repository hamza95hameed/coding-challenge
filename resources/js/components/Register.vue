<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card bg-dark text-white shadow">
                    <div class="card-header">Register</div>

                    <div class="card-body">
                        <form @submit.prevent="submitForm()">
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" v-model="formData.name" class="form-control bg-dark text-white" name="name" value="" required autocomplete="name" autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" v-model="formData.email" class="form-control bg-dark text-white" name="email" value="" required autocomplete="email">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" v-model="formData.password" class="form-control bg-dark text-white" name="password" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" v-model="formData.password_confirmation" class="form-control bg-dark text-white" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default{
        data(){
            return{
                formData:{
                    name:'',
                    email:'',
                    password:'',
                    password_confirmation:''
                }
            }
        },
        methods:{
            submitForm(){
                let form = new FormData();
                form.append('name', this.formData.name);
                form.append('email', this.formData.email);
                form.append('password', this.formData.password);
                form.append('password_confirmation', this.formData.password_confirmation);
                axios.post('http://127.0.0.1:8000/api/register', form)
                .then((response) => {
                    this.$router.push({ name: 'Login' })
                })
                .catch((error) => {
                     // error.response.status Check status code
                }).finally(() => {
                     //Perform action in always
            });

            }
        }

    }
</script>