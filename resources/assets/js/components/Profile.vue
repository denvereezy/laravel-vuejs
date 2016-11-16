<template>
<div v-show="!profile" class="panel panel-default" style="text-align: center;">
    <h1>Profile</h1>
    <div class="panel-body">
        <div class="form-group">
            <label for="username">Username</label>
            <br />
            <h3 id="username">{{ list.name }}</h3>
        </div>
        <button class="btn btn-primary" @click="editProfile(list.id)">Update Profile</button>
    </div>
</div>

<div v-show="profileForm" class="panel panel-default">
    <h1 style="text-align: center;">Update</h1>
    <form action="#" @submit.prevent="edit ? editProfile(user.id) : updateProfile(user.id)" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input class="form-control" id="username" type="text" name="name" value="{{user.name}}" v-model="user.name"></input>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success">Update Profile</button>
            <button @click="fetchUserProfile()" class="btn btn-primary pull-right">Cancel</button>
        </div>
    </form>
</div>
</template>
<script>
export default {
    data: function() {
        return {
            profileForm: false,
            profile: false,
            list: [],
            user: {
                id: '',
                name: '',
                image: null
            }
        };
    },

    ready: function() {
        this.fetchUserProfile();
    },

    methods: {
        fetchUserProfile: function() {
            this.$http.get('profile').then(function(response) {
                this.list = response.data;
                this.profileForm = false;
            });
        },

        editProfile: function(id) {
            this.$http.get('/profile/edit/' + id).then(function(response) {
                this.user.id = response.data.id;
                this.user.name = response.data.name;
            });
            this.profileForm = true;
            this.profile = true;
        },

        updateProfile: function(id) {
            this.$http.patch('/profile/update/' + id, this.user).then(function(response) {
            this.profile = false;
            router.go('/profile');
            this.fetchUserProfile();
          });
        },
    },
};
</script>
