require('./bootstrap');

require('alpinejs')

import Vue   from 'vue';
import Todos from './components/Todos';

Vue.component('todos', Todos)

new Vue({

}).$mount('#app');
