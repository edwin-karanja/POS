<template>
    <div class="panel panel-primary">
        <div class="panel-heading">Update Password</div>

        <div class="panel-body">
            <div class="alert alert-info" v-if="alert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" @click="clear">&times;</a>
                <strong>Info!</strong> {{ alert_message }}.
            </div>
            <form class="form-horizontal" role="form" onsubmit="return false">
                <!-- Form Errors -->
                <div class="alert alert-danger" v-if="updatePass.errors.length">
                    <p><strong>Whoops!</strong> Something went wrong!</p>
                    <br>
                    <ul>
                        <li v-for="error in updatePass.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>
                <div class="form-group">
                    <label for="password" id="password" class="control-label col-sm-4">Password</label>
                    <div class="col-sm-8">
                        <input type="password" name="password" id="password" class="form-control" v-model="updatePass.password" @keyup.enter="updateForm" placeholder="New Password">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" id="password" class="control-label col-sm-4">Confirmation</label>
                    <div class="col-sm-8">
                        <input type="password" name="password" id="password_confirmation" class="form-control" v-model="updatePass.password_confirmation" @keyup.enter="updateForm"  placeholder="Confirm Password">
                    </div>
                </div>
            </form>
            <button type="button" class="btn btn-default" @click="updateForm">
                <i class="fa fa-pencil-square-o"v-show="update_icon"></i>
                <i class="fa fa-spin fa-spinner" v-show="button_spinner"></i>
                Update Password
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                alert: false,
                alert_message: '',
                update_icon: true,
                update_spinner: false,
                user: {},

                updatePass: {
                    errors: [],
                    password: '',
                    password_confirmation: ''
                }

            };
        },

        mounted() {
            this.prepareComponent();
        },

        methods: {
            prepareComponent() {
                 this.getUser();
            },

            getUser() {
                this.$http.get('/user')
                    .then(response => {
                        this.user = response.data;
                    });
            },

            updateForm() {
                // Display the spinner
                this.update_icon = false;
                this.button_spinner = true;

                this.updatePass.errors = [];

                this.$http.post('/user/' + this.user.id, this.updatePass)
                    .then(response => {
                        this.updatePass.password = '';
                        this.updatePass.password_confirmation = '';

                        this.alert_message = 'Password Updated!';
                        this.alert = true;

                    })
                    .catch(response => {
                        // Display Add icon and Hide the Spinner
                        this.update_icon = true;
                        this.button_spinner = false;

                        if (typeof response.data === 'object') {
                            this.updatePass.errors = _.flatten(_.toArray(response.data));
                        } else {
                            this.updatePass.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            clear() {
                this.alert = false;
                this.alert_message = '';
            }


        }
    }
</script>
