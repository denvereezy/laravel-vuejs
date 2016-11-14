/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
require('./bootstrap');
Vue.use(VueRouter);
Vue.use(VueResource);
    /**
     * Next, we will create a fresh Vue application instance and attach it to
     * the body of the page. From here, you may begin adding components to
     * the application, or feel free to tweak this setup for your needs.
     */
Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('content');
Vue.http.options.emulateJSON = true;
var App = Vue.extend({});

var tasks = Vue.component('tasks', require('./components/Tasks.vue'));
var users = Vue.component('users', require('./components/Users.vue'));
var userProfile = Vue.component('profile', require('./components/Profile.vue'));

window.router = new VueRouter({
    hashbang: true,
});


router.map({
    '/': {
        component: {
            template: `<div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 style="text-align:center;"> Welcome to Task App <img src="http://images.clipartpanda.com/task-clipart-task.svg" style="width:10%; height:10%;" /></h1>
                                    <h3 style="text-align:center;">Go to <a v-link="{path: '/tasks'}">tasks</a></h3>
                            </div>
                      </div>`
        }
    },
    '/tasks': {
        name: 'tasks',
        component: tasks
    },
    '/users': {
        name: 'users',
        component: users
    },
    '/profile': {
        name: 'profile',
        component: userProfile
    }
});

router.redirect({
    '*': '/'
});

router.start(App, '#app');
