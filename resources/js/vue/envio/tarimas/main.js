import Vue from 'vue'
// import Tarimas from '/resources/js/vue/components/tarimas.vue'
import Tarimas from '../../components/tarimas.vue'

import VueProgressBar from 'vue-progressbar'


Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '2px',
    thickness: '8px',
})



new Vue({
    el: '#tarimas',
    components: { Tarimas },
    render: h => h('Tarimas', { props: {tarimatipoid: 2} }),
    VueProgressBar
})